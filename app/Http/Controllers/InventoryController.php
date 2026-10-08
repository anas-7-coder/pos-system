<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReorderLevelRequest;
use App\Http\Requests\StoreStockAdjustmentRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct(private InventoryService $inventoryService) {}

    public function index(Request $request): View
    {
        $this->authorize('viewAny', StockMovement::class);

        $user = $request->user();

        $balances = StockBalance::query()->with(['warehouse.branch', 'product', 'productVariant'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($query) use ($search) {
                    $query->whereHas('product', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    }
                    )->orWhereHas('productVariant', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    }
                    )->orWhereHas('warehouse', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    }
                    );
                });
            }
            )
            ->when(! $user->hasRole('super_admin'), function ($query) use ($user) {
                $query->whereHas('warehouse', function ($query) use ($user) {
                    $query->whereIn('branch_id', $user->branches()
                        ->pluck('branches.id'));
                }
                );
            }
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('inventory.index', compact('balances'));
    }

    public function movements(Request $request): View
    {
        $this->authorize('viewAny', StockMovement::class);

        $user = $request->user();

        $movements = StockMovement::query()
            ->with(['warehouse.branch', 'product', 'productVariant', 'creator'])
            ->when(! $user->hasRole('super_admin'), function ($query) use ($user) {
                $query->whereHas('warehouse', function ($query) use ($user) {
                    $query->whereIn('branch_id', $user->branches()
                        ->pluck('branches.id'));
                }
                );
            }
            )
            ->latest('occurred_at')
            ->paginate(20)
            ->withQueryString();

        return view('inventory.movements', compact('movements'));
    }

    public function adjustmentCreate(Request $request): View
    {
        $this->authorize('adjust', StockMovement::class);

        $user = $request->user();

        $warehouses = Warehouse::query()
            ->where('status', 'Active')
            ->when(! $user->hasRole('super_admin'), function ($query) use ($user) {
                $query->whereIn('branch_id', $user->branches()
                    ->pluck('branches.id')
                );
            }
            )
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->where('status', 'Active')
            ->where('track_inventory', true)
            ->orderBy('name')
            ->get();

        $variants = ProductVariant::query()
            ->where('status', 'Active')
            ->with('product')
            ->orderBy('name')
            ->get();

        return view('inventory.adjust', compact('warehouses', 'products', 'variants'));
    }

    public function adjustmentStore(StoreStockAdjustmentRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            $this->inventoryService->adjust(
                user       : $request->user(),
                warehouseId: (int) $data['warehouse_id'],
                productId  : (int) $data['product_id'],
                variantId  : ! empty($data['product_variant_id']) ? (int) $data['product_variant_id'] : null,
                quantity   : (float) $data['quantity'],
                direction  : $data['direction'],
                reason     : $data['reason']
            );

            return redirect()->route('inventory.index')->with('success', 'Inventory adjusted successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : 'Unable to adjust inventory.');
        }
    }

    public function lowStock(Request $request): View
    {
        $this->authorize('viewAny', StockMovement::class);
        $user = $request->user();

        $balances = StockBalance::query()
            ->with(['warehouse.branch', 'product', 'productVariant'])
            ->where('reorder_level', '>', 0)
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->when(! $user->hasRole('super_admin'), function ($query) use ($user) {
                $query->whereHas('warehouse', function ($query) use ($user) {
                    $query->whereIn('branch_id', $user->branches()
                        ->pluck('branches.id')
                    );
                }
                );
            }
            )
            ->orderBy('quantity')
            ->paginate(20)
            ->withQueryString();

        return view('inventory.low-stock', compact('balances'));
    }

    public function reorderLevelCreate(Request $request): View
    {
        $this->authorize('adjust', StockMovement::class);
        $user = $request->user();

        $warehouses = Warehouse::query()
            ->where('status', 'Active')
            ->when(! $user->hasRole('super_admin'), function ($query) use ($user) {
                $query->whereIn('branch_id', $user->branches()
                    ->pluck('branches.id')
                );
            }
            )
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->where('status', 'Active')
            ->where('track_inventory', true)
            ->orderBy('name')
            ->get();

        $variants = ProductVariant::query()
            ->where('status', 'Active')
            ->with('product')
            ->orderBy('name')
            ->get();

        return view('inventory.reorder-level', compact('warehouses', 'products', 'variants'));
    }

    public function reorderLevelStore(StoreReorderLevelRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            $this->inventoryService->setReorderLevel(
                user        : $request->user(),
                warehouseId : (int) $data['warehouse_id'],
                productId   : (int) $data['product_id'],
                variantId   : ! empty($data['product_variant_id']) ? (int) $data['product_variant_id'] : null,
                reorderLevel: (float) $data['reorder_level']
            );

            return redirect()->route('inventory.index')->with('success', 'Reorder level updated successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : 'Unable to update reorder level.');
        }
    }
}
