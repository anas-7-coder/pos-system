{{-- @extends('layouts.app')

@section('title', 'Customer Receivables')

@section('content')

<h1>Customer Receivables</h1>

<hr>

<table border="1" cellpadding="8" cellspacing="0">

    <thead>

        <tr>
            <th>Customer</th>
            <th>Code</th>
            <th>Credit Limit</th>
            <th>Current Balance</th>
            <th>Available Credit</th>
            <th>Action</th>
        </tr>

    </thead>

    <tbody>

        @forelse($customers as $customer)
            @php
                $balance         = $balances[$customer->id];
                $availableCredit = max( (float) $customer->credit_limit - $balance, 0 );
            @endphp

            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->code }}</td>
                <td>{{ number_format($customer->credit_limit, 2) }}</td>
                <td>{{ number_format($balance, 2) }}</td>
                <td>{{ number_format($availableCredit, 2) }}</td>
                <td><a href="{{ route('customers.receivables', $customer) }}"><button type="button">View Ledger</button></a></td>
            </tr>

        @empty
            <tr>
                <td colspan="6">
                    No customers found.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

<br>

{{ $customers->links() }}

@endsection --}}










@extends('layouts.app')

@section('title', 'Customer Receivables')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Customer Receivables</h1>
            <p class="page-subtitle">
                Monitor customer outstanding balances, credit limits, and available credit.
            </p>
        </div>
    </div>

    <div class="card">

        <div class="card-header">
            <div>
                <h2>Receivables Overview</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Review customer credit exposure and open their detailed ledger.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Code</th>
                            <th>Credit Limit</th>
                            <th>Current Balance</th>
                            <th>Available Credit</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($customers as $customer)

                            @php
                                $balance = (float) ($balances[$customer->id] ?? 0);

                                $creditLimit = (float) $customer->credit_limit;

                                $availableCredit = max(
                                    $creditLimit - $balance,
                                    0
                                );

                                $hasOutstandingBalance = $balance > 0;
                                $creditExhausted = $availableCredit <= 0 && $creditLimit > 0;
                            @endphp

                            <tr>

                                <td>
                                    <strong>
                                        {{ $customer->name }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $customer->code }}
                                </td>

                                <td>
                                    PKR {{ number_format($creditLimit, 2) }}
                                </td>

                                <td>
                                    @if($hasOutstandingBalance)
                                        <strong>
                                            PKR {{ number_format($balance, 2) }}
                                        </strong>
                                    @else
                                        <span>
                                            PKR 0.00
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($creditExhausted)
                                        <span class="badge badge-danger">
                                            PKR {{ number_format($availableCredit, 2) }}
                                        </span>
                                    @elseif($availableCredit > 0)
                                        <span class="badge badge-success">
                                            PKR {{ number_format($availableCredit, 2) }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            PKR 0.00
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="table-actions">

                                        <a href="{{ route('customers.receivables', $customer) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            View Ledger
                                        </a>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="table-empty">
                                    No customers found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($customers->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $customers->links() }}
        </div>
    @endif

@endsection