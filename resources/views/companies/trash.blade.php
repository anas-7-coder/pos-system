{{-- @extends('layouts.app')

@section('title', 'Company Trash')

@section('content')

<h1>Company Trash</h1>

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
            <th>Name</th>
            <th>Code</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Currency</th>
            <th>Status</th>
            <th>Deleted At</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($companies as $company)

            <tr>
                <td>{{ $company->id }}</td>
                <td>{{ $company->name }}</td>
                <td>{{ $company->code }}</td>
                <td>{{ $company->email }}</td>
                <td>{{ $company->phone }}</td>
                <td>{{ $company->currency }}</td>
                <td>{{ $company->status }}</td>
                <td>{{ $company->deleted_at }}</td>
                <td>
                    @can('restore', $company)
                        <form action="{{ route('companies.restore', $company->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $company)
                        <form action="{{ route('companies.force-delete', $company->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this company?')">
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

{{ $companies->links() }}

@endsection --}}








@extends('layouts.app')

@section('title', 'Company Trash')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Company Trash
        </h1>

        <p class="page-subtitle">
            Restore deleted companies or permanently remove them.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('companies.index') }}"
            class="btn btn-secondary"
        >
            Back to Companies
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
                <th>Name</th>
                <th>Code</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Currency</th>
                <th>Status</th>
                <th>Deleted At</th>
                <th>Actions</th>

            </tr>

        </thead>


        <tbody>

            @forelse($companies as $company)

                <tr>

                    <td>
                        {{ $company->id }}
                    </td>

                    <td>
                        <strong>
                            {{ $company->name }}
                        </strong>
                    </td>

                    <td>
                        {{ $company->code }}
                    </td>

                    <td>
                        {{ $company->email ?? '-' }}
                    </td>

                    <td>
                        {{ $company->phone ?? '-' }}
                    </td>

                    <td>
                        {{ $company->currency }}
                    </td>

                    <td>

                        @if($company->status === 'Active')

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
                        {{ $company->deleted_at }}
                    </td>

                    <td>

                        <div class="table-actions">

                            @can('restore', $company)

                                <form
                                    action="{{ route(
                                        'companies.restore',
                                        $company->id
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


                            @can('forceDelete', $company)

                                <form
                                    action="{{ route(
                                        'companies.force-delete',
                                        $company->id
                                    ) }}"
                                    method="POST"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm(
                                            'Are you sure you want to permanently delete this company? This action cannot be undone.'
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


@if($companies->hasPages())

    <div class="pagination">
        {{ $companies->links() }}
    </div>

@endif

@endsection