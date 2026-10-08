@extends('layouts.app')

@section('title', 'Profitability Report')

@section('content')

<style>
    .profit-page {
        max-width: 1600px;
        margin: 0 auto;
    }

    .profit-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .profit-header h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #111827;
    }

    .profit-header p {
        margin: 6px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 9px 14px;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: #fff;
        color: #374151;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn:hover {
        background: #f9fafb;
    }

    .btn-dark {
        background: #111827;
        border-color: #111827;
        color: #fff;
    }

    .btn-dark:hover {
        background: #1f2937;
    }

    .filter-card,
    .report-card,
    .notice-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }

    .filter-card {
        padding: 20px;
        margin-bottom: 24px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(180px, 1fr)) auto auto;
        gap: 14px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .filter-group label {
        font-size: 12px;
        font-weight: 700;
        color: #374151;
    }

    .filter-group input {
        width: 100%;
        box-sizing: border-box;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        outline: none;
    }

    .filter-group input:focus {
        border-color: #6b7280;
        box-shadow: 0 0 0 3px rgba(107,114,128,.12);
    }

    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(180px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .kpi-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }

    .kpi-label {
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 9px;
    }

    .kpi-value {
        color: #111827;
        font-size: 24px;
        font-weight: 700;
    }

    .kpi-sub {
        margin-top: 6px;
        color: #9ca3af;
        font-size: 11px;
    }

    .profit-card {
        border-top: 3px solid #047857;
    }

    .loss-card {
        border-top: 3px solid #b91c1c;
    }

    .notice-card {
        padding: 15px 18px;
        margin-bottom: 24px;
        background: #fffbeb;
        border-color: #fde68a;
        color: #92400e;
        font-size: 13px;
        line-height: 1.5;
    }

    .report-card {
        overflow: hidden;
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
        color: #111827;
    }

    .report-card-header span {
        color: #6b7280;
        font-size: 12px;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .profit-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .profit-table th {
        padding: 13px 15px;
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        color: #6b7280;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .profit-table td {
        padding: 14px 15px;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
        font-size: 13px;
        white-space: nowrap;
    }

    .profit-table tbody tr:hover {
        background: #fafafa;
    }

    .product-name {
        font-weight: 700;
        color: #111827;
    }

    .product-id {
        margin-top: 3px;
        color: #9ca3af;
        font-size: 11px;
    }

    .number {
        text-align: right;
    }

    .profit-positive {
        color: #047857;
        font-weight: 700;
    }

    .profit-negative {
        color: #b91c1c;
        font-weight: 700;
    }

    .margin {
        font-weight: 700;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #9ca3af;
    }

    .pagination-area {
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 12px;
    }

    .pagination {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        min-width: 34px;
        height: 34px;
        padding: 0 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #d1d5db;
        border-radius: 7px;
        background: #fff;
        color: #374151;
        text-decoration: none;
        font-size: 12px;
        box-sizing: border-box;
    }

    .pagination .active {
        background: #111827;
        border-color: #111827;
        color: #fff;
        font-weight: 700;
    }

    .pagination .disabled {
        color: #d1d5db;
    }

    @media (max-width: 1050px) {
        .kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 650px) {
        .kpi-grid,
        .filter-grid {
            grid-template-columns: 1fr;
        }

        .profit-header h1 {
            font-size: 23px;
        }

        .header-actions {
            width: 100%;
        }

        .header-actions .btn {
            flex: 1;
        }
    }
</style>


<div class="profit-page">

    {{-- Header --}}
    <div class="profit-header">

        <div>
            <h1>Profitability Report</h1>

            <p>
                Analyse sales revenue, returns, inventory cost and gross profit.
            </p>
        </div>

        <div class="header-actions">

            <a
                href="{{ route('reports.sales') }}"
                class="btn"
            >
                Sales Report
            </a>

            <a
                href="{{ route('reports.inventory') }}"
                class="btn"
            >
                Inventory Report
            </a>

        </div>

    </div>


    {{-- Filters --}}
    <div class="filter-card">

        <form method="GET" action="{{ route('reports.profitability') }}">

            <div class="filter-grid">

                <div class="filter-group">
                    <label for="from">
                        From Date
                    </label>

                    <input
                        id="from"
                        type="date"
                        name="from"
                        value="{{ $from }}"
                    >
                </div>

                <div class="filter-group">
                    <label for="to">
                        To Date
                    </label>

                    <input
                        id="to"
                        type="date"
                        name="to"
                        value="{{ $to }}"
                    >
                </div>

                <button
                    type="submit"
                    class="btn btn-dark"
                >
                    Apply Filter
                </button>

                <a
                    href="{{ route('reports.profitability') }}"
                    class="btn"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>


    {{-- KPI Cards --}}
    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-label">
                Gross Sales
            </div>

            <div class="kpi-value">
                {{ number_format($grossSales, 2) }}
            </div>

            <div class="kpi-sub">
                Before discounts, excluding tax
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">
                Discounts
            </div>

            <div class="kpi-value">
                {{ number_format($discountAmount, 2) }}
            </div>

            <div class="kpi-sub">
                Sales discounts
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">
                Returns
            </div>

            <div class="kpi-value">
                {{ number_format($returnAmount, 2) }}
            </div>

            <div class="kpi-sub">
                Completed sale returns
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">
                Net Sales
            </div>

            <div class="kpi-value">
                {{ number_format($netSales, 2) }}
            </div>

            <div class="kpi-sub">
                After discounts and returns
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">
                Cost of Goods Sold
            </div>

            <div class="kpi-value">
                {{ number_format($totalCogs, 2) }}
            </div>

            <div class="kpi-sub">
                Based on inventory movements
            </div>
        </div>


        <div class="kpi-card {{ $grossProfit >= 0 ? 'profit-card' : 'loss-card' }}">

            <div class="kpi-label">
                Gross Profit
            </div>

            <div class="kpi-value">
                {{ number_format($grossProfit, 2) }}
            </div>

            <div class="kpi-sub">
                Net sales minus COGS
            </div>

        </div>


        <div class="kpi-card">

            <div class="kpi-label">
                Gross Margin
            </div>

            <div class="kpi-value">
                {{ number_format($grossMargin, 2) }}%
            </div>

            <div class="kpi-sub">
                Profit as % of net sales
            </div>

        </div>


        <div class="kpi-card">

            <div class="kpi-label">
                Invoices
            </div>

            <div class="kpi-value">
                {{ number_format($invoiceCount) }}
            </div>

            <div class="kpi-sub">
                Completed/refunded sales
            </div>

        </div>

    </div>


    {{-- Cost Notice --}}
    @if($missingCostMovements > 0)

        <div class="notice-card">

            <strong>Cost data notice:</strong>

            {{ number_format($missingCostMovements) }}
            inventory movement(s) have no recorded unit cost.

            Those movements contribute zero to COGS until a cost is available.

        </div>

    @endif


    {{-- Tax Information --}}
    <div class="report-card" style="margin-bottom:24px;">

        <div class="report-card-header">

            <div>
                <h2>Additional Summary</h2>
            </div>

            <span>
                Tax is excluded from profitability calculations
            </span>

        </div>

        <div style="padding:20px;">

            <strong>Net Tax:</strong>

            {{ number_format($netTax, 2) }}

        </div>

    </div>


    {{-- Product Profitability --}}
    <div class="report-card">

        <div class="report-card-header">

            <div>
                <h2>Product Profitability</h2>
            </div>

            <span>
                {{ number_format($products->total()) }} products
            </span>

        </div>


        <div class="table-wrapper">

            @if($products->count())

                <table class="profit-table">

                    <thead>

                        <tr>
                            <th>Product</th>
                            <th class="number">Qty Sold</th>
                            <th class="number">Gross Sales</th>
                            <th class="number">Discount</th>
                            <th class="number">Returns</th>
                            <th class="number">Net Sales</th>
                            <th class="number">COGS</th>
                            <th class="number">Gross Profit</th>
                            <th class="number">Margin</th>
                        </tr>

                    </thead>

                    <tbody>

                        @foreach($products as $product)

                            <tr>

                                <td>

                                    <div class="product-name">
                                        {{ $product['product_name'] }}
                                    </div>

                                    <div class="product-id">
                                        Product #{{ $product['product_id'] }}
                                    </div>

                                </td>


                                <td class="number">
                                    {{ number_format($product['quantity_sold'], 2) }}
                                </td>


                                <td class="number">
                                    {{ number_format($product['gross_sales'], 2) }}
                                </td>


                                <td class="number">
                                    {{ number_format($product['discount'], 2) }}
                                </td>


                                <td class="number">
                                    {{ number_format($product['returns'], 2) }}
                                </td>


                                <td class="number">
                                    {{ number_format($product['net_sales'], 2) }}
                                </td>


                                <td class="number">
                                    {{ number_format($product['cogs'], 2) }}
                                </td>


                                <td class="number">

                                    <span
                                        class="{{ $product['gross_profit'] >= 0
                                            ? 'profit-positive'
                                            : 'profit-negative' }}"
                                    >
                                        {{ number_format($product['gross_profit'], 2) }}
                                    </span>

                                </td>


                                <td class="number">

                                    <span
                                        class="margin {{ $product['margin'] >= 0
                                            ? 'profit-positive'
                                            : 'profit-negative' }}"
                                    >
                                        {{ number_format($product['margin'], 2) }}%
                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty-state">

                    <h3>
                        No profitability data found
                    </h3>

                    <p>
                        Try changing the selected date range.
                    </p>

                </div>

            @endif

        </div>


        {{-- Pagination --}}
        @if($products->total() > 0)

            @php
                $start =
                    (($products->currentPage() - 1) * $products->perPage()) + 1;

                $end =
                    min(
                        $products->currentPage() * $products->perPage(),
                        $products->total()
                    );
            @endphp

            <div class="pagination-area">

                <div class="pagination-info">
                    Showing {{ $start }}–{{ $end }}
                    of {{ $products->total() }} products
                </div>


                @if($products->lastPage() > 1)

                    <div class="pagination">

                        @if($products->onFirstPage())

                            <span class="disabled">
                                ‹
                            </span>

                        @else

                            <a href="{{ $products->url($products->currentPage() - 1) }}">
                                ‹
                            </a>

                        @endif


                        @for(
                            $page = 1;
                            $page <= $products->lastPage();
                            $page++
                        )

                            @if(
                                $page === 1 ||
                                $page === $products->lastPage() ||
                                abs($page - $products->currentPage()) <= 2
                            )

                                @if($page === $products->currentPage())

                                    <span class="active">
                                        {{ $page }}
                                    </span>

                                @else

                                    <a href="{{ $products->url($page) }}">
                                        {{ $page }}
                                    </a>

                                @endif

                            @elseif(
                                $page === 2 ||
                                $page === $products->lastPage() - 1
                            )

                                <span>
                                    …
                                </span>

                            @endif

                        @endfor


                        @if($products->hasMorePages())

                            <a href="{{ $products->url($products->currentPage() + 1) }}">
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