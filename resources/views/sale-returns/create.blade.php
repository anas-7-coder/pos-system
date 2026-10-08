{{-- @extends('layouts.app')

@section('title', 'Create Sale Return')

@section('content')

<h1>Create Sale Return</h1>

@if(session('error'))
    <p style="color:red">
        {{ session('error') }}
    </p>
@endif

@if($errors->any())
    <div style="color:red">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<hr>

<p><strong>Invoice:</strong> {{ $sale->invoice_number }}</p>
<p><strong>Total:</strong> {{ number_format($sale->grand_total, 2) }}</p>

<form action="{{ route('sales.returns.store', $sale) }}" method="POST">
    @csrf

    <table border="1" cellpadding="8" cellspacing="0">

        <thead>

            <tr>
                <th>Product</th>
                <th>Sold</th>
                <th>Already Returned</th>
                <th>Remaining</th>
                <th>Return Quantity</th>
                <th>Reason</th>
            </tr>

        </thead>

        <tbody>

            @foreach($sale->items as $item)
                @if($item->remaining_quantity > 0)
                    <tr>
                        <td>{{ $item->product->name }}
                            @if($item->productVariant)
                                — {{ $item->productVariant->name }}
                            @endif
                        </td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->returned_quantity }}</td>
                        <td>{{ $item->remaining_quantity }}</td>
                        <td>
                            <input type="hidden" name="items[{{ $item->id }}][sale_item_id]" value="{{ $item->id }}">
                            <input type="number" name="items[{{ $item->id }}][quantity]" step="0.001" min="0" max="{{ $item->remaining_quantity }}" value="0">
                        </td>
                        <td>
                            <input type="text" name="items[{{ $item->id }}][reason]">
                        </td>
                    </tr>
                @endif
            @endforeach

        </tbody>

    </table>

    <br>

    <label for="notes">Notes</label><br>
    <textarea name="notes" id="notes" rows="3">{{ old('notes') }}</textarea>
    <br><br>
<hr>
    <button type="submit">
        Create Return
    </button>
        &nbsp; | &nbsp;
    <a href="{{ route('sales.show', $sale) }}">
        <button type="button">Cancel</button>
    </a>

</form>

@endsection --}}







@extends('layouts.app')

@section('title', 'Create Sale Return')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Create Sale Return</h1>
            <p class="page-subtitle">
                Select the sold items and quantities being returned by the customer.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('sales.show', $sale) }}"
               class="btn btn-secondary">
                ← Back to Sale
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
                    <div class="detail-label">Customer</div>
                    <div class="detail-value">
                        {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Sale Total</div>
                    <div class="detail-value">
                        <strong>
                            PKR {{ number_format((float) $sale->grand_total, 2) }}
                        </strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Sale Date</div>
                    <div class="detail-value">
                        {{ $sale->sale_date->format('Y-m-d H:i') }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="badge badge-success">
                            {{ ucwords(str_replace('_', ' ', $sale->status)) }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <form action="{{ route('sales.returns.store', $sale) }}"
          method="POST">

        @csrf

        {{-- Return Items --}}
        <div class="card">

            <div class="card-header">
                <div>
                    <h2>Return Items</h2>
                    <p class="page-subtitle" style="margin-top:4px;">
                        Enter the quantity being returned. The quantity cannot exceed the remaining returnable quantity.
                    </p>
                </div>
            </div>

            <div class="card-body">

                <div class="table-wrapper">

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Sold</th>
                                <th>Already Returned</th>
                                <th>Remaining</th>
                                <th style="min-width:150px;">Return Quantity</th>
                                <th style="min-width:220px;">Reason</th>
                            </tr>
                        </thead>

                        <tbody>

                            @php
                                $hasReturnableItems = false;
                            @endphp

                            @foreach($sale->items as $item)

                                @if($item->remaining_quantity > 0)

                                    @php
                                        $hasReturnableItems = true;
                                    @endphp

                                    <tr>

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
                                            {{ number_format((float) $item->returned_quantity, 4) }}
                                        </td>

                                        <td>
                                            <span class="badge badge-warning">
                                                {{ number_format((float) $item->remaining_quantity, 4) }}
                                            </span>
                                        </td>

                                        <td>

                                            <input
                                                type="hidden"
                                                name="items[{{ $item->id }}][sale_item_id]"
                                                value="{{ $item->id }}"
                                            >

                                            <input
                                                type="number"
                                                name="items[{{ $item->id }}][quantity]"
                                                value="{{ old("items.{$item->id}.quantity", 0) }}"
                                                min="0"
                                                max="{{ $item->remaining_quantity }}"
                                                step="0.001"
                                                class="@error("items.{$item->id}.quantity") is-invalid @enderror"
                                            >

                                            @error("items.{$item->id}.quantity")
                                                <div class="field-error">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </td>

                                        <td>

                                            <input
                                                type="text"
                                                name="items[{{ $item->id }}][reason]"
                                                value="{{ old("items.{$item->id}.reason") }}"
                                                class="@error("items.{$item->id}.reason") is-invalid @enderror"
                                                placeholder="Reason for return"
                                            >

                                            @error("items.{$item->id}.reason")
                                                <div class="field-error">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </td>

                                    </tr>

                                @endif

                            @endforeach

                            @if(!$hasReturnableItems)

                                <tr>
                                    <td colspan="6" class="table-empty">
                                        No items are available for return.
                                    </td>
                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

        {{-- Return Notes --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Return Notes</h2>
            </div>

            <div class="card-body">

                <div class="form-group">

                    <label for="notes" class="form-label">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        rows="4"
                        class="@error('notes') is-invalid @enderror"
                        placeholder="Add any additional information about this return..."
                    >{{ old('notes') }}</textarea>

                    @error('notes')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="card">

            <div class="card-body">

                <div class="form-actions">

                    @if($hasReturnableItems)
                        <button type="submit"
                                class="btn btn-primary">
                            Create Return
                        </button>
                    @endif
                    
                    <a href="{{ route('sales.show', $sale) }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </div>
        </div>

    </form>

@endsection