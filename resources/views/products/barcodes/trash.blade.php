{{-- @extends('layouts.app')

@section('title', 'Barcode Trash')

@section('content')

<h1>Barcode Trash — {{ $product->name }}</h1>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

<a href="{{ route('products.barcodes.index', $product) }}">Back to Barcodes</a>

<hr>

<table border="1" cellpadding="8">

    <thead>

        <tr>
            <th>ID</th>
            <th>Barcode</th>
            <th>Type</th>
            <th>Primary</th>
            <th>Deleted At</th>
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
                <td>{{ $barcode->deleted_at }}</td>
                <td>
                    @can('restore', $barcode)
                        <form action="{{ route('products.barcodes.restore', $barcode->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $barcode)
                        <form action="{{ route('products.barcodes.force-delete', $barcode->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this product-barcode?')">
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

{{ $barcodes->links() }}

@endsection --}}





@extends('layouts.app')

@section('title', 'Barcode Trash')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Barcode Trash</h1>
            <p class="page-subtitle">
                Manage deleted barcodes for
                <strong>{{ $product->name }}</strong>.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('products.barcodes.index', $product) }}"
                class="btn btn-secondary"
            >
                ← Back to Barcodes
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
                <h2 class="card-title">Deleted Barcodes</h2>
                <p class="card-subtitle">
                    Restore deleted barcodes or permanently remove them.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $barcodes->total() }} Deleted
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
                                <th>Deleted At</th>
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
                                                Yes
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                No
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $barcode->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('restore', $barcode)
                                                <form
                                                    action="{{ route('products.barcodes.restore', $barcode->id) }}"
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

                                            @can('forceDelete', $barcode)
                                                <form
                                                    action="{{ route('products.barcodes.force-delete', $barcode->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete this product barcode? This action cannot be undone.')"
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
                    {{ $barcodes->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted barcodes for this product.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection