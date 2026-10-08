{{-- @extends('layouts.app')

@section('title', 'Warehouses')

@section('content')

<h1>Warehouses</h1>

@if(session('success'))
    <p style="color: green;">
        {{ session('success') }}
    </p>
@endif

<hr>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>ID</th>
            <th>Branch</th>
            <th>Name</th>
            <th>Code</th>
            <th>Default</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($warehouses as $warehouse)

            <tr>
                <td>{{ $warehouse->id }}</td>
                <td>{{ $warehouse->branch->name }}</td>
                <td>{{ $warehouse->name }}</td>
                <td>{{ $warehouse->code }}</td>
                <td>{{ $warehouse->is_default ? 'Yes' : 'No' }}</td>
                <td>{{ $warehouse->status }}</td>
                <td>
                    @can('view', $warehouse)
                        <a href="{{ route('warehouses.show', $warehouse) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $warehouse)
                        <a href="{{ route('warehouses.edit', $warehouse) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $warehouse)
                        <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this warehouse?')">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="7">
                    No warehouses found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $warehouses->links() }}

@endsection --}}










@extends('layouts.app')

@section('title', 'Warehouses')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Warehouses
        </h1>

        <p class="page-subtitle">
            Manage warehouses and their branch assignments.
        </p>
    </div>

    <div class="page-actions">

        @can('create', App\Models\Warehouse::class)

            <a
                href="{{ route('warehouses.create') }}"
                class="btn btn-primary"
            >
                + Add Warehouse
            </a>

        @endcan

    </div>

</div>


@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


<div class="table-wrapper">

    <table>

        <thead>

            <tr>
                <th>ID</th>
                <th>Branch</th>
                <th>Warehouse</th>
                <th>Code</th>
                <th>Default</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

        </thead>


        <tbody>

            @forelse($warehouses as $warehouse)

                <tr>

                    <td>
                        {{ $warehouse->id }}
                    </td>

                    <td>
                        <strong>
                            {{ $warehouse->branch->name }}
                        </strong>
                    </td>

                    <td>
                        {{ $warehouse->name }}
                    </td>

                    <td>
                        {{ $warehouse->code }}
                    </td>

                    <td>

                        @if($warehouse->is_default)

                            <span class="badge badge-primary">
                                Default
                            </span>

                        @else

                            <span class="badge badge-neutral">
                                No
                            </span>

                        @endif

                    </td>


                    <td>

                        @if($warehouse->status === 'Active')

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

                            @can('view', $warehouse)

                                <a
                                    href="{{ route(
                                        'warehouses.show',
                                        $warehouse
                                    ) }}"
                                    class="btn btn-secondary btn-sm"
                                >
                                    View
                                </a>

                            @endcan


                            @can('update', $warehouse)

                                <a
                                    href="{{ route(
                                        'warehouses.edit',
                                        $warehouse
                                    ) }}"
                                    class="btn btn-outline-primary btn-sm"
                                >
                                    Edit
                                </a>

                            @endcan


                            @can('delete', $warehouse)

                                <form
                                    action="{{ route(
                                        'warehouses.destroy',
                                        $warehouse
                                    ) }}"
                                    method="POST"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm(
                                            'Are you sure you want to delete this warehouse?'
                                        )"
                                    >
                                        Delete
                                    </button>

                                </form>

                            @endcan

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="7"
                        class="table-empty"
                    >
                        No warehouses found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>


@if($warehouses->hasPages())

    <div class="pagination">

        {{ $warehouses->links() }}

    </div>

@endif

@endsection