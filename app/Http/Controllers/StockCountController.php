<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockCountRequest;
use App\Http\Requests\UpdateStockCountRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockCount;
use App\Models\Warehouse;
use App\Services\StockCountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class StockCountController extends Controller
{
    public function __construct(private StockCountService $stockCountService) {}

    public function index(): View
    {
        $this->authorize('viewAny', StockCount::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $stockCounts = StockCount::query()
            ->with(['warehouse', 'postedBy'])
            ->whereHas('warehouse', function ($query) use($user) {
                $query->whereIn('branch_id', $user->accessibleBranchIds());
            })
            ->latest()
            ->paginate(15);
    
        return view('stock-counts.index', compact('stockCounts'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', StockCount::class);
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

        return view('stock-counts.create', compact('warehouses', 'products', 'variants'));
    }

    public function store(StoreStockCountRequest $request): RedirectResponse
    {
        try {
            $stockCount = $this->stockCountService->create($request->user(), $request->validated());

            return redirect()->route('stock-counts.show', $stockCount)->with('success', 'Stock count created successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : 'Unable to create stock count.');
        }
    }

    public function show(StockCount $stockCount): View
    {
        $this->authorize('view', $stockCount);
        $stockCount->load(['warehouse', 'postedBy', 'items.product', 'items.productVariant']);

        return view('stock-counts.show', compact('stockCount'));
    }

    public function edit(StockCount $stockCount): View
    {
        $this->authorize('update', $stockCount);
        $stockCount->load('items');
        $user = request()->user();

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

        return view('stock-counts.edit', compact('stockCount', 'warehouses', 'products', 'variants'));
    }

    public function update(UpdateStockCountRequest $request, StockCount $stockCount): RedirectResponse
    {
        try {
            $this->stockCountService->update($request->user(), $stockCount, $request->validated());

            return redirect()->route('stock-counts.show', $stockCount)->with('success', 'Stock count updated successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : 'Unable to update stock count.');
        }
    }

    public function post(StockCount $stockCount): RedirectResponse
    {
        $this->authorize('post', $stockCount);

        try {
            $this->stockCountService->post(request()->user(), $stockCount);

            return back()->with('success', 'Stock count posted and inventory reconciled successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : $e->getMessage());
        }
    }

    public function cancel(StockCount $stockCount): RedirectResponse
    {
        $this->authorize('cancel', $stockCount);

        try {
            $this->stockCountService->cancel($stockCount);

            return back()->with('success', 'Stock count cancelled successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to cancel stock count.');
        }
    }
}
