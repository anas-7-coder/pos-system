{{-- @extends('layouts.app')

@section('title', 'Sales')

@section('content')

<h1>Sales</h1>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<hr>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>Invoice</th>
            <th>Customer</th>
            <th>Branch</th>
            <th>Warehouse</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Cashier</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ $sale->customer?->name ?? 'Walk-in Customer' }}</td>
                <td>{{ $sale->branch->name }}</td>
                <td>{{ $sale->warehouse->name }}</td>
                <td>{{ $sale->sale_date->format('Y-m-d H:i') }}</td>
                <td> PKR {{ number_format( (float) $sale->grand_total, 2 ) }}</td>
                <td>{{ ucfirst($sale->status) }}</td>
                <td>{{ $sale->cashier->name }}</td>
                <td>
                    <a href="{{ route('sales.show', $sale) }}"><button type="button">View</button></a>
                        &nbsp;
                    @if($sale->status === 'draft')
                        <a href="{{ route('sales.edit', $sale) }}"><button type="button">Edit</button></a>
                    @endif
                </td>
            </tr>
        
        @empty
            <tr>
                <td colspan="9">
                    No sales found.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

<br>

{{ $sales->links() }}

@endsection --}}








@extends('layouts.app')

@section('title', 'Sales')

@section('content')

    @php
        $statusClass = function ($status) {
            return match ($status) {
                'draft' => 'badge-warning',
                'completed' => 'badge-success',
                'partially_refunded' => 'badge-info',
                'refunded' => 'badge-secondary',
                'cancelled' => 'badge-danger',
                default => 'badge-secondary',
            };
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Sales</h1>
            <p class="page-subtitle">
                Manage sales invoices, customers, warehouse activity, and payment status.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('sales.create') }}"
               class="btn btn-primary">
                + Create Sale
            </a>

        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <div>
                <h2>Sales History</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Review invoices, customers, totals, and current sale status.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Branch</th>
                            <th>Warehouse</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Cashier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($sales as $sale)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $sale->invoice_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                                </td>

                                <td>
                                    {{ $sale->branch->name }}
                                </td>

                                <td>
                                    {{ $sale->warehouse->name }}
                                </td>

                                <td>
                                    {{ $sale->sale_date->format('Y-m-d H:i') }}
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $sale->grand_total, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass($sale->status) }}">
                                        {{ ucwords(str_replace('_', ' ', $sale->status)) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $sale->cashier->name }}
                                </td>

                                <td>
                                    <div class="table-actions">

                                        @can('view', $sale)
                                            <a href="{{ route('sales.show', $sale) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                View
                                            </a>
                                        @endcan

                                        @if($sale->status === 'draft')
                                            <a href="{{ route('sales.edit', $sale) }}"
                                               class="btn btn-secondary btn-sm">
                                                Edit
                                            </a>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="table-empty">
                                    No sales found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($sales->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $sales->links() }}
        </div>
    @endif

@endsection