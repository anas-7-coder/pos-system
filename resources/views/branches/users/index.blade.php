{{-- @extends('layouts.app')

@section('title', 'Branch Users')

@section('content')

<h1>
    Users — {{ $branch->name }}
</h1>

<a href="{{ route('branches.show', $branch) }}">Back to Branch Details</a>

<hr>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

@can('assign', App\Models\User::class)
    <a href="{{ route('branches.users.create', $branch) }}"> Assign User</a>
@endcan

<hr>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Employee Code</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>

        @forelse($users as $user)

            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->employee->employee_code ?? '-' }}</td>
                <td>{{ $user->status }}</td>
                <td>
                    @can('remove', App\Models\User::class)
                        <form action="{{ route('branches.users.destroy', [$branch, $user]) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit">
                                Remove
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="6">
                    No users assigned to this branch.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $users->links() }}

@endsection --}}










@extends('layouts.app')

@section('title', 'Branch Users')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Branch Users
        </h1>

        <p class="page-subtitle">
            Manage users assigned to the {{ $branch->name }} branch.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('branches.show', $branch) }}"
            class="btn btn-secondary"
        >
            Back to Branch
        </a>

        @can('assign', App\Models\User::class)

            <a
                href="{{ route('branches.users.create', $branch) }}"
                class="btn btn-primary"
            >
                + Assign User
            </a>

        @endcan

    </div>

</div>


@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


<div class="card">

    <div class="card-header">

        <h2 class="card-title">
            Assigned Users
        </h2>

        <span class="badge badge-info">
            {{ $users->total() }} Users
        </span>

    </div>


    <div class="table-wrapper">

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Employee Code</th>

                    <th>Status</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

                @forelse($users as $user)

                    <tr>

                        <td>
                            {{ $user->id }}
                        </td>

                        <td>

                            <strong>
                                {{ $user->name }}
                            </strong>

                        </td>

                        <td>
                            {{ $user->email }}
                        </td>

                        <td>
                            {{ $user->employee->employee_code ?? '-' }}
                        </td>

                        <td>

                            @if($user->status === 'active' || $user->status === 'Active')

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

                            @can('remove', App\Models\User::class)

                                <form
                                    action="{{ route(
                                        'branches.users.destroy',
                                        [$branch, $user]
                                    ) }}"
                                    method="POST"
                                    style="display:inline;"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm(
                                            'Are you sure you want to remove this user from the branch?'
                                        )"
                                    >
                                        Remove
                                    </button>

                                </form>

                            @endcan

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="table-empty"
                        >
                            No users are currently assigned to this branch.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


@if($users->hasPages())

    <div class="pagination">

        {{ $users->links() }}

    </div>

@endif

@endsection