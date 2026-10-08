{{-- @extends('layouts.app')

@section('title', 'Branch Trash')

@section('content')

<h1>Branch Trash</h1>

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
            <th>Deleted At</th>
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
                <td>{{ $branch->phone }}</td>
                <td>{{ $branch->status }}</td>
                <td>{{ $branch->deleted_at }}</td>
                <td>
                    @can('restore', $branch)
                        <form action="{{ route('branches.restore', $branch->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $branch)
                        <form action="{{ route('branches.force-delete', $branch->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this branch?')">
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

{{ $branches->links() }}

@endsection --}}








@extends('layouts.app')

@section('title', 'Branch Trash')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Branch Trash
        </h1>

        <p class="page-subtitle">
            Restore deleted branches or permanently remove them.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('branches.index') }}"
            class="btn btn-secondary"
        >
            Back to Branches
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
                <th>Company</th>
                <th>Branch</th>
                <th>Code</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Deleted At</th>
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
                        {{ $branch->deleted_at }}
                    </td>

                    <td>

                        <div class="table-actions">

                            @can('restore', $branch)

                                <form
                                    action="{{ route(
                                        'branches.restore',
                                        $branch->id
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


                            @can('forceDelete', $branch)

                                <form
                                    action="{{ route(
                                        'branches.force-delete',
                                        $branch->id
                                    ) }}"
                                    method="POST"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm(
                                            'Are you sure you want to permanently delete this branch? This action cannot be undone.'
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
                        colspan="9"
                        class="table-empty"
                    >
                        Trash is empty.
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