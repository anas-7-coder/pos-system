{{-- @extends('layouts.app')

@section('title', 'Warehouse Trash')

@section('content')

<h1>Warehouse Trash</h1>

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
            <th>Deleted At</th>
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
                <td>{{ $warehouse->deleted_at }}</td>
                <td>
                    @can('restore', $warehouse)
                        <form action="{{ route('warehouses.restore', $warehouse->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $warehouse)
                        <form action="{{ route('warehouses.force-delete', $warehouse->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this warehouse?')">
                                Force Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="8">
                    Trash is empty.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $warehouses->links() }}

@endsection --}}








@extends('layouts.app')

@section('title', 'Warehouse Trash')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Warehouse Trash
        </h1>

        <p class="page-subtitle">
            Restore deleted warehouses or permanently remove them.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('warehouses.index') }}"
            class="btn btn-secondary"
        >
            Back to Warehouses
        </a>

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
                <th>Deleted At</th>
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
                        {{ $warehouse->deleted_at }}
                    </td>

                    <td>

                        <div class="table-actions">

                            @can('restore', $warehouse)

                                <form
                                    action="{{ route(
                                        'warehouses.restore',
                                        $warehouse->id
                                    ) }}"
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


                            @can('forceDelete', $warehouse)

                                <form
                                    action="{{ route(
                                        'warehouses.force-delete',
                                        $warehouse->id
                                    ) }}"
                                    method="POST"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm(
                                            'Are you sure you want to permanently delete this warehouse? This action cannot be undone.'
                                        )"
                                    >
                                        Force Delete
                                    </button>

                                </form>

                            @endcan

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="8"
                        class="table-empty"
                    >
                        Trash is empty.
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