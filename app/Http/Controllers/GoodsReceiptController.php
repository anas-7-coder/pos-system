<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoodsReceiptRequest;
use App\Http\Requests\UpdateGoodsReceiptRequest;
use App\Models\GoodsReceipt;
use App\Models\PurchaseOrder;
// use App\Models\PurchaseOrderItem;
use App\Services\GoodsReceiptService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class GoodsReceiptController extends Controller
{
    public function __construct(private GoodsReceiptService $goodsReceiptService) {}

    public function index(PurchaseOrder $purchaseOrder): View
    {
        $this->authorize('viewAny', GoodsReceipt::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $goodsReceipts = GoodsReceipt::query()
            ->whereHas('purchaseOrder.warehouse', function ($query) use($user) {
                $query->whereIn('branch_id', $user->accessibleBranchIds());
            })
            ->with(['purchaseOrder', 'supplier', 'branch', 'warehouse'])
            ->latest()
            ->paginate(15);

        return view('goods-receipts.index', compact('goodsReceipts', 'purchaseOrder'));
    }

    public function create(PurchaseOrder $purchaseOrder): View
    {
        $this->authorize('create', [GoodsReceipt::class, $purchaseOrder]);

        if ($purchaseOrder->status !== 'approved') {
            abort(403, 'Only approved purchase orders can receive goods.');
        }

        $purchaseOrder->load('items.product', 'items.productVariant');

        $receivedQuantities = DB::table('goods_receipt_items')
            ->join('goods_receipts', 'goods_receipt_items.goods_receipt_id', '=', 'goods_receipts.id')
            ->where('goods_receipts.purchase_order_id', $purchaseOrder->id)
            ->where('goods_receipts.status', 'posted')
            ->select('goods_receipt_items.purchase_order_item_id')
            ->selectRaw('SUM(goods_receipt_items.received_quantity) as received_quantity')
            ->groupBy('goods_receipt_items.purchase_order_item_id')
            ->pluck('received_quantity', 'purchase_order_item_id');

        return view('goods-receipts.create', compact('purchaseOrder', 'receivedQuantities'));
    }

    public function store(StoreGoodsReceiptRequest $request, PurchaseOrder $purchaseOrder): RedirectResponse
    {
        try {
            $receipt = $this->goodsReceiptService->create($request->user(), $purchaseOrder, $request->validated());

            return redirect()->route('goods-receipts.index', $receipt)->with('success', 'Goods receipt created successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->getMessage() : 'Unable to create goods receipt.');
        }
    }

    public function show(GoodsReceipt $goodsReceipt): View
    {
        $this->authorize('view', $goodsReceipt);

        $goodsReceipt->load([
            'purchaseOrder',
            'supplier',
            'branch',
            'warehouse',
            'postedBy',
            'items.product',
            'items.productVariant',
        ]);

        $purchaseOrder = $goodsReceipt->purchaseOrder;

        return view('goods-receipts.show', compact('goodsReceipt', 'purchaseOrder'));
    }

    public function edit(GoodsReceipt $goodsReceipt): View
    {
        $this->authorize('update', $goodsReceipt);
        $goodsReceipt->load(['purchaseOrder.items.product', 'purchaseOrder.items.productVariant', 'items']);
        $purchaseOrder = $goodsReceipt->purchaseOrder;

        $receivedQuantities = DB::table('goods_receipt_items')
            ->join('goods_receipts', 'goods_receipt_items.goods_receipt_id', '=', 'goods_receipts.id')
            ->where('goods_receipts.purchase_order_id', $purchaseOrder->id)
            ->where('goods_receipts.status', 'posted')
            ->select('goods_receipt_items.purchase_order_item_id')
            ->selectRaw('SUM(goods_receipt_items.received_quantity) as received_quantity')
            ->groupBy('goods_receipt_items.purchase_order_item_id')
            ->pluck('received_quantity', 'purchase_order_item_id');

        return view('goods-receipts.edit', compact('goodsReceipt', 'purchaseOrder', 'receivedQuantities'));
    }

    public function update(UpdateGoodsReceiptRequest $request, GoodsReceipt $goodsReceipt): RedirectResponse
    {
        try {
            $this->goodsReceiptService->update($goodsReceipt, $request->validated());

            return redirect()->route('purchase-orders.goods-receipts.index', $goodsReceipt->purchase_order_id)->with('success', 'Goods receipt updated successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Unable to update goods receipt.');
        }
    }

    public function post(GoodsReceipt $goodsReceipt): RedirectResponse
    {
        $this->authorize('post', $goodsReceipt);

        try {
            $this->goodsReceiptService->post(request()->user(), $goodsReceipt);

            return back()->with('success', 'Goods receipt posted successfully. Stock has been increased.');
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', $e instanceof ValidationException ? $e->validator->errors()->first() : $e->getMessage());
        }
    }

    public function cancel(GoodsReceipt $goodsReceipt): RedirectResponse
    {
        $this->authorize('cancel', $goodsReceipt);

        try {
            $this->goodsReceiptService->cancel($goodsReceipt);

            return back()->with('success', 'Goods receipt cancelled successfully.');
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to cancel goods receipt.');
        }
    }

    public function purchaseOrderIndex(PurchaseOrder $purchaseOrder): View
    {
        $this->authorize('viewAny', GoodsReceipt::class);
        $goodsReceipts = $purchaseOrder->goodsReceipts()->with(['supplier', 'branch', 'warehouse'])->paginate(15);

        return view('goods-receipts.purchase-order-index', compact('purchaseOrder', 'goodsReceipts'));
    }
}
