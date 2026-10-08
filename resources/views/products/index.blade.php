{{-- @extends('layouts.app')

@section('title', 'Products')

@section('content')

<h1>Products</h1>

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
            <th>SKU</th>
            <th>Category</th>
            <th>Brand</th>
            <th>Unit</th>
            <th>Cost</th>
            <th>Selling Price</th>
            <th>Type</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

        @forelse($products as $product)

            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->category->name }}</td>
                <td>{{ $product->brand->name ?? '-' }}</td>
                <td>{{ $product->unit->short_name }}</td>
                <td>{{ $product->cost_price }}</td>
                <td>{{ $product->selling_price }}</td>
                <td>{{ $product->product_type }}</td>
                <td>{{ $product->status }}</td>
                <td>
                    @can('view', $product)
                        <a href="{{ route('products.show', $product) }}"><button type="button">View</button></a>
                    @endcan
                        &nbsp;
                    @can('update', $product)
                        <a href="{{ route('products.edit', $product) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $product)
                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="11">
                    No products found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $products->links() }}

@endsection --}}





@extends('layouts.app')

@section('title', 'Products')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Products</h1>
            <p class="page-subtitle">
                Manage products, pricing, inventory settings, and catalog information.
            </p>
        </div>

        <div class="page-actions">

            @can('create', App\Models\Product::class)
                <a
                    href="{{ route('products.create') }}"
                    class="btn btn-primary"
                >
                    + Create Product
                </a>
            @endcan

            <a
                href="{{ route('products.trash') }}"
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
                <h2 class="card-title">All Products</h2>
                <p class="card-subtitle">
                    View and manage products in your catalog.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $products->total() }} Products
                </span>
            </div>
        </div>

        <div class="card-body">

            @if($products->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Brand</th>
                                <th>Unit</th>
                                <th>Cost</th>
                                <th>Selling Price</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($products as $product)

                                <tr>

                                    <td>
                                        {{ $product->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $product->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $product->sku }}
                                    </td>

                                    <td>
                                        {{ $product->category->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $product->brand->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $product->unit->short_name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ number_format((float) $product->cost_price, 2) }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ number_format((float) $product->selling_price, 2) }}
                                        </strong>
                                    </td>

                                    <td>
                                        @if($product->product_type === 'stockable')
                                            <span class="badge badge-secondary">
                                                Stockable
                                            </span>
                                        @else
                                            <span class="badge badge-info">
                                                Service
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($product->status === 'Active')
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

                                            @can('view', $product)
                                                <a
                                                    href="{{ route('products.show', $product) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $product)
                                                <a
                                                    href="{{ route('products.edit', $product) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $product)
                                                <form
                                                    action="{{ route('products.destroy', $product) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this product?')"
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
                    {{ $products->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Products Found</h3>

                    <p>
                        There are currently no products available in the catalog.
                    </p>

                    @can('create', App\Models\Product::class)
                        <a
                            href="{{ route('products.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Product
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection