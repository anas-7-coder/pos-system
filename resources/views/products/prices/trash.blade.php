{{-- @extends('layouts.app')

@section('title', 'Deleted Product Prices')

@section('content')

<h1>Deleted Product Prices</h1>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('products.prices.index', $product) }}">Back to Prices</a>

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
            <th>Deleted At</th>
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
                <td>{{ $price->deleted_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <form action="{{ route( 'products.prices.restore', [$product, $price->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')

                        <button type="submit">
                            Restore
                        </button>
                    </form>
                        &nbsp;
                    <form action="{{ route('products.prices.force-delete', [$product, $price->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="return confirm('Are you sure you want to delete this product-price?')">
                            Force Delete
                        </button>
                    </form>
                </td>
            </tr>

        @endforeach

    </tbody>

</table>

{{ $prices->links() }}

@else

<p>No deleted prices found.</p>

@endif

@endsection --}}






@extends('layouts.app')

@section('title', 'Deleted Product Prices')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Deleted Product Prices</h1>
            <p class="page-subtitle">
                Manage deleted pricing configurations for
                <strong>{{ $product->name }}</strong>.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('products.prices.index', $product) }}"
                class="btn btn-secondary"
            >
                ← Back to Prices
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
                <h2 class="card-title">Deleted Product Prices</h2>
                <p class="card-subtitle">
                    Restore deleted prices or permanently remove them.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $prices->total() }} Deleted
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
                                <th>Deleted At</th>
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
                                        {{ $price->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            <form
                                                action="{{ route('products.prices.restore', [$product, $price->id]) }}"
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

                                            <form
                                                action="{{ route('products.prices.force-delete', [$product, $price->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to permanently delete this product price? This action cannot be undone.')"
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

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted prices for this product.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection