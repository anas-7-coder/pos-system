{{-- @extends('layouts.app')

@section('title', 'Product Price Details')

@section('content')

<h1>Product Price Details</h1>

<a href="{{ route('products.prices.index', $product) }}">Back To Prices</a>


<hr>

<p><strong>Product:</strong> {{ $product->name }}</p>
<p><strong>Variant:</strong> {{ $productPrice->productVariant?->name ?? 'All / Default' }}</p>
<p><strong>Branch:</strong> {{ $productPrice->branch?->name ?? 'All Branches' }}</p>
<p><strong>Price Type:</strong> {{ ucfirst($productPrice->price_type) }}</p>
<p><strong>Price:</strong> PKR {{ number_format((float) $productPrice->price, 2) }}</p>
<p><strong>Effective From:</strong> {{ $productPrice->effective_from?->format('Y-m-d') }}</p>
<p><strong>Effective To:</strong> {{ $productPrice->effective_to?->format('Y-m-d') ?? 'Open ended' }}</p>
<p><strong>Status:</strong> {{ ucfirst($productPrice->status) }}</p>
<p><strong>Description:</strong> {{ $productPrice->description ?? '-' }}</p>

@endsection --}}










@extends('layouts.app')

@section('title', 'Product Price Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Product Price Details</h1>
            <p class="page-subtitle">
                View the pricing configuration and effective period for this product.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('products.prices.index', $product) }}"
                class="btn btn-secondary"
            >
                ← Back to Prices
            </a>

            <a
                href="{{ route('products.prices.edit', [$product, $productPrice]) }}"
                class="btn btn-primary"
            >
                Edit Price
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
                <h2 class="card-title">Price Information</h2>
                <p class="card-subtitle">
                    Pricing configuration for
                    <strong>{{ $product->name }}</strong>.
                </p>
            </div>

            <div>
                @if($productPrice->status === 'Active')
                    <span class="badge badge-success">
                        Active
                    </span>
                @else
                    <span class="badge badge-danger">
                        Inactive
                    </span>
                @endif
            </div>

        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <span class="detail-label">Product</span>
                    <span class="detail-value">
                        {{ $product->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Variant</span>
                    <span class="detail-value">
                        {{ $productPrice->productVariant?->name ?? 'All / Default' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Branch</span>
                    <span class="detail-value">
                        {{ $productPrice->branch?->name ?? 'All Branches' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Price Type</span>
                    <span class="detail-value">
                        {{ ucfirst($productPrice->price_type) }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Price</span>
                    <span class="detail-value">
                        <strong>
                            PKR {{ number_format((float) $productPrice->price, 2) }}
                        </strong>
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Effective From</span>
                    <span class="detail-value">
                        {{ $productPrice->effective_from?->format('Y-m-d') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Effective To</span>
                    <span class="detail-value">
                        {{ $productPrice->effective_to?->format('Y-m-d') ?? 'Open ended' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($productPrice->status === 'Active')
                            <span class="badge badge-success">
                                Active
                            </span>
                        @else
                            <span class="badge badge-danger">
                                Inactive
                            </span>
                        @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">
                        {{ $productPrice->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $productPrice->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">
                        {{ $productPrice->description ?: '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>

@endsection