{{-- @extends('layouts.app')

@section('title', 'Tax Class Details')

@section('content')

<h1>Tax Class Details</h1>

<a href="{{ route('tax-classes.index') }}">Back to Tax Classes</a>

<hr>

<p><strong>Name:</strong> {{ $taxClass->name }}</p>
<p><strong>Code:</strong> {{ $taxClass->code }}</p>
<p><strong>Description:</strong> {{ $taxClass->description ?? '-' }}</p>
<p><strong>Status:</strong> {{ $taxClass->status }}</p>

<hr>

<h3>Tax Rates</h3>

@forelse($taxClass->taxRates as $taxRate)

    <p>
        {{ $taxRate->name }} -
        {{ $taxRate->rate }}%
    </p>

@empty
    <p>No tax rates configured.</p>

@endforelse

<hr>

@endsection --}}








@extends('layouts.app')

@section('title', 'Tax Class Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Tax Class Details</h1>
            <p class="page-subtitle">
                View tax class information and its configured tax rates.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('tax-classes.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Tax Classes
            </a>

            @can('update', $taxClass)
                <a
                    href="{{ route('tax-classes.edit', $taxClass) }}"
                    class="btn btn-primary"
                >
                    Edit Tax Class
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
                <h2 class="card-title">Tax Class Information</h2>
                <p class="card-subtitle">
                    Basic information about this tax classification.
                </p>
            </div>

            <div>
                @if($taxClass->status === 'Active')
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
                        {{ $taxClass->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">
                        {{ $taxClass->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Code</span>
                    <span class="detail-value">
                        {{ $taxClass->code }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($taxClass->status === 'Active')
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
                        {{ $taxClass->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $taxClass->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">
                        {{ $taxClass->description ?: '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    <div class="card" style="margin-top: 24px;">

        <div class="card-header">
            <div>
                <h2 class="card-title">Tax Rates</h2>
                <p class="card-subtitle">
                    Tax rates currently configured for this tax class.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $taxClass->taxRates->count() }}
                </span>
            </div>
        </div>

        <div class="card-body">

            @if($taxClass->taxRates->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Rate</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($taxClass->taxRates as $taxRate)

                                <tr>

                                    <td>
                                        {{ $taxRate->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $taxRate->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ number_format((float) $taxRate->rate, 2) }}%
                                    </td>

                                    <td>
                                        @if($taxRate->status === 'Active')
                                            <span class="badge badge-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                Inactive
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

                    <h3>No Tax Rates Configured</h3>

                    <p>
                        There are currently no tax rates associated with this tax class.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection