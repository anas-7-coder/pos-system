{{-- @extends('layouts.app')

@section('title', 'Tax Rate Trash')

@section('content')

<h1>Tax Rate Trash</h1>

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
            <th>Deleted At</th>
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
                <td>{{ $taxRate->deleted_at }}</td>
                <td>
                    @can('restore', $taxRate)
                        <form action="{{ route('tax-rates.restore', $taxRate->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $taxRate)
                        <form action="{{ route('tax-rates.force-delete', $taxRate->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this tax-rate?')">
                                Force Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="9">
                    Trash is empty.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $taxRates->links() }}

@endsection --}}






@extends('layouts.app')

@section('title', 'Tax Rate Trash')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Tax Rate Trash</h1>
            <p class="page-subtitle">
                Manage deleted tax rates and restore or permanently remove them.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('tax-rates.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Tax Rates
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
                <h2 class="card-title">Deleted Tax Rates</h2>
                <p class="card-subtitle">
                    Restore deleted tax rates or permanently remove them.
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
                                <th>Deleted At</th>
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
                                        {{ $taxRate->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('restore', $taxRate)
                                                <form
                                                    action="{{ route('tax-rates.restore', $taxRate->id) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-success btn-sm"
                                                    >
                                                        Restore
                                                    </button>
                                                </form>
                                            @endcan

                                            @can('forceDelete', $taxRate)
                                                <form
                                                    action="{{ route('tax-rates.force-delete', $taxRate->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete this tax rate? This action cannot be undone.')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger btn-sm"
                                                    >
                                                        Force Delete
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

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted tax rates to display.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection