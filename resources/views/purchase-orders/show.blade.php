{{-- 
@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')

<h1>Purchase Order Details</h1>

<a href="{{ route('purchase-orders.index') }}">Back to Purchase Orders</a>

@if(
    !in_array($purchaseOrder->status, ['cancelled', 'closed'])
)
    &nbsp; | &nbsp;
    <form action="{{ route('purchase-orders.cancel', $purchaseOrder) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')

        <button type="submit">
            Cancel Purchase Order
        </button>
    </form>
@endif

@if($purchaseOrder->status === 'draft')
    &nbsp; | &nbsp;
    <form action="{{ route('purchase-orders.submit', $purchaseOrder) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')

        <button type="submit">
            Submit
        </button>
    </form>
@endif

@if($purchaseOrder->status === 'submitted')
    &nbsp; | &nbsp;
    <form action="{{ route('purchase-orders.approve', $purchaseOrder) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')

        <button type="submit">
            Approve
        </button>
    </form>
@endif

&nbsp; | &nbsp;

<a href="{{ route('purchase-orders.goods-receipts.index', $purchaseOrder) }}">View Goods Receipts</a>

<hr>

<p><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</p>
<p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}</p>
<p><strong>Branch:</strong> {{ $purchaseOrder->branch->name }}</p>
<p><strong>Warehouse:</strong> {{ $purchaseOrder->warehouse->name }}</p>
<p><strong>Order Date:</strong> {{ $purchaseOrder->order_date->format('Y-m-d') }}</p>
<p><strong>Expected Date:</strong> {{ $purchaseOrder->expected_date?->format('Y-m-d') ?? '-' }}</p>
<p><strong>Status:</strong> {{ ucfirst($purchaseOrder->status) }}</p>

<hr>

<h2>Items</h2>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>Product</th>
            <th>Variant</th>
            <th>Quantity</th>
            <th>Unit Cost</th>
            <th>Discount</th>
            <th>Tax</th>
            <th>Line Total</th>
        </tr>
    </thead>

    <tbody>

        @foreach($purchaseOrder->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->productVariant?->name ?? '-' }}</td>
                <td>{{ $item->quantity }}</td>
                <td> PKR {{ number_format( (float) $item->unit_cost, 2 ) }}</td>
                <td> PKR {{ number_format( (float) $item->discount_amount, 2 ) }}</td>
                <td> PKR {{ number_format( (float) $item->tax_amount, 2 ) }}</td>
                <td> PKR {{ number_format( (float) $item->line_total, 2 ) }}</td>
            </tr>
        @endforeach

    </tbody>

</table>

<hr>

<p><strong>Subtotal:</strong> PKR {{ number_format( (float) $purchaseOrder->subtotal, 2 ) }}</p>
<p><strong>Discount:</strong> PKR {{ number_format( (float) $purchaseOrder->discount_amount, 2 ) }}</p>
<p><strong>Tax:</strong> PKR {{ number_format( (float) $purchaseOrder->tax_amount, 2 ) }}</p>
<p><strong>Grand Total:</strong> PKR {{ number_format( (float) $purchaseOrder->grand_total, 2 ) }}</p>
<p><strong>Notes:</strong> {{ $purchaseOrder->notes ?? '-' }}</p>

@endsection --}}






@extends('layouts.app')

@section('title', 'Purchase Order Details')

@section('content')

    @php
        $statusClass = match ($purchaseOrder->status) {
            'draft' => 'badge-warning',
            'submitted' => 'badge-info',
            'approved' => 'badge-success',
            'goods_received' => 'badge-primary',
            'closed' => 'badge-success',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Purchase Order Details</h1>
            <p class="page-subtitle">
                View purchase order information, items, totals, and workflow status.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('purchase-orders.index') }}"
               class="btn btn-secondary">
                ← Back
            </a>

            @if($purchaseOrder->status === 'draft')
                <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}"
                   class="btn btn-outline-primary">
                    Edit
                </a>
            @endif

            <a href="{{ route('purchase-orders.goods-receipts.index', $purchaseOrder) }}"
               class="btn btn-outline-primary">
                Goods Receipts
            </a>

            @if(!in_array($purchaseOrder->status, ['cancelled', 'closed']))
                <form action="{{ route('purchase-orders.cancel', $purchaseOrder) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to cancel this purchase order?');">
                        Cancel
                    </button>
                </form>
            @endif

            @if($purchaseOrder->status === 'draft')
                <form action="{{ route('purchase-orders.submit', $purchaseOrder) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Submit this purchase order?');">
                        Submit
                    </button>
                </form>
            @endif

            @if($purchaseOrder->status === 'submitted')
                <form action="{{ route('purchase-orders.approve', $purchaseOrder) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Approve this purchase order?');">
                        Approve
                    </button>
                </form>
            @endif

        </div>
    </div>

    {{-- Purchase Order Information --}}
    <div class="card">

        <div class="card-header">
            <h2>Purchase Order Information</h2>
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
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="badge {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $purchaseOrder->status)) }}
                        </span>
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
                    <div class="detail-label">Order Date</div>
                    <div class="detail-value">
                        {{ $purchaseOrder->order_date->format('Y-m-d') }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Expected Date</div>
                    <div class="detail-value">
                        {{ $purchaseOrder->expected_date?->format('Y-m-d') ?? '-' }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Items --}}
    <div class="card">

        <div class="card-header">
            <h2>Purchase Order Items</h2>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Line Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($purchaseOrder->items as $index => $item)

                            <tr>
                                <td>
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    <strong>{{ $item->product->name }}</strong>
                                </td>

                                <td>
                                    {{ $item->productVariant?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ number_format((float) $item->quantity, 2) }}
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
                                    No items found for this purchase order.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- Financial Summary --}}
    <div class="card">

        <div class="card-header">
            <h2>Order Summary</h2>
        </div>

        <div class="card-body">

            <div class="summary-box">

                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>
                        PKR {{ number_format((float) $purchaseOrder->subtotal, 2) }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Discount</span>
                    <strong>
                        PKR {{ number_format((float) $purchaseOrder->discount_amount, 2) }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Tax</span>
                    <strong>
                        PKR {{ number_format((float) $purchaseOrder->tax_amount, 2) }}
                    </strong>
                </div>

                <div class="summary-row summary-total">
                    <span>Grand Total</span>
                    <strong>
                        PKR {{ number_format((float) $purchaseOrder->grand_total, 2) }}
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
                    {{ $purchaseOrder->notes ?? '-' }}
                </div>
            </div>

        </div>
    </div>

@endsection