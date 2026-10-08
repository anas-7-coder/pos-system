{{-- @extends('layouts.app')

@section('title', 'Product Variants')

@section('content')

<h1>Variants — {{ $product->name }}</h1>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

<a href="{{ route('products.show', $product) }}">Back to Product</a>
    &nbsp; | &nbsp;
@can('create', App\Models\ProductVariant::class)
    <a href="{{ route('products.variants.create', $product) }}">Add Variant</a>
@endcan
    &nbsp; | &nbsp;
@can('viewTrash', App\Models\ProductVariant::class)
    <a href="{{ route('products.variants.trash', $product) }}">Trash</a>
@endcan

<hr>

<table border="1" cellpadding="8">

    <thead>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>SKU</th>
            <th>Cost Price</th>
            <th>Selling Price</th>
            <th>Default</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

        @forelse($variants as $variant)

            <tr>
                <td>{{ $variant->id }}</td>
                <td>{{ $variant->name }}</td>
                <td>{{ $variant->sku }}</td>
                <td>{{ $variant->cost_price ?? '-' }}</td>
                <td>{{ $variant->selling_price ?? '-' }}</td>
                <td>{{ $variant->is_default ? 'Yes' : 'No' }}</td>
                <td>{{ $variant->status }}</td>
                <td>
                    @can('view', $variant)
                        <a href="{{ route('products.variants.show', $variant) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $variant)
                        <a href="{{ route('products.variants.edit', $variant) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $variant)
                        <form action="{{ route('products.variants.destroy', $variant) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this product-variant?')">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="8">
                    No variants found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $variants->links() }}

@endsection --}}






@extends('layouts.app')

@section('title', 'Product Variants')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Product Variants</h1>
            <p class="page-subtitle">
                Manage variants for
                <strong>{{ $product->name }}</strong>.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('products.show', $product) }}"
                class="btn btn-secondary"
            >
                ← Back to Product
            </a>

            @can('create', App\Models\ProductVariant::class)
                <a
                    href="{{ route('products.variants.create', $product) }}"
                    class="btn btn-primary"
                >
                    + Add Variant
                </a>
            @endcan

            @can('viewTrash', App\Models\ProductVariant::class)
                <a
                    href="{{ route('products.variants.trash', $product) }}"
                    class="btn btn-secondary"
                >
                    Trash
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
                <h2 class="card-title">Variants</h2>
                <p class="card-subtitle">
                    View and manage all active variants for this product.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $variants->total() }} Variants
                </span>
            </div>

        </div>

        <div class="card-body">

            @if($variants->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>SKU</th>
                                <th>Cost Price</th>
                                <th>Selling Price</th>
                                <th>Default</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($variants as $variant)

                                <tr>

                                    <td>
                                        {{ $variant->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $variant->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $variant->sku }}
                                    </td>

                                    <td>
                                        {{ $variant->cost_price !== null
                                            ? number_format((float) $variant->cost_price, 2)
                                            : '-' }}
                                    </td>

                                    <td>
                                        {{ $variant->selling_price !== null
                                            ? number_format((float) $variant->selling_price, 2)
                                            : '-' }}
                                    </td>

                                    <td>
                                        @if($variant->is_default)
                                            <span class="badge badge-success">
                                                Default
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                No
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($variant->status === 'Active')
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

                                            @can('view', $variant)
                                                <a
                                                    href="{{ route('products.variants.show', $variant) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $variant)
                                                <a
                                                    href="{{ route('products.variants.edit', $variant) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $variant)
                                                <form
                                                    action="{{ route('products.variants.destroy', $variant) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this product variant?')"
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
                    {{ $variants->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Variants Found</h3>

                    <p>
                        This product does not have any active variants configured.
                    </p>

                    @can('create', App\Models\ProductVariant::class)
                        <a
                            href="{{ route('products.variants.create', $product) }}"
                            class="btn btn-primary"
                        >
                            Add First Variant
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection