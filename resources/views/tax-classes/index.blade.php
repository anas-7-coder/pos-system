{{-- @extends('layouts.app')

@section('title', 'Tax Classes')

@section('content')

<h1>Tax Classes</h1>

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
            <th>Code</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

        @forelse($taxClasses as $taxClass)

            <tr>
                <td>{{ $taxClass->id }}</td>
                <td>{{ $taxClass->name }}</td>
                <td>{{ $taxClass->code }}</td>
                <td>{{ $taxClass->status }}</td>
                <td>
                    @can('view', $taxClass)
                        <a href="{{ route('tax-classes.show', $taxClass) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $taxClass)
                        <a href="{{ route('tax-classes.edit', $taxClass) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $taxClass)
                        <form action="{{ route('tax-classes.destroy', $taxClass) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this tax-class?')">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="5">
                    No tax classes found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $taxClasses->links() }}

@endsection --}}







@extends('layouts.app')

@section('title', 'Tax Classes')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Tax Classes</h1>
            <p class="page-subtitle">
                Manage tax classifications used for products and tax rates.
            </p>
        </div>

        <div class="page-actions">
            @can('create', App\Models\TaxClass::class)
                <a
                    href="{{ route('tax-classes.create') }}"
                    class="btn btn-primary"
                >
                    + Create Tax Class
                </a>
            @endcan

            <a
                href="{{ route('tax-classes.trash') }}"
                class="btn btn-secondary"
            >
                Trash
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
                <h2 class="card-title">All Tax Classes</h2>
                <p class="card-subtitle">
                    View and manage your configured tax classifications.
                </p>
            </div>
        </div>

        <div class="card-body">

            @if($taxClasses->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($taxClasses as $taxClass)

                                <tr>

                                    <td>
                                        {{ $taxClass->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $taxClass->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $taxClass->code }}
                                    </td>

                                    <td>
                                        @if($taxClass->status === 'Active')
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

                                            @can('view', $taxClass)
                                                <a
                                                    href="{{ route('tax-classes.show', $taxClass) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $taxClass)
                                                <a
                                                    href="{{ route('tax-classes.edit', $taxClass) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $taxClass)
                                                <form
                                                    action="{{ route('tax-classes.destroy', $taxClass) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this tax class?')"
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
                    {{ $taxClasses->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Tax Classes Found</h3>

                    <p>
                        There are currently no tax classes available.
                    </p>

                    @can('create', App\Models\TaxClass::class)
                        <a
                            href="{{ route('tax-classes.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Tax Class
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection