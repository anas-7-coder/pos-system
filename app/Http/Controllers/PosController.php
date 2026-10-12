<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductVariant;
use App\Models\RegisterSession;
use App\Models\Sale;
use App\Models\StockBalance;
// use App\Models\TaxRate;
use App\Models\Warehouse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PosController extends Controller
{
    /**
     * Display the separate cashier terminal.
     */
    public function index(Request $request): View
    {
        $this->authorize('create', Sale::class);

        $user         = $request->user();
        $isSuperAdmin = $user->hasRole('super_admin');

        $sessions = RegisterSession::query()
            ->with('register')
            ->where('status', 'Open')
            ->when( !$isSuperAdmin, fn ($query) => $query
                    ->where('user_id', $user->id)
                    ->whereIn('branch_id', $user->accessibleBranchIds())
            )
            ->latest('opened_at')
            ->get();

        $warehouses = Warehouse::query()
            ->where('status', 'Active')
            ->when( !$isSuperAdmin, fn ($query) => $query->whereIn('branch_id', $user->accessibleBranchIds()) )
            ->orderBy('name')
            ->get();

        $customers = Customer::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->limit(200)
            ->get(['id', 'name']);

        return view('pos.index', compact('sessions', 'warehouses', 'customers'));
    }

    /**
     * Search active products for the selected register and warehouse.
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $this->authorize('create', Sale::class);

        $validated = $request->validate([
            'q'                   => ['nullable', 'string',  'max:100'],
            'register_session_id' => ['required', 'integer', 'min:1'],
            'warehouse_id'        => ['required', 'integer', 'min:1'],
        ]);

        $user         = $request->user();
        $isSuperAdmin = $user->hasRole('super_admin');

        $sessionQuery = RegisterSession::query()
            ->whereKey($validated['register_session_id'])
            ->where('status', 'Open');

        if (!$isSuperAdmin) {
            $sessionQuery
                ->where('user_id', $user->id)
                ->whereIn('branch_id', $user->accessibleBranchIds());
        }

        $session = $sessionQuery->firstOrFail();

        $warehouse = Warehouse::query()
            ->whereKey($validated['warehouse_id'])
            ->where('status', 'Active')
            ->where('branch_id', $session->branch_id)
            ->firstOrFail();

        $search = trim($validated['q'] ?? '');

        $products = Product::query()
            ->where('status', 'Active')
            ->where('track_inventory', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->limit(30)
            ->get();

        $productIds = $products->pluck('id');

        $variants = ProductVariant::query()
            ->whereIn('product_id', $productIds)
            ->where('status', 'Active')
            ->orderBy('name')
            ->get()
            ->groupBy('product_id');

        $balances = StockBalance::query()
            ->where('warehouse_id', $warehouse->id)
            ->whereIn('product_id', $productIds)
            ->get()
            ->keyBy(fn ($balance) =>
                $balance->product_id . ':' .
                (int) ($balance->product_variant_id ?? 0)
            );

        $saleDate = now()->toDateString();

        $results = $products->map(function ($product) use (
            $variants,
            $balances,
            $session,
            $saleDate
        ) {
            $baseBalance = $balances->get($product->id . ':0');

            $productVariants = $variants
                ->get($product->id, collect())
                ->map(function ($variant) use ($product, $balances, $session, $saleDate) {
                    $balance = $balances->get($product->id . ':' . $variant->id);

                    return [
                        'id'    => $variant->id,
                        'name'  => $variant->name,
                        'price' => $this->resolveRetailPrice($product, $variant, (int) $session->branch_id, $saleDate),
                        'stock' => (float) ($balance?->quantity ?? 0),
                    ];
                })
                ->values();

            return [
                'id'                   => $product->id,
                'name'                 => $product->name,
                'sku'                  => $product->sku,
                'price'                => $this->resolveRetailPrice($product, null, (int) $session->branch_id, $saleDate),
                'stock'                => (float) ($baseBalance?->quantity ?? 0),
                'allow_negative_stock' => (bool) $product->allow_negative_stock,
                'variants'             => $productVariants,
            ];
        })->values();

        return response()->json(['data' => $results]);
    }

    /**
     * Resolve the displayed retail price using the existing POS pricing rules.
     * The final sale will still calculate prices on the server.
     */
    private function resolveRetailPrice(Product $product, ?ProductVariant $variant, int $branchId, string $saleDate): float {
        $price = ProductPrice::query()
            ->where('product_id', $product->id)
            ->where('price_type', 'retail')
            ->where('status', 'Active')
            ->whereDate('effective_from', '<=', $saleDate)
            ->where(function ($query) use ($saleDate) {
                $query->whereNull('effective_to')
                    ->orWhereDate('effective_to', '>=', $saleDate);
            })
            ->where(function ($query) use ($variant, $branchId) {
                $query->where('branch_id', $branchId)
                    ->orWhereNull('branch_id');

                if ($variant) {
                    $query->where('product_variant_id', $variant->id);
                } else {
                    $query->whereNull('product_variant_id');
                }
            })
            ->orderByRaw('CASE WHEN branch_id = ? THEN 0 ELSE 1 END', [$branchId])
            ->latest('effective_from')
            ->first();

        if ($price) {
            return (float) $price->price;
        }

        if ($variant?->selling_price !== null) {
            return (float) $variant->selling_price;
        }

        return (float) $product->selling_price;
    }
}
