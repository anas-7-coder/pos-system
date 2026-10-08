{{-- @extends('layouts.app')

@section('title', 'Customer Group Details')

@section('content')

<h1>Customer Group Details</h1>

<a href="{{ route('customer-groups.index') }}">Back to Customer Groups</a>

<hr>

<p><strong>Name:</strong> {{ $customerGroup->name }}</p>
<p><strong>Code:</strong> {{ $customerGroup->code }}</p>
<p><strong>Description:</strong> {{ $customerGroup->description ?? '-' }}</p>
<p><strong>Status:</strong> {{ $customerGroup->status }}</p>
<p><strong>Total Customers:</strong> {{ $customerGroup->customers_count }}</p>

@endsection --}}





@extends('layouts.app')

@section('title', 'Customer Group Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Customer Group Details</h1>
            <p class="page-subtitle">
                View customer group information and its current customer count.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('customer-groups.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Customer Groups
            </a>

            @can('update', $customerGroup)
                <a
                    href="{{ route('customer-groups.edit', $customerGroup) }}"
                    class="btn btn-primary"
                >
                    Edit Customer Group
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
                <h2 class="card-title">Customer Group Information</h2>
                <p class="card-subtitle">
                    Basic information about this customer group.
                </p>
            </div>

            <div>
                @if($customerGroup->status === 'Active')
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
                        {{ $customerGroup->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">
                        {{ $customerGroup->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Code</span>
                    <span class="detail-value">
                        {{ $customerGroup->code }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Total Customers</span>
                    <span class="detail-value">
                        <span class="badge badge-secondary">
                            {{ $customerGroup->customers_count }}
                        </span>
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($customerGroup->status === 'Active')
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
                        {{ $customerGroup->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $customerGroup->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">
                        {{ $customerGroup->description ?: '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>

@endsection