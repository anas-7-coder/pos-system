{{-- @extends('layouts.app')

@section('title', 'Register Details')

@section('content')

<h1>Register Details</h1>

<a href="{{ route('registers.index') }}">Back to Registers</a>

<hr>

<p><strong>Branch:</strong> {{ $register->branch->name }}</p>
<p><strong>Name:</strong> {{ $register->name }}</p>
<p><strong>Code:</strong> {{ $register->code }}</p>
<p><strong>Device Identifier:</strong> {{ $register->device_identifier ?? '-' }}</p>
<p><strong>Status:</strong> {{ $register->status }}</p>

<hr>

@endsection --}}








@extends('layouts.app')

@section('title', 'Register Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Register Details</h1>
            <p class="page-subtitle">
                View information about this POS register.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('registers.index') }}" class="btn btn-secondary">
                ← Back to Registers
            </a>

            @can('update', $register)
                <a
                    href="{{ route('registers.edit', $register) }}"
                    class="btn btn-primary"
                >
                    Edit Register
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
                <h2 class="card-title">Register Information</h2>
                <p class="card-subtitle">
                    Details and current status of the register.
                </p>
            </div>

            <div>
                @if($register->status === 'Active')
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
                        {{ $register->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Branch</span>
                    <span class="detail-value">
                        {{ $register->branch->name ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Register Name</span>
                    <span class="detail-value">
                        {{ $register->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Register Code</span>
                    <span class="detail-value">
                        {{ $register->code }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Device Identifier</span>
                    <span class="detail-value">
                        {{ $register->device_identifier ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($register->status === 'Active')
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
                        {{ $register->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $register->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>

@endsection