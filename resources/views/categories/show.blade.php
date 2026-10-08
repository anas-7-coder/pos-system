{{-- @extends('layouts.app')

@section('title', 'Category Details')

@section('content')

<h1>Category Details</h1>

<a href="{{ route('categories.index') }}">Back to Categories</a>

<hr>

<p><strong>Name:</strong> {{ $category->name }}</p>
<p><strong>Code:</strong> {{ $category->code }}</p>
<p><strong>Parent:</strong> {{ $category->parent->name ?? '-' }}</p>
<p><strong>Description:</strong> {{ $category->description ?? '-' }}</p>
<p><strong>Status:</strong> {{ $category->status }}</p>

<hr>

<h3>Child Categories</h3>

@forelse($category->children as $child)
    <p>{{ $child->name }}</p>

@empty
    <p>No child categories.</p>

@endforelse

<hr>

@endsection --}}






@extends('layouts.app')

@section('title', 'Category Details')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Category Details</h1>
            <p class="page-subtitle">
                View category information and its child categories.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('categories.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Categories
            </a>

            @can('update', $category)
                <a
                    href="{{ route('categories.edit', $category) }}"
                    class="btn btn-primary"
                >
                    Edit Category
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
                <h2 class="card-title">Category Information</h2>
                <p class="card-subtitle">
                    Basic information about this category.
                </p>
            </div>

            <div>
                @if($category->status === 'Active')
                    <span class="badge badge-success">
                        Active
                    </span>
                @else
                    <span class="badge badge-danger">
                        Inactive
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <span class="detail-label">ID</span>
                    <span class="detail-value">
                        {{ $category->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Name</span>
                    <span class="detail-value">
                        {{ $category->name }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Code</span>
                    <span class="detail-value">
                        {{ $category->code }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Parent Category</span>
                    <span class="detail-value">
                        {{ $category->parent->name ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($category->status === 'Active')
                            <span class="badge badge-success">
                                Active
                            </span>
                        @else
                            <span class="badge badge-danger">
                                Inactive
                            </span>
                        @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">
                        {{ $category->created_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Last Updated</span>
                    <span class="detail-value">
                        {{ $category->updated_at?->format('d M Y, h:i A') ?? '-' }}
                    </span>
                </div>

                <div class="detail-item full-width">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">
                        {{ $category->description ?: '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    <div class="card" style="margin-top: 24px;">

        <div class="card-header">
            <div>
                <h2 class="card-title">Child Categories</h2>
                <p class="card-subtitle">
                    Categories that belong directly to this category.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $category->children->count() }}
                </span>
            </div>
        </div>

        <div class="card-body">

            @if($category->children->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($category->children as $child)

                                <tr>

                                    <td>
                                        {{ $child->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $child->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $child->code }}
                                    </td>

                                    <td>
                                        @if($child->status === 'Active')
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

                                            @can('view', $child)
                                                <a
                                                    href="{{ route('categories.show', $child) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $child)
                                                <a
                                                    href="{{ route('categories.edit', $child) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                        </div>
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="table-empty">
                    <h3>No Child Categories</h3>
                    <p>
                        This category does not have any child categories.
                    </p>
                </div>

            @endif

        </div>

    </div>

@endsection