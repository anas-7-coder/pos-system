{{-- @extends('layouts.app')

@section('title', 'Add Barcode')

@section('content')

<h1>Add Barcode</h1>

<a href="{{ route('products.barcodes.index', $product) }}">Back to Barcodes</a>

<hr>

<p><strong>Product:</strong> {{ $product->name }}</p>

<form action="{{ route('products.barcodes.store', $product) }}" method="POST">
    @csrf

    <div>
        <label>Barcode</label><br>
        <input type="text" name="barcode" value="{{ old('barcode') }}" required>
        @error('barcode')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Type</label><br>
        <select name="type">
            <option value="internal">Internal</option>
            <option value="ean">EAN</option>
            <option value="upc">UPC</option>
            <option value="code128">Code 128</option>
            <option value="other">Other</option>
        </select>
        @error('type')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>
            <input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}>
            Primary Barcode
        </label>
        @error('is_primary')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Add Barcode
    </button>

</form>

@endsection --}}









@extends('layouts.app')

@section('title', 'Add Barcode')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Add Barcode</h1>
            <p class="page-subtitle">
                Add a barcode identifier to this product.
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

    <div class="card form-card">

        <div class="card-header">
            <div>
                <h2 class="card-title">Barcode Information</h2>
                <p class="card-subtitle">
                    Product:
                    <strong>{{ $product->name }}</strong>
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('products.barcodes.store', $product) }}"
                method="POST"
            >
                @csrf

                <div class="form-grid">

                    {{-- Barcode --}}
                    <div class="form-group">

                        <label for="barcode" class="form-label">
                            Barcode
                        </label>

                        <input
                            type="text"
                            id="barcode"
                            name="barcode"
                            class="form-control @error('barcode') is-invalid @enderror"
                            value="{{ old('barcode') }}"
                            placeholder="Enter barcode"
                            required
                        >

                        @error('barcode')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Type --}}
                    <div class="form-group">

                        <label for="type" class="form-label">
                            Type
                        </label>

                        <select
                            id="type"
                            name="type"
                            class="form-control @error('type') is-invalid @enderror"
                        >
                            <option
                                value="internal"
                                {{ old('type', 'internal') === 'internal' ? 'selected' : '' }}
                            >
                                Internal
                            </option>

                            <option
                                value="ean"
                                {{ old('type') === 'ean' ? 'selected' : '' }}
                            >
                                EAN
                            </option>

                            <option
                                value="upc"
                                {{ old('type') === 'upc' ? 'selected' : '' }}
                            >
                                UPC
                            </option>

                            <option
                                value="code128"
                                {{ old('type') === 'code128' ? 'selected' : '' }}
                            >
                                Code 128
                            </option>

                            <option
                                value="other"
                                {{ old('type') === 'other' ? 'selected' : '' }}
                            >
                                Other
                            </option>
                        </select>

                        @error('type')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Primary Barcode --}}
                    <div class="form-group full-width">

                        <label class="form-label">
                            Barcode Settings
                        </label>

                        <label
                            style="display:flex; align-items:center; gap:10px; cursor:pointer;"
                        >
                            <input
                                type="checkbox"
                                name="is_primary"
                                value="1"
                                {{ old('is_primary') ? 'checked' : '' }}
                            >

                            <span>
                                Set as Primary Barcode
                            </span>
                        </label>

                        @error('is_primary')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Add Barcode
                    </button>
                    
                    <a
                        href="{{ route('products.barcodes.index', $product) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection