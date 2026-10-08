{{-- @extends('layouts.app')

@section('title', 'Customer Payment')

@section('content')

<h1>Customer Payment</h1>

<a href="{{ route('customers.receivables', $customerPayment->customer) }}">Back to Customer Receivable</a>

<hr>

<p><strong>Payment Number:</strong> {{ $customerPayment->payment_number }}</p>
<p><strong>Customer:</strong> {{ $customerPayment->customer->name }}</p>
<p><strong>Method:</strong> {{ ucfirst(str_replace('_', ' ', $customerPayment->payment_method)) }}</p>
<p><strong>Amount:</strong> {{ number_format($customerPayment->amount, 2) }}</p>
<p><strong>Received:</strong> {{ number_format($customerPayment->received_amount, 2) }}</p>
<p><strong>Change:</strong> {{ number_format($customerPayment->change_amount, 2) }}</p>
<p><strong>Reference:</strong> {{ $customerPayment->reference ?? '-' }}</p>
<p><strong>Status:</strong> {{ ucfirst($customerPayment->status) }}</p>

<hr>

<h3>Allocated Invoices</h3>

<table border="1" cellpadding="8" cellspacing="0">

    <thead>
        <tr>
            <th>Invoice</th>
            <th>Allocated Amount</th>
        </tr>
    </thead>

    <tbody>

        @forelse($customerPayment->allocations as $allocation)
            <tr>
                <td><a href="{{ route('sales.show', $allocation->sale) }}"> {{ $allocation->sale->invoice_number }}</a></td>
                <td>{{ number_format($allocation->amount, 2) }}</td>
            </tr>

        @empty
            <tr>
                <td colspan="2">
                    No invoice allocation.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

@endsection --}}









@extends('layouts.app')

@section('title', 'Customer Payment')

@section('content')

    @php
        $statusClass = match ($customerPayment->status) {
            'posted', 'completed' => 'badge-success',
            'pending' => 'badge-warning',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };

        $methodClass = match ($customerPayment->payment_method) {
            'cash' => 'badge-success',
            'card' => 'badge-info',
            'bank_transfer' => 'badge-primary',
            'mobile_wallet' => 'badge-warning',
            default => 'badge-secondary',
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Customer Payment</h1>
            <p class="page-subtitle">
                Review payment details and invoice allocations for this customer payment.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('customers.receivables', $customerPayment->customer) }}"
               class="btn btn-secondary">
                ← Back to Customer Receivable
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

    {{-- Payment Information --}}
    <div class="card">

        <div class="card-header">
            <h2>Payment Information</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">Payment Number</div>
                    <div class="detail-value">
                        <strong>
                            {{ $customerPayment->payment_number }}
                        </strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Customer</div>
                    <div class="detail-value">
                        {{ $customerPayment->customer->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Payment Method</div>
                    <div class="detail-value">
                        <span class="badge {{ $methodClass }}">
                            {{ ucwords(str_replace('_', ' ', $customerPayment->payment_method)) }}
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="badge {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $customerPayment->status)) }}
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Amount</div>
                    <div class="detail-value">
                        <strong>
                            PKR {{ number_format((float) $customerPayment->amount, 2) }}
                        </strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Received</div>
                    <div class="detail-value">
                        PKR {{ number_format((float) $customerPayment->received_amount, 2) }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Change</div>
                    <div class="detail-value">
                        PKR {{ number_format((float) $customerPayment->change_amount, 2) }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Reference</div>
                    <div class="detail-value">
                        {{ $customerPayment->reference ?? '-' }}
                    </div>
                </div>

                @if($customerPayment->paid_at)
                    <div class="detail-item">
                        <div class="detail-label">Paid At</div>
                        <div class="detail-value">
                            {{ $customerPayment->paid_at }}
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

    {{-- Allocated Invoices --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Allocated Invoices</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Sales invoices against which this customer payment was allocated.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Sale Date</th>
                            <th>Invoice Total</th>
                            <th>Allocated Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($customerPayment->allocations as $allocation)

                            <tr>

                                <td>
                                    <a href="{{ route('sales.show', $allocation->sale) }}"
                                       style="font-weight:600;">
                                        {{ $allocation->sale->invoice_number }}
                                    </a>
                                </td>

                                <td>
                                    {{ $allocation->sale->sale_date?->format('Y-m-d H:i') ?? '-' }}
                                </td>

                                <td>
                                    PKR {{ number_format((float) $allocation->sale->grand_total, 2) }}
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $allocation->amount, 2) }}
                                    </strong>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="table-empty">
                                    No invoice allocation found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

@endsection