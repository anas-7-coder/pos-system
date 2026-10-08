{{-- @extends('layouts.app')

@section('title', 'Category Trash')

@section('content')

<h1>Category Trash</h1>

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
            <th>Deleted At</th>
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
                <td>{{ $category->deleted_at }}</td>
                <td>
                    @can('restore', $category)
                        <form action="{{ route('categories.restore', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $category)
                        <form action="{{ route('categories.force-delete', $category->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this category?')">
                                Force Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="7">
                    Trash is empty.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $categories->links() }}

@endsection --}}





@extends('layouts.app')

@section('title', 'Category Trash')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Category Trash</h1>
            <p class="page-subtitle">
                Manage deleted categories and restore or permanently remove them.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('categories.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Categories
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
                <h2 class="card-title">Deleted Categories</h2>
                <p class="card-subtitle">
                    Restore deleted categories or permanently remove them.
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
                                <th>Deleted At</th>
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
                                        {{ $category->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('restore', $category)
                                                <form
                                                    action="{{ route('categories.restore', $category->id) }}"
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

                                            @can('forceDelete', $category)
                                                <form
                                                    action="{{ route('categories.force-delete', $category->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete this category? This action cannot be undone.')"
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
                    {{ $categories->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted categories to display.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection