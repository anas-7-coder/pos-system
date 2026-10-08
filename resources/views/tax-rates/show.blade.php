{{-- @extends('layouts.app')

@section('title', 'Tax Rate Details')

@section('content')

<h1>Tax Rate Details</h1>

<a href="{{ route('tax-rates.index') }}">Back to Tax Rates</a>

<hr>

<p><strong>Tax Class:</strong> {{ $taxRate->taxClass->name }}</p>
<p><strong>Name:</strong> {{ $taxRate->name }}</p>
<p><strong>Rate:</strong> {{ $taxRate->rate }}%</p>
<p><strong>Effective From:</strong> {{ $taxRate->effective_from->format('Y-m-d') }}</p>
<p><strong>Effective To:</strong> {{ $taxRate->effective_to?->format('Y-m-d') ?? '-' }}</p>
<p><strong>Description:</strong> {{ $taxRate->description ?? '-' }}</p>
<p><strong>Status:</strong> {{ $taxRate->status }}</p>

<hr>

@endsection --}}






@extends('layouts.app')

@section('title', 'Tax Rate Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Tax Rate Details</h1>
            <p class="page-subtitle">
                View tax rate information, validity period, and status.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('tax-rates.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Tax Rates
            </a>

            @can('update', $taxRate)
                <a
                    href="{{ route('tax-rates.edit', $taxRate) }}"
                    class="btn btn-primary"
                >
                    Edit Tax Rate
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
                <h2 class="card-title">Tax Rate Information</h2>
                <p class="card-subtitle">
                    Basic information about this configured tax rate.
                </p>
            </div>

            <div>
                @if($taxRate->status === 'Active')
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
                        {{ $taxRate->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Tax Class</span>
                    <span class="detail-value">
                        {{ $taxRate->taxClass->name ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">
                        {{ $taxRate->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Rate</span>
                    <span class="detail-value">
                        {{ number_format((float) $taxRate->rate, 2) }}%
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Effective From</span>
                    <span class="detail-value">
                        {{ $taxRate->effective_from?->format('Y-m-d') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Effective To</span>
                    <span class="detail-value">
                        {{ $taxRate->effective_to?->format('Y-m-d') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($taxRate->status === 'Active')
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
                        {{ $taxRate->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $taxRate->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">
                        {{ $taxRate->description ?: '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>

@endsection