{{-- @extends('layouts.app')

@section('title', 'Purchase Order Goods Receipts')

@section('content')

<h1>Goods Receipts</h1>

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

<a href="{{ route('purchase-orders.show', $purchaseOrder) }}">Back to Purchase Order</a>
    &nbsp; | &nbsp;
@if($purchaseOrder->status === 'approved')
    <a href="{{ route('goods-receipts.create', $purchaseOrder) }}">Receive Goods</a>
@endif

<hr>

<p><strong>Purchase Order:</strong> {{ $purchaseOrder->po_number }}</p>

<hr>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>Receipt Number</th>
            <th>Supplier</th>
            <th>Branch</th>
            <th>Warehouse</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($goodsReceipts as $goodsReceipt)

            <tr>
                <td>{{ $goodsReceipt->receipt_number }}</td>
                <td>{{ $goodsReceipt->supplier->name }}</td>
                <td>{{ $goodsReceipt->branch->name }}</td>
                <td>{{ $goodsReceipt->warehouse->name }}</td>
                <td>{{ $goodsReceipt->receipt_date->format('Y-m-d') }}</td>
                <td> PKR {{ number_format( (float) $goodsReceipt->grand_total, 2 ) }}</td>
                <td>{{ ucfirst($goodsReceipt->status) }}</td>
                <td>
                    <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"><button type="button">View</button></a>

                    @if($goodsReceipt->status === 'draft')
                        &nbsp;
                        <a href="{{ route('goods-receipts.edit', $goodsReceipt) }}"><button type="button">Edit</button></a>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8">
                    No goods receipts found for this purchase order.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

<br>

{{ $goodsReceipts->links() }}

@endsection --}}








@extends('layouts.app')

@section('title', 'Purchase Order Goods Receipts')

@section('content')

    @php
        $statusClass = function ($status) {
            return match ($status) {
                'draft' => 'badge-warning',
                'posted' => 'badge-success',
                'cancelled' => 'badge-danger',
                default => 'badge-secondary',
            };
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Goods Receipts</h1>
            <p class="page-subtitle">
                View goods received against purchase order
                <strong>{{ $purchaseOrder->po_number }}</strong>.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
               class="btn btn-secondary">
                ← Back to Purchase Order
            </a>

            @if($purchaseOrder->status === 'approved')
                <a href="{{ route('goods-receipts.create', $purchaseOrder) }}"
                   class="btn btn-primary">
                    + Receive Goods
                </a>
            @endif

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

    {{-- Purchase Order Summary --}}
    <div class="card">

        <div class="card-header">
            <h2>Purchase Order</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">PO Number</div>
                    <div class="detail-value">
                        {{ $purchaseOrder->po_number }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Supplier</div>
                    <div class="detail-value">
                        {{ $purchaseOrder->supplier->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Branch</div>
                    <div class="detail-value">
                        {{ $purchaseOrder->branch->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Warehouse</div>
                    <div class="detail-value">
                        {{ $purchaseOrder->warehouse->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="badge {{ $statusClass($purchaseOrder->status) }}">
                            {{ ucwords(str_replace('_', ' ', $purchaseOrder->status)) }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Goods Receipts --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Goods Receipts</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Receipt history for this purchase order.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Receipt Number</th>
                            <th>Supplier</th>
                            <th>Branch</th>
                            <th>Warehouse</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($goodsReceipts as $goodsReceipt)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $goodsReceipt->receipt_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $goodsReceipt->supplier->name }}
                                </td>

                                <td>
                                    {{ $goodsReceipt->branch->name }}
                                </td>

                                <td>
                                    {{ $goodsReceipt->warehouse->name }}
                                </td>

                                <td>
                                    {{ $goodsReceipt->receipt_date->format('Y-m-d') }}
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $goodsReceipt->grand_total, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass($goodsReceipt->status) }}">
                                        {{ ucwords(str_replace('_', ' ', $goodsReceipt->status)) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="table-actions">

                                        <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            View
                                        </a>

                                        @if($goodsReceipt->status === 'draft')
                                            <a href="{{ route('goods-receipts.edit', $goodsReceipt) }}"
                                               class="btn btn-secondary btn-sm">
                                                Edit
                                            </a>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="table-empty">
                                    No goods receipts found for this purchase order.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($goodsReceipts->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $goodsReceipts->links() }}
        </div>
    @endif

@endsection