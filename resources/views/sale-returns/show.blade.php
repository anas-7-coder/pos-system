{{-- @extends('layouts.app')

@section('title', 'Sale Return')

@section('content')

<h1>Sale Return</h1>

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

<a href="{{ route('sale-returns.index') }}">Back to Returns</a> 
    &nbsp; | &nbsp;
@if($saleReturn->status === 'completed' && !$saleReturn->refund)
    <a href="{{ route('sale-returns.refunds.create', $saleReturn) }}">Process Refund</a>
@endif

<hr>

<p><strong>Return Number:</strong> {{ $saleReturn->return_number }}</p>
<p><strong>Invoice:</strong> <a href="{{ route('sales.show', $saleReturn->sale) }}">{{ $saleReturn->sale->invoice_number }}</a></p>
<p><strong>Status:</strong> {{ ucfirst($saleReturn->status) }}</p>
<p><strong>Total:</strong> {{ number_format($saleReturn->grand_total, 2) }}</p>

<hr>

<table border="1" cellpadding="8" cellspacing="0">

    <thead>

        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Tax</th>
            <th>Total</th>
            <th>Reason</th>
        </tr>

    </thead>

    <tbody>

        @foreach($saleReturn->items as $item)
            <tr>
                <td>{{ $item->product->name }}
                    @if($item->productVariant)
                        — {{ $item->productVariant->name }}
                    @endif
                </td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->unit_price, 2) }}</td>
                <td>{{ number_format($item->tax_amount, 2) }}</td>
                <td>{{ number_format($item->line_total, 2) }}</td>
                <td>{{ $item->reason ?? '-' }}</td>
            </tr>
        @endforeach

    </tbody>

</table>

<br>

@if($saleReturn->status === 'draft')
    <form action="{{ route('sale-returns.complete', $saleReturn) }}" method="POST" style="display:inline">
        @csrf
        @method('PATCH')

        <button type="submit">
            Complete Return
        </button>
    </form>
        &nbsp; | &nbsp;
    <form action="{{ route('sale-returns.cancel', $saleReturn) }}" method="POST" style="display:inline">
        @csrf
        @method('PATCH')

        <button type="submit">
            Cancel Return
        </button>
    </form>

@endif

@if($saleReturn->refund)
    <hr>

    <h3>Refund</h3>

    <p><strong>Amount:</strong> {{ number_format($saleReturn->refund->amount, 2) }}</p>
    <p><strong>Method:</strong> {{ ucfirst(str_replace('_', ' ', $saleReturn->refund->payment_method)) }}</p>
    <p><strong>Status:</strong> {{ ucfirst($saleReturn->refund->status) }}</p>
@endif

@endsection --}}









@extends('layouts.app')

@section('title', 'Sale Return Details')

