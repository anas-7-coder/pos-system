{{-- @extends('layouts.app')

@section('title', 'Cash Movements')

@section('content')

<h1>Cash Movements</h1>

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
            <th>Register Session</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Reference</th>
            <th>User</th>
            <th>Date</th>
        </tr>

    </thead>

    <tbody>

        @forelse($movements as $movement)
            <tr>
                <td>{{ $movement->id }}</td>
                <td>{{ $movement->registerSession->register->name }} — #{{ $movement->register_session_id }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $movement->type)) }}</td>
                <td>{{ number_format($movement->amount, 2) }}</td>
                <td>{{ $movement->reference ?? '-' }}</td>
                <td>{{ $movement->user->name }}</td>
                <td>{{ $movement->occurred_at }}</td>
            </tr>

        @empty
            <tr>
                <td colspan="7">
                    No cash movements found.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

<br>

{{ $movements->links() }}

@endsection --}}






@extends('layouts.app')

@section('title', 'Cash Movements')

@section('content')

    @php
        $typeClass = function ($type) {
            return match ($type) {
                'cash_in' => 'badge-success',
                'cash_out' => 'badge-danger',
                'sale_payment' => 'badge-info',
                'cash_refund' => 'badge-warning',
                'customer_payment' => 'badge-primary',
                default => 'badge-secondary',
            };
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Cash Movements</h1>
            <p class="page-subtitle">
                Review cash inflows and outflows recorded against register sessions.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('cash-movements.create') }}"
               class="btn btn-primary">
                + Record Cash Movement
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
                <h2>Cash Movement History</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Track register cash activity, references, users, and transaction dates.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Register Session</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Reference</th>
                            <th>User</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($movements as $movement)

                            <tr>

                                <td>
                                    {{ $movement->id }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $movement->registerSession->register->name }}
                                    </strong>

                                    <div style="font-size:12px; color:#64748b; margin-top:3px;">
                                        Session #{{ $movement->register_session_id }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge {{ $typeClass($movement->type) }}">
                                        {{ ucwords(str_replace('_', ' ', $movement->type)) }}
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $movement->amount, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $movement->reference ?? '-' }}
                                </td>

                                <td>
                                    {{ $movement->user?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $movement->occurred_at }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="table-empty">
                                    No cash movements found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($movements->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $movements->links() }}
        </div>
    @endif

@endsection