{{-- @extends('layouts.app')

@section('title', 'Stock Movements')

@section('content')

<h1>Stock Movements</h1>

<a href="{{ route('inventory.index') }}">Inventory</a>
    &nbsp; | &nbsp;
<a href="{{ route('inventory.adjust.create') }}">Adjust Inventory</a>

<hr>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>Date</th>
            <th>Warehouse</th>
            <th>Product</th>
            <th>Variant</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>User</th>
            <th>Notes</th>
        </tr>
    </thead>

    <tbody>

        @forelse($movements as $movement)
            
            <tr>
                <td>{{ $movement->occurred_at->format('Y-m-d H:i') }}</td>
                <td>{{ $movement->warehouse->name }}</td>
                <td>{{ $movement->product->name }}</td>
                <td>{{ $movement->productVariant?->name ?? '-' }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $movement->movement_type)) }}</td>
                <td>{{ number_format( (float) $movement->quantity, 4 ) }}</td>
                <td>{{ $movement->creator?->name ?? '-' }}</td>
                <td>{{ $movement->notes ?? '-' }}</td>
            </tr>
        
        @empty
            <tr>
                <td colspan="8">
                    No stock movements found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

<br>

{{ $movements->links() }}

@endsection --}}







@extends('layouts.app')

@section('title', 'Stock Movements')

@section('content')

    @php
        $movementBadge = function ($type) {
            return match ($type) {
                'purchase_receipt', 'goods_receipt', 'sale_return', 'adjustment_in' => 'badge-success',
                'sale', 'adjustment_out', 'transfer_out' => 'badge-danger',
                'transfer_in' => 'badge-info',
                default => 'badge-secondary',
            };
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Stock Movements</h1>
            <p class="page-subtitle">
                Review the complete history of inventory movements across warehouses and products.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('inventory.index') }}"
               class="btn btn-secondary">
                ← Back to Inventory
            </a>

            <a href="{{ route('inventory.adjust.create') }}"
               class="btn btn-primary">
                + Adjust Inventory
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
                <h2>Movement History</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Inventory transactions recorded by the system.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Warehouse</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>User</th>
                            <th>Notes</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($movements as $movement)

                            <tr>

                                <td>
                                    {{ $movement->occurred_at->format('Y-m-d H:i') }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $movement->warehouse->name }}
                                    </strong>
                                </td>

                                <td>
                                    <strong>
                                        {{ $movement->product->name }}
                                    </strong>

                                    @if($movement->product->sku)
                                        <div style="font-size:12px; color:#64748b; margin-top:3px;">
                                            SKU: {{ $movement->product->sku }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $movement->productVariant?->name ?? '-' }}
                                </td>

                                <td>
                                    <span class="badge {{ $movementBadge($movement->movement_type) }}">
                                        {{ ucwords(str_replace('_', ' ', $movement->movement_type)) }}
                                    </span>
                                </td>

                                <td>
                                    @php
                                        $quantity = (float) $movement->quantity;
                                    @endphp

                                    <strong>
                                        {{ number_format(abs($quantity), 4) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $movement->creator?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $movement->notes ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="table-empty">
                                    No stock movements found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- <style>
        .pagination-wrapper svg {
            width: 20px !important;
            height: 20px !important;
        }
    </style> --}}

    <style>
        .pagination-wrapper svg {
            width: 20px !important;
            height: 20px !important;
        }

        /* Hide Laravel's mobile pagination */
        .pagination-wrapper > nav > div:first-child {
            display: none !important;
        }
    </style>

    @if($movements->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $movements->links() }}
        </div>
    @endif

@endsection