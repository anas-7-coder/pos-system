{{-- @extends('layouts.app')

@section('title', 'Branches')

@section('content')

<h1>Branches</h1>

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
            <th>Company</th>
            <th>Name</th>
            <th>Code</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($branches as $branch)

            <tr>
                <td>{{ $branch->id }}</td>
                <td>{{ $branch->company->name }}</td>
                <td>{{ $branch->name }}</td>
                <td>{{ $branch->code }}</td>
                <td>{{ $branch->email }}</td>
                <td>{{ $branch->phone ?? '-' }}</td>
                <td>{{ $branch->status }}</td>
                <td>
                    @can('view', $branch)
                        <a href="{{ route('branches.show', $branch) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $branch)
                        <a href="{{ route('branches.edit', $branch) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $branch)
                        <form action="{{ route('branches.destroy', $branch) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this company?')">
                                Delete
                            </button>
                        </form>
                    @endcan

                </td>
            </tr>

        @empty
            <tr>
                <td colspan="8">
                    No branches found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $branches->links() }}

@endsection --}}












@extends('layouts.app')

@section('title', 'Branches')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Branches
        </h1>

        <p class="page-subtitle">
            Manage company branches and their operational details.
        </p>
    </div>

    <div class="page-actions">

        @can('create', App\Models\Branch::class)

            <a
                href="{{ route('branches.create') }}"
                class="btn btn-primary"
            >
                + Add Branch
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
                <th>Company</th>
                <th>Branch</th>
                <th>Code</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Actions</th>

            </tr>

        </thead>


        <tbody>

            @forelse($branches as $branch)

                <tr>

                    <td>
                        {{ $branch->id }}
                    </td>

                    <td>
                        <strong>
                            {{ $branch->company->name }}
                        </strong>
                    </td>

                    <td>
                        {{ $branch->name }}
                    </td>

                    <td>
                        {{ $branch->code }}
                    </td>

                    <td>
                        {{ $branch->email ?? '-' }}
                    </td>

                    <td>
                        {{ $branch->phone ?? '-' }}
                    </td>

                    <td>

                        @if($branch->status === 'Active')

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

                            @can('view', $branch)

                                <a
                                    href="{{ route(
                                        'branches.show',
                                        $branch
                                    ) }}"
                                    class="btn btn-secondary btn-sm"
                                >
                                    View
                                </a>

                            @endcan


                            @can('update', $branch)

                                <a
                                    href="{{ route(
                                        'branches.edit',
                                        $branch
                                    ) }}"
                                    class="btn btn-outline-primary btn-sm"
                                >
                                    Edit
                                </a>

                            @endcan


                            @can('delete', $branch)

                                <form
                                    action="{{ route(
                                        'branches.destroy',
                                        $branch
                                    ) }}"
                                    method="POST"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm(
                                            'Are you sure you want to delete this branch?'
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
                        colspan="8"
                        class="table-empty"
                    >
                        No branches found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>


@if($branches->hasPages())

    <div class="pagination">

        {{ $branches->links() }}

    </div>

@endif

@endsection