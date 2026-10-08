<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StockBalance;
use App\Models\PurchaseOrder;
use App\Models\GoodsReceipt;
use App\Models\Customer;
use App\Models\CustomerLedgerEntry;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\SaleReturn;
use App\Models\StockMovement;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $branchIds = $user->accessibleBranchIds();

        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = $validated['from'] ?? today()->startOfMonth()->toDateString();
        $to   = $validated['to'] ?? today()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | Base Sales Query
        |--------------------------------------------------------------------------
        */

        $salesQuery = Sale::query()
            ->whereIn('branch_id', $branchIds)
            ->whereBetween('sale_date', [
                $from . ' 00:00:00',
                $to . ' 23:59:59',
            ])
            ->whereNotIn('status', ['draft', 'cancelled']);

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalSales = (float) (clone $salesQuery)
            ->sum('grand_total');

        $invoiceCount = (clone $salesQuery)
            ->count();

        $totalDiscount = (float) (clone $salesQuery)
            ->sum('discount_amount');

        $totalTax = (float) (clone $salesQuery)
            ->sum('tax_amount');

        /*
        |--------------------------------------------------------------------------
        | Payment Summary
        |--------------------------------------------------------------------------
        */

        $paymentBreakdown = Payment::query()
            ->where('status', 'posted')
            ->whereHas('sale', function ($query) use ($branchIds, $from, $to) {
                $query
                    ->whereIn('branch_id', $branchIds)
                    ->whereBetween('sale_date', [
                        $from . ' 00:00:00',
                        $to . ' 23:59:59',
                    ])
                    ->whereNotIn('status', ['draft', 'cancelled']);
            })
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Top Products
        |--------------------------------------------------------------------------
        */

        $topProducts = SaleItem::query()
            ->with('product')
            ->whereHas('sale', function ($query) use ($branchIds, $from, $to) {
                $query
                    ->whereIn('branch_id', $branchIds)
                    ->whereBetween('sale_date', [
                        $from . ' 00:00:00',
                        $to . ' 23:59:59',
                    ])
                    ->whereNotIn('status', ['draft', 'cancelled']);
            })
            ->selectRaw('
                product_id,
                SUM(quantity) as total_quantity,
                SUM(line_total) as total_sales
            ')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Branch Summary
        |--------------------------------------------------------------------------
        */

        $branchSummary = Sale::query()
            ->with('branch')
            ->whereIn('branch_id', $branchIds)
            ->whereBetween('sale_date', [
                $from . ' 00:00:00',
                $to . ' 23:59:59',
            ])
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->selectRaw('branch_id, COUNT(*) as invoice_count, SUM(grand_total) as total_sales')
            ->groupBy('branch_id')
            ->orderByDesc('total_sales')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Sales List
        |--------------------------------------------------------------------------
        */

        $sales = (clone $salesQuery)
            ->with(['customer', 'branch'])
            ->latest('sale_date')
            ->paginate(20)
            ->withQueryString();

        return view('reports.sales', [
            'from' => $from,
            'to' => $to,

            'totalSales' => $totalSales,
            'invoiceCount' => $invoiceCount,
            'totalDiscount' => $totalDiscount,
            'totalTax' => $totalTax,

            'paymentBreakdown' => $paymentBreakdown,
            'topProducts' => $topProducts,
            'branchSummary' => $branchSummary,
            'sales' => $sales,
        ]);
    }

    public function inventory()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $branchIds = $user->accessibleBranchIds();

        /*
        |--------------------------------------------------------------------------
        | Inventory Query
        |--------------------------------------------------------------------------
        */

        $inventoryQuery = StockBalance::query()
            ->with([
                'warehouse.branch',
                'product',
                'productVariant',
            ])
            ->whereHas('warehouse', function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            });

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalItems = (clone $inventoryQuery)->count();

        $totalQuantity = (float) (clone $inventoryQuery)
            ->sum('quantity');

        $totalReserved = (float) (clone $inventoryQuery)
            ->sum('reserved_quantity');

        $lowStockCount = (clone $inventoryQuery)
            ->where('reorder_level', '>', 0)
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Estimated Stock Value
        |--------------------------------------------------------------------------
        */

        $inventoryRows = (clone $inventoryQuery)
            ->get();

        $estimatedValue = $inventoryRows->sum(function ($stock) {
            $costPrice = $stock->product?->cost_price ?? 0;

            return (float) $stock->quantity * (float) $costPrice;
        });

        /*
        |--------------------------------------------------------------------------
        | Warehouse Summary
        |--------------------------------------------------------------------------
        */

        $warehouseSummary = $inventoryRows
            ->groupBy('warehouse_id')
            ->map(function ($items) {
                return [
                    'warehouse' => $items->first()->warehouse,
                    'item_count' => $items->count(),
                    'quantity' => $items->sum('quantity'),
                    'reserved' => $items->sum('reserved_quantity'),
                    'value' => $items->sum(function ($stock) {
                        return (float) $stock->quantity
                            * (float) ($stock->product?->cost_price ?? 0);
                    }),
                ];
            })
            ->sortByDesc('value')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Low Stock Items
        |--------------------------------------------------------------------------
        */

        $lowStockItems = (clone $inventoryQuery)
            ->where('reorder_level', '>', 0)
            ->whereColumn('quantity', '<=', 'reorder_level')
            ->orderBy('quantity')
            ->limit(20)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Inventory Detail
        |--------------------------------------------------------------------------
        */

        $inventory = (clone $inventoryQuery)
            ->latest('updated_at')
            ->paginate(20)
            ->withQueryString();

        return view('reports.inventory', [
            'totalItems' => $totalItems,
            'totalQuantity' => $totalQuantity,
            'totalReserved' => $totalReserved,
            'lowStockCount' => $lowStockCount,
            'estimatedValue' => $estimatedValue,
            'warehouseSummary' => $warehouseSummary,
            'lowStockItems' => $lowStockItems,
            'inventory' => $inventory,
        ]);
    }

    public function purchasing(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $branchIds = $user->accessibleBranchIds();

        $from = $request->input('from');
        $to   = $request->input('to');

        /*
        |--------------------------------------------------------------------------
        | Purchase Orders
        |--------------------------------------------------------------------------
        */

        $purchaseOrderQuery = PurchaseOrder::query()
            ->with([
                'supplier',
                'warehouse.branch',
                'items.product',
                'items.productVariant',
            ])
            ->whereHas('warehouse', function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            })
            ->when($from, function ($query) use ($from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('created_at', '<=', $to);
            });

        $allPurchaseOrders = (clone $purchaseOrderQuery)->get();

        $activePurchaseOrders = $allPurchaseOrders
            ->where('status', '!=', 'cancelled');

        $totalPurchaseOrders = $allPurchaseOrders->count();

        $approvedPurchaseOrders = $allPurchaseOrders
            ->whereIn('status', [
                'approved',
                'goods_received',
                'closed',
            ])
            ->count();

        $cancelledPurchaseOrders = $allPurchaseOrders
            ->where('status', 'cancelled')
            ->count();

        $totalPurchaseValue = $activePurchaseOrders->sum(function ($purchaseOrder) {
            return $purchaseOrder->items->sum(function ($item) {
                return (float) $item->quantity
                    * (float) ($item->unit_cost ?? 0);
            });
        });

        $totalOrderedQuantity = $activePurchaseOrders->sum(function ($purchaseOrder) {
            return $purchaseOrder->items->sum(function ($item) {
                return (float) $item->quantity;
            });
        });

        $totalReceivedQuantity = $activePurchaseOrders->sum(function ($purchaseOrder) {
            return $purchaseOrder->items->sum(function ($item) {
                return (float) ($item->received_quantity ?? 0);
            });
        });

        $totalPendingQuantity = max(
            0,
            $totalOrderedQuantity - $totalReceivedQuantity
        );

        /*
        |--------------------------------------------------------------------------
        | Supplier Summary
        |--------------------------------------------------------------------------
        */

        $supplierSummary = $activePurchaseOrders
            ->groupBy('supplier_id')
            ->map(function ($orders) {
                $supplier = $orders->first()->supplier;

                return [
                    'supplier' => $supplier,
                    'orders' => $orders->count(),
                    'value' => $orders->sum(function ($order) {
                        return $order->items->sum(function ($item) {
                            return (float) $item->quantity
                                * (float) ($item->unit_cost ?? 0);
                        });
                    }),
                    'ordered_quantity' => $orders->sum(function ($order) {
                        return $order->items->sum('quantity');
                    }),
                    'received_quantity' => $orders->sum(function ($order) {
                        return $order->items->sum(function ($item) {
                            return (float) ($item->received_quantity ?? 0);
                        });
                    }),
                ];
            })
            ->sortByDesc('value')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Warehouse / Branch Summary
        |--------------------------------------------------------------------------
        */

        $warehouseSummary = $activePurchaseOrders
            ->groupBy('warehouse_id')
            ->map(function ($orders) {
                $warehouse = $orders->first()->warehouse;

                return [
                    'warehouse' => $warehouse,
                    'branch' => $warehouse?->branch,
                    'orders' => $orders->count(),
                    'value' => $orders->sum(function ($order) {
                        return $order->items->sum(function ($item) {
                            return (float) $item->quantity
                                * (float) ($item->unit_cost ?? 0);
                        });
                    }),
                    'ordered_quantity' => $orders->sum(function ($order) {
                        return $order->items->sum('quantity');
                    }),
                    'received_quantity' => $orders->sum(function ($order) {
                        return $order->items->sum(function ($item) {
                            return (float) ($item->received_quantity ?? 0);
                        });
                    }),
                ];
            })
            ->sortByDesc('value')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Goods Receipts
        |--------------------------------------------------------------------------
        */

        $goodsReceiptQuery = GoodsReceipt::query()
            ->with([
                'purchaseOrder.supplier',
                'purchaseOrder.warehouse.branch',
                'items',
            ])
            ->whereHas('purchaseOrder.warehouse', function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            })
            ->when($from, function ($query) use ($from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('created_at', '<=', $to);
            });

        $allGoodsReceipts = (clone $goodsReceiptQuery)->get();

        $goodsReceiptCount = $allGoodsReceipts->count();

        $postedGoodsReceipts = $allGoodsReceipts
            ->where('status', 'posted');

        $postedGoodsReceiptCount = $postedGoodsReceipts->count();

        $receivedValue = $postedGoodsReceipts->sum(function ($receipt) {
            return $receipt->items->sum(function ($item) {
                return (float) $item->quantity
                    * (float) ($item->unit_cost ?? 0);
            });
        });

        /*
        |--------------------------------------------------------------------------
        | Paginated Purchase Orders
        |--------------------------------------------------------------------------
        */

        $purchaseOrders = (clone $purchaseOrderQuery)
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('reports.purchasing', [
            'from' => $from,
            'to' => $to,

            'totalPurchaseOrders' => $totalPurchaseOrders,
            'approvedPurchaseOrders' => $approvedPurchaseOrders,
            'cancelledPurchaseOrders' => $cancelledPurchaseOrders,

            'totalPurchaseValue' => $totalPurchaseValue,
            'totalOrderedQuantity' => $totalOrderedQuantity,
            'totalReceivedQuantity' => $totalReceivedQuantity,
            'totalPendingQuantity' => $totalPendingQuantity,

            'supplierSummary' => $supplierSummary,
            'warehouseSummary' => $warehouseSummary,

            'goodsReceiptCount' => $goodsReceiptCount,
            'postedGoodsReceiptCount' => $postedGoodsReceiptCount,
            'receivedValue' => $receivedValue,

            'purchaseOrders' => $purchaseOrders,
        ]);
    }

    public function customers(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $branchIds = $user->accessibleBranchIds();

        $from = $request->input('from');
        $to   = $request->input('to');

        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        */

        $customerQuery = Customer::query()
            ->where('status', 'Active')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        /*
        |--------------------------------------------------------------------------
        | Sales Aggregation
        |--------------------------------------------------------------------------
        */

        $salesQuery = Sale::query()
            ->whereIn('branch_id', $branchIds)
            ->whereNotNull('customer_id')
            ->whereIn('status', [
                'completed',
                'partially_refunded',
                'refunded',
            ])
            ->when($from, function ($query) use ($from) {
                $query->whereDate('sale_date', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('sale_date', '<=', $to);
            });

        $salesSummary = (clone $salesQuery)
            ->selectRaw('
                customer_id,
                COUNT(*) as invoice_count,
                COALESCE(SUM(grand_total), 0) as sales_value
            ')
            ->groupBy('customer_id')
            ->get()
            ->keyBy('customer_id');

        /*
        |--------------------------------------------------------------------------
        | Period Customer Payments
        |--------------------------------------------------------------------------
        */

        $paymentSummary = CustomerLedgerEntry::query()
            ->whereIn('branch_id', $branchIds)
            ->whereNotNull('customer_payment_id')
            ->when($from, function ($query) use ($from) {
                $query->whereDate('transaction_date', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('transaction_date', '<=', $to);
            })
            ->selectRaw('
                customer_id,
                COALESCE(SUM(credit), 0) as payments
            ')
            ->groupBy('customer_id')
            ->get()
            ->keyBy('customer_id');

        /*
        |--------------------------------------------------------------------------
        | All-Time Branch-Scoped Receivable Balance
        |--------------------------------------------------------------------------
        */

        $balanceSummary = CustomerLedgerEntry::query()
            ->whereIn('branch_id', $branchIds)
            ->selectRaw('
                customer_id,
                COALESCE(SUM(debit), 0) as total_debit,
                COALESCE(SUM(credit), 0) as total_credit
            ')
            ->groupBy('customer_id')
            ->get()
            ->keyBy('customer_id');

        /*
        |--------------------------------------------------------------------------
        | Main Customer Collection
        |--------------------------------------------------------------------------
        */

        $customers = $customerQuery
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Attach Report Metrics
        |--------------------------------------------------------------------------
        */

        $customers->getCollection()->transform(function ($customer) use (
            $salesSummary,
            $paymentSummary,
            $balanceSummary
        ) {
            $sales = $salesSummary->get($customer->id);

            $payments = $paymentSummary->get($customer->id);

            $balance = $balanceSummary->get($customer->id);

            $customer->report_invoice_count =
                (int) ($sales->invoice_count ?? 0);

            $customer->report_sales_value =
                (float) ($sales->sales_value ?? 0);

            $customer->report_payments =
                (float) ($payments->payments ?? 0);

            $customer->report_debit =
                (float) ($balance->total_debit ?? 0);

            $customer->report_credit =
                (float) ($balance->total_credit ?? 0);

            $customer->report_balance = round(
                $customer->report_debit
                - $customer->report_credit,
                2
            );

            return $customer;
        });

        /*
        |--------------------------------------------------------------------------
        | Summary Cards
        |--------------------------------------------------------------------------
        */

        $totalCustomers = Customer::query()
            ->where('status', 'Active')
            ->count();

        $customersWithSales = (clone $salesQuery)
            ->distinct('customer_id')
            ->count('customer_id');

        $salesValue = (clone $salesQuery)
            ->sum('grand_total');

        $invoiceCount = (clone $salesQuery)
            ->count();

        $customerPayments = CustomerLedgerEntry::query()
            ->whereIn('branch_id', $branchIds)
            ->whereNotNull('customer_payment_id')
            ->when($from, function ($query) use ($from) {
                $query->whereDate('transaction_date', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('transaction_date', '<=', $to);
            })
            ->sum('credit');

        $outstandingReceivables =
            CustomerLedgerEntry::query()
                ->whereIn('branch_id', $branchIds)
                ->sum('debit')
            -
            CustomerLedgerEntry::query()
                ->whereIn('branch_id', $branchIds)
                ->sum('credit');

        return view('reports.customers', [
            'from' => $from,
            'to' => $to,
            'search' => $request->input('search'),

            'customers' => $customers,

            'totalCustomers' => $totalCustomers,
            'customersWithSales' => $customersWithSales,
            'salesValue' => $salesValue,
            'invoiceCount' => $invoiceCount,
            'customerPayments' => $customerPayments,
            'outstandingReceivables' => $outstandingReceivables,
        ]);
    }

    public function suppliers(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $from = $request->input('from');
        $to   = $request->input('to');
        $search = trim((string) $request->input('search'));

        $branchIds = $user->hasRole('super_admin')
            ? null
            : $user->accessibleBranchIds();

        /*
        |--------------------------------------------------------------------------
        | Purchase Order Query
        |--------------------------------------------------------------------------
        */

        $purchaseOrders = PurchaseOrder::query()
            ->with([
                'supplier',
                'warehouse.branch',
                'items',
            ])
            ->when($branchIds !== null, function ($query) use ($branchIds) {
                $query->whereHas('warehouse', function ($warehouseQuery) use ($branchIds) {
                    $warehouseQuery->whereIn('branch_id', $branchIds);
                });
            })
            ->when($from, function ($query) use ($from) {
                $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('created_at', '<=', $to);
            });

        /*
        |--------------------------------------------------------------------------
        | Supplier Search
        |--------------------------------------------------------------------------
        */

        $supplierIds = Supplier::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->pluck('id');

        if ($search !== '') {
            $purchaseOrders->whereIn('supplier_id', $supplierIds);
        }

        /*
        |--------------------------------------------------------------------------
        | Summary KPIs
        |--------------------------------------------------------------------------
        */

        $allPurchaseOrders = (clone $purchaseOrders)->get();

        $totalPurchaseOrders = $allPurchaseOrders->count();

        $approvedPurchaseOrders = $allPurchaseOrders
            ->where('status', 'approved')
            ->count();

        $completedPurchaseOrders = $allPurchaseOrders
            ->where('status', 'closed')
            ->count();

        $cancelledPurchaseOrders = $allPurchaseOrders
            ->where('status', 'cancelled')
            ->count();

        $totalPurchaseValue = $allPurchaseOrders->sum(
            fn ($po) => (float) $po->grand_total
        );

        $supplierCount = Supplier::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->count();

        $activeSupplierCount = Supplier::query()
            ->where('status', 'Active')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Supplier Performance
        |--------------------------------------------------------------------------
        */

        $supplierSummary = Supplier::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->get()
            ->map(function ($supplier) use ($purchaseOrders) {

                $orders = (clone $purchaseOrders)
                    ->where('supplier_id', $supplier->id)
                    ->get();

                $purchaseValue = $orders->sum(
                    fn ($po) => (float) $po->grand_total
                );

                $orderedQuantity = 0;
                $receivedQuantity = 0;
                $receivedValue = 0;

                foreach ($orders as $order) {
                    foreach ($order->items as $item) {
                        $ordered = (float) $item->quantity;
                        $received = (float) ($item->received_quantity ?? 0);
                        $unitCost = (float) $item->unit_cost;

                        $orderedQuantity += $ordered;
                        $receivedQuantity += $received;
                        $receivedValue += $received * $unitCost;
                    }
                }

                $pendingQuantity = max(
                    0,
                    $orderedQuantity - $receivedQuantity
                );

                $pendingValue = max(
                    0,
                    $purchaseValue - $receivedValue
                );

                return (object) [
                    'supplier'          => $supplier,
                    'purchase_orders'   => $orders->count(),
                    'purchase_value'    => $purchaseValue,
                    'ordered_quantity'  => $orderedQuantity,
                    'received_quantity' => $receivedQuantity,
                    'received_value'    => $receivedValue,
                    'pending_quantity'  => $pendingQuantity,
                    'pending_value'     => $pendingValue,
                ];
            })
            ->sortByDesc('purchase_value');

        /*
        |--------------------------------------------------------------------------
        | Paginated Supplier List
        |--------------------------------------------------------------------------
        */

        $suppliers = $supplierSummary->values();

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        $currentItems = $suppliers
            ->slice(($currentPage - 1) * $perPage, $perPage)
            ->values();

        $suppliers = new LengthAwarePaginator(
            $currentItems,
            $suppliers->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('reports.suppliers', compact(
            'suppliers',
            'supplierCount',
            'activeSupplierCount',
            'totalPurchaseOrders',
            'approvedPurchaseOrders',
            'completedPurchaseOrders',
            'cancelledPurchaseOrders',
            'totalPurchaseValue',
            'from',
            'to',
            'search',
        ));
    }

    public function profitability(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $from = $validated['from'] ?? null;
        $to   = $validated['to'] ?? null;

        $branchIds = $user->hasRole('super_admin')
            ? null
            : $user->accessibleBranchIds();

        /*
        |--------------------------------------------------------------------------
        | Sales
        |--------------------------------------------------------------------------
        */

        $sales = Sale::query()
            ->with([
                'items.product',
                'items.productVariant',
            ])
            ->whereIn('status', [
                'completed',
                'partially_refunded',
                'refunded',
            ])
            ->when($branchIds !== null, function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            })
            ->when($from, function ($query) use ($from) {
                $query->whereDate('sale_date', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('sale_date', '<=', $to);
            })
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Completed Returns
        |--------------------------------------------------------------------------
        */

        $returns = SaleReturn::query()
            ->with([
                'items.product',
                'items.productVariant',
            ])
            ->where('status', 'completed')
            ->when($branchIds !== null, function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            })
            ->when($from, function ($query) use ($from) {
                $query->whereDate('return_date', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('return_date', '<=', $to);
            })
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Inventory Cost Movements
        |--------------------------------------------------------------------------
        |
        | Sale movements = outgoing stock / COGS
        | Sale return movements = stock returned / COGS reversal
        |
        */

        $stockMovements = StockMovement::query()
            ->whereIn('movement_type', [
                'sale',
                'sale_return',
            ])
            ->when($branchIds !== null, function ($query) use ($branchIds) {
                $query->whereHas('warehouse', function ($warehouseQuery) use ($branchIds) {
                    $warehouseQuery->whereIn('branch_id', $branchIds);
                });
            })
            ->when($from, function ($query) use ($from) {
                $query->whereDate('occurred_at', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                $query->whereDate('occurred_at', '<=', $to);
            })
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $invoiceCount = $sales->count();

        $grossSales = 0;
        $discountAmount = 0;
        $salesTax = 0;

        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {

                $grossSales +=
                    (float) $item->quantity *
                    (float) $item->unit_price;

                $discountAmount +=
                    (float) $item->discount_amount;

                $salesTax +=
                    (float) $item->tax_amount;
            }
        }

        $returnAmount = 0;
        $returnTax = 0;

        foreach ($returns as $return) {
            foreach ($return->items as $item) {

                $returnAmount +=
                    (float) $item->line_total -
                    (float) $item->tax_amount;

                $returnTax +=
                    (float) $item->tax_amount;
            }
        }

        $salesNetBeforeReturns =
            $grossSales - $discountAmount;

        $netSales =
            $salesNetBeforeReturns - $returnAmount;

        $netTax =
            $salesTax - $returnTax;

        /*
        |--------------------------------------------------------------------------
        | Cost Of Goods Sold
        |--------------------------------------------------------------------------
        */

        $saleCogsByProduct = $stockMovements
            ->where('movement_type', 'sale')
            ->groupBy('product_id')
            ->map(function ($movements) {
                return $movements->sum(function ($movement) {
                    return abs((float) $movement->quantity)
                        * (float) ($movement->unit_cost ?? 0);
                });
            });

        $returnCogsByProduct = $stockMovements
            ->where('movement_type', 'sale_return')
            ->groupBy('product_id')
            ->map(function ($movements) {
                return $movements->sum(function ($movement) {
                    return (float) $movement->quantity
                        * (float) ($movement->unit_cost ?? 0);
                });
            });

        /*
        |--------------------------------------------------------------------------
        | Product Profitability
        |--------------------------------------------------------------------------
        */

        $productStats = [];

        foreach ($sales as $sale) {

            foreach ($sale->items as $item) {

                $productId = (int) $item->product_id;

                if (!isset($productStats[$productId])) {

                    $productStats[$productId] = [
                        'product_id'       => $productId,
                        'product_name'     => $item->product?->name ?? 'Unknown Product',
                        'quantity_sold'    => 0,
                        'gross_sales'     => 0,
                        'discount'        => 0,
                        'returns'         => 0,
                        'net_sales'       => 0,
                        'cogs'            => 0,
                        'gross_profit'    => 0,
                        'margin'          => 0,
                    ];
                }

                $gross =
                    (float) $item->quantity *
                    (float) $item->unit_price;

                $discount =
                    (float) $item->discount_amount;

                $net =
                    $gross - $discount;

                $productStats[$productId]['quantity_sold'] +=
                    (float) $item->quantity;

                $productStats[$productId]['gross_sales'] +=
                    $gross;

                $productStats[$productId]['discount'] +=
                    $discount;

                $productStats[$productId]['net_sales'] +=
                    $net;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Apply Returns
        |--------------------------------------------------------------------------
        */

        foreach ($returns as $return) {

            foreach ($return->items as $item) {

                $productId = (int) $item->product_id;

                if (!isset($productStats[$productId])) {

                    $productStats[$productId] = [
                        'product_id'       => $productId,
                        'product_name'     => $item->product?->name ?? 'Unknown Product',
                        'quantity_sold'    => 0,
                        'gross_sales'     => 0,
                        'discount'        => 0,
                        'returns'         => 0,
                        'net_sales'       => 0,
                        'cogs'            => 0,
                        'gross_profit'    => 0,
                        'margin'          => 0,
                    ];
                }

                $returnNet =
                    (float) $item->line_total -
                    (float) $item->tax_amount;

                $productStats[$productId]['returns'] +=
                    $returnNet;

                $productStats[$productId]['quantity_sold'] -=
                    (float) $item->quantity;

                $productStats[$productId]['net_sales'] -=
                    $returnNet;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Apply COGS
        |--------------------------------------------------------------------------
        */

        foreach ($productStats as $productId => &$row) {

            $saleCogs =
                (float) ($saleCogsByProduct[$productId] ?? 0);

            $returnCogs =
                (float) ($returnCogsByProduct[$productId] ?? 0);

            $row['cogs'] =
                $saleCogs - $returnCogs;

            $row['gross_profit'] =
                $row['net_sales'] - $row['cogs'];

            $row['margin'] =
                $row['net_sales'] > 0
                    ? ($row['gross_profit'] / $row['net_sales']) * 100
                    : 0;
        }

        unset($row);

        /*
        |--------------------------------------------------------------------------
        | Product Sorting
        |--------------------------------------------------------------------------
        */

        $productStats = collect($productStats)
            ->sortByDesc('gross_profit')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Summary COGS / Profit
        |--------------------------------------------------------------------------
        */

        $totalCogs = $productStats->sum('cogs');

        $grossProfit =
            $netSales - $totalCogs;

        $grossMargin =
            $netSales > 0
                ? ($grossProfit / $netSales) * 100
                : 0;

        /*
        |--------------------------------------------------------------------------
        | Missing Cost Information
        |--------------------------------------------------------------------------
        */

        $missingCostMovements = $stockMovements
            ->filter(function ($movement) {
                return in_array(
                    $movement->movement_type,
                    ['sale', 'sale_return'],
                    true
                )
                && $movement->unit_cost === null;
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = 15;

        $currentPage =
            LengthAwarePaginator::resolveCurrentPage();

        $currentItems = $productStats
            ->slice(
                ($currentPage - 1) * $perPage,
                $perPage
            )
            ->values();

        $products = new LengthAwarePaginator(
            $currentItems,
            $productStats->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return view('reports.profitability', compact(
            'products',
            'from',
            'to',
            'invoiceCount',
            'grossSales',
            'discountAmount',
            'returnAmount',
            'netSales',
            'totalCogs',
            'grossProfit',
            'grossMargin',
            'netTax',
            'missingCostMovements',
        ));
    }
}