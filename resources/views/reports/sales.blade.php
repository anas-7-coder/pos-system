@extends('layouts.app')

@section('title', 'Sales Report')

@section('content')

<div class="report-page">

    <div class="report-header">
        <div>
            <h1>Sales Report</h1>
            <p>Sales performance for the selected period.</p>
        </div>

        <a href="{{ route('sales.index') }}" class="report-button">
            View Sales
        </a>
    </div>


    {{-- Filters --}}
    <div class="report-panel">

        <form method="GET" action="{{ route('reports.sales') }}">

            <div class="report-filters">

                <div>
                    <label for="from">From</label>

                    <input
                        type="date"
                        id="from"
                        name="from"
                        value="{{ $from }}"
                    >
                </div>


                <div>
                    <label for="to">To</label>

                    <input
                        type="date"
                        id="to"
                        name="to"
                        value="{{ $to }}"
                    >
                </div>


                <div class="filter-action">
                    <button type="submit" class="report-button">
                        Generate Report
                    </button>
                </div>

            </div>

        </form>

    </div>


    {{-- Summary --}}
    <div class="report-summary-grid">

        <div class="report-card">
            <span>Total Sales</span>

            <strong>
                ₨ {{ number_format($totalSales, 2) }}
            </strong>
        </div>


        <div class="report-card">
            <span>Total Invoices</span>

            <strong>
                {{ number_format($invoiceCount) }}
            </strong>
        </div>


        <div class="report-card">
            <span>Total Discount</span>

            <strong>
                ₨ {{ number_format($totalDiscount, 2) }}
            </strong>
        </div>


        <div class="report-card">
            <span>Total Tax</span>

            <strong>
                ₨ {{ number_format($totalTax, 2) }}
            </strong>
        </div>

    </div>


    {{-- Payment + Top Products --}}
    <div class="report-two-column">

        <div class="report-panel">

            <div class="report-panel-header">
                <div>
                    <h2>Payment Methods</h2>
                    <p>Posted payments during this period.</p>
                </div>
            </div>

            @forelse($paymentBreakdown as $payment)

                <div class="report-row">

                    <span>
                        {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                    </span>

                    <strong>
                        ₨ {{ number_format($payment->total, 2) }}
                    </strong>

                </div>

            @empty

                <div class="empty-state">
                    No payment data found.
                </div>

            @endforelse

        </div>


        <div class="report-panel">

            <div class="report-panel-header">
                <div>
                    <h2>Top Products</h2>
                    <p>Products with the highest quantity sold.</p>
                </div>
            </div>

            @forelse($topProducts as $item)

                <div class="report-row">

                    <div>
                        <strong>
                            {{ $item->product?->name ?? 'Unknown Product' }}
                        </strong>

                        <small>
                            {{ number_format($item->total_quantity, 2) }}
                            units
                        </small>
                    </div>

                    <strong>
                        ₨ {{ number_format($item->total_sales, 2) }}
                    </strong>

                </div>

            @empty

                <div class="empty-state">
                    No product data found.
                </div>

            @endforelse

        </div>

    </div>


    {{-- Branch Summary --}}
    @if(count($branchSummary) > 0)

        <div class="report-panel">

            <div class="report-panel-header">
                <div>
                    <h2>Branch Summary</h2>
                    <p>Sales for your accessible branches.</p>
                </div>
            </div>

            <div class="table-wrapper">

                <table class="report-table">

                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Invoices</th>
                            <th class="text-right">Sales</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($branchSummary as $branch)

                            <tr>

                                <td>
                                    {{ $branch->branch?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ number_format($branch->invoice_count) }}
                                </td>

                                <td class="text-right">
                                    ₨ {{ number_format($branch->total_sales, 2) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    @endif


    {{-- Sales Detail --}}
    <div class="report-panel">

        <div class="report-panel-header">

            <div>
                <h2>Sales Detail</h2>
                <p>
                    {{ $from }} to {{ $to }}
                </p>
            </div>

        </div>


        @if($sales->isEmpty())

            <div class="empty-state">
                No sales found for the selected period.
            </div>

        @else

            <div class="table-wrapper">

                <table class="report-table">

                    <thead>

                        <tr>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th class="text-right">Total</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($sales as $sale)

                            <tr>

                                <td>
                                    <a
                                        href="{{ route('sales.show', $sale) }}"
                                        class="report-link"
                                    >
                                        {{ $sale->invoice_number }}
                                    </a>
                                </td>

                                <td>
                                    {{ $sale->sale_date->format('d M Y h:i A') }}
                                </td>

                                <td>
                                    {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                                </td>

                                <td>
                                    {{ $sale->branch?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $sale->status)) }}
                                </td>

                                <td class="text-right">
                                    ₨ {{ number_format($sale->grand_total, 2) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="report-pagination">
                {{ $sales->links() }}
            </div>

        @endif

    </div>

</div>


<style>

.report-page {
    padding: 24px;
}

.report-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.report-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.report-header p {
    margin: 6px 0 0;
    color: #6b7280;
}

.report-panel {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.report-filters {
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 16px;
    align-items: end;
}

.report-filters label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 6px;
}

.report-filters input {
    width: 100%;
    padding: 9px 10px;
    border: 1px solid #d1d5db;
    border-radius: 7px;
}

.report-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 20px;
}

.report-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
}

.report-card span {
    display: block;
    color: #6b7280;
    font-size: 13px;
    margin-bottom: 10px;
}

.report-card strong {
    font-size: 24px;
}

.report-two-column {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.report-panel-header {
    margin-bottom: 15px;
}

.report-panel-header h2 {
    margin: 0;
    font-size: 18px;
}

.report-panel-header p {
    margin: 5px 0 0;
    color: #6b7280;
    font-size: 13px;
}

.report-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.report-row:last-child {
    border-bottom: none;
}

.report-row small {
    display: block;
    color: #6b7280;
    margin-top: 4px;
}

.report-button {
    display: inline-block;
    border: none;
    background: #111827;
    color: #ffffff;
    padding: 9px 14px;
    border-radius: 7px;
    text-decoration: none;
    cursor: pointer;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table th,
.report-table td {
    padding: 13px 10px;
    border-bottom: 1px solid #f1f5f9;
    text-align: left;
}

.report-table th {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
}

.report-table td {
    font-size: 14px;
}

.report-link {
    color: #2563eb;
    text-decoration: none;
}

.empty-state {
    text-align: center;
    padding: 25px;
    color: #6b7280;
}

.report-pagination {
    margin-top: 20px;
}

.text-right {
    text-align: right !important;
}

.table-wrapper {
    overflow-x: auto;
}

@media (max-width: 900px) {

    .report-summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .report-two-column {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 600px) {

    .report-page {
        padding: 15px;
    }

    .report-summary-grid {
        grid-template-columns: 1fr;
    }

    .report-filters {
        grid-template-columns: 1fr;
    }

    .report-header {
        flex-direction: column;
        gap: 10px;
    }

}

</style>

@endsection