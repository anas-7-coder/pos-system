{{-- @extends('layouts.app')

@section('title', 'Low Stock')

@section('content')

<h1>Low Stock</h1>

<a href="{{ route('inventory.index') }}">Inventory</a>

<hr>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>Warehouse</th>
            <th>Product</th>
            <th>Variant</th>
            <th>Current Stock</th>
            <th>Reorder Level</th>
            <th>Shortage</th>
        </tr>
    </thead>

    <tbody>

        @forelse($balances as $balance)

            @php
                $current  = (float) $balance->quantity;
                $reorder  = (float) $balance->reorder_level;
                $shortage = max(0, $reorder - $current);
            @endphp

            <tr>
                <td>{{ $balance->warehouse->name }}</td>
                <td>{{ $balance->product->name }}</td>
                <td>{{ $balance->productVariant?->name ?? '-' }}</td>
                <td>{{ number_format($current, 4) }}</td>
                <td>{{ number_format($reorder, 4) }}</td>
                <td>{{ number_format($shortage, 4) }}</td>
            </tr>
        
        @empty
            <tr>
                <td colspan="6">
                    No low-stock products found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

<br>

{{ $balances->links() }}

@endsection --}}







@extends('layouts.app')

@section('title', 'Low Stock')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Low Stock</h1>
            <p class="page-subtitle">
                Monitor products that have reached or fallen below their reorder levels.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('inventory.index') }}"
               class="btn btn-secondary">
                ← Back to Inventory
            </a>

            <a href="{{ route('inventory.reorder-level.create') }}"
               class="btn btn-primary">
                Set Reorder Levels
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
                <h2>Low Stock Products</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Products currently at or below their configured reorder threshold.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Warehouse</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Current Stock</th>
                            <th>Reorder Level</th>
                            <th>Shortage</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($balances as $balance)

                            @php
                                $current = (float) $balance->quantity;
                                $reorder = (float) $balance->reorder_level;
                                $shortage = max(0, $reorder - $current);
                            @endphp

                            <tr>

                                <td>
                                    <strong>
                                        {{ $balance->warehouse->name }}
                                    </strong>
                                </td>

                                <td>
                                    <strong>
                                        {{ $balance->product->name }}
                                    </strong>

                                    @if($balance->product->sku)
                                        <div style="font-size:12px; color:#64748b; margin-top:3px;">
                                            SKU: {{ $balance->product->sku }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $balance->productVariant?->name ?? '-' }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($current, 4) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ number_format($reorder, 4) }}
                                </td>

                                <td>
                                    @if($shortage > 0)
                                        <span class="badge badge-danger">
                                            {{ number_format($shortage, 4) }}
                                        </span>
                                    @else
                                        <span class="badge badge-warning">
                                            0.0000
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge badge-danger">
                                        Low Stock
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="table-empty">
                                    No low-stock products found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($balances->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $balances->links() }}
        </div>
    @endif

@endsection