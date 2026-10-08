{{-- @extends('layouts.app')

@section('title', 'Warehouse Details')

@section('content')

<h1>Warehouse Details</h1>

<a href="{{ route('warehouses.index') }}">Back to Warehouses</a>

<hr>

<p><strong>Branch:</strong> {{ $warehouse->branch->name }}</p>
<p><strong>Name:</strong> {{ $warehouse->name }}</p>
<p><strong>Code:</strong> {{ $warehouse->code }}</p>
<p><strong>Address:</strong> {{ $warehouse->address ?? '-' }}</p>
<p><strong>Default:</strong> {{ $warehouse->is_default ? 'Yes' : 'No' }}</p>
<p><strong>Status:</strong> {{ $warehouse->status }}</p>

<hr>

@endsection --}}







@extends('layouts.app')

@section('title', 'Warehouse Details')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Warehouse Details
        </h1>

        <p class="page-subtitle">
            View warehouse information and configuration.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('warehouses.index') }}"
            class="btn btn-secondary"
        >
            Back to Warehouses
        </a>

        @can('update', $warehouse)

            <a
                href="{{ route('warehouses.edit', $warehouse) }}"
                class="btn btn-primary"
            >
                Edit Warehouse
            </a>

        @endcan

    </div>

</div>


<div class="card">

    <div class="card-header">

        <h2 class="card-title">
            Warehouse Information
        </h2>

        <div class="action-group">

            @if($warehouse->is_default)

                <span class="badge badge-primary">
                    Default Warehouse
                </span>

            @endif

            @if($warehouse->status === 'Active')

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

            {{-- Branch --}}
            <div class="detail-item">

                <span class="detail-label">
                    Branch
                </span>

                <span class="detail-value">
                    {{ $warehouse->branch->name }}
                </span>

            </div>


            {{-- Warehouse Name --}}
            <div class="detail-item">

                <span class="detail-label">
                    Warehouse Name
                </span>

                <span class="detail-value">
                    {{ $warehouse->name }}
                </span>

            </div>


            {{-- Code --}}
            <div class="detail-item">

                <span class="detail-label">
                    Warehouse Code
                </span>

                <span class="detail-value">
                    {{ $warehouse->code }}
                </span>

            </div>


            {{-- Default --}}
            <div class="detail-item">

                <span class="detail-label">
                    Default Warehouse
                </span>

                <span class="detail-value">

                    @if($warehouse->is_default)

                        <span class="badge badge-primary">
                            Yes
                        </span>

                    @else

                        <span class="badge badge-neutral">
                            No
                        </span>

                    @endif

                </span>

            </div>


            {{-- Status --}}
            <div class="detail-item">

                <span class="detail-label">
                    Status
                </span>

                <span class="detail-value">

                    @if($warehouse->status === 'Active')

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


            {{-- Address --}}
            <div
                class="detail-item"
                style="grid-column: 1 / -1;"
            >

                <span class="detail-label">
                    Address
                </span>

                <span class="detail-value">
                    {{ $warehouse->address ?? '-' }}
                </span>

            </div>

        </div>

    </div>

</div>

@endsection