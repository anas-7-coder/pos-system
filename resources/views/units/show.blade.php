{{-- @extends('layouts.app')

@section('title', 'Unit Details')

@section('content')

<h1>Unit Details</h1>

<a href="{{ route('units.index') }}">Back to Units</a>

<hr>

<p><strong>Name:</strong> {{ $unit->name }}</p>
<p><strong>Short Name:</strong> {{ $unit->short_name }}</p>
<p><strong>Code:</strong> {{ $unit->code }}</p>
<p><strong>Allows Decimal:</strong> {{ $unit->allows_decimal ? 'Yes' : 'No' }}</p>
<p><strong>Precision:</strong> {{ $unit->precision }}</p>
<p><strong>Description:</strong> {{ $unit->description ?? '-' }}</p>
<p><strong>Status:</strong> {{ $unit->status }}</p>

<hr>

@endsection --}}






@extends('layouts.app')

@section('title', 'Unit Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Unit Details</h1>
            <p class="page-subtitle">
                View information about this measurement unit.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('units.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Units
            </a>

            @can('update', $unit)
                <a
                    href="{{ route('units.edit', $unit) }}"
                    class="btn btn-primary"
                >
                    Edit Unit
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
                <h2 class="card-title">Unit Information</h2>
                <p class="card-subtitle">
                    Basic information about this measurement unit.
                </p>
            </div>

            <div>
                @if($unit->status === 'Active')
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
                        {{ $unit->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">
                        {{ $unit->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Short Name</span>
                    <span class="detail-value">
                        {{ $unit->short_name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Code</span>
                    <span class="detail-value">
                        {{ $unit->code }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Allows Decimal Quantity</span>
                    <span class="detail-value">
                        @if($unit->allows_decimal)
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
                    <span class="detail-label">Precision</span>
                    <span class="detail-value">
                        {{ $unit->precision }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($unit->status === 'Active')
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
                        {{ $unit->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $unit->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">
                        {{ $unit->description ?: '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>

@endsection