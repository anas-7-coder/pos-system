{{-- @extends('layouts.app')

@section('title', 'Inventory')

@section('content')

<h1>Inventory</h1>

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

<a href="{{ route('inventory.adjust.create') }}">Adjust Inventory</a>
    &nbsp; | &nbsp;
<a href="{{ route('inventory.movements') }}">Stock Movements</a>
    &nbsp; | &nbsp;
<a href="{{ route('inventory.low-stock') }}">Low Stock</a>
    &nbsp; | &nbsp;
<a href="{{ route('inventory.reorder-level.create') }}">Set Reorder Level</a>

<hr>

<form method="GET">

    <input type="text" name="search" placeholder="Search product, SKU or warehouse" value="{{ request('search') }}">

    <button type="submit">
        Search
    </button>
        &nbsp; | &nbsp;
    <a href="{{ route('inventory.index') }}"><button type="button">Clear</button></a>

</form>

<br>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>ID</th>
            <th>Warehouse</th>
            <th>Product</th>
            <th>Variant</th>
            <th>Quantity</th>
            <th>Reserved</th>
            <th>Available</th>
            <th>Reorder Level</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

        @forelse($balances as $balance)

            @php
                $available = (float) $balance->quantity - (float) $balance->reserved_quantity;
            @endphp

            <tr>
                <td>{{ $balance->id }}</td>
                <td>{{ $balance->warehouse->name }}</td>
                <td>{{ $balance->product->name }} - ({{ $balance->product->sku }})</td>
                <td>{{ $balance->productVariant?->name ?? '-' }}</td>
                <td>{{ number_format( (float) $balance->quantity, 4) }}</td>
                <td>{{ number_format( (float) $balance->reserved_quantity, 4) }}</td>
                <td>{{ number_format($available, 4) }}</td>
                <td>{{ number_format( (float) $balance->reorder_level, 4) }}</td>
                <td>
                    @if( (float) $balance->reorder_level > 0 && (float) $balance->quantity <= (float) $balance->reorder_level)
                        Low Stock
                    @else
                        Normal
                    @endif
                </td>
            </tr>
        
        @empty
            <tr>
                <td colspan="9">
                    No inventory records found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

<br>

{{ $balances->links() }}

@endsection --}}





@extends('layouts.app')

@section('title', 'Inventory')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Inventory</h1>
            <p class="page-subtitle">
                Monitor stock levels, availability, reservations, and reorder thresholds across warehouses.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('inventory.adjust.create') }}"
               class="btn btn-primary">
                + Adjust Inventory
            </a>

            <a href="{{ route('inventory.movements') }}"
               class="btn btn-outline-primary">
                Stock Movements
            </a>

            <a href="{{ route('inventory.low-stock') }}"
               class="btn btn-outline-primary">
                Low Stock
            </a>

            <a href="{{ route('inventory.reorder-level.create') }}"
               class="btn btn-secondary">
                Reorder Levels
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

    {{-- Search --}}
    <div class="card">

        <div class="card-header">
            <h2>Search Inventory</h2>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('inventory.index') }}">

                <div class="filters">

                    <div class="form-group" style="margin:0; flex:1; min-width:250px;">
                        <label for="search" class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search product, SKU or warehouse"
                        >
                    </div>
                <br>
                    <div style="display:flex; align-items:end; gap:10px;">

                        <button type="submit"
                                class="btn btn-primary">
                            Search
                        </button>

                        <a href="{{ route('inventory.index') }}"
                           class="btn btn-secondary">
                            Clear
                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>

    {{-- Inventory Table --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Stock Balances</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Current inventory position by warehouse and product.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Warehouse</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Quantity</th>
                            <th>Reserved</th>
                            <th>Available</th>
                            <th>Reorder Level</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($balances as $balance)

                            @php
                                $quantity = (float) $balance->quantity;
                                $reserved = (float) $balance->reserved_quantity;
                                $available = $quantity - $reserved;
                                $reorderLevel = (float) $balance->reorder_level;

                                $isLowStock = $reorderLevel > 0
                                    && $quantity <= $reorderLevel;
                            @endphp

                            <tr>

                                <td>
                                    {{ $balance->id }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $balance->warehouse->name }}
                                    </strong>
                                </td>

                                <td>
                                    <strong>
                                        {{ $balance->product->name }}
                                    </strong>

                                    <div style="font-size:12px; color:#64748b; margin-top:3px;">
                                        SKU: {{ $balance->product->sku }}
                                    </div>
                                </td>

                                <td>
                                    {{ $balance->productVariant?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ number_format($quantity, 4) }}
                                </td>

                                <td>
                                    {{ number_format($reserved, 4) }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($available, 4) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ number_format($reorderLevel, 4) }}
                                </td>

                                <td>
                                    @if($isLowStock)
                                        <span class="badge badge-danger">
                                            Low Stock
                                        </span>
                                    @else
                                        <span class="badge badge-success">
                                            Normal
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="table-empty">
                                    No inventory records found.
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