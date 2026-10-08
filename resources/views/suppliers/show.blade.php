{{-- @extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')

<h1>Supplier Details</h1>

<a href="{{ route('suppliers.index') }}">Back to Suppliers</a>

<hr>

<p><strong>Name:</strong> {{ $supplier->name }}</p>
<p><strong>Supplier Code:</strong> {{ $supplier->code }}</p>
<p><strong>Phone:</strong> {{ $supplier->phone ?? '-' }}</p>
<p><strong>Email:</strong> {{ $supplier->email ?? '-' }}</p>
<p><strong>Address:</strong> {{ $supplier->address ?? '-' }}</p>
<p><strong>Tax Number:</strong> {{ $supplier->tax_number ?? '-' }}</p>
<p><strong>Opening Balance:</strong> PKR {{ number_format( (float) $supplier->opening_balance, 2) }}</p>
<p><strong>Credit Limit:</strong> PKR {{ number_format( (float) $supplier->credit_limit, 2) }}</p>
<p><strong>Status:</strong> {{ ucfirst($supplier->status) }}</p>
<p><strong>Notes:</strong> {{ $supplier->notes ?? '-' }}</p>

@endsection --}}





@extends('layouts.app')

@section('title', 'Supplier Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Supplier Details</h1>
            <p class="page-subtitle">
                View supplier contact, account, and credit information.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('suppliers.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Suppliers
            </a>

            @can('update', $supplier)
                <a
                    href="{{ route('suppliers.edit', $supplier) }}"
                    class="btn btn-primary"
                >
                    Edit Supplier
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
                <h2 class="card-title">Supplier Information</h2>
                <p class="card-subtitle">
                    Basic account and contact information for this supplier.
                </p>
            </div>

            <div>
                @if($supplier->status === 'Active')
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
                    <span class="detail-label">Supplier ID</span>
                    <span class="detail-value">
                        {{ $supplier->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">
                        {{ $supplier->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Supplier Code</span>
                    <span class="detail-value">
                        {{ $supplier->code }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value">
                        {{ $supplier->phone ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">
                        {{ $supplier->email ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Tax Number</span>
                    <span class="detail-value">
                        {{ $supplier->tax_number ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Opening Balance</span>
                    <span class="detail-value">
                        PKR {{ number_format((float) $supplier->opening_balance, 2) }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Credit Limit</span>
                    <span class="detail-value">
                        PKR {{ number_format((float) $supplier->credit_limit, 2) }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($supplier->status === 'Active')
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
                        {{ $supplier->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $supplier->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Address</span>
                    <span class="detail-value">
                        {{ $supplier->address ?: '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Notes</span>
                    <span class="detail-value">
                        {{ $supplier->notes ?: '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>

@endsection