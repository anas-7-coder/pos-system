{{-- @extends('layouts.app')

@section('title', 'Product Barcodes')

@section('content')

<h1>Barcodes — {{ $product->name }}</h1>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

<a href="{{ route('products.show', $product) }}">Back to Product</a>
    &nbsp; | &nbsp;
@can('create', App\Models\ProductBarcode::class)
    <a href="{{ route('products.barcodes.create', $product) }}">Add Barcode</a>
@endcan
    &nbsp; | &nbsp;
@can('viewTrash', App\Models\ProductBarcode::class)
    <a href="{{ route('products.barcodes.trash', $product) }}">Trash</a>
@endcan

<hr>

<table border="1" cellpadding="8">

    <thead>

        <tr>
            <th>ID</th>
            <th>Barcode</th>
            <th>Type</th>
            <th>Primary</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

        @forelse($barcodes as $barcode)

            <tr>
                <td>{{ $barcode->id }}</td>
                <td>{{ $barcode->barcode }}</td>
                <td>{{ $barcode->type }}</td>
                <td>{{ $barcode->is_primary ? 'Yes' : 'No' }}</td>
                <td>
                    @can('update', $barcode)
                        <a href="{{ route('products.barcodes.edit', $barcode) }}"><button type="button">Edit</button></a>
                    @endcan
                        &nbsp;
                    @can('delete', $barcode)
                        <form
                            action="{{ route('products.barcodes.destroy', $barcode) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this product-barcode?')">
                                Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="5">
                    No barcodes found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $barcodes->links() }}

@endsection --}}










@extends('layouts.app')

@section('title', 'Product Barcodes')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Product Barcodes</h1>
            <p class="page-subtitle">
                Manage barcode identifiers for
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

            @can('create', App\Models\ProductBarcode::class)
                <a
                    href="{{ route('products.barcodes.create', $product) }}"
                    class="btn btn-primary"
                >
                    + Add Barcode
                </a>
            @endcan

            @can('viewTrash', App\Models\ProductBarcode::class)
                <a
                    href="{{ route('products.barcodes.trash', $product) }}"
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
                <h2 class="card-title">Barcodes</h2>
                <p class="card-subtitle">
                    View and manage all active barcodes assigned to this product.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $barcodes->total() }} Barcodes
                </span>
            </div>

        </div>

        <div class="card-body">

            @if($barcodes->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Barcode</th>
                                <th>Type</th>
                                <th>Primary</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($barcodes as $barcode)

                                <tr>

                                    <td>
                                        {{ $barcode->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $barcode->barcode }}
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ strtoupper($barcode->type) }}
                                        </span>
                                    </td>

                                    <td>

                                        @if($barcode->is_primary)
                                            <span class="badge badge-success">
                                                Primary
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                No
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('update', $barcode)
                                                <a
                                                    href="{{ route('products.barcodes.edit', $barcode) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $barcode)
                                                <form
                                                    action="{{ route('products.barcodes.destroy', $barcode) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this product barcode?')"
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
                    {{ $barcodes->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Barcodes Found</h3>

                    <p>
                        This product does not have any active barcodes configured.
                    </p>

                    @can('create', App\Models\ProductBarcode::class)
                        <a
                            href="{{ route('products.barcodes.create', $product) }}"
                            class="btn btn-primary"
                        >
                            Add First Barcode
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection