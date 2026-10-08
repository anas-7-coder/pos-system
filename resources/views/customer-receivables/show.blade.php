{{-- @extends('layouts.app')

@section('title', 'Customer Ledger')

@section('content')

<h1>Customer Ledger</h1>

<a href="{{ route('customer-receivables.index') }}">Back to Receivables</a>

<hr>

<p><strong>Customer:</strong> {{ $customer->name }}</p>
<p><strong>Code:</strong> {{ $customer->code }}</p>
<p><strong>Opening Balance:</strong> {{ number_format($customer->opening_balance, 2) }}</p>
<p><strong>Credit Limit:</strong> {{ number_format($customer->credit_limit, 2) }}</p>
<p><strong>Current Balance:</strong> {{ number_format($balance, 2) }}</p>

<hr>

<p><a href="{{ route('customers.payments.create', $customer) }}"><button type="button">Receive Customer Payment</button></a></p>

<table border="1" cellpadding="8" cellspacing="0">

    <thead>

        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Invoice</th>
            <th>Description</th>
            <th>Debit</th>
            <th>Credit</th>
        </tr>

    </thead>

    <tbody>

        @forelse($customer->ledgerEntries as $entry)
            <tr>
                <td>{{ $entry->transaction_date }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $entry->type)) }}</td>
                <td>
                    @if($entry->sale)
                        <a href="{{ route('sales.show', $entry->sale) }}">{{ $entry->sale->invoice_number }}</a>
                    @else
                        -
                    @endif
                </td>
                <td>{{ $entry->description ?? '-' }}</td>
                <td>{{ number_format($entry->debit, 2) }}</td>
                <td>{{ number_format($entry->credit, 2) }}</td>
            </tr>

        @empty
            <tr>
                <td colspan="6">
                    No ledger entries found.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

@endsection --}}











@extends('layouts.app')

@section('title', 'Customer Ledger')

@section('content')

    @php
        $balanceClass = $balance > 0
            ? 'badge-warning'
            : 'badge-success';

        $typeClass = function ($type) {
            return match ($type) {
                'sale', 'invoice' => 'badge-warning',
                'payment', 'customer_payment' => 'badge-success',
                'refund' => 'badge-info',
                default => 'badge-secondary',
            };
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Customer Ledger</h1>
            <p class="page-subtitle">
                Review the customer's receivable transactions, invoices, payments, and outstanding balance.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('customer-receivables.index') }}"
               class="btn btn-secondary">
                ← Back to Receivables
            </a>

            <a href="{{ route('customers.payments.create', $customer) }}"
               class="btn btn-primary">
                + Receive Customer Payment
            </a>

        </div>
    </div>

    {{-- Customer Summary --}}
    <div class="card">

        <div class="card-header">
            <h2>Customer Information</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">Customer</div>
                    <div class="detail-value">
                        <strong>
                            {{ $customer->name }}
                        </strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Customer Code</div>
                    <div class="detail-value">
                        {{ $customer->code }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Opening Balance</div>
                    <div class="detail-value">
                        PKR {{ number_format((float) $customer->opening_balance, 2) }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Credit Limit</div>
                    <div class="detail-value">
                        PKR {{ number_format((float) $customer->credit_limit, 2) }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Current Balance</div>
                    <div class="detail-value">
                        <span class="badge {{ $balanceClass }}">
                            PKR {{ number_format((float) $balance, 2) }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Ledger --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Ledger Entries</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Complete receivable transaction history for this customer.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Invoice</th>
                            <th>Description</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Balance</th>
                        </tr>
                    </thead>

                    <tbody>

                        @php
                            $runningBalance = (float) $customer->opening_balance;
                        @endphp

                        @forelse($customer->ledgerEntries as $entry)

                            @php
                                $debit = (float) $entry->debit;
                                $credit = (float) $entry->credit;

                                $runningBalance += $debit - $credit;
                            @endphp

                            <tr>

                                <td>
                                    {{ $entry->transaction_date }}
                                </td>

                                <td>
                                    <span class="badge {{ $typeClass($entry->type) }}">
                                        {{ ucwords(str_replace('_', ' ', $entry->type)) }}
                                    </span>
                                </td>

                                <td>

                                    @if($entry->sale)

                                        <a href="{{ route('sales.show', $entry->sale) }}"
                                           style="font-weight:600;">
                                            {{ $entry->sale->invoice_number }}
                                        </a>

                                    @else
                                        -
                                    @endif

                                </td>

                                <td>
                                    {{ $entry->description ?? '-' }}
                                </td>

                                <td>
                                    @if($debit > 0)
                                        <strong>
                                            PKR {{ number_format($debit, 2) }}
                                        </strong>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    @if($credit > 0)
                                        <strong>
                                            PKR {{ number_format($credit, 2) }}
                                        </strong>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format($runningBalance, 2) }}
                                    </strong>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="table-empty">
                                    No ledger entries found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

@endsection