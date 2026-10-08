{{-- @extends('layouts.app')

@section('title', 'Tax Rates')

@section('content')

<h1>Tax Rates</h1>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

<hr>

<table border="1" cellpadding="8">

    <thead>

        <tr>
            <th>ID</th>
            <th>Tax Class</th>
            <th>Name</th>
            <th>Rate</th>
            <th>Effective From</th>
            <th>Effective To</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

        @forelse($taxRates as $taxRate)

            <tr>
                <td>{{ $taxRate->id }}</td>
                <td>{{ $taxRate->taxClass->name }}</td>
                <td>{{ $taxRate->name }}</td>
                <td>{{ $taxRate->rate }}%</td>
                <td>{{ $taxRate->effective_from->format('Y-m-d') }}</td>
                <td>{{ $taxRate->effective_to?->format('Y-m-d') ?? '-' }}</td>
                <td>{{ $taxRate->status }}</td>
                <td>
                    @can('view', $taxRate)
                        <a href="{{ route('tax-rates.show', $taxRate) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $taxRate)
                        <a href="{{ route('tax-rates.edit', $taxRate) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $taxRate)
                        <form action="{{ route('tax-rates.destroy', $taxRate) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this tax-rate?')">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="8">
                    No tax rates found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $taxRates->links() }}

@endsection --}}






@extends('layouts.app')

@section('title', 'Tax Rates')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Tax Rates</h1>
            <p class="page-subtitle">
                Manage tax rates, tax classes, and their effective periods.
            </p>
        </div>

        <div class="page-actions">
            @can('create', App\Models\TaxRate::class)
                <a
                    href="{{ route('tax-rates.create') }}"
                    class="btn btn-primary"
                >
                    + Create Tax Rate
                </a>
            @endcan

            <a
                href="{{ route('tax-rates.trash') }}"
                class="btn btn-secondary"
            >
                Trash
            </a>
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
                <h2 class="card-title">All Tax Rates</h2>
                <p class="card-subtitle">
                    View and manage configured tax rates and validity periods.
                </p>
            </div>
        </div>

        <div class="card-body">

            @if($taxRates->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tax Class</th>
                                <th>Name</th>
                                <th>Rate</th>
                                <th>Effective From</th>
                                <th>Effective To</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($taxRates as $taxRate)

                                <tr>

                                    <td>
                                        {{ $taxRate->id }}
                                    </td>

                                    <td>
                                        {{ $taxRate->taxClass->name ?? '-' }}
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
                                        {{ $taxRate->effective_from?->format('Y-m-d') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $taxRate->effective_to?->format('Y-m-d') ?? '-' }}
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

                                    <td>

                                        <div class="table-actions">

                                            @can('view', $taxRate)
                                                <a
                                                    href="{{ route('tax-rates.show', $taxRate) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $taxRate)
                                                <a
                                                    href="{{ route('tax-rates.edit', $taxRate) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $taxRate)
                                                <form
                                                    action="{{ route('tax-rates.destroy', $taxRate) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this tax rate?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger btn-sm"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="pagination-wrapper">
                    {{ $taxRates->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Tax Rates Found</h3>

                    <p>
                        There are currently no tax rates available.
                    </p>

                    @can('create', App\Models\TaxRate::class)
                        <a
                            href="{{ route('tax-rates.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Tax Rate
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection