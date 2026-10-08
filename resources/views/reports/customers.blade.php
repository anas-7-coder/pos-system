@extends('layouts.app')

@section('title', 'Customer Report')

@section('content')

<div class="report-page">

    <div class="report-header">
        <div>
            <h1>Customer Report</h1>
            <p>Customer sales activity, payments and receivable balances.</p>
        </div>
    </div>


    {{-- Filters --}}
    <form method="GET"
          action="{{ route('reports.customers') }}"
          class="filter-card">

        <div class="filter-group">
            <label>Search Customer</label>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Name, code, phone or email"
            >
        </div>

        <div class="filter-group">
            <label>From</label>

            <input
                type="date"
                name="from"
                value="{{ $from }}"
            >
        </div>

        <div class="filter-group">
            <label>To</label>

            <input
                type="date"
                name="to"
                value="{{ $to }}"
            >
        </div>

        <div class="filter-actions">

            <button type="submit"
                    class="btn-primary">
                Apply
            </button>

            <a href="{{ route('reports.customers') }}"
               class="btn-secondary">
                Reset
            </a>

        </div>

    </form>


    {{-- KPI Cards --}}
    <div class="kpi-grid">

        <div class="kpi-card">
            <div class="kpi-label">Active Customers</div>

            <div class="kpi-value">
                {{ number_format($totalCustomers) }}
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">Customers With Sales</div>

            <div class="kpi-value">
                {{ number_format($customersWithSales) }}
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">Invoices</div>

            <div class="kpi-value">
                {{ number_format($invoiceCount) }}
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">Sales Value</div>

            <div class="kpi-value">
                ₨ {{ number_format($salesValue, 2) }}
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">Customer Payments</div>

            <div class="kpi-value">
                ₨ {{ number_format($customerPayments, 2) }}
            </div>
        </div>


        <div class="kpi-card">
            <div class="kpi-label">Outstanding Receivables</div>

            <div class="kpi-value">
                ₨ {{ number_format($outstandingReceivables, 2) }}
            </div>
        </div>

    </div>


    {{-- Customer Detail --}}
    <div class="report-card">

        <div class="card-header">
            <h2>Customer Detail</h2>
        </div>


        <div class="table-wrapper">

            <table class="report-table">

                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Code</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Invoices</th>
                        <th>Sales</th>
                        <th>Payments</th>
                        <th>Receivable</th>
                        <th>Credit Limit</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($customers as $customer)

                        <tr>

                            <td>
                                <a
                                    href="{{ route('customers.show', $customer) }}"
                                    class="table-link"
                                >
                                    {{ $customer->name }}
                                </a>
                            </td>

                            <td>
                                {{ $customer->code ?? '—' }}
                            </td>

                            <td>
                                {{ $customer->phone ?? '—' }}
                            </td>

                            <td>
                                {{ $customer->status }}
                            </td>

                            <td>
                                {{ number_format($customer->report_invoice_count) }}
                            </td>

                            <td>
                                ₨ {{ number_format($customer->report_sales_value, 2) }}
                            </td>

                            <td>
                                ₨ {{ number_format($customer->report_payments, 2) }}
                            </td>

                            <td>
                                ₨ {{ number_format($customer->report_balance, 2) }}
                            </td>

                            <td>
                                @if($customer->credit_limit !== null)
                                    ₨ {{ number_format($customer->credit_limit, 2) }}
                                @else
                                    Unlimited
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9"
                                class="empty-state">
                                No customers found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="pagination-wrapper">

            {{ $customers->links() }}

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
    min-width: 180px;
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
    grid-template-columns: repeat(3, 1fr);
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

@media (max-width: 900px) {

    .kpi-grid {
        grid-template-columns: repeat(2, 1fr);
    }

}

@media (max-width: 600px) {

    .kpi-grid {
        grid-template-columns: 1fr;
    }

}

</style>

@endsection