{{-- @extends('layouts.app')

@section('title', 'Brand Trash')

@section('content')

<h1>Brand Trash</h1>

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
            <th>Deleted At</th>
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
                <td>{{ $brand->deleted_at }}</td>
                <td>
                    @can('restore', $brand)
                        <form action="{{ route('brands.restore', $brand->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $brand)
                        <form action="{{ route('brands.force-delete', $brand->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this brand?')">
                                Force Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="6">
                    Trash is empty.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $brands->links() }}

@endsection --}}







@extends('layouts.app')

@section('title', 'Brand Trash')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Brand Trash</h1>
            <p class="page-subtitle">
                Manage deleted brands and restore or permanently remove them.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('brands.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Brands
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
                <h2 class="card-title">Deleted Brands</h2>
                <p class="card-subtitle">
                    Restore deleted brands or permanently remove them.
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
                                <th>Deleted At</th>
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
                                        {{ $brand->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('restore', $brand)
                                                <form
                                                    action="{{ route('brands.restore', $brand->id) }}"
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

                                            @can('forceDelete', $brand)
                                                <form
                                                    action="{{ route('brands.force-delete', $brand->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete this brand? This action cannot be undone.')"
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
                    {{ $brands->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted brands to display.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection