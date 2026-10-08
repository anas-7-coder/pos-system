{{-- @extends('layouts.app')

@section('title', 'Refunds')

@section('content')

<h1>Refunds</h1>

<hr>

<table border="1" cellpadding="8" cellspacing="0">

    <thead>

        <tr>
            <th>ID</th>
            <th>Return</th>
            <th>Invoice</th>
            <th>Method</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
        </tr>

    </thead>

    <tbody>

        @forelse($refunds as $refund)
            <tr>
                <td>{{ $refund->id }}</td>
                <td><a href="{{ route('sale-returns.show', $refund->saleReturn) }}">{{ $refund->saleReturn->return_number }}</a></td>
                <td>{{ $refund->sale->invoice_number }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $refund->payment_method)) }}</td>
                <td>{{ number_format($refund->amount, 2) }}</td>
                <td>{{ ucfirst($refund->status) }}</td>
                <td>{{ $refund->refunded_at }}</td>
            </tr>

        @empty
            <tr>
                <td colspan="7">
                    No refunds found.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

<br>

{{ $refunds->links() }}

@endsection --}}








@extends('layouts.app')

@section('title', 'Refunds')

@section('content')

    @php
        $statusClass = function ($status) {
            return match ($status) {
                'pending' => 'badge-warning',
                'completed', 'posted' => 'badge-success',
                'cancelled' => 'badge-danger',
                default => 'badge-secondary',
            };
        };

        $methodClass = function ($method) {
            return match ($method) {
                'cash' => 'badge-success',
                'card' => 'badge-info',
                'bank_transfer' => 'badge-primary',
                'mobile_wallet' => 'badge-warning',
                default => 'badge-secondary',
            };
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Refunds</h1>
            <p class="page-subtitle">
                Review refund transactions processed against sale returns.
            </p>
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
                <h2>Refund History</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Track refund references, payment methods, amounts, and processing status.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Return</th>
                            <th>Invoice</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($refunds as $refund)

                            <tr>

                                <td>
                                    {{ $refund->id }}
                                </td>

                                <td>
                                    <a href="{{ route('sale-returns.show', $refund->saleReturn) }}"
                                       style="font-weight:600;">
                                        {{ $refund->saleReturn->return_number }}
                                    </a>
                                </td>

                                <td>
                                    <a href="{{ route('sales.show', $refund->sale) }}"
                                       style="font-weight:600;">
                                        {{ $refund->sale->invoice_number }}
                                    </a>
                                </td>

                                <td>
                                    <span class="badge {{ $methodClass($refund->payment_method) }}">
                                        {{ ucwords(str_replace('_', ' ', $refund->payment_method)) }}
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $refund->amount, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass($refund->status) }}">
                                        {{ ucwords(str_replace('_', ' ', $refund->status)) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $refund->refunded_at ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="table-empty">
                                    No refunds found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($refunds->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $refunds->links() }}
        </div>
    @endif

@endsection