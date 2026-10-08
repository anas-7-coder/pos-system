{{-- @extends('layouts.app')

@section('title', 'Product Details')

@section('content')

<h1>Product Details</h1>

<a href="{{ route('products.index') }}">Back to Products</a> 
    &nbsp; | &nbsp;
@can('viewAny', App\Models\ProductBarcode::class)
    <a href="{{ route('products.barcodes.index', $product) }}">Manage Barcodes</a> 
@endcan
    &nbsp; | &nbsp;
@can('viewAny', App\Models\ProductVariant::class)
    <a href="{{ route('products.variants.index', $product) }}">Manage Variants</a> 
@endcan
    &nbsp; | &nbsp;
<a href="{{ route('products.prices.index', $product) }}">Manage Prices</a>

<hr>

<p><strong>Name:</strong> {{ $product->name }}</p>
<p><strong>SKU:</strong> {{ $product->sku }}</p>
<p><strong>Category:</strong> {{ $product->category->name }}</p>
<p><strong>Brand:</strong> {{ $product->brand->name ?? '-' }}</p>
<p><strong>Unit:</strong> {{ $product->unit->name }} ({{ $product->unit->short_name }})</p>
<p><strong>Tax Class:</strong> {{ $product->taxClass->name ?? '-' }}</p>
<p><strong>Description:</strong> {{ $product->description ?? '-' }}</p>
<p><strong>Product Type:</strong> {{ $product->product_type }}</p>
<p><strong>Cost Price:</strong> {{ $product->cost_price }}</p>
<p><strong>Selling Price:</strong> {{ $product->selling_price }}</p>
<p><strong>Track Inventory:</strong> {{ $product->track_inventory ? 'Yes' : 'No' }}</p>
<p><strong>Allow Negative Stock:</strong> {{ $product->allow_negative_stock ? 'Yes' : 'No' }}</p>
<p><strong>Status:</strong> {{ $product->status }}</p>

<hr>

@endsection --}}





@extends('layouts.app')

@section('title', 'Product Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Product Details</h1>
            <p class="page-subtitle">
                View product information, inventory settings, and related catalog data.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('products.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Products
            </a>

            @can('update', $product)
                <a
                    href="{{ route('products.edit', $product) }}"
                    class="btn btn-primary"
                >
                    Edit Product
                </a>
            @endcan

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


    {{-- Product Information --}}
    <div class="card">

        <div class="card-header">

            <div>
                <h2 class="card-title">Product Information</h2>
                <p class="card-subtitle">
                    Basic product details and inventory configuration.
                </p>
            </div>

            <div>

                @if($product->status === 'Active')
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
                    <span class="detail-label">ID</span>
                    <span class="detail-value">
                        {{ $product->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">
                        {{ $product->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">SKU</span>
                    <span class="detail-value">
                        {{ $product->sku }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Category</span>
                    <span class="detail-value">
                        {{ $product->category->name ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Brand</span>
                    <span class="detail-value">
                        {{ $product->brand->name ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Unit</span>
                    <span class="detail-value">
                        {{ $product->unit->name ?? '-' }}
                        @if($product->unit)
                            ({{ $product->unit->short_name }})
                        @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Tax Class</span>
                    <span class="detail-value">
                        {{ $product->taxClass->name ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Product Type</span>
                    <span class="detail-value">
                        @if($product->product_type === 'stockable')
                            <span class="badge badge-secondary">
                                Stockable
                            </span>
                        @else
                            <span class="badge badge-info">
                                Service
                            </span>
                        @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Cost Price</span>
                    <span class="detail-value">
                        {{ number_format((float) $product->cost_price, 2) }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Selling Price</span>
                    <span class="detail-value">
                        {{ number_format((float) $product->selling_price, 2) }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Track Inventory</span>
                    <span class="detail-value">
                        @if($product->track_inventory)
                            <span class="badge badge-success">
                                Yes
                            </span>
                        @else
                            <span class="badge badge-secondary">
                                No
                            </span>
                        @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Allow Negative Stock</span>
                    <span class="detail-value">
                        @if($product->allow_negative_stock)
                            <span class="badge badge-warning">
                                Yes
                            </span>
                        @else
                            <span class="badge badge-secondary">
                                No
                            </span>
                        @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($product->status === 'Active')
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
                        {{ $product->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $product->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">
                        {{ $product->description ?: '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- Product Management --}}
    <div class="card" style="margin-top: 24px;">

        <div class="card-header">

            <div>
                <h2 class="card-title">Product Management</h2>
                <p class="card-subtitle">
                    Manage related barcodes, variants, and pricing.
                </p>
            </div>

        </div>

        <div class="card-body">

            <div class="page-actions">

                @can('viewAny', App\Models\ProductBarcode::class)
                    <a
                        href="{{ route('products.barcodes.index', $product) }}"
                        class="btn btn-secondary"
                    >
                        Manage Barcodes
                    </a>
                @endcan

                @can('viewAny', App\Models\ProductVariant::class)
                    <a
                        href="{{ route('products.variants.index', $product) }}"
                        class="btn btn-secondary"
                    >
                        Manage Variants
                    </a>
                @endcan

                <a
                    href="{{ route('products.prices.index', $product) }}"
                    class="btn btn-secondary"
                >
                    Manage Prices
                </a>

            </div>

        </div>

    </div>

@endsection