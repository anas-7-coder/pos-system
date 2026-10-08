{{-- @extends('layouts.app')

@section('title', 'Customer Payments')

@section('content')

<h1>Customer Payments</h1>

<hr>

<table border="1" cellpadding="8" cellspacing="0">

    <thead>

        <tr>
            <th>Payment Number</th>
            <th>Customer</th>
            <th>Method</th>
            <th>Amount</th>
            <th>Received</th>
            <th>Change</th>
            <th>Reference</th>
            <th>Date</th>
        </tr>

    </thead>

    <tbody>

        @forelse($payments as $payment)
            <tr>
                <td><a href="{{ route('customer-payments.show', $payment) }}"> {{ $payment->payment_number }}</a></td>
                <td>{{ $payment->customer->name }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                <td>{{ number_format($payment->amount, 2) }}</td>
                <td>{{ number_format($payment->received_amount, 2) }}</td>
                <td>{{ number_format($payment->change_amount, 2) }}</td>
                <td>{{ $payment->reference ?? '-' }}</td>
                <td>{{ $payment->paid_at }}</td>
            </tr>

        @empty
            <tr>
                <td colspan="8">
                    No customer payments found.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

<br>

{{ $payments->links() }}

@endsection --}}











@extends('layouts.app')

@section('title', 'Customer Payments')

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
            <h1 class="page-title">Customer Payments</h1>
            <p class="page-subtitle">
                Review payments received from customers against outstanding receivables.
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
                <h2>Customer Payment History</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Track payment numbers, customers, methods, amounts, references, and dates.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Payment Number</th>
                            <th>Customer</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Received</th>
                            <th>Change</th>
                            <th>Reference</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($payments as $payment)

                            <tr>

                                <td>
                                    <a href="{{ route('customer-payments.show', $payment) }}"
                                       style="font-weight:600;">
                                        {{ $payment->payment_number }}
                                    </a>
                                </td>

                                <td>
                                    <strong>
                                        {{ $payment->customer->name }}
                                    </strong>
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

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="table-empty">
                                    No customer payments found.
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