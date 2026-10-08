{{-- @extends('layouts.app')

@section('title', 'Company Details')

@section('content')

<h1>Company Details</h1>

<a href="{{ route('companies.index') }}">
    Back to Companies
</a>

<hr>

<p><strong>Name:</strong> {{ $company->name }}</p>
<p><strong>Code:</strong> {{ $company->code }}</p>
<p><strong>Email:</strong> {{ $company->email ?? '-' }}</p>
<p><strong>Phone:</strong> {{ $company->phone ?? '-' }}</p>
<p><strong>Address:</strong> {{ $company->address ?? '-' }}</p>
<p><strong>Tax Number:</strong> {{ $company->tax_number ?? '-' }}</p>
<p><strong>Currency:</strong> {{ $company->currency }}</p>
<p><strong>Timezone:</strong> {{ $company->timezone }}</p>
<p><strong>Status:</strong> {{ $company->status }}</p>

<hr>

@endsection --}}







@extends('layouts.app')

@section('title', 'Company Details')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Company Details
        </h1>

        <p class="page-subtitle">
            View company information and configuration.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('companies.index') }}"
            class="btn btn-secondary"
        >
            Back to Companies
        </a>

        @can('update', $company)

            <a
                href="{{ route('companies.edit', $company) }}"
                class="btn btn-primary"
            >
                Edit Company
            </a>

        @endcan

    </div>

</div>


<div class="card">

    <div class="card-header">

        <h2 class="card-title">
            Company Information
        </h2>

        @if($company->status === 'Active')

            <span class="badge badge-success">
                Active
            </span>

        @else

            <span class="badge badge-danger">
                Inactive
            </span>

        @endif

    </div>


    <div class="card-body">

        <div class="detail-grid">

            {{-- Name --}}
            <div class="detail-item">

                <span class="detail-label">
                    Company Name
                </span>

                <span class="detail-value">
                    {{ $company->name }}
                </span>

            </div>


            {{-- Code --}}
            <div class="detail-item">

                <span class="detail-label">
                    Company Code
                </span>

                <span class="detail-value">
                    {{ $company->code }}
                </span>

            </div>


            {{-- Email --}}
            <div class="detail-item">

                <span class="detail-label">
                    Email
                </span>

                <span class="detail-value">
                    {{ $company->email ?? '-' }}
                </span>

            </div>


            {{-- Phone --}}
            <div class="detail-item">

                <span class="detail-label">
                    Phone
                </span>

                <span class="detail-value">
                    {{ $company->phone ?? '-' }}
                </span>

            </div>


            {{-- Tax Number --}}
            <div class="detail-item">

                <span class="detail-label">
                    Tax Number
                </span>

                <span class="detail-value">
                    {{ $company->tax_number ?? '-' }}
                </span>

            </div>


            {{-- Currency --}}
            <div class="detail-item">

                <span class="detail-label">
                    Currency
                </span>

                <span class="detail-value">
                    {{ $company->currency }}
                </span>

            </div>


            {{-- Timezone --}}
            <div class="detail-item">

                <span class="detail-label">
                    Timezone
                </span>

                <span class="detail-value">
                    {{ $company->timezone }}
                </span>

            </div>


            {{-- Status --}}
            <div class="detail-item">

                <span class="detail-label">
                    Status
                </span>

                <span class="detail-value">

                    @if($company->status === 'Active')

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
                    {{ $company->address ?? '-' }}
                </span>

            </div>

        </div>

    </div>

</div>

@endsection