@section('content')

    @php
        $statusClass = match ($saleReturn->status) {
            'draft' => 'badge-warning',
            'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            'partially_refunded' => 'badge-info',
            'refunded' => 'badge-secondary',
            default => 'badge-secondary',
        };

        $refundStatusClass = $saleReturn->refund
            ? match ($saleReturn->refund->status) {
                'pending' => 'badge-warning',
                'completed', 'posted' => 'badge-success',
                'cancelled' => 'badge-danger',
                default => 'badge-secondary',
            }
            : null;
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Sale Return Details</h1>
            <p class="page-subtitle">
                Review returned items, return totals, and refund information.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('sale-returns.index') }}"
               class="btn btn-secondary">
                ← Back to Returns
            </a>

            @if($saleReturn->status === 'completed' && !$saleReturn->refund)

                <a href="{{ route('sale-returns.refunds.create', $saleReturn) }}"
                   class="btn btn-primary">
                    Process Refund
                </a>

            @endif

            @if($saleReturn->status === 'draft')

                <form action="{{ route('sale-returns.complete', $saleReturn) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Are you sure you want to complete this return?');">
                        Complete Return
                    </button>
                </form>

                <form action="{{ route('sale-returns.cancel', $saleReturn) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to cancel this return?');">
                        Cancel Return
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

    {{-- Return Information --}}
    <div class="card">

        <div class="card-header">
            <h2>Return Information</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">Return Number</div>
                    <div class="detail-value">
                        <strong>{{ $saleReturn->return_number }}</strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="badge {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $saleReturn->status)) }}
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Invoice</div>
                    <div class="detail-value">
                        <a href="{{ route('sales.show', $saleReturn->sale) }}">
                            {{ $saleReturn->sale->invoice_number }}
                        </a>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Customer</div>
                    <div class="detail-value">
                        {{ $saleReturn->sale->customer?->name ?? 'Walk-in Customer' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Branch</div>
                    <div class="detail-value">
                        {{ $saleReturn->branch->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Return Date</div>
                    <div class="detail-value">
                        {{ $saleReturn->return_date }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Return Total</div>
                    <div class="detail-value">
                        <strong>
                            PKR {{ number_format((float) $saleReturn->grand_total, 2) }}
                        </strong>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Returned Items --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Returned Items</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Products and quantities included in this return.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Tax</th>
                            <th>Line Total</th>
                            <th>Reason</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($saleReturn->items as $index => $item)

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

                                    @if($item->productVariant)
                                        <div style="font-size:12px; color:#64748b; margin-top:3px;">
                                            Variant: {{ $item->productVariant->name }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    {{ number_format((float) $item->quantity, 4) }}
                                </td>

                                <td>
                                    PKR {{ number_format((float) $item->unit_price, 2) }}
                                </td>

                                <td>
                                    PKR {{ number_format((float) $item->tax_amount, 2) }}
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $item->line_total, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $item->reason ?? '-' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="table-empty">
                                    No returned items found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- Return Summary --}}
    <div class="card">

        <div class="card-header">
            <h2>Return Summary</h2>
        </div>

        <div class="card-body">

            <div class="summary-box">

                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>
                        PKR {{ number_format((float) $saleReturn->subtotal, 2) }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Discount</span>
                    <strong>
                        PKR {{ number_format((float) $saleReturn->discount_amount, 2) }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Tax</span>
                    <strong>
                        PKR {{ number_format((float) $saleReturn->tax_amount, 2) }}
                    </strong>
                </div>

                <div class="summary-row summary-total">
                    <span>Grand Total</span>
                    <strong>
                        PKR {{ number_format((float) $saleReturn->grand_total, 2) }}
                    </strong>
                </div>

            </div>

        </div>
    </div>

    {{-- Refund --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Refund</h2>

                <p class="page-subtitle" style="margin-top:4px;">
                    Refund information associated with this sale return.
                </p>
            </div>

            @if($saleReturn->status === 'completed' && !$saleReturn->refund)
                <a href="{{ route('sale-returns.refunds.create', $saleReturn) }}"
                   class="btn btn-primary">
                    Process Refund
                </a>
            @endif
        </div>

        <div class="card-body">

            @if($saleReturn->refund)

                <div class="detail-grid">

                    <div class="detail-item">
                        <div class="detail-label">Refund Amount</div>
                        <div class="detail-value">
                            <strong>
                                PKR {{ number_format((float) $saleReturn->refund->amount, 2) }}
                            </strong>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Payment Method</div>
                        <div class="detail-value">
                            {{ ucwords(str_replace('_', ' ', $saleReturn->refund->payment_method)) }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="badge {{ $refundStatusClass }}">
                                {{ ucwords(str_replace('_', ' ', $saleReturn->refund->status)) }}
                            </span>
                        </div>
                    </div>

                    @if($saleReturn->refund->reference)
                        <div class="detail-item">
                            <div class="detail-label">Reference</div>
                            <div class="detail-value">
                                {{ $saleReturn->refund->reference }}
                            </div>
                        </div>
                    @endif

                    @if($saleReturn->refund->refunded_at)
                        <div class="detail-item">
                            <div class="detail-label">Refunded At</div>
                            <div class="detail-value">
                                {{ $saleReturn->refund->refunded_at }}
                            </div>
                        </div>
                    @endif

                </div>

            @else

                <div class="table-empty">
                    No refund has been processed for this return yet.
                </div>

            @endif

        </div>
    </div>

@endsection