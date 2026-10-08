@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="dashboard-page">

    <div class="dashboard-header">
        <div>
            <h1>Dashboard</h1>
            <p>Overview of your POS operations.</p>
        </div>

        <div class="dashboard-date">
            {{ now()->format('d M Y') }}
        </div>
    </div>


    {{-- KPI Cards --}}
    <div class="dashboard-grid">

        <div class="dashboard-card">
            <div class="dashboard-card-label">
                Today's Sales
            </div>

            <div class="dashboard-card-value">
                ₨ {{ number_format($todaySales, 2) }}
            </div>

            <div class="dashboard-card-meta">
                Gross sales for today
            </div>
        </div>


        <div class="dashboard-card">
            <div class="dashboard-card-label">
                Today's Invoices
            </div>

            <div class="dashboard-card-value">
                {{ number_format($todayInvoices) }}
            </div>

            <div class="dashboard-card-meta">
                Completed sales today
            </div>
        </div>


        <div class="dashboard-card">
            <div class="dashboard-card-label">
                Receivables
            </div>

            <div class="dashboard-card-value">
                ₨ {{ number_format($receivables, 2) }}
            </div>

            <div class="dashboard-card-meta">
                Outstanding customer balance
            </div>
        </div>


        <div class="dashboard-card">
            <div class="dashboard-card-label">
                Low Stock
            </div>

            <div class="dashboard-card-value">
                {{ number_format($lowStockCount) }}
            </div>

            <div class="dashboard-card-meta">
                Items at or below reorder level
            </div>
        </div>

    </div>


    {{-- Register + Branch Summary --}}
    <div class="dashboard-two-column">

        <div class="dashboard-panel">

            <div class="dashboard-panel-header">
                <h2>My Register</h2>
            </div>

            @if($openRegister)

                <div class="register-status open">

                    <div>
                        <strong>{{ $openRegister->register->name }}</strong>

                        <div class="dashboard-muted">
                            {{ $openRegister->branch->name }}
                        </div>
                    </div>

                    <span class="status-badge status-open">
                        Open
                    </span>

                </div>

                <div class="register-details">

                    <div>
                        <span>Opened</span>
                        <strong>
                            {{ $openRegister->opened_at->format('d M Y h:i A') }}
                        </strong>
                    </div>

                    <div>
                        <span>Opening Cash</span>
                        <strong>
                            ₨ {{ number_format($openRegister->opening_cash, 2) }}
                        </strong>
                    </div>

                </div>

                <div class="dashboard-actions">
                    <a
                        href="{{ route('register-sessions.show', $openRegister) }}"
                        class="dashboard-button"
                    >
                        View Register
                    </a>
                </div>

            @else

                <div class="empty-state">
                    <strong>No open register session</strong>

                    <p>
                        You do not currently have an open register.
                    </p>

                    @can('open', App\Models\RegisterSession::class)
                        <a
                            href="{{ route('register-sessions.create') }}"
                            class="dashboard-button"
                        >
                            Open Register
                        </a>
                    @endcan
                </div>

            @endif

        </div>


        <div class="dashboard-panel">

            <div class="dashboard-panel-header">
                <h2>Branch Access</h2>
            </div>

            <div class="branch-summary">

                <div class="branch-summary-number">
                    {{ $branchCount }}
                </div>

                <div>
                    <strong>
                        {{ $branchCount === 1 ? 'Branch' : 'Branches' }}
                    </strong>

                    <p>
                        Data on this dashboard is limited to your accessible branches.
                    </p>
                </div>

            </div>

        </div>

    </div>


    {{-- Recent Sales --}}
    <div class="dashboard-panel">

        <div class="dashboard-panel-header">

            <div>
                <h2>Recent Sales</h2>
                <p>Latest sales from your accessible branches.</p>
            </div>

            <a
                href="{{ route('sales.index') }}"
                class="dashboard-link"
            >
                View All
            </a>

        </div>


        @if($recentSales->isEmpty())

            <div class="empty-state">
                <strong>No sales found</strong>

                <p>
                    There are no sales to display yet.
                </p>
            </div>

        @else

            <div class="table-wrapper">

                <table class="dashboard-table">

                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Branch</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($recentSales as $sale)

                            <tr>

                                <td>
                                    <a
                                        href="{{ route('sales.show', $sale) }}"
                                        class="dashboard-link"
                                    >
                                        {{ $sale->invoice_number }}
                                    </a>
                                </td>

                                <td>
                                    {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                                </td>

                                <td>
                                    {{ $sale->branch?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $sale->sale_date->format('d M Y h:i A') }}
                                </td>

                                <td>
                                    <span class="status-badge">
                                        {{ ucfirst(str_replace('_', ' ', $sale->status)) }}
                                    </span>
                                </td>

                                <td class="text-right">
                                    ₨ {{ number_format($sale->grand_total, 2) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </div>

    {{-- Analytics --}}
    <div class="dashboard-analytics-grid">

        {{-- Last 7 Days Sales --}}
        <div class="dashboard-panel">

            <div class="dashboard-panel-header">
                <div>
                    <h2>Sales — Last 7 Days</h2>
                    <p>Gross sales by day.</p>
                </div>
            </div>

            @php
                $maxSales = max($salesChartValues);
            @endphp

            <div class="sales-chart">

                @foreach($salesChartLabels as $index => $label)

                    @php
                        $value = $salesChartValues[$index];
                        $percentage = $maxSales > 0
                            ? ($value / $maxSales) * 100
                            : 0;
                    @endphp

                    <div class="sales-chart-column">

                        <div class="sales-chart-value">
                            ₨ {{ number_format($value, 0) }}
                        </div>

                        <div class="sales-chart-bar-wrapper">

                            <div
                                class="sales-chart-bar"
                                style="height: {{ max($percentage, 3) }}%;"
                            ></div>

                        </div>

                        <div class="sales-chart-label">
                            {{ $label }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>


        {{-- Payment Methods --}}
        <div class="dashboard-panel">

            <div class="dashboard-panel-header">
                <div>
                    <h2>Payment Methods</h2>
                    <p>Posted payments.</p>
                </div>
            </div>

            @forelse($paymentBreakdown as $payment)

                <div class="analytics-row">

                    <div>
                        <strong>
                            {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                        </strong>
                    </div>

                    <div class="analytics-row-value">
                        ₨ {{ number_format($payment->total, 2) }}
                    </div>

                </div>

            @empty

                <div class="empty-state">
                    No payment data available.
                </div>

            @endforelse

        </div>

    </div>


    {{-- Top Products + Branch Sales --}}
    <div class="dashboard-analytics-grid">

        {{-- Top Selling Products --}}
        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>
                    <h2>Top Selling Products</h2>
                    <p>Highest quantity sold.</p>
                </div>

            </div>

            @forelse($topProducts as $item)

                <div class="top-product-row">

                    <div>

                        <strong>
                            {{ $item->product?->name ?? 'Unknown Product' }}
                        </strong>

                        <div class="dashboard-muted">
                            {{ number_format($item->total_quantity, 2) }}
                            units sold
                        </div>

                    </div>

                    <div class="analytics-row-value">
                        ₨ {{ number_format($item->total_sales, 2) }}
                    </div>

                </div>

            @empty

                <div class="empty-state">
                    No product sales data available.
                </div>

            @endforelse

        </div>


        {{-- Branch Sales --}}
        <div class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>
                    <h2>Today's Branch Sales</h2>
                    <p>Sales by accessible branch.</p>
                </div>

            </div>

            @forelse($branchSales as $branch)

                <div class="analytics-row">

                    <div>
                        <strong>
                            {{ $branch->branch?->name ?? 'Unknown Branch' }}
                        </strong>
                    </div>

                    <div class="analytics-row-value">
                        ₨ {{ number_format($branch->total_sales, 2) }}
                    </div>

                </div>

            @empty

                <div class="empty-state">
                    No branch sales today.
                </div>

            @endforelse

        </div>

    </div>

</div>


<style>

.dashboard-page {
    padding: 24px;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}

.dashboard-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.dashboard-header p {
    margin: 6px 0 0;
    color: #6b7280;
}

.dashboard-date {
    color: #6b7280;
    font-size: 14px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 20px;
}

.dashboard-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
}

.dashboard-card-label {
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 10px;
}

.dashboard-card-value {
    font-size: 26px;
    font-weight: 700;
}

.dashboard-card-meta {
    margin-top: 8px;
    font-size: 13px;
    color: #9ca3af;
}

.dashboard-two-column {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.dashboard-panel {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.dashboard-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.dashboard-panel-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
}

.dashboard-panel-header p {
    margin: 5px 0 0;
    color: #6b7280;
    font-size: 13px;
}

.dashboard-muted {
    color: #6b7280;
    margin-top: 4px;
}

.register-status {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.register-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-top: 20px;
}

.register-details span {
    display: block;
    font-size: 12px;
    color: #9ca3af;
    margin-bottom: 4px;
}

.register-details strong {
    font-size: 14px;
}

.status-badge {
    display: inline-block;
    padding: 5px 9px;
    border-radius: 6px;
    background: #f3f4f6;
    font-size: 12px;
    font-weight: 600;
}

.status-open {
    background: #dcfce7;
    color: #166534;
}

.branch-summary {
    display: flex;
    align-items: center;
    gap: 20px;
}

.branch-summary-number {
    font-size: 42px;
    font-weight: 700;
}

.branch-summary p {
    margin: 5px 0 0;
    color: #6b7280;
    font-size: 13px;
}

.dashboard-actions {
    margin-top: 20px;
}

.dashboard-button {
    display: inline-block;
    padding: 9px 14px;
    background: #111827;
    color: white;
    text-decoration: none;
    border-radius: 7px;
    font-size: 13px;
}

.dashboard-button:hover {
    opacity: 0.9;
}

.dashboard-link {
    color: #2563eb;
    text-decoration: none;
}

.empty-state {
    text-align: center;
    padding: 30px 10px;
    color: #6b7280;
}

.empty-state p {
    margin: 7px 0 15px;
}

.table-wrapper {
    overflow-x: auto;
}

.dashboard-table {
    width: 100%;
    border-collapse: collapse;
}

.dashboard-table th,
.dashboard-table td {
    padding: 13px 10px;
    border-bottom: 1px solid #f1f5f9;
    text-align: left;
    white-space: nowrap;
}

.dashboard-table th {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
}

.dashboard-table td {
    font-size: 14px;
}

.text-right {
    text-align: right !important;
}

@media (max-width: 1000px) {

    .dashboard-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .dashboard-two-column {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 600px) {

    .dashboard-page {
        padding: 15px;
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
    }

    .dashboard-header {
        flex-direction: column;
        gap: 10px;
    }

}

.dashboard-analytics-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.sales-chart {
    display: flex;
    align-items: flex-end;
    gap: 14px;
    height: 260px;
    padding-top: 15px;
}

.sales-chart-column {
    flex: 1;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
}

.sales-chart-value {
    font-size: 11px;
    color: #6b7280;
    margin-bottom: 7px;
}

.sales-chart-bar-wrapper {
    height: 180px;
    width: 100%;
    display: flex;
    align-items: flex-end;
}

.sales-chart-bar {
    width: 100%;
    min-height: 5px;
    background: #111827;
    border-radius: 6px 6px 2px 2px;
}

.sales-chart-label {
    font-size: 11px;
    color: #6b7280;
    margin-top: 8px;
}

.analytics-row,
.top-product-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 13px 0;
    border-bottom: 1px solid #f1f5f9;
}

.analytics-row:last-child,
.top-product-row:last-child {
    border-bottom: none;
}

.analytics-row-value {
    font-weight: 600;
    white-space: nowrap;
}

.top-product-row {
    gap: 20px;
}

@media (max-width: 900px) {

    .dashboard-analytics-grid {
        grid-template-columns: 1fr;
    }

}

</style>

@endsection























{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}


{{-- @extends('layouts.app')

@section('content')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

@endsection --}}