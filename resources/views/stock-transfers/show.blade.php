{{-- @extends('layouts.app')

@section('title', 'Stock Transfer Details')

@section('content')

<h1>Stock Transfer Details</h1>

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

<a href="{{ route('stock-transfers.index') }}">Back to Stock Transfers</a>

@if($stockTransfer->status === 'draft')
        &nbsp; | &nbsp;
    <form action="{{ route('stock-transfers.post', $stockTransfer) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')

        <button type="submit">
            Post Transfer
        </button>
    </form>
        &nbsp; | &nbsp;
    <form action="{{ route('stock-transfers.cancel', $stockTransfer) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')

        <button type="submit">
            Cancel
        </button>
    </form>
@endif

<hr>

<p><strong>Transfer Number:</strong> {{ $stockTransfer->transfer_number }}</p>
<p><strong>From Warehouse:</strong> {{ $stockTransfer->fromWarehouse->name }}</p>
<p><strong>To Warehouse:</strong> {{ $stockTransfer->toWarehouse->name }}</p>
<p><strong>Transfer Date:</strong> {{ $stockTransfer->transfer_date->format('Y-m-d') }}</p>
<p><strong>Status:</strong> {{ ucfirst($stockTransfer->status) }}</p>

<hr>

<h2>Items</h2>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>Product</th>
            <th>Variant</th>
            <th>Quantity</th>
        </tr>
    </thead>

    <tbody>

        @foreach($stockTransfer->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->productVariant?->name ?? '-' }}</td>
                <td>{{ number_format( (float) $item->quantity, 4 ) }}</td>
            </tr>
        @endforeach

    </tbody>

</table>

<hr>

<p><strong>Notes:</strong> {{ $stockTransfer->notes ?? '-' }}</p>

@if($stockTransfer->posted_at)
    <p><strong>Posted At:</strong> {{ $stockTransfer->posted_at->format('Y-m-d H:i') }}</p>
    <p><strong>Posted By:</strong> {{ $stockTransfer->postedBy?->name ?? '-' }}</p>
@endif

@endsection --}}










@extends('layouts.app')

@section('title', 'Stock Transfer Details')

@section('content')

    @php
        $statusClass = match ($stockTransfer->status) {
            'draft' => 'badge-warning',
            'posted', 'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            'in_transit' => 'badge-info',
            default => 'badge-secondary',
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Stock Transfer Details</h1>
            <p class="page-subtitle">
                Review the transfer details, items, and posting status.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('stock-transfers.index') }}"
               class="btn btn-secondary">
                ← Back to Stock Transfers
            </a>

            @if($stockTransfer->status === 'draft')

                <a href="{{ route('stock-transfers.edit', $stockTransfer) }}"
                   class="btn btn-outline-primary">
                    Edit
                </a>

                <form action="{{ route('stock-transfers.post', $stockTransfer) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Are you sure you want to post this stock transfer?');">
                        Post Transfer
                    </button>
                </form>

                <form action="{{ route('stock-transfers.cancel', $stockTransfer) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to cancel this stock transfer?');">
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

    {{-- Transfer Information --}}
    <div class="card">

        <div class="card-header">
            <h2>Transfer Information</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">Transfer Number</div>
                    <div class="detail-value">
                        {{ $stockTransfer->transfer_number }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="badge {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $stockTransfer->status)) }}
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">From Warehouse</div>
                    <div class="detail-value">
                        {{ $stockTransfer->fromWarehouse->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">To Warehouse</div>
                    <div class="detail-value">
                        {{ $stockTransfer->toWarehouse->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Transfer Date</div>
                    <div class="detail-value">
                        {{ $stockTransfer->transfer_date->format('Y-m-d') }}
                    </div>
                </div>

                @if($stockTransfer->posted_at)
                    <div class="detail-item">
                        <div class="detail-label">Posted At</div>
                        <div class="detail-value">
                            {{ $stockTransfer->posted_at->format('Y-m-d H:i') }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Posted By</div>
                        <div class="detail-value">
                            {{ $stockTransfer->postedBy?->name ?? '-' }}
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

    {{-- Transfer Items --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Transfer Items</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Products and quantities included in this transfer.
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
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($stockTransfer->items as $index => $item)

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
                                    <strong>
                                        {{ number_format((float) $item->quantity, 4) }}
                                    </strong>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="table-empty">
                                    No transfer items found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

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
                    {{ $stockTransfer->notes ?? '-' }}
                </div>
            </div>

        </div>
    </div>

@endsection