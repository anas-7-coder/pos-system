{{-- @extends('layouts.app')

@section('title', 'Stock Count Details')

@section('content')

<h1>Stock Count Details</h1>

<a href="{{ route('stock-counts.index') }}">Back to Stock Counts</a>

@if($stockCount->status === 'draft')
        &nbsp; | &nbsp;
    <form action="{{ route('stock-counts.post', $stockCount) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')

        <button type="submit">
            Post Count
        </button>
    </form>
        &nbsp; | &nbsp;
    <form action="{{ route('stock-counts.cancel',$stockCount) }}" method="POST" style="display:inline;">
        @csrf
        @method('PATCH')

        <button type="submit">
            Cancel
        </button>
    </form>
@endif

<hr>

<p><strong>Count Number:</strong> {{ $stockCount->count_number }}</p>
<p><strong>Warehouse:</strong> {{ $stockCount->warehouse->name }}</p>
<p><strong>Count Date:</strong> {{ $stockCount->count_date->format('Y-m-d') }}</p>
<p><strong>Status:</strong> {{ ucfirst($stockCount->status) }}</p>

<hr>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>Product</th>
            <th>Variant</th>
            <th>System Quantity</th>
            <th>Counted Quantity</th>
            <th>Difference</th>
        </tr>
    </thead>

    <tbody>

        @foreach($stockCount->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->productVariant?->name ?? '-' }}</td>
                <td>{{ number_format( (float) $item->system_quantity, 4 ) }}</td>
                <td>{{ number_format( (float) $item->counted_quantity, 4 ) }}</td>
                <td>{{ number_format( (float) $item->difference, 4 ) }}</td>
            </tr>
        @endforeach

    </tbody>

</table>

<hr>

<p><strong>Notes:</strong> {{ $stockCount->notes ?? '-' }}</p>

@if($stockCount->posted_at)
    <p><strong>Posted At:</strong> {{ $stockCount->posted_at->format('Y-m-d H:i') }}</p>
    <p><strong>Posted By:</strong> {{ $stockCount->postedBy?->name ?? '-' }}</p>
@endif

@endsection --}}






@extends('layouts.app')

@section('title', 'Stock Count Details')

@section('content')

    @php
        $statusClass = match ($stockCount->status) {
            'draft' => 'badge-warning',
            'posted', 'completed' => 'badge-success',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Stock Count Details</h1>
            <p class="page-subtitle">
                Review the physical count, system quantities, reconciliation differences, and posting status.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('stock-counts.index') }}"
               class="btn btn-secondary">
                ← Back to Stock Counts
            </a>

            @if($stockCount->status === 'draft')

                <a href="{{ route('stock-counts.edit', $stockCount) }}"
                   class="btn btn-outline-primary">
                    Edit
                </a>

                <form action="{{ route('stock-counts.post', $stockCount) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-primary"
                            onclick="return confirm('Are you sure you want to post this stock count?');">
                        Post Count
                    </button>
                </form>

                <form action="{{ route('stock-counts.cancel', $stockCount) }}"
                      method="POST"
                      style="display:inline;">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to cancel this stock count?');">
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

    {{-- Count Information --}}
    <div class="card">

        <div class="card-header">
            <h2>Count Information</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">Count Number</div>
                    <div class="detail-value">
                        {{ $stockCount->count_number }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="badge {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $stockCount->status)) }}
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Warehouse</div>
                    <div class="detail-value">
                        {{ $stockCount->warehouse->name }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Count Date</div>
                    <div class="detail-value">
                        {{ $stockCount->count_date->format('Y-m-d') }}
                    </div>
                </div>

                @if($stockCount->posted_at)

                    <div class="detail-item">
                        <div class="detail-label">Posted At</div>
                        <div class="detail-value">
                            {{ $stockCount->posted_at->format('Y-m-d H:i') }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Posted By</div>
                        <div class="detail-value">
                            {{ $stockCount->postedBy?->name ?? '-' }}
                        </div>
                    </div>

                @endif

            </div>

        </div>
    </div>

    {{-- Count Items --}}
    <div class="card">

        <div class="card-header">
            <div>
                <h2>Count Items</h2>
                <p class="page-subtitle" style="margin-top:4px;">
                    Comparison between system stock and the physical count.
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
                            <th>System Quantity</th>
                            <th>Counted Quantity</th>
                            <th>Difference</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($stockCount->items as $index => $item)

                            @php
                                $systemQuantity = (float) $item->system_quantity;
                                $countedQuantity = (float) $item->counted_quantity;
                                $difference = (float) $item->difference;
                            @endphp

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
                                    {{ number_format($systemQuantity, 4) }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($countedQuantity, 4) }}
                                    </strong>
                                </td>

                                <td>
                                    @if($difference > 0)
                                        <span class="badge badge-success">
                                            +{{ number_format($difference, 4) }}
                                        </span>
                                    @elseif($difference < 0)
                                        <span class="badge badge-danger">
                                            {{ number_format($difference, 4) }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            0.0000
                                        </span>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="table-empty">
                                    No stock count items found.
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
                    {{ $stockCount->notes ?? '-' }}
                </div>
            </div>

        </div>
    </div>

@endsection