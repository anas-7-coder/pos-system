{{-- @extends('layouts.app')

@section('title', 'Sale Returns')

@section('content')

<h1>Sale Returns</h1>

@if(session('success'))
    <p style="color:green">
        {{ session('success') }}
    </p>
@endif

<hr>

<table border="1" cellpadding="8" cellspacing="0">

    <thead>

        <tr>
            <th>Return Number</th>
            <th>Invoice</th>
            <th>Branch</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
        </tr>

    </thead>

    <tbody>

        @forelse($returns as $return)
            <tr>
                <td><a href="{{ route('sale-returns.show', $return) }}">{{ $return->return_number }}</a></td>
                <td>{{ $return->sale->invoice_number }}</td>
                <td>{{ $return->branch->name }}</td>
                <td>{{ number_format($return->grand_total, 2) }}</td>
                <td>{{ ucfirst($return->status) }}</td>
                <td>{{ $return->return_date }}</td>
            </tr>

        @empty
            <tr>
                <td colspan="6">
                    No returns found.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

<br>

{{ $returns->links() }}

@endsection --}}









@extends('layouts.app')

@section('title', 'Sale Returns')

@section('content')

    @php
        $statusClass = function ($status) {
            return match ($status) {
                'draft' => 'badge-warning',
                'completed' => 'badge-success',
                'cancelled' => 'badge-danger',
                'partially_refunded' => 'badge-info',
                'refunded' => 'badge-secondary',
                default => 'badge-secondary',
            };
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Sale Returns</h1>
            <p class="page-subtitle">
                Review returned sale items, return totals, and current return status.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('sales.index') }}"
               class="btn btn-secondary">
                ← Back to Sales
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
                <h2>Return History</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Sale returns recorded across branches and invoices.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Return Number</th>
                            <th>Invoice</th>
                            <th>Branch</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($returns as $return)

                            <tr>

                                <td>
                                    <a href="{{ route('sale-returns.show', $return) }}"
                                       style="font-weight:600;">
                                        {{ $return->return_number }}
                                    </a>
                                </td>

                                <td>
                                    {{ $return->sale->invoice_number }}
                                </td>

                                <td>
                                    {{ $return->branch->name }}
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $return->grand_total, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass($return->status) }}">
                                        {{ ucwords(str_replace('_', ' ', $return->status)) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $return->return_date }}
                                </td>

                                <td>
                                    <div class="table-actions">

                                        @can('view', $return)
                                            <a href="{{ route('sale-returns.show', $return) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                View
                                            </a>
                                        @endcan

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="table-empty">
                                    No returns found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($returns->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $returns->links() }}
        </div>
    @endif

@endsection