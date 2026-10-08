{{-- @extends('layouts.app')

@section('title', 'Register Trash')

@section('content')

<h1>Register Trash</h1>

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
            <th>Branch</th>
            <th>Name</th>
            <th>Code</th>
            <th>Device</th>
            <th>Status</th>
            <th>Deleted At</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($registers as $register)

            <tr>
                <td>{{ $register->id }}</td>
                <td>{{ $register->branch->name }}</td>
                <td>{{ $register->name }}</td>
                <td>{{ $register->code }}</td>
                <td>{{ $register->device_identifier }}</td>
                <td>{{ $register->status }}</td>
                <td>{{ $register->deleted_at }}</td>
                <td>
                    @can('restore', $register)
                        <form action="{{ route('registers.restore', $register->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $register)
                        <form action="{{ route('registers.force-delete', $register->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this register?')">
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

{{ $registers->links() }}

@endsection --}}





@extends('layouts.app')

@section('title', 'Register Trash')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Register Trash</h1>
            <p class="page-subtitle">
                Manage deleted registers and restore or permanently remove them.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('registers.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Registers
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
                <h2 class="card-title">Deleted Registers</h2>
                <p class="card-subtitle">
                    Restore registers or permanently delete them.
                </p>
            </div>
        </div>

        <div class="card-body">

            @if($registers->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Branch</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Device</th>
                                <th>Status</th>
                                <th>Deleted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($registers as $register)

                                <tr>

                                    <td>
                                        {{ $register->id }}
                                    </td>

                                    <td>
                                        {{ $register->branch->name ?? '-' }}
                                    </td>

                                    <td>
                                        <strong>{{ $register->name }}</strong>
                                    </td>

                                    <td>
                                        {{ $register->code }}
                                    </td>

                                    <td>
                                        {{ $register->device_identifier ?? '-' }}
                                    </td>

                                    <td>
                                        @if($register->status === 'Active')
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
                                        {{ $register->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('restore', $register)
                                                <form
                                                    action="{{ route('registers.restore', $register->id) }}"
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

                                            @can('forceDelete', $register)
                                                <form
                                                    action="{{ route('registers.force-delete', $register->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete this register? This action cannot be undone.')"
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
                    {{ $registers->links() }}
                </div>

            @else

                <div class="table-empty">
                    <h3>Trash Is Empty</h3>
                    <p>
                        There are no deleted registers to display.
                    </p>
                </div>

            @endif

        </div>

    </div>

@endsection