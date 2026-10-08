{{-- @extends('layouts.app')

@section('title', 'Edit Goods Receipt')

@section('content')

<h1>Edit Goods Receipt</h1>

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('purchase-orders.goods-receipts.index', $purchaseOrder->id) }}">Back to Goods-Receipts</a>

<hr>

<p><strong>Purchase Order:</strong> {{ $purchaseOrder->po_number }}</p>
<p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}</p>
<p><strong>Warehouse:</strong> {{ $purchaseOrder->warehouse->name }}</p>

<hr>

<form action="{{ route('goods-receipts.update', $goodsReceipt) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Receipt Number</label><br>
        <input type="text" name="receipt_number" value="{{ old('receipt_number', $goodsReceipt->receipt_number) }}">
        @error('receipt_number')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Receipt Date</label><br>
        <input type="date" name="receipt_date" value="{{ old( 'receipt_date', $goodsReceipt->receipt_date->format('Y-m-d')) }}">
        @error('receipt_date')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <h2>Items</h2>

    <table border="1" cellpadding="8">

        <thead>
            <tr>
                <th>Product</th>
                <th>Variant</th>
                <th>Ordered</th>
                <th>Already Received</th>
                <th>Receive Now</th>
            </tr>
        </thead>

        <tbody>

            @foreach($purchaseOrder->items as $index => $item)
                @php
                    $postedReceived = (float) ($receivedQuantities[$item->id] ?? 0);
                    $currentReceiptQuantity = $goodsReceipt->items
                            ->where('purchase_order_item_id', $item->id)
                            ->sum('received_quantity');
                    $available = (float) $item->quantity - $postedReceived;
                @endphp

                @if($available > 0 || $currentReceiptQuantity > 0)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->productVariant?->name ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $postedReceived }}</td>
                        <td>
                            <input type="hidden" name="items[{{ $index }}][purchase_order_item_id]" value="{{ $item->id }}">
                            <input type="number" name="items[{{ $index }}][received_quantity]" value="{{ old( "items.$index.received_quantity", $currentReceiptQuantity) }}" min="0.0001" max="{{ max( $available, $currentReceiptQuantity) }}" step="0.0001">
                        </td>
                    </tr>
                @endif
            @endforeach

        </tbody>

    </table>
    <br>

    <div>
        <label>Notes</label><br>
        <textarea name="notes" rows="3">{{ old('notes', $goodsReceipt->notes) }}</textarea>
    </div>
    <br>
<hr>
    <button type="submit">
        Update Goods Receipt
    </button>

</form>

@endsection --}}











@extends('layouts.app')

@section('title', 'Edit Goods Receipt')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Goods Receipt</h1>
            <p class="page-subtitle">
                Update receipt details and received quantities for this purchase order.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('purchase-orders.goods-receipts.index', $purchaseOrder->id) }}"
               class="btn btn-secondary">
                ← Back to Goods Receipts
            </a>

            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
               class="btn btn-outline-primary">
                View Purchase Order
            </a>
        </div>
    </div>

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
                    <div class="detail-label">Receipt Number</div>
                    <div class="detail-value">
                        {{ $goodsReceipt->receipt_number }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    <form action="{{ route('goods-receipts.update', $goodsReceipt) }}"
          method="POST">

        @csrf
        @method('PUT')

        {{-- Receipt Information --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Receipt Information</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    <div class="form-group">
                        <label for="receipt_number" class="form-label">
                            Receipt Number
                        </label>

                        <input
                            type="text"
                            id="receipt_number"
                            name="receipt_number"
                            value="{{ old('receipt_number', $goodsReceipt->receipt_number) }}"
                            class="@error('receipt_number') is-invalid @enderror"
                            required
                        >

                        @error('receipt_number')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="receipt_date" class="form-label">
                            Receipt Date
                        </label>

                        <input
                            type="date"
                            id="receipt_date"
                            name="receipt_date"
                            value="{{ old('receipt_date', $goodsReceipt->receipt_date->format('Y-m-d')) }}"
                            class="@error('receipt_date') is-invalid @enderror"
                            required
                        >

                        @error('receipt_date')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

            </div>
        </div>

        {{-- Receipt Items --}}
        <div class="card">

            <div class="card-header">
                <div>
                    <h2>Received Items</h2>
                    <p class="page-subtitle" style="margin-top:4px;">
                        Adjust the quantities received in this goods receipt.
                    </p>
                </div>
            </div>

            <div class="card-body">

                <div class="table-wrapper">

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Variant</th>
                                <th>Ordered</th>
                                <th>Already Received</th>
                                <th>Receive Now</th>
                            </tr>
                        </thead>

                        <tbody>

                            @php
                                $hasItems = false;
                            @endphp

                            @foreach($purchaseOrder->items as $index => $item)

                                @php
                                    $postedReceived = (float) ($receivedQuantities[$item->id] ?? 0);

                                    $currentReceiptQuantity = (float) $goodsReceipt->items
                                        ->where('purchase_order_item_id', $item->id)
                                        ->sum('received_quantity');

                                    $available = max(
                                        0,
                                        (float) $item->quantity - $postedReceived
                                    );

                                    $maxQuantity = max(
                                        $available,
                                        $currentReceiptQuantity
                                    );
                                @endphp

                                @if($available > 0 || $currentReceiptQuantity > 0)

                                    @php
                                        $hasItems = true;
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
                                        </td>

                                        <td>
                                            {{ $item->productVariant?->name ?? '-' }}
                                        </td>

                                        <td>
                                            {{ number_format((float) $item->quantity, 4) }}
                                        </td>

                                        <td>
                                            {{ number_format($postedReceived, 4) }}
                                        </td>

                                        <td style="min-width:180px;">

                                            <input
                                                type="hidden"
                                                name="items[{{ $index }}][purchase_order_item_id]"
                                                value="{{ $item->id }}"
                                            >

                                            <input
                                                type="number"
                                                name="items[{{ $index }}][received_quantity]"
                                                value="{{ old("items.$index.received_quantity", $currentReceiptQuantity) }}"
                                                min="0"
                                                max="{{ $maxQuantity }}"
                                                step="0.0001"
                                                class="@error("items.$index.received_quantity") is-invalid @enderror"
                                                required
                                            >

                                            @error("items.$index.received_quantity")
                                                <div class="field-error">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </td>

                                    </tr>

                                @endif

                            @endforeach

                            @if(!$hasItems)

                                <tr>
                                    <td colspan="5" class="table-empty">
                                        No receivable items are available for this goods receipt.
                                    </td>
                                </tr>

                            @endif

                        </tbody>

                    </table>

                </div>

            </div>
        </div>

        {{-- Notes --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Notes</h2>
            </div>

            <div class="card-body">

                <div class="form-group">

                    <label for="notes" class="form-label">
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        class="@error('notes') is-invalid @enderror"
                        placeholder="Add receiving notes or delivery remarks..."
                    >{{ old('notes', $goodsReceipt->notes) }}</textarea>

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

                    <a href="{{ route('purchase-orders.goods-receipts.index', $purchaseOrder->id) }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    @if($hasItems)
                        <button type="submit"
                                class="btn btn-primary">
                            Update Goods Receipt
                        </button>
                    @endif

                </div>

            </div>
        </div>

    </form>

@endsection