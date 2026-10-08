{{-- @extends('layouts.app')

@section('title', 'Brand Details')

@section('content')

<h1>Brand Details</h1>

<a href="{{ route('brands.index') }}">Back to Brands</a>

<hr>

<p><strong>Name:</strong> {{ $brand->name }}</p>
<p><strong>Code:</strong> {{ $brand->code }}</p>
<p><strong>Description:</strong> {{ $brand->description ?? '-' }}</p>
<p><strong>Status:</strong> {{ $brand->status }}</p>

<hr>

@endsection --}}




@extends('layouts.app')

@section('title', 'Brand Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Brand Details</h1>
            <p class="page-subtitle">
                View information about this product brand.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('brands.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Brands
            </a>

            @can('update', $brand)
                <a
                    href="{{ route('brands.edit', $brand) }}"
                    class="btn btn-primary"
                >
                    Edit Brand
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

    <div class="card">

        <div class="card-header">
            <div>
                <h2 class="card-title">Brand Information</h2>
                <p class="card-subtitle">
                    Basic information about this brand.
                </p>
            </div>

            <div>
                @if($brand->status === 'Active')
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
                        {{ $brand->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">
                        {{ $brand->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Code</span>
                    <span class="detail-value">
                        {{ $brand->code }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($brand->status === 'Active')
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
                        {{ $brand->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $brand->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">
                        {{ $brand->description ?: '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>

@endsection