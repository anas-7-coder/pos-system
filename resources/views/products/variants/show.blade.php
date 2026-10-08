{{-- @extends('layouts.app')

@section('title', 'Variant Details')

@section('content')

<h1>Variant Details</h1>

<a href="{{ route('products.variants.index', $productVariant->product) }}">Back to Variants</a>

<hr>

<p><strong>Product:</strong> {{ $productVariant->product->name }}</p>
<p><strong>Name:</strong> {{ $productVariant->name }}</p>
<p><strong>SKU:</strong> {{ $productVariant->sku }}</p>
<p><strong>Cost Price:</strong> {{ $productVariant->cost_price ?? 'Use Product Price' }}</p>
<p><strong>Selling Price:</strong> {{ $productVariant->selling_price ?? 'Use Product Price' }}</p>
<p><strong>Default:</strong> {{ $productVariant->is_default ? 'Yes' : 'No' }}</p>
<p><strong>Status:</strong> {{ $productVariant->status }}</p>
<p><strong>Attributes:</strong>
    @forelse($productVariant->attributes ?? [] as $key => $value)
        <br>
        {{ ucfirst($key) }}: {{ $value }}
    @empty
        None
    @endforelse
</p>

<hr>

<h3>Barcodes</h3>

@forelse($productVariant->barcodes as $barcode)
    <p>
        {{ $barcode->barcode }}
        -
        {{ $barcode->type }}
    </p>

@empty
    <p>No barcodes found.</p>

@endforelse

@endsection --}}









@extends('layouts.app')

@section('title', 'Variant Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Variant Details</h1>
            <p class="page-subtitle">
                View variant information, attributes, pricing, and assigned barcodes.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('products.variants.index', $productVariant->product) }}"
                class="btn btn-secondary"
            >
                ← Back to Variants
            </a>

            @can('update', $productVariant)
                <a
                    href="{{ route('products.variants.edit', $productVariant) }}"
                    class="btn btn-primary"
                >
                    Edit Variant
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


    {{-- Variant Information --}}
    <div class="card">

        <div class="card-header">

            <div>
                <h2 class="card-title">Variant Information</h2>
                <p class="card-subtitle">
                    Details for this product variant.
                </p>
            </div>

            <div>
                @if($productVariant->status === 'Active')
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
                    <span class="detail-label">Variant ID</span>
                    <span class="detail-value">
                        {{ $productVariant->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Product</span>
                    <span class="detail-value">
                        {{ $productVariant->product->name ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Variant Name</span>
                    <span class="detail-value">
                        {{ $productVariant->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">SKU</span>
                    <span class="detail-value">
                        {{ $productVariant->sku }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Cost Price</span>
                    <span class="detail-value">
                        {{ $productVariant->cost_price !== null
                            ? number_format((float) $productVariant->cost_price, 2)
                            : 'Use Product Price' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Selling Price</span>
                    <span class="detail-value">
                        {{ $productVariant->selling_price !== null
                            ? number_format((float) $productVariant->selling_price, 2)
                            : 'Use Product Price' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Default Variant</span>
                    <span class="detail-value">
                        @if($productVariant->is_default)
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
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($productVariant->status === 'Active')
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
                        {{ $productVariant->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $productVariant->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- Variant Attributes --}}
    <div class="card" style="margin-top: 24px;">

        <div class="card-header">
            <div>
                <h2 class="card-title">Variant Attributes</h2>
                <p class="card-subtitle">
                    Attribute values configured for this variant.
                </p>
            </div>
        </div>

        <div class="card-body">

            @if(!empty($productVariant->attributes))

                <div class="detail-grid">

                    @foreach($productVariant->attributes as $key => $value)

                        <div class="detail-item">

                            <span class="detail-label">
                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                            </span>

                            <span class="detail-value">
                                {{ $value ?: '-' }}
                            </span>

                        </div>

                    @endforeach

                </div>

            @else

                <div class="table-empty">
                    <h3>No Attributes</h3>
                    <p>
                        No additional attributes have been configured for this variant.
                    </p>
                </div>

            @endif

        </div>

    </div>


    {{-- Variant Barcodes --}}
    <div class="card" style="margin-top: 24px;">

        <div class="card-header">

            <div>
                <h2 class="card-title">Barcodes</h2>
                <p class="card-subtitle">
                    Barcodes currently assigned to this variant.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $productVariant->barcodes->count() }} Barcodes
                </span>
            </div>

        </div>

        <div class="card-body">

            @if($productVariant->barcodes->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Barcode</th>
                                <th>Type</th>
                                <th>Primary</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($productVariant->barcodes as $barcode)

                                <tr>

                                    <td>
                                        {{ $barcode->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $barcode->barcode }}
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ strtoupper($barcode->type) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($barcode->is_primary)
                                            <span class="badge badge-success">
                                                Primary
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                No
                                            </span>
                                        @endif
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="table-empty">

                    <h3>No Barcodes Found</h3>

                    <p>
                        No barcodes are currently assigned to this variant.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection