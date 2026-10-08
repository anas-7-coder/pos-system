{{-- @extends('layouts.app')

@section('title', 'Categories')

@section('content')

<h1>Categories</h1>

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
            <th>Parent</th>
            <th>Name</th>
            <th>Code</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

        @forelse($categories as $category)

            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->parent->name ?? '-' }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->code }}</td>
                <td>{{ $category->status }}</td>
                <td>
                    @can('view', $category)
                        <a href="{{ route('categories.show', $category) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $category)
                        <a href="{{ route('categories.edit', $category) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $category)
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this category?')">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="6">
                    No categories found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $categories->links() }}

@endsection --}}










@extends('layouts.app')

@section('title', 'Categories')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Categories</h1>
            <p class="page-subtitle">
                Manage product categories and their parent-child structure.
            </p>
        </div>

        <div class="page-actions">
            @can('create', App\Models\Category::class)
                <a
                    href="{{ route('categories.create') }}"
                    class="btn btn-primary"
                >
                    + Create Category
                </a>
            @endcan

            <a
                href="{{ route('categories.trash') }}"
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
                <h2 class="card-title">All Categories</h2>
                <p class="card-subtitle">
                    View and manage your product category hierarchy.
                </p>
            </div>
        </div>

        <div class="card-body">

            @if($categories->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Parent</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($categories as $category)

                                <tr>

                                    <td>
                                        {{ $category->id }}
                                    </td>

                                    <td>
                                        {{ $category->parent->name ?? '-' }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $category->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $category->code }}
                                    </td>

                                    <td>
                                        @if($category->status === 'Active')
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

                                            @can('view', $category)
                                                <a
                                                    href="{{ route('categories.show', $category) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $category)
                                                <a
                                                    href="{{ route('categories.edit', $category) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $category)
                                                <form
                                                    action="{{ route('categories.destroy', $category) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this category?')"
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
                    {{ $categories->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Categories Found</h3>

                    <p>
                        There are currently no categories available.
                    </p>

                    @can('create', App\Models\Category::class)
                        <a
                            href="{{ route('categories.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Category
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection