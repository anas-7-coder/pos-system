{{-- @extends('layouts.app')

@section('title', 'Companies')

@section('content')

<h1>Companies</h1>

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
                <td>
                    @can('view', $company)
                        <a href="{{ route('companies.show', $company) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $company)
                        <a href="{{ route('companies.edit', $company) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $company)
                        <form action="{{ route('companies.destroy', $company) }}" method="POST" style="display:inline;">
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
                    No companies found.
                </td>
            </tr>

        @endforelse
    </tbody>
</table>

{{ $companies->links() }}

@endsection --}}






@extends('layouts.app')

@section('title', 'Companies')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Companies
        </h1>

        <p class="page-subtitle">
            Manage companies registered in your POS system.
        </p>
    </div>

    <div class="page-actions">

        @can('create', App\Models\Company::class)

            <a
                href="{{ route('companies.create') }}"
                class="btn btn-primary"
            >
                + Add Company
            </a>

        @endcan

    </div>

</div>


{{-- Success Message --}}
@if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>

@endif


{{-- Companies Table --}}
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

                        <div class="table-actions">

                            @can('view', $company)

                                <a
                                    href="{{ route(
                                        'companies.show',
                                        $company
                                    ) }}"
                                    class="btn btn-secondary btn-sm"
                                >
                                    View
                                </a>

                            @endcan


                            @can('update', $company)

                                <a
                                    href="{{ route(
                                        'companies.edit',
                                        $company
                                    ) }}"
                                    class="btn btn-outline-primary btn-sm"
                                >
                                    Edit
                                </a>

                            @endcan


                            @can('delete', $company)

                                <form
                                    action="{{ route(
                                        'companies.destroy',
                                        $company
                                    ) }}"
                                    method="POST"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm(
                                            'Are you sure you want to delete this company?'
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
                        No companies found.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>


{{-- Pagination --}}
@if($companies->hasPages())

    <div class="pagination">

        {{ $companies->links() }}

    </div>

@endif

@endsection