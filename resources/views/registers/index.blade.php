{{-- @extends('layouts.app')

@section('title', 'Registers')

@section('content')

<h1>Registers</h1>

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
                <td>{{ $register->device_identifier ?? '-' }}</td>
                <td>{{ $register->status }}</td>
                <td>
                    @can('view', $register)
                        <a href="{{ route('registers.show', $register) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $register)
                        <a href="{{ route('registers.edit', $register) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $register)
                        <form action="{{ route('registers.destroy', $register) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this register?')">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="7">
                    No registers found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $registers->links() }}

@endsection --}}










@extends('layouts.app')

@section('title', 'Registers')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Registers</h1>
            <p class="page-subtitle">
                Manage POS registers across your branches.
            </p>
        </div>

        <div class="page-actions">
            @can('create', App\Models\Register::class)
                <a href="{{ route('registers.create') }}" class="btn btn-primary">
                    + Create Register
                </a>
            @endcan

            @can('viewAny', App\Models\Register::class)
                <a href="{{ route('registers.trash') }}" class="btn btn-secondary">
                    Trash
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
                <h2 class="card-title">All Registers</h2>
                <p class="card-subtitle">
                    View and manage configured POS registers.
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

                                        <div class="table-actions">

                                            @can('view', $register)
                                                <a
                                                    href="{{ route('registers.show', $register) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $register)
                                                <a
                                                    href="{{ route('registers.edit', $register) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $register)
                                                <form
                                                    action="{{ route('registers.destroy', $register) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this register?')"
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
                    {{ $registers->links() }}
                </div>

            @else

                <div class="table-empty">
                    <h3>No Registers Found</h3>
                    <p>
                        There are currently no registers configured.
                    </p>

                    @can('create', App\Models\Register::class)
                        <a
                            href="{{ route('registers.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Register
                        </a>
                    @endcan
                </div>

            @endif

        </div>

    </div>

@endsection