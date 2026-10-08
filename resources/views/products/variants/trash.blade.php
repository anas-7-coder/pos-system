{{-- @extends('layouts.app')

@section('title', 'Product Variant Trash')

@section('content')

<h1>Variant Trash — {{ $product->name }}</h1>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

<a href="{{ route('products.variants.index', $product) }}">Back to Variants</a>

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
            <th>Deleted At</th>
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
                <td>{{ $variant->deleted_at }}</td>
                <td>
                    @can('restore', $variant)
                        <form action="{{ route('products.variants.restore', $variant->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $variant)
                        <form action="{{ route('products.variants.force-delete', $variant->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this product-variant?')">
                                Force Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="9">
                    Trash is empty.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $variants->links() }}

@endsection --}}









@extends('layouts.app')

@section('title', 'Product Variant Trash')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Variant Trash</h1>
            <p class="page-subtitle">
                Manage deleted variants for
                <strong>{{ $product->name }}</strong>.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('products.variants.index', $product) }}"
                class="btn btn-secondary"
            >
                ← Back to Variants
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
                <h2 class="card-title">Deleted Variants</h2>
                <p class="card-subtitle">
                    Restore deleted variants or permanently remove them.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $variants->total() }} Deleted
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
                                <th>Deleted At</th>
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
                                        {{ $variant->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('restore', $variant)
                                                <form
                                                    action="{{ route('products.variants.restore', $variant->id) }}"
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

                                            @can('forceDelete', $variant)
                                                <form
                                                    action="{{ route('products.variants.force-delete', $variant->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete this product variant? This action cannot be undone.')"
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
                    {{ $variants->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted variants for this product.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection