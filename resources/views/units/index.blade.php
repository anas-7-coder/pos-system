{{-- @extends('layouts.app')

@section('title', 'Units')

@section('content')

<h1>Units</h1>

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
            <th>Name</th>
            <th>Short Name</th>
            <th>Code</th>
            <th>Decimal</th>
            <th>Precision</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

        @forelse($units as $unit)

            <tr>
                <td>{{ $unit->id }}</td>
                <td>{{ $unit->name }}</td>
                <td>{{ $unit->short_name }}</td>
                <td>{{ $unit->code }}</td>
                <td>{{ $unit->allows_decimal ? 'Yes' : 'No' }}</td>
                <td>{{ $unit->precision }}</td>
                <td>{{ $unit->status }}</td>
                <td>
                    @can('view', $unit)
                        <a href="{{ route('units.show', $unit) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $unit)
                        <a href="{{ route('units.edit', $unit) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $unit)
                        <form action="{{ route('units.destroy', $unit) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this unit?')">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="8">
                    No units found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $units->links() }}

@endsection --}}






@extends('layouts.app')

@section('title', 'Units')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Units</h1>
            <p class="page-subtitle">
                Manage measurement units used for products and inventory quantities.
            </p>
        </div>

        <div class="page-actions">
            @can('create', App\Models\Unit::class)
                <a
                    href="{{ route('units.create') }}"
                    class="btn btn-primary"
                >
                    + Create Unit
                </a>
            @endcan

            <a
                href="{{ route('units.trash') }}"
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
                <h2 class="card-title">All Units</h2>
                <p class="card-subtitle">
                    View and manage product measurement units.
                </p>
            </div>
        </div>

        <div class="card-body">

            @if($units->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Short Name</th>
                                <th>Code</th>
                                <th>Decimal</th>
                                <th>Precision</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($units as $unit)

                                <tr>

                                    <td>
                                        {{ $unit->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $unit->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $unit->short_name }}
                                    </td>

                                    <td>
                                        {{ $unit->code }}
                                    </td>

                                    <td>
                                        @if($unit->allows_decimal)
                                            <span class="badge badge-success">
                                                Yes
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                No
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $unit->precision }}
                                    </td>

                                    <td>
                                        @if($unit->status === 'Active')
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

                                            @can('view', $unit)
                                                <a
                                                    href="{{ route('units.show', $unit) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $unit)
                                                <a
                                                    href="{{ route('units.edit', $unit) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $unit)
                                                <form
                                                    action="{{ route('units.destroy', $unit) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this unit?')"
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
                    {{ $units->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Units Found</h3>

                    <p>
                        There are currently no measurement units available.
                    </p>

                    @can('create', App\Models\Unit::class)
                        <a
                            href="{{ route('units.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Unit
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection