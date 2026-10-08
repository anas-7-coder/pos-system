{{-- @extends('layouts.app')

@section('title', 'Sale Details')

@section('content')

<h1>Sale Details</h1>

@if(session('success'))
    <p style="color: green;">
        {{ session('success') }}
    </p>
@endif

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('sales.index') }}">Back to Sales</a>

@if($sale->status === 'draft')
        &nbsp; | &nbsp;
    <a href="{{ route('sales.edit', $sale) }}"><button type="button">Edit</button></a>
        &nbsp; | &nbsp;
    <form action="{{ route('sales.cancel', $sale) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')

        <button type="submit">
            Cancel Sale
        </button>
    </form>
@endif

@if($sale->status === 'draft' && $sale->customer_id && $sale->grand_total > 0)
        &nbsp; | &nbsp;
    <form action="{{ route('sales.credit', $sale) }}" method="POST" style="display:inline">
        @csrf
        @method('PATCH')

        <button type="submit">
            Complete on Credit
        </button>
    </form>
@endif

@if(in_array($sale->status, ['completed', 'partially_refunded']))
        &nbsp; | &nbsp;
    <a href="{{ route('sales.returns.create', $sale) }}"><button type="button">Create Return</button></a>
@endif

<hr>

<p><strong>Invoice:</strong> {{ $sale->invoice_number }}</p>
<p><strong>Customer:</strong> {{ $sale->customer?->name ?? 'Walk-in Customer' }}</p>
<p><strong>Branch:</strong> {{ $sale->branch->name }}</p>
<p><strong>Warehouse:</strong> {{ $sale->warehouse->name }}</p>
<p><strong>Register:</strong> {{ $sale->register->name }}</p>
<p><strong>Cashier:</strong> {{ $sale->cashier->name }}</p>
<p><strong>Date:</strong> {{ $sale->sale_date->format('Y-m-d H:i') }}</p>
<p><strong>Status:</strong> {{ ucfirst($sale->status) }}</p>

@if($sale->customer)
<hr>    
    <h3>Customer Credit</h3>

    <p><strong>Customer:</strong> {{ $sale->customer->name }}</p>
    <p><strong>Current Balance:</strong> {{ number_format($customerBalance, 2) }}</p>
    <p><strong>Credit Limit:</strong> {{ number_format($sale->customer->credit_limit, 2) }}</p>
    
@endif

<hr>

<h2>Items</h2>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>Product</th>
            <th>Variant</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Discount</th>
            <th>Tax Rate</th>
            <th>Tax</th>
            <th>Line Total</th>
        </tr>
    </thead>

    <tbody>

        @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->productVariant?->name ?? '-' }}</td>
                <td>{{ $item->quantity }}</td>
                <td> PKR {{ number_format( (float) $item->unit_price, 2 ) }}</td>
                <td> PKR {{ number_format( (float) $item->discount_amount, 2 ) }}</td>
                <td>{{ number_format( (float) $item->tax_rate, 2 ) }}%</td>
                <td> PKR {{ number_format( (float) $item->tax_amount, 2 ) }}</td>
                <td> PKR {{ number_format( (float) $item->line_total, 2 ) }}</td>
            </tr>
        @endforeach

    </tbody>

</table>

<hr>

<p><strong>Subtotal:</strong> PKR {{ number_format( (float) $sale->subtotal, 2 ) }}</p>
<p><strong>Discount:</strong> PKR {{ number_format( (float) $sale->discount_amount, 2 ) }}</p>
<p><strong>Tax:</strong> PKR {{ number_format( (float) $sale->tax_amount, 2 ) }}</p>
<p><strong>Grand Total:</strong> PKR {{ number_format( (float) $sale->grand_total, 2 ) }}</p>
<p><strong>Notes:</strong> {{ $sale->notes ?? '-' }}</p>

<hr>

<h2>Payment Summary</h2>

@php
    $paidAmount      = $sale->payments->where('status', 'posted')->sum('amount');
    $remainingAmount = max(0, (float) $sale->grand_total - (float) $paidAmount);
@endphp

<p><strong>Total:</strong> {{ number_format($sale->grand_total, 2) }}</p>
<p><strong>Paid:</strong> {{ number_format($paidAmount, 2) }}</p>
<p><strong>Remaining:</strong> {{ number_format($remainingAmount, 2) }}</p>

@if($sale->status === 'draft' && $remainingAmount > 0)
    <a href="{{ route('sales.payments.create', $sale) }}"><button type="button">Receive Payment</button></a>
@endif

<h3>Payments</h3>

<table border="1" cellpadding="8" cellspacing="0">

    <thead>
        <tr>
            <th>Method</th>
            <th>Amount</th>
            <th>Received</th>
            <th>Change</th>
            <th>Reference</th>
            <th>Paid At</th>
        </tr>
    </thead>

    <tbody>

        @forelse($sale->payments->where('status', 'posted') as $payment)
            <tr>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                <td>{{ number_format($payment->amount, 2) }}</td>
                <td>{{ number_format($payment->received_amount, 2) }}</td>
                <td>{{ number_format($payment->change_amount, 2) }}</td>
                <td>{{ $payment->reference ?? '-' }}</td>
                <td>{{ $payment->paid_at }}</td>
            </tr>

        @empty
            <tr>
                <td colspan="6">
                    No payments recorded.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

@endsection --}}







@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')

    @php
        $statusClass = match ($sale->status) {
            'draft' => 'badge-warning',
            'completed' => 'badge-success',
            'partially_refunded' => 'badge-info',
            'refunded' => 'badge-secondary',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };

        $paidAmount = (float) $sale->payments
            ->where('status', 'posted')
            ->sum('amount');

        $remainingAmount = max(
            0,
            (float) $sale->grand_total - $paidAmount
        );
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Sale Details</h1>
            <p class="page-subtitle">
                Review invoice information, items, payments, and customer credit details.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('sales.index') }}"
               class="btn btn-secondary">
                ← Back to Sales
            </a>

            @if($sale->status === 'draft')

                <a href="{{ route('sales.edit', $sale) }}"
                   class="btn btn-outline-primary">
                    Edit
                </a>

                <form action="{{ route('sales.cancel', $sale) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to cancel this sale?');">
                        Cancel Sale
                    </button>
                </form>

            @endif

            @if($sale->status === 'draft' && $sale->customer_id && $sale->grand_total > 0)

                <form action="{{ route('sales.credit', $sale) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Complete this sale on customer credit?');">
                        Complete on Credit
                    </button>
                </form>

            @endif

            @if(in_array($sale->status, ['completed', 'partially_refunded']))

                <a href="{{ route('sales.returns.create', $sale) }}"
                   class="btn btn-outline-primary">
                    Create Return
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

    {{-- Sale Information --}}
    <div class="card">

        <div class="card-header">
            <h2>Sale Information</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">Invoice</div>
                    <div class="detail-value">
                        <strong>{{ $sale->invoice_number }}</strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="badge {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $sale->status)) }}
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Customer</div>
                    <div class="detail-value">
                        {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Branch</div>
                    <div class="detail-value">
                        {{ $sale->branch->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Warehouse</div>
                    <div class="detail-value">
                        {{ $sale->warehouse->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Register</div>
                    <div class="detail-value">
                        {{ $sale->register->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Cashier</div>
                    <div class="detail-value">
                        {{ $sale->cashier->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Sale Date</div>
                    <div class="detail-value">
                        {{ $sale->sale_date->format('Y-m-d H:i') }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Customer Credit --}}
    @if($sale->customer)

        <div class="card">

            <div class="card-header">
                <h2>Customer Credit</h2>
            </div>

            <div class="card-body">

                <div class="detail-grid">

                    <div class="detail-item">
                        <div class="detail-label">Customer</div>
                        <div class="detail-value">
                            {{ $sale->customer->name }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Current Balance</div>
                        <div class="detail-value">
                            <strong>
                                PKR {{ number_format((float) $customerBalance, 2) }}
                            </strong>
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Credit Limit</div>
                        <div class="detail-value">
                            PKR {{ number_format((float) $sale->customer->credit_limit, 2) }}
                        </div>
                    </div>

                </div>

            </div>
        </div>

    @endif

    {{-- Sale Items --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Sale Items</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Products, quantities, pricing, discounts, and taxes included in this sale.
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
                            <th>Variant</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Discount</th>
                            <th>Tax Rate</th>
                            <th>Tax</th>
                            <th>Line Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($sale->items as $index => $item)

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
                                    {{ number_format((float) $item->quantity, 4) }}
                                </td>

                                <td>
                                    PKR {{ number_format((float) $item->unit_price, 2) }}
                                </td>

                                <td>
                                    PKR {{ number_format((float) $item->discount_amount, 2) }}
                                </td>

                                <td>
                                    {{ number_format((float) $item->tax_rate, 2) }}%
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
                                <td colspan="9" class="table-empty">
                                    No sale items found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    {{-- Sale Summary --}}
    <div class="card">

        <div class="card-header">
            <h2>Sale Summary</h2>
        </div>

        <div class="card-body">

            <div class="summary-box">

                <div class="summary-row">
                    <span>Subtotal</span>
                    <strong>
                        PKR {{ number_format((float) $sale->subtotal, 2) }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Discount</span>
                    <strong>
                        PKR {{ number_format((float) $sale->discount_amount, 2) }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Tax</span>
                    <strong>
                        PKR {{ number_format((float) $sale->tax_amount, 2) }}
                    </strong>
                </div>

                <div class="summary-row summary-total">
                    <span>Grand Total</span>
                    <strong>
                        PKR {{ number_format((float) $sale->grand_total, 2) }}
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
                    {{ $sale->notes ?? '-' }}
                </div>
            </div>

        </div>
    </div>

    {{-- Payment Summary --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Payment Summary</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Review payments received and the remaining amount due.
                </p>
            </div>

            @if($sale->status === 'draft' && $remainingAmount > 0)
                <a href="{{ route('sales.payments.create', $sale) }}"
                   class="btn btn-primary">
                    Receive Payment
                </a>
            @endif
        </div>

        <div class="card-body">

            <div class="summary-box">

                <div class="summary-row">
                    <span>Total</span>
                    <strong>
                        PKR {{ number_format((float) $sale->grand_total, 2) }}
                    </strong>
                </div>

                <div class="summary-row">
                    <span>Paid</span>
                    <strong>
                        PKR {{ number_format($paidAmount, 2) }}
                    </strong>
                </div>

                <div class="summary-row summary-total">
                    <span>Remaining</span>
                    <strong>
                        PKR {{ number_format($remainingAmount, 2) }}
                    </strong>
                </div>

            </div>

        </div>
    </div>

    {{-- Payments --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Payments</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Posted payment transactions recorded against this sale.
                </p>
            </div>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Received</th>
                            <th>Change</th>
                            <th>Reference</th>
                            <th>Paid At</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($sale->payments->where('status', 'posted') as $payment)

                            <tr>

                                <td>
                                    <span class="badge badge-info">
                                        {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $payment->amount, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    PKR {{ number_format((float) $payment->received_amount, 2) }}
                                </td>

                                <td>
                                    PKR {{ number_format((float) $payment->change_amount, 2) }}
                                </td>

                                <td>
                                    {{ $payment->reference ?? '-' }}
                                </td>

                                <td>
                                    {{ $payment->paid_at }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="table-empty">
                                    No payments recorded.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

@endsection