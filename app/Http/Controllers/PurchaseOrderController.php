<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Services\PurchaseOrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function __construct(private PurchaseOrderService $purchaseOrderService) {}

    private function ensureBranchAccess(PurchaseOrder $purchaseOrder): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user->canAccessBranch( (int) $purchaseOrder->warehouse->branch_id), 403);
    }

    public function index(): View
    {
        $this->authorize('viewAny', PurchaseOrder::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $purchaseOrders = PurchaseOrder::query()
            ->with(['supplier', 'branch', 'warehouse'])
            ->whereHas('warehouse', function ($query) use($user) {
                $query->whereIn('branch_id', $user->accessibleBranchIds());
            })
            ->latest()
            ->paginate(15);

        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    public function create(): View
    {
        $this->authorize('create', PurchaseOrder::class);

        $suppliers = Supplier::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $branches = $this->accessibleBranches();

        $warehouses = Warehouse::query()
            ->where('status', 'Active')
            ->whereIn('branch_id', $branches->pluck('id'))
            ->with('branch')
            ->orderBy('name')
            ->get();
            
        $products = Product::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $variants = ProductVariant::query()
            ->where('status', 'Active')
            ->with('product')
            ->orderBy('name')
            ->get();

        return view('purchase-orders.create', compact('suppliers', 'branches', 'warehouses', 'products', 'variants'));
    }

    public function store(StorePurchaseOrderRequest $request): RedirectResponse
    {
        try {
            $purchaseOrder = $this->purchaseOrderService->create($request->user(), $request->validated());

            return redirect()->route('purchase-orders.index', $purchaseOrder)->with('success', 'Purchase order created successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to create purchase order.');
        }
    }

    public function show(PurchaseOrder $purchaseOrder): View
    {
        $this->authorize('view', $purchaseOrder);
        $this->ensureBranchAccess($purchaseOrder);

        $purchaseOrder->load(['supplier', 'branch', 'warehouse', 'items.product', 'items.productVariant']);

        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder): View
    {
        $this->authorize('update', $purchaseOrder);
        $this->ensureBranchAccess($purchaseOrder);

        if ($purchaseOrder->status !== 'draft') {
            abort(403, 'Only draft purchase orders can be edited.');
        }

        $suppliers = Supplier::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $branches = $this->accessibleBranches();

        $warehouses = Warehouse::query()
            ->where('status', 'Active')
            ->whereIn('branch_id', $branches->pluck('id'))
            ->with('branch')
            ->orderBy('name')
            ->get();

        $products = Product::query()
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        $variants = ProductVariant::query()
            ->where('status', 'Active')
            ->with('product')
            ->orderBy('name')
            ->get();

        $purchaseOrder->load('items');

        return view('purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'branches', 'warehouses', 'products', 'variants'));
    }

    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        try {
            $this->purchaseOrderService->update($request->user(), $purchaseOrder, $request->validated());
            $this->ensureBranchAccess($purchaseOrder);

            return redirect()->route('purchase-orders.index', $purchaseOrder)->with('success', 'Purchase order updated successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->getMessage() : 'Unable to update purchase order.');
        }
    }

    public function cancel(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $this->authorize('cancel', $purchaseOrder);
        $this->ensureBranchAccess($purchaseOrder);

        try {
            $this->purchaseOrderService->cancel($purchaseOrder);

            return back()->with('success', 'Purchase order cancelled successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to cancel purchase order.');
        }
    }

    private function accessibleBranches()
    {
        $user = request()->user();

        return Branch::query()
            ->where('status', 'Active')
            ->when(
                ! $user->hasRole('super_admin'),
                function ($query) use ($user) {
                    $query->whereIn('id', $user->branches()->pluck('branches.id'));
                }
            )
            ->orderBy('name')
            ->get();
    }

    public function submit(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $this->authorize('submit', $purchaseOrder);
        $this->ensureBranchAccess($purchaseOrder);

        try {
            $this->purchaseOrderService->submit($purchaseOrder);

            return back()->with('success', 'Purchase order submitted successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to submit purchase order.');
        }
    }

    public function approve(PurchaseOrder $purchaseOrder): RedirectResponse
    {
        $this->authorize('approve', $purchaseOrder);
        $this->ensureBranchAccess($purchaseOrder);

        try {
            $this->purchaseOrderService->approve($purchaseOrder);

            return back()->with('success', 'Purchase order approved successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to approve purchase order.');
        }
    }
}
