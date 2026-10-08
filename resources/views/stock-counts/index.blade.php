{{-- @extends('layouts.app')

@section('title', 'Stock Counts')

@section('content')

<h1>Stock Counts</h1>

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
            <th>Count Number</th>
            <th>Warehouse</th>
            <th>Date</th>
            <th>Status</th>
            <th>Posted By</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($stockCounts as $stockCount)

            <tr>
                <td>{{ $stockCount->count_number }}</td>
                <td>{{ $stockCount->warehouse->name }}</td>
                <td>{{ $stockCount->count_date->format('Y-m-d') }}</td>
                <td>{{ ucfirst($stockCount->status) }}</td>
                <td>{{ $stockCount->postedBy?->name ?? '-' }}</td>
                <td>
                    <a href="{{ route('stock-counts.show', $stockCount) }}"><button type="button">View</button></a>
                        &nbsp;
                    @if($stockCount->status === 'draft')
                        <a href="{{ route('stock-counts.edit', $stockCount) }}"><button type="button">Edit</button></a>
                    @endif
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="6">
                    No stock counts found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

<br>

{{ $stockCounts->links() }}

@endsection --}}







@extends('layouts.app')

@section('title', 'Stock Counts')

@section('content')

    @php
        $statusClass = function ($status) {
            return match ($status) {
                'draft' => 'badge-warning',
                'completed', 'posted' => 'badge-success',
                'cancelled' => 'badge-danger',
                default => 'badge-secondary',
            };
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Stock Counts</h1>
            <p class="page-subtitle">
                Manage physical inventory counts and review stock reconciliation records.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('stock-counts.create') }}"
               class="btn btn-primary">
                + Create Stock Count
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
                <h2>Stock Count History</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Review stock counts by warehouse, date, and posting status.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Count Number</th>
                            <th>Warehouse</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Posted By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($stockCounts as $stockCount)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $stockCount->count_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $stockCount->warehouse->name }}
                                </td>

                                <td>
                                    {{ $stockCount->count_date->format('Y-m-d') }}
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass($stockCount->status) }}">
                                        {{ ucwords(str_replace('_', ' ', $stockCount->status)) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $stockCount->postedBy?->name ?? '-' }}
                                </td>

                                <td>
                                    <div class="table-actions">

                                        @can('view', $stockCount)
                                             <a href="{{ route('stock-counts.show', $stockCount) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                View
                                            </a>
                                        @endcan
                                       
                                        @if($stockCount->status === 'draft')
                                            <a href="{{ route('stock-counts.edit', $stockCount) }}"
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
                                    No stock counts found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($stockCounts->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $stockCounts->links() }}
        </div>
    @endif

@endsection