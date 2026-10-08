{{-- @extends('layouts.app')

@section('title', 'Product Prices')

@section('content')

<h1>Product Prices</h1>

@if(session('success'))
    <p style="color: green;">
        {{ session('success') }}
    </p>
@endif

@if(session('error'))
    <p style="color: red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('products.show', $product) }}">Back to Product</a>
    &nbsp; | &nbsp;
<a href="{{ route('products.prices.create', $product) }}">Add Price</a>
    &nbsp; | &nbsp;
<a href="{{ route('products.prices.trash', $product) }}">Trash</a>

<hr>

<p>Product: <strong>{{ $product->name }}</strong></p>


@if($prices->count())

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>Type</th>
            <th>Variant</th>
            <th>Branch</th>
            <th>Price</th>
            <th>Effective From</th>
            <th>Effective To</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($prices as $price)
            <tr>
                <td>{{ ucfirst($price->price_type) }}</td>
                <td>{{ $price->productVariant?->name ?? 'All / Default' }}</td>
                <td>{{ $price->branch?->name ?? 'All Branches' }}</td>
                <td>PKR {{ number_format((float) $price->price, 2) }}</td>
                <td>{{ $price->effective_from?->format('Y-m-d') }}</td>
                <td>{{ $price->effective_to?->format('Y-m-d') ?? 'Open ended' }}</td>
                <td>{{ ucfirst($price->status) }}</td>
                <td>
                    <a href="{{ route('products.prices.show', [$product, $price]) }}"><button type="button">View</button></a>
                        &nbsp;
                    <a href="{{ route('products.prices.edit', [$product, $price]) }}"><button type="button">Edit</button></a>
                        &nbsp;
                    <form action="{{ route('products.prices.destroy', [$product, $price]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="return confirm('Are you sure you want to delete this product-price?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $prices->links() }}

@else

<p>No prices found.</p>

@endif

@endsection --}}









@extends('layouts.app')

@section('title', 'Product Prices')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Product Prices</h1>
            <p class="page-subtitle">
                Manage pricing for
                <strong>{{ $product->name }}</strong>
                across variants, branches, and price types.
            </p>
        </div>

        <div class="page-actions">

            <a
                href="{{ route('products.show', $product) }}"
                class="btn btn-secondary"
            >
                ← Back to Product
            </a>

            <a
                href="{{ route('products.prices.create', $product) }}"
                class="btn btn-primary"
            >
                + Add Price
            </a>

            <a
                href="{{ route('products.prices.trash', $product) }}"
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
                <h2 class="card-title">Product Prices</h2>
                <p class="card-subtitle">
                    View and manage all active pricing configurations.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $prices->total() }} Prices
                </span>
            </div>

        </div>

        <div class="card-body">

            @if($prices->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Variant</th>
                                <th>Branch</th>
                                <th>Price</th>
                                <th>Effective From</th>
                                <th>Effective To</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($prices as $price)

                                <tr>

                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ ucfirst($price->price_type) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $price->productVariant?->name ?? 'All / Default' }}
                                    </td>

                                    <td>
                                        {{ $price->branch?->name ?? 'All Branches' }}
                                    </td>

                                    <td>
                                        <strong>
                                            PKR {{ number_format((float) $price->price, 2) }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $price->effective_from?->format('Y-m-d') ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $price->effective_to?->format('Y-m-d') ?? 'Open ended' }}
                                    </td>

                                    <td>
                                        @if($price->status === 'Active')
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

                                            <a
                                                href="{{ route('products.prices.show', [$product, $price]) }}"
                                                class="btn btn-secondary btn-sm"
                                            >
                                                View
                                            </a>

                                            <a
                                                href="{{ route('products.prices.edit', [$product, $price]) }}"
                                                class="btn btn-outline-primary btn-sm"
                                            >
                                                Edit
                                            </a>

                                            <form
                                                action="{{ route('products.prices.destroy', [$product, $price]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this product price?')"
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

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="pagination-wrapper">
                    {{ $prices->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Prices Found</h3>

                    <p>
                        There are currently no pricing configurations for this product.
                    </p>

                    <a
                        href="{{ route('products.prices.create', $product) }}"
                        class="btn btn-primary"
                    >
                        Add First Price
                    </a>

                </div>

            @endif

        </div>

    </div>

@endsection