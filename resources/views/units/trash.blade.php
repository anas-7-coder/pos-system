{{-- @extends('layouts.app')

@section('title', 'Unit Trash')

@section('content')

<h1>Unit Trash</h1>

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
            <th>Deleted At</th>
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
                <td>{{ $unit->deleted_at }}</td>
                <td>
                    @can('restore', $unit)
                        <form action="{{ route('units.restore', $unit->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $unit)
                        <form action="{{ route('units.force-delete', $unit->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this unit?')">
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

{{ $units->links() }}

@endsection --}}






@extends('layouts.app')

@section('title', 'Unit Trash')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Unit Trash</h1>
            <p class="page-subtitle">
                Manage deleted units and restore or permanently remove them.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('units.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Units
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
                <h2 class="card-title">Deleted Units</h2>
                <p class="card-subtitle">
                    Restore deleted units or permanently remove them.
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
                                <th>Deleted At</th>
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
                                        {{ $unit->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('restore', $unit)
                                                <form
                                                    action="{{ route('units.restore', $unit->id) }}"
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

                                            @can('forceDelete', $unit)
                                                <form
                                                    action="{{ route('units.force-delete', $unit->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete this unit? This action cannot be undone.')"
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
                    {{ $units->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted units to display.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection