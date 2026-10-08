{{-- @extends('layouts.app')

@section('title', 'Goods Receipt Details')

@section('content')

<h1>Goods Receipt Details</h1>

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('purchase-orders.goods-receipts.index', $purchaseOrder->id) }}">Back to Goods Receipts</a>

@if($goodsReceipt->status === 'draft')
    &nbsp; | &nbsp;

    <form action="{{ route('goods-receipts.post', $goodsReceipt) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')

        <button type="submit">
            Post Receipt
        </button>
    </form>

    &nbsp; | &nbsp;

    <form action="{{ route('goods-receipts.cancel', $goodsReceipt) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')

        <button type="submit">
            Cancel
        </button>
    </form>
@endif

@if($goodsReceipt->posted_at)
<hr>
    <p><strong>Posted At:</strong> {{ $goodsReceipt->posted_at->format('Y-m-d H:i') }}</p>
@endif

<hr>

<p><strong>Receipt Number:</strong> {{ $goodsReceipt->receipt_number }}</p>
<p><strong>Purchase Order:</strong> {{ $goodsReceipt->purchaseOrder->po_number }}</p>
<p><strong>Supplier:</strong> {{ $goodsReceipt->supplier->name }}</p>
<p><strong>Branch:</strong> {{ $goodsReceipt->branch->name }}</p>
<p><strong>Warehouse:</strong> {{ $goodsReceipt->warehouse->name }}</p>
<p><strong>Receipt Date:</strong> {{ $goodsReceipt->receipt_date->format('Y-m-d') }}</p>
<p><strong>Status:</strong> {{ ucfirst($goodsReceipt->status) }}</p>

<hr>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>Product</th>
            <th>Variant</th>
            <th>Received Quantity</th>
            <th>Unit Cost</th>
            <th>Discount</th>
            <th>Tax</th>
            <th>Line Total</th>
        </tr>
    </thead>

    <tbody>

        @foreach($goodsReceipt->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->productVariant?->name ?? '-' }}</td>
                <td>{{ $item->received_quantity }}</td>
                <td> PKR {{ number_format( (float) $item->unit_cost, 2 ) }}</td>
                <td> PKR {{ number_format( (float) $item->discount_amount, 2 ) }}</td>
                <td> PKR {{ number_format( (float) $item->tax_amount, 2 ) }}</td>
                <td> PKR {{ number_format( (float) $item->line_total, 2 ) }}</td>
            </tr>
        @endforeach

    </tbody>

</table>

<hr>

<p><strong>Subtotal:</strong> PKR {{ number_format( (float) $goodsReceipt->subtotal, 2 ) }}</p>
<p><strong>Discount:</strong> PKR {{ number_format( (float) $goodsReceipt->discount_amount, 2 ) }}</p>
<p><strong>Tax:</strong> PKR {{ number_format( (float) $goodsReceipt->tax_amount, 2 ) }}</p>
<p><strong>Grand Total:</strong> PKR {{ number_format( (float) $goodsReceipt->grand_total, 2 ) }}</p>
<p><strong>Notes:</strong> {{ $goodsReceipt->notes ?? '-' }}</p>

@endsection --}}






@extends('layouts.app')

@section('title', 'Goods Receipt Details')

@section('content')

    @php
        $statusClass = match ($goodsReceipt->status) {
            'draft' => 'badge-warning',
            'posted' => 'badge-success',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Goods Receipt Details</h1>
            <p class="page-subtitle">
                Review received items, receipt totals, and posting status.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('purchase-orders.goods-receipts.index', $purchaseOrder->id) }}"
               class="btn btn-secondary">
                ← Back to Goods Receipts
            </a>

            @if($goodsReceipt->status === 'draft')
                <a href="{{ route('goods-receipts.edit', $goodsReceipt) }}"
                   class="btn btn-outline-primary">
                    Edit
                </a>

                <form action="{{ route('goods-receipts.post', $goodsReceipt) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Are you sure you want to post this goods receipt?');">
                        Post Receipt
                    </button>
                </form>

                <form action="{{ route('goods-receipts.cancel', $goodsReceipt) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to cancel this goods receipt?');">
                        Cancel
                    </button>
                </form>
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

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul style="margin:10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Receipt Information --}}
    <div class="card">

        <div class="card-header">
            <h2>Receipt Information</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">Receipt Number</div>
                    <div class="detail-value">
                        {{ $goodsReceipt->receipt_number }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="badge {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $goodsReceipt->status)) }}
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Purchase Order</div>
                    <div class="detail-value">
                        {{ $goodsReceipt->purchaseOrder->po_number }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Supplier</div>
                    <div class="detail-value">
                        {{ $goodsReceipt->supplier->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Branch</div>
                    <div class="detail-value">
                        {{ $goodsReceipt->branch->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Warehouse</div>
                    <div class="detail-value">
                        {{ $goodsReceipt->warehouse->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Receipt Date</div>
                    <div class="detail-value">
                        {{ $goodsReceipt->receipt_date->format('Y-m-d') }}
                    </div>
                </div>

                @if($goodsReceipt->posted_at)
                    <div class="detail-item">
                        <div class="detail-label">Posted At</div>
                        <div class="detail-value">
                            {{ $goodsReceipt->posted_at->format('Y-m-d H:i') }}
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

    {{-- Received Items --}}
    <div class="card">

        <div class="card-header">
            <h2>Received Items</h2>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Received Quantity</th>
                            <th>Unit Cost</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Line Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($goodsReceipt->items as $index => $item)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $item->product->name }}
                                    </strong>

                                    @if($item->product->sku)
                                        <div style="font-size:12px; color:#64748b; margin-top:3px;">
                                            SKU: {{ $item->product->sku }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ $item->productVariant?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ number_format((float) $item->received_quantity, 4) }}
                                </td>

                                <td>
                                    PKR {{ number_format((float) $item->unit_cost, 2) }}
                                </td>

                                <td>
                                    PKR {{ number_format((float) $item->discount_amount, 2) }}
                                </td>

                                <td>
                                    PKR {{ number_format((float) $item->tax_amount, 2) }}
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $item->line_total, 2) }}
                                    </strong>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="table-empty">
                                    No items found for this goods receipt.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- Receipt Summary --}}
    <div class="card">

        <div class="card-header">
            <h2>Receipt Summary</h2>
        </div>

        <div class="card-body">

            <div class="summary-box">

                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>
                        PKR {{ number_format((float) $goodsReceipt->subtotal, 2) }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Discount</span>
                    <strong>
                        PKR {{ number_format((float) $goodsReceipt->discount_amount, 2) }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Tax</span>
                    <strong>
                        PKR {{ number_format((float) $goodsReceipt->tax_amount, 2) }}
                    </strong>
                </div>

                <div class="summary-row summary-total">
                    <span>Grand Total</span>
                    <strong>
                        PKR {{ number_format((float) $goodsReceipt->grand_total, 2) }}
                    </strong>
                </div>

            </div>

        </div>
    </div>

    {{-- Notes --}}
    <div class="card">

        <div class="card-header">
            <h2>Notes</h2>
        </div>

        <div class="card-body">

            <div class="detail-item">
                <div class="detail-value">
                    {{ $goodsReceipt->notes ?? '-' }}
                </div>
            </div>

        </div>
    </div>

@endsection