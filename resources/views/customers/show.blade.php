{{-- @extends('layouts.app')

@section('title', 'Customer Details')

@section('content')

<h1>Customer Details</h1>

<a href="{{ route('customers.index') }}">Back to Customers</a>

<hr>

<p><strong>Name:</strong> {{ $customer->name }}</p>
<p><strong>Customer Code:</strong> {{ $customer->code }}</p>
<p><strong>Customer Group:</strong> {{ $customer->customerGroup?->name ?? 'No Group' }}</p>
<p><strong>Phone:</strong> {{ $customer->phone ?? '-' }}</p>
<p><strong>Email:</strong> {{ $customer->email ?? '-' }}</p>
<p><strong>Address:</strong> {{ $customer->address ?? '-' }}</p>
<p><strong>Tax Number:</strong> {{ $customer->tax_number ?? '-' }}</p>
<p><strong>Opening Balance:</strong> PKR {{ number_format((float) $customer->opening_balance, 2) }}</p>
<p><strong>Credit Limit:</strong> PKR {{ number_format((float) $customer->credit_limit, 2) }}</p>
<p><strong>Status:</strong> {{ $customer->status }}</p>
<p><strong>Notes:</strong> {{ $customer->notes ?? '-' }}</p>

@endsection --}}




@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Customer Details</h1>
            <p class="page-subtitle">
                View customer account, contact, and credit information.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('customers.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Customers
            </a>

            @can('update', $customer)
                <a
                    href="{{ route('customers.edit', $customer) }}"
                    class="btn btn-primary"
                >
                    Edit Customer
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
                <h2 class="card-title">Customer Information</h2>
                <p class="card-subtitle">
                    Basic account and contact information for this customer.
                </p>
            </div>

            <div>
                @if($customer->status === 'Active')
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
                    <span class="detail-label">Customer ID</span>
                    <span class="detail-value">
                        {{ $customer->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">
                        {{ $customer->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Customer Code</span>
                    <span class="detail-value">
                        {{ $customer->code }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Customer Group</span>
                    <span class="detail-value">
                        {{ $customer->customerGroup?->name ?? 'No Group' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value">
                        {{ $customer->phone ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Email</span>
                    <span class="detail-value">
                        {{ $customer->email ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Tax Number</span>
                    <span class="detail-value">
                        {{ $customer->tax_number ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Opening Balance</span>
                    <span class="detail-value">
                        PKR {{ number_format((float) $customer->opening_balance, 2) }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Credit Limit</span>
                    <span class="detail-value">
                        PKR {{ number_format((float) $customer->credit_limit, 2) }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($customer->status === 'Active')
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
                        {{ $customer->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $customer->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Address</span>
                    <span class="detail-value">
                        {{ $customer->address ?: '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Notes</span>
                    <span class="detail-value">
                        {{ $customer->notes ?: '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>

@endsection