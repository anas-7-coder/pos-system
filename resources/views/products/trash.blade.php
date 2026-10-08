{{-- @extends('layouts.app')

@section('title', 'Product Trash')

@section('content')

<h1>Product Trash</h1>

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
            <th>Deleted At</th>
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
                <td>{{ $product->deleted_at }}</td>
                <td>
                    @can('restore', $product)
                        <form action="{{ route('products.restore', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $product)
                        <form action="{{ route('products.force-delete', $product->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this product?')">
                                Force Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="12">
                    Trash is empty.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $products->links() }}

@endsection --}}






@extends('layouts.app')

@section('title', 'Product Trash')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Product Trash</h1>
            <p class="page-subtitle">
                Manage deleted products and restore or permanently remove them.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('products.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Products
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
                <h2 class="card-title">Deleted Products</h2>
                <p class="card-subtitle">
                    Restore deleted products or permanently remove them.
                </p>
            </div>
        </div>

        <div class="card-body">

            @if($products->count())

                <div class="table-wrapper">

                    <table>

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
                                <th>Deleted At</th>
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
                                        {{ number_format((float) $product->selling_price, 2) }}
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
                                        {{ $product->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('restore', $product)
                                                <form
                                                    action="{{ route('products.restore', $product->id) }}"
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

                                            @can('forceDelete', $product)
                                                <form
                                                    action="{{ route('products.force-delete', $product->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete this product? This action cannot be undone.')"
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
                    {{ $products->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted products to display.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection