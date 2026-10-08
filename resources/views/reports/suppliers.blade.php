@extends('layouts.app')

@section('title', 'Supplier Report')

@section('content')

<style>
    .report-page {
        max-width: 1600px;
        margin: 0 auto;
    }

    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .report-title h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #111827;
    }

    .report-title p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .report-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .report-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 14px;
        border-radius: 8px;
        text-decoration: none;
        border: 1px solid #d1d5db;
        background: #ffffff;
        color: #374151;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .report-btn:hover {
        background: #f9fafb;
    }

    .report-btn-primary {
        background: #111827;
        color: #ffffff;
        border-color: #111827;
    }

    .report-btn-primary:hover {
        background: #1f2937;
    }

    .filter-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: minmax(220px, 2fr) repeat(2, minmax(160px, 1fr)) auto auto;
        gap: 14px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .filter-group input {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #ffffff;
        color: #111827;
        font-size: 14px;
        outline: none;
    }

    .filter-group input:focus {
        border-color: #6b7280;
        box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.12);
    }

    .filter-actions {
        display: flex;
        gap: 8px;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .summary-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .summary-card-label {
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .summary-card-value {
        color: #111827;
        font-size: 25px;
        font-weight: 700;
        line-height: 1.2;
    }

    .summary-card-meta {
        margin-top: 7px;
        font-size: 12px;
        color: #9ca3af;
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .status-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px 18px;
    }

    .status-card-title {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 5px;
    }

    .status-card-value {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
    }

    .report-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .report-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .report-card-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #111827;
    }

    .report-card-header span {
        color: #6b7280;
        font-size: 13px;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .supplier-table {
        width: 100%;
        min-width: 1250px;
        border-collapse: collapse;
    }

    .supplier-table th {
        padding: 13px 16px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        color: #6b7280;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .supplier-table td {
        padding: 15px 16px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        font-size: 14px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .supplier-table tbody tr:hover {
        background: #fafafa;
    }

    .supplier-name {
        font-weight: 700;
        color: #111827;
    }

    .supplier-code {
        color: #6b7280;
        font-size: 12px;
        margin-top: 3px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-active {
        background: #ecfdf3;
        color: #047857;
    }

    .status-inactive {
        background: #fef2f2;
        color: #b91c1c;
    }

    .number-cell {
        text-align: right;
    }

    .purchase-value {
        font-weight: 700;
        color: #111827;
    }

    .pending-value {
        font-weight: 700;
        color: #b45309;
    }

    .received-value {
        color: #047857;
        font-weight: 600;
    }

    .progress-wrapper {
        width: 125px;
    }

    .progress-bar {
        width: 100%;
        height: 7px;
        background: #e5e7eb;
        border-radius: 999px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: #111827;
        border-radius: 999px;
    }

    .progress-text {
        margin-top: 5px;
        color: #6b7280;
        font-size: 11px;
    }

    .view-link {
        display: inline-flex;
        align-items: center;
        padding: 7px 10px;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        color: #374151;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
    }

    .view-link:hover {
        background: #f9fafb;
    }

    .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 34px;
        margin-bottom: 10px;
        color: #9ca3af;
    }

    .empty-state h3 {
        margin: 0 0 6px;
        color: #374151;
        font-size: 16px;
    }

    .empty-state p {
        margin: 0;
        color: #9ca3af;
        font-size: 13px;
    }

    .pagination-area {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 18px 20px;
        flex-wrap: wrap;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 13px;
    }

    .pagination {
        display: flex;
        gap: 6px;
        align-items: center;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        min-width: 34px;
        height: 34px;
        padding: 0 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13px;
        color: #374151;
        background: #ffffff;
        box-sizing: border-box;
    }

    .pagination a:hover {
        background: #f9fafb;
    }

    .pagination .active {
        background: #111827;
        border-color: #111827;
        color: #ffffff;
        font-weight: 700;
    }

    .pagination .disabled {
        color: #d1d5db;
        cursor: not-allowed;
    }

    @media (max-width: 1100px) {
        .filter-grid {
            grid-template-columns: repeat(2, minmax(180px, 1fr));
        }

        .filter-actions {
            grid-column: span 2;
        }

        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .status-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 700px) {
        .report-title h1 {
            font-size: 23px;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            grid-column: auto;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .filter-actions {
            width: 100%;
        }

        .filter-actions .report-btn {
            flex: 1;
        }

        .pagination-area {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>


<div class="report-page">

    {{-- Header --}}
    <div class="report-header">

        <div class="report-title">
            <h1>Supplier Report</h1>

            <p>
                Analyse supplier activity, purchase orders, received quantities and pending purchases.
            </p>
        </div>

        <div class="report-actions">

            <a
                href="{{ route('suppliers.index') }}"
                class="report-btn"
            >
                Suppliers
            </a>

            <a
                href="{{ route('purchase-orders.index') }}"
                class="report-btn"
            >
                Purchase Orders
            </a>

        </div>

    </div>


    {{-- Filters --}}
    <div class="filter-card">

        <form method="GET" action="{{ route('reports.suppliers') }}">

            <div class="filter-grid">

                <div class="filter-group">
                    <label for="search">Supplier</label>

                    <input
                        id="search"
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search by name, code, phone or email"
                    >
                </div>


                <div class="filter-group">
                    <label for="from">From Date</label>

                    <input
                        id="from"
                        type="date"
                        name="from"
                        value="{{ $from }}"
                    >
                </div>


                <div class="filter-group">
                    <label for="to">To Date</label>

                    <input
                        id="to"
                        type="date"
                        name="to"
                        value="{{ $to }}"
                    >
                </div>


                <div class="filter-actions">

                    <button
                        type="submit"
                        class="report-btn report-btn-primary"
                    >
                        Filter
                    </button>

                </div>


                <div class="filter-actions">

                    <a
                        href="{{ route('reports.suppliers') }}"
                        class="report-btn"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- Main KPI Cards --}}
    <div class="summary-grid">

        <div class="summary-card">

            <div class="summary-card-label">
                Total Suppliers
            </div>

            <div class="summary-card-value">
                {{ number_format($supplierCount) }}
            </div>

            <div class="summary-card-meta">
                Suppliers matching current search
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-card-label">
                Active Suppliers
            </div>

            <div class="summary-card-value">
                {{ number_format($activeSupplierCount) }}
            </div>

            <div class="summary-card-meta">
                Currently active
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-card-label">
                Purchase Orders
            </div>

            <div class="summary-card-value">
                {{ number_format($totalPurchaseOrders) }}
            </div>

            <div class="summary-card-meta">
                Orders in selected period
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-card-label">
                Purchase Value
            </div>

            <div class="summary-card-value">
                {{ number_format($totalPurchaseValue, 2) }}
            </div>

            <div class="summary-card-meta">
                Total purchase order value
            </div>

        </div>

    </div>


    {{-- Order Status Summary --}}
    <div class="status-grid">

        <div class="status-card">

            <div class="status-card-title">
                Approved Orders
            </div>

            <div class="status-card-value">
                {{ number_format($approvedPurchaseOrders) }}
            </div>

        </div>


        <div class="status-card">

            <div class="status-card-title">
                Completed Orders
            </div>

            <div class="status-card-value">
                {{ number_format($completedPurchaseOrders) }}
            </div>

        </div>


        <div class="status-card">

            <div class="status-card-title">
                Cancelled Orders
            </div>

            <div class="status-card-value">
                {{ number_format($cancelledPurchaseOrders) }}
            </div>

        </div>

    </div>


    {{-- Supplier Table --}}
    <div class="report-card">

        <div class="report-card-header">

            <div>
                <h2>Supplier Performance</h2>
            </div>

            <span>
                {{ number_format($suppliers->total()) }} suppliers
            </span>

        </div>


        <div class="table-wrapper">

            @if($suppliers->count())

                <table class="supplier-table">

                    <thead>

                        <tr>

                            <th>Supplier</th>
                            <th>Status</th>
                            <th class="number-cell">Orders</th>
                            <th class="number-cell">Purchase Value</th>
                            <th class="number-cell">Ordered Qty</th>
                            <th class="number-cell">Received Qty</th>
                            <th>Received Progress</th>
                            <th class="number-cell">Received Value</th>
                            <th class="number-cell">Pending Qty</th>
                            <th class="number-cell">Pending Value</th>
                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($suppliers as $row)

                            @php

                                $orderedQuantity = max((float) $row->ordered_quantity, 0);

                                $receivedQuantity = max((float) $row->received_quantity, 0);

                                $receiveRate = $orderedQuantity > 0
                                    ? min(100, ($receivedQuantity / $orderedQuantity) * 100)
                                    : 0;

                                $supplierStatus = strtolower((string) $row->supplier->status);

                            @endphp

                            <tr>

                                <td>

                                    <div class="supplier-name">
                                        {{ $row->supplier->name }}
                                    </div>

                                    @if($row->supplier->code)
                                        <div class="supplier-code">
                                            {{ $row->supplier->code }}
                                        </div>
                                    @endif

                                </td>


                                <td>

                                    <span
                                        class="status-badge
                                        {{ $supplierStatus === 'active'
                                            ? 'status-active'
                                            : 'status-inactive' }}"
                                    >
                                        {{ $row->supplier->status }}
                                    </span>

                                </td>


                                <td class="number-cell">
                                    {{ number_format($row->purchase_orders) }}
                                </td>


                                <td class="number-cell purchase-value">
                                    {{ number_format($row->purchase_value, 2) }}
                                </td>


                                <td class="number-cell">
                                    {{ number_format($row->ordered_quantity, 2) }}
                                </td>


                                <td class="number-cell received-value">
                                    {{ number_format($row->received_quantity, 2) }}
                                </td>


                                <td>

                                    <div class="progress-wrapper">

                                        <div class="progress-bar">

                                            <div
                                                class="progress-fill"
                                                style="width: {{ $receiveRate }}%;"
                                            ></div>

                                        </div>

                                        <div class="progress-text">
                                            {{ number_format($receiveRate, 1) }}% received
                                        </div>

                                    </div>

                                </td>


                                <td class="number-cell received-value">
                                    {{ number_format($row->received_value, 2) }}
                                </td>


                                <td class="number-cell">
                                    {{ number_format($row->pending_quantity, 2) }}
                                </td>


                                <td class="number-cell pending-value">
                                    {{ number_format($row->pending_value, 2) }}
                                </td>


                                <td>

                                    <a
                                        href="{{ route('suppliers.show', $row->supplier) }}"
                                        class="view-link"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">

                    <div class="empty-state-icon">
                        ♜
                    </div>

                    <h3>
                        No suppliers found
                    </h3>

                    <p>
                        Try changing your search or date filters.
                    </p>

                </div>

            @endif

        </div>


        @if($suppliers->total() > 0)

            @php

                $start = (($suppliers->currentPage() - 1) * $suppliers->perPage()) + 1;

                $end = min(
                    $suppliers->currentPage() * $suppliers->perPage(),
                    $suppliers->total()
                );

            @endphp

            <div class="pagination-area">

                <div class="pagination-info">
                    Showing {{ $start }}–{{ $end }}
                    of {{ $suppliers->total() }} suppliers
                </div>


                @if($suppliers->lastPage() > 1)

                    <div class="pagination">

                        {{-- Previous --}}
                        @if($suppliers->onFirstPage())

                            <span class="disabled">
                                ‹
                            </span>

                        @else

                            <a href="{{ $suppliers->url($suppliers->currentPage() - 1) }}">
                                ‹
                            </a>

                        @endif


                        {{-- Page Numbers --}}
                        @for($page = 1; $page <= $suppliers->lastPage(); $page++)

                            @if(
                                $page === 1 ||
                                $page === $suppliers->lastPage() ||
                                abs($page - $suppliers->currentPage()) <= 2
                            )

                                @if($page === $suppliers->currentPage())

                                    <span class="active">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a href="{{ $suppliers->url($page) }}">
                                        {{ $page }}
                                    </a>

                                @endif

                            @elseif(
                                $page === 2 ||
                                $page === $suppliers->lastPage() - 1
                            )

                                <span>
                                    …
                                </span>

                            @endif

                        @endfor


                        {{-- Next --}}
                        @if($suppliers->hasMorePages())

                            <a href="{{ $suppliers->url($suppliers->currentPage() + 1) }}">
                                ›
                            </a>

                        @else

                            <span class="disabled">
                                ›
                            </span>

                        @endif

                    </div>

                @endif

            </div>

        @endif

    </div>

</div>

@endsection