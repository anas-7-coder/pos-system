{{-- @extends('layouts.app')

@section('title', 'Brands')

@section('content')

<h1>Brands</h1>

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

        @forelse($brands as $brand)

            <tr>
                <td>{{ $brand->id }}</td>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->code }}</td>
                <td>{{ $brand->status }}</td>
                <td>
                    @can('view', $brand)
                        <a href="{{ route('brands.show', $brand) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $brand)
                        <a href="{{ route('brands.edit', $brand) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $brand)
                        <form action="{{ route('brands.destroy', $brand) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this brand?')">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="5">
                    No brands found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $brands->links() }}

@endsection --}}







@extends('layouts.app')

@section('title', 'Brands')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Brands</h1>
            <p class="page-subtitle">
                Manage product brands used throughout your catalog.
            </p>
        </div>

        <div class="page-actions">
            @can('create', App\Models\Brand::class)
                <a
                    href="{{ route('brands.create') }}"
                    class="btn btn-primary"
                >
                    + Create Brand
                </a>
            @endcan

            <a
                href="{{ route('brands.trash') }}"
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
                <h2 class="card-title">All Brands</h2>
                <p class="card-subtitle">
                    View and manage your product brands.
                </p>
            </div>
        </div>

        <div class="card-body">

            @if($brands->count())

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

                            @foreach($brands as $brand)

                                <tr>

                                    <td>
                                        {{ $brand->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $brand->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $brand->code }}
                                    </td>

                                    <td>
                                        @if($brand->status === 'Active')
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

                                            @can('view', $brand)
                                                <a
                                                    href="{{ route('brands.show', $brand) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $brand)
                                                <a
                                                    href="{{ route('brands.edit', $brand) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $brand)
                                                <form
                                                    action="{{ route('brands.destroy', $brand) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this brand?')"
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
                    {{ $brands->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Brands Found</h3>

                    <p>
                        There are currently no brands available.
                    </p>

                    @can('create', App\Models\Brand::class)
                        <a
                            href="{{ route('brands.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Brand
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection