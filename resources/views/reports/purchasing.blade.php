@extends('layouts.app')

@section('title', 'Purchasing Report')

@section('content')

<div class="report-page">

    <div class="report-header">
        <div>
            <h1>Purchasing Report</h1>
            <p>Purchase orders, supplier activity and goods receipts.</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('reports.purchasing') }}" class="filter-card">

        <div class="filter-group">
            <label>Created From</label>
            <input
                type="date"
                name="from"
                value="{{ $from }}"
            >
        </div>

        <div class="filter-group">
            <label>Created To</label>
            <input
                type="date"
                name="to"
                value="{{ $to }}"
            >
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-primary">
                Apply
            </button>

            <a href="{{ route('reports.purchasing') }}" class="btn-secondary">
                Reset
            </a>
        </div>

    </form>


    {{-- KPI Cards --}}
    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-label">Purchase Orders</div>
            <div class="kpi-value">
                {{ number_format($totalPurchaseOrders) }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Approved / Processing</div>
            <div class="kpi-value">
                {{ number_format($approvedPurchaseOrders) }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Purchase Value</div>
            <div class="kpi-value">
                ₨ {{ number_format($totalPurchaseValue, 2) }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Ordered Quantity</div>
            <div class="kpi-value">
                {{ number_format($totalOrderedQuantity, 2) }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Received Quantity</div>
            <div class="kpi-value">
                {{ number_format($totalReceivedQuantity, 2) }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Pending Quantity</div>
            <div class="kpi-value">
                {{ number_format($totalPendingQuantity, 2) }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Goods Receipts</div>
            <div class="kpi-value">
                {{ number_format($goodsReceiptCount) }}
            </div>
        </div>

        <div class="kpi-card">
            <div class="kpi-label">Received Value</div>
            <div class="kpi-value">
                ₨ {{ number_format($receivedValue, 2) }}
            </div>
        </div>

    </div>


    {{-- Supplier Summary --}}
    <div class="report-card">

        <div class="card-header">
            <h2>Supplier Summary</h2>
        </div>

        <div class="table-wrapper">

            <table class="report-table">

                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Orders</th>
                        <th>Purchase Value</th>
                        <th>Ordered Qty</th>
                        <th>Received Qty</th>
                        <th>Pending Qty</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($supplierSummary as $row)

                        @php
                            $pending = max(
                                0,
                                $row['ordered_quantity'] - $row['received_quantity']
                            );
                        @endphp

                        <tr>
                            <td>
                                {{ $row['supplier']?->name ?? 'Unknown Supplier' }}
                            </td>

                            <td>
                                {{ number_format($row['orders']) }}
                            </td>

                            <td>
                                ₨ {{ number_format($row['value'], 2) }}
                            </td>

                            <td>
                                {{ number_format($row['ordered_quantity'], 2) }}
                            </td>

                            <td>
                                {{ number_format($row['received_quantity'], 2) }}
                            </td>

                            <td>
                                {{ number_format($pending, 2) }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="empty-state">
                                No supplier data found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Warehouse Summary --}}
    <div class="report-card">

        <div class="card-header">
            <h2>Warehouse / Branch Summary</h2>
        </div>

        <div class="table-wrapper">

            <table class="report-table">

                <thead>
                    <tr>
                        <th>Warehouse</th>
                        <th>Branch</th>
                        <th>Orders</th>
                        <th>Purchase Value</th>
                        <th>Ordered Qty</th>
                        <th>Received Qty</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($warehouseSummary as $row)

                        <tr>
                            <td>
                                {{ $row['warehouse']?->name ?? 'Unknown' }}
                            </td>

                            <td>
                                {{ $row['branch']?->name ?? 'Unknown' }}
                            </td>

                            <td>
                                {{ number_format($row['orders']) }}
                            </td>

                            <td>
                                ₨ {{ number_format($row['value'], 2) }}
                            </td>

                            <td>
                                {{ number_format($row['ordered_quantity'], 2) }}
                            </td>

                            <td>
                                {{ number_format($row['received_quantity'], 2) }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="empty-state">
                                No warehouse data found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Purchase Order Detail --}}
    <div class="report-card">

        <div class="card-header">
            <h2>Purchase Order Detail</h2>
        </div>

        <div class="table-wrapper">

            <table class="report-table">

                <thead>
                    <tr>
                        <th>PO</th>
                        <th>Supplier</th>
                        <th>Warehouse</th>
                        <th>Branch</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Order Value</th>
                        <th>Ordered Qty</th>
                        <th>Received Qty</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($purchaseOrders as $purchaseOrder)

                        @php
                            $orderValue = $purchaseOrder->items->sum(function ($item) {
                                return (float) $item->quantity
                                    * (float) ($item->unit_cost ?? 0);
                            });

                            $orderedQty = $purchaseOrder->items->sum(function ($item) {
                                return (float) $item->quantity;
                            });

                            $receivedQty = $purchaseOrder->items->sum(function ($item) {
                                return (float) ($item->received_quantity ?? 0);
                            });
                        @endphp

                        <tr>

                            <td>
                                <a
                                    href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                                    class="table-link"
                                >
                                    {{ $purchaseOrder->order_number ?? 'PO-' . $purchaseOrder->id }}
                                </a>
                            </td>

                            <td>
                                {{ $purchaseOrder->supplier?->name ?? 'Unknown' }}
                            </td>

                            <td>
                                {{ $purchaseOrder->warehouse?->name ?? 'Unknown' }}
                            </td>

                            <td>
                                {{ $purchaseOrder->warehouse?->branch?->name ?? 'Unknown' }}
                            </td>

                            <td>
                                {{ ucwords(str_replace('_', ' ', $purchaseOrder->status)) }}
                            </td>

                            <td>
                                {{ $purchaseOrder->created_at?->format('Y-m-d') }}
                            </td>

                            <td>
                                ₨ {{ number_format($orderValue, 2) }}
                            </td>

                            <td>
                                {{ number_format($orderedQty, 2) }}
                            </td>

                            <td>
                                {{ number_format($receivedQty, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="empty-state">
                                No purchase orders found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination-wrapper">
            {{ $purchaseOrders->links() }}
        </div>

    </div>


    {{-- Recent Goods Receipts --}}
    <div class="report-card">

        <div class="card-header">
            <h2>Goods Receipt Summary</h2>
        </div>

        <div class="receipt-summary">

            <div>
                <span>Total Receipts</span>
                <strong>{{ number_format($goodsReceiptCount) }}</strong>
            </div>

            <div>
                <span>Posted Receipts</span>
                <strong>{{ number_format($postedGoodsReceiptCount) }}</strong>
            </div>

            <div>
                <span>Received Value</span>
                <strong>
                    ₨ {{ number_format($receivedValue, 2) }}
                </strong>
            </div>

        </div>

    </div>

</div>


<style>

.report-page {
    padding: 24px;
}

.report-header {
    margin-bottom: 24px;
}

.report-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.report-header p {
    margin-top: 6px;
    color: #6b7280;
}

.filter-card,
.report-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 24px;
}

.filter-card {
    display: flex;
    gap: 16px;
    align-items: end;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-group label {
    font-size: 13px;
    font-weight: 600;
}

.filter-group input {
    padding: 9px 11px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
}

.filter-actions {
    display: flex;
    gap: 8px;
}

.btn-primary,
.btn-secondary {
    padding: 9px 16px;
    border-radius: 6px;
    text-decoration: none;
    border: 0;
    cursor: pointer;
}

.btn-primary {
    background: #111827;
    color: #fff;
}

.btn-secondary {
    background: #f3f4f6;
    color: #111827;
}

.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}

.kpi-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 18px;
}

.kpi-label {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 8px;
}

.kpi-value {
    font-size: 23px;
    font-weight: 700;
}

.card-header {
    margin-bottom: 16px;
}

.card-header h2 {
    margin: 0;
    font-size: 19px;
}

.table-wrapper {
    overflow-x: auto;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table th,
.report-table td {
    padding: 11px 12px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
    white-space: nowrap;
}

.report-table th {
    background: #f9fafb;
    font-size: 13px;
}

.report-table td {
    font-size: 14px;
}

.table-link {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
}

.empty-state {
    text-align: center !important;
    padding: 30px !important;
    color: #6b7280;
}

.pagination-wrapper {
    margin-top: 18px;
}

.receipt-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.receipt-summary div {
    background: #f9fafb;
    padding: 16px;
    border-radius: 8px;
}

.receipt-summary span {
    display: block;
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 6px;
}

.receipt-summary strong {
    font-size: 20px;
}

@media (max-width: 1100px) {
    .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 700px) {
    .kpi-grid,
    .receipt-summary {
        grid-template-columns: 1fr;
    }
}

</style>

@endsection