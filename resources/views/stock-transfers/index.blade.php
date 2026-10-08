{{-- @extends('layouts.app')

@section('title', 'Stock Transfers')

@section('content')

<h1>Stock Transfers</h1>

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
            <th>Transfer Number</th>
            <th>From</th>
            <th>To</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($stockTransfers as $stockTransfer)

            <tr>
                <td>{{ $stockTransfer->transfer_number }}</td>
                <td>{{ $stockTransfer->fromWarehouse->name }}</td>
                <td>{{ $stockTransfer->toWarehouse->name }}</td>
                <td>{{ $stockTransfer->transfer_date->format('Y-m-d') }}</td>
                <td>{{ ucfirst($stockTransfer->status) }}</td>
                <td>
                    <a href="{{ route('stock-transfers.show', $stockTransfer) }}"><button type="button">View</button></a>
                        &nbsp;
                    @if($stockTransfer->status === 'draft')
                        <a href="{{ route('stock-transfers.edit', $stockTransfer) }}"><button type="button">Edit</button></a>
                    @endif
                </td>
            </tr>
        
        @empty
            <tr>
                <td colspan="6">
                    No stock transfers found.
                </td>
            </tr>
        @endforelse

</table>

<br>

{{ $stockTransfers->links() }}

@endsection --}}





@extends('layouts.app')

@section('title', 'Stock Transfers')

@section('content')

    @php
        $statusClass = function ($status) {
            return match ($status) {
                'draft' => 'badge-warning',
                'completed' => 'badge-success',
                'cancelled' => 'badge-danger',
                'in_transit' => 'badge-info',
                default => 'badge-secondary',
            };
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Stock Transfers</h1>
            <p class="page-subtitle">
                Manage inventory transfers between warehouses and track their status.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('stock-transfers.create') }}"
               class="btn btn-primary">
                + Create Stock Transfer
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
                <h2>Transfer History</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Review warehouse-to-warehouse inventory transfers.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Transfer Number</th>
                            <th>From Warehouse</th>
                            <th>To Warehouse</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($stockTransfers as $stockTransfer)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $stockTransfer->transfer_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $stockTransfer->fromWarehouse->name }}
                                </td>

                                <td>
                                    {{ $stockTransfer->toWarehouse->name }}
                                </td>

                                <td>
                                    {{ $stockTransfer->transfer_date->format('Y-m-d') }}
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass($stockTransfer->status) }}">
                                        {{ ucwords(str_replace('_', ' ', $stockTransfer->status)) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="table-actions">

                                        <a href="{{ route('stock-transfers.show', $stockTransfer) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            View
                                        </a>

                                        @if($stockTransfer->status === 'draft')
                                            <a href="{{ route('stock-transfers.edit', $stockTransfer) }}"
                                               class="btn btn-secondary btn-sm">
                                                Edit
                                            </a>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="table-empty">
                                    No stock transfers found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($stockTransfers->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $stockTransfers->links() }}
        </div>
    @endif

@endsection