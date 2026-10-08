{{-- @extends('layouts.app')

@section('title', 'Payments')

@section('content')

<h1>Payments</h1>

@if(session('success'))
    <p style="color:green">
        {{ session('success') }}
    </p>
@endif

@if(session('error'))
    <p style="color:red">
        {{ session('error') }}
    </p>
@endif

<hr>

<table border="1" cellpadding="8" cellspacing="0">

    <thead>
        <tr>
            <th>ID</th>
            <th>Invoice</th>
            <th>Payment Method</th>
            <th>Amount</th>
            <th>Received</th>
            <th>Change</th>
            <th>Reference</th>
            <th>Paid At</th>
            <th>Created By</th>
        </tr>
    </thead>

    <tbody>

        @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td><a href="{{ route('sales.show', $payment->sale) }}">{{ $payment->sale->invoice_number }}</a></td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                <td>{{ number_format($payment->amount, 2) }}</td>
                <td>{{ number_format($payment->received_amount, 2) }}</td>
                <td>{{ number_format($payment->change_amount, 2) }}</td>
                <td>{{ $payment->reference ?? '-' }}</td>
                <td>{{ $payment->paid_at }}</td>
                <td>{{ $payment->createdBy?->name ?? '-' }}</td>
            </tr>

        @empty
            <tr>
                <td colspan="9">
                    No payments found.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

<br>

{{ $payments->links() }}

@endsection --}}









@extends('layouts.app')

@section('title', 'Payments')

@section('content')

    @php
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
            <h1 class="page-title">Payments</h1>
            <p class="page-subtitle">
                Review payment transactions recorded against sales invoices.
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
                <h2>Payment History</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Posted and recorded payments across sales transactions.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Invoice</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Received</th>
                            <th>Change</th>
                            <th>Reference</th>
                            <th>Paid At</th>
                            <th>Created By</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($payments as $payment)

                            <tr>

                                <td>
                                    {{ $payment->id }}
                                </td>

                                <td>
                                    <a href="{{ route('sales.show', $payment->sale) }}"
                                       style="font-weight:600;">
                                        {{ $payment->sale->invoice_number }}
                                    </a>
                                </td>

                                <td>
                                    <span class="badge {{ $methodClass($payment->payment_method) }}">
                                        {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $payment->amount, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    PKR {{ number_format((float) $payment->received_amount, 2) }}
                                </td>

                                <td>
                                    PKR {{ number_format((float) $payment->change_amount, 2) }}
                                </td>

                                <td>
                                    {{ $payment->reference ?? '-' }}
                                </td>

                                <td>
                                    {{ $payment->paid_at }}
                                </td>

                                <td>
                                    {{ $payment->createdBy?->name ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="table-empty">
                                    No payments found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($payments->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $payments->links() }}
        </div>
    @endif

@endsection