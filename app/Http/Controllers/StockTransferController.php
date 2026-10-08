<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockTransferRequest;
use App\Http\Requests\UpdateStockTransferRequest;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use App\Services\StockTransferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class StockTransferController extends Controller
{
    public function __construct(private StockTransferService $stockTransferService) {}

    public function index(): View
    {
        $this->authorize('viewAny', StockTransfer::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $stockTransfers = StockTransfer::query()
            ->with(['fromWarehouse', 'toWarehouse'])
            ->whereHas('fromWarehouse', function ($query) use($user) {
                $query->whereIn('branch_id', $user->accessibleBranchIds());
            })

            ->latest()
            ->paginate(15);

        return view('stock-transfers.index', compact('stockTransfers'));
    }

    public function create(Request $request): View
    {
        $this->authorize('create', StockTransfer::class);

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

        return view('stock-transfers.create', compact('warehouses', 'products', 'variants'));
    }

    public function store(StoreStockTransferRequest $request): RedirectResponse
    {
        try {
            $stockTransfer = $this->stockTransferService->create($request->user(), $request->validated());

            return redirect()->route('stock-transfers.index', $stockTransfer)->with('success', 'Stock transfer created successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : 'Unable to create stock transfer.');
        }
    }

    public function show(StockTransfer $stockTransfer): View
    {
        $this->authorize('view', $stockTransfer);
        $stockTransfer->load(['fromWarehouse', 'toWarehouse', 'postedBy', 'items.product', 'items.productVariant']);

        return view('stock-transfers.show', compact('stockTransfer'));
    }

    public function edit(StockTransfer $stockTransfer): View
    {
        $this->authorize('update', $stockTransfer);
        $stockTransfer->load('items');
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

        return view('stock-transfers.edit', compact('stockTransfer', 'warehouses', 'products', 'variants'));
    }

    public function update(UpdateStockTransferRequest $request, StockTransfer $stockTransfer): RedirectResponse
    {
        try {
            $this->stockTransferService->update($request->user(), $stockTransfer, $request->validated());

            return redirect()->route('stock-transfers.index', $stockTransfer)->with('success', 'Stock transfer updated successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : 'Unable to update stock transfer.');
        }
    }

    public function post(StockTransfer $stockTransfer): RedirectResponse
    {
        $this->authorize('post', $stockTransfer);

        try {
            $this->stockTransferService->post(request()->user(), $stockTransfer);

            return back()->with('success', 'Stock transfer posted successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : $e->getMessage());
        }
    }

    public function cancel(StockTransfer $stockTransfer): RedirectResponse
    {
        $this->authorize('cancel', $stockTransfer);

        try {
            $this->stockTransferService->cancel($stockTransfer);

            return back()->with('success', 'Stock transfer cancelled successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to cancel stock transfer.');
        }
    }
}
