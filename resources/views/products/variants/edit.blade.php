{{-- @extends('layouts.app')

@section('title', 'Edit Product Variant')

@section('content')

<h1>Edit Product Variant</h1>

<a href="{{ route('products.variants.show', $productVariant) }}">Back to Variant</a>

<hr>

<form action="{{ route('products.variants.update', $productVariant) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Name</label><br>
        <input type="text" name="name" value="{{ old('name', $productVariant->name) }}"required>
        @error('name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>SKU</label><br>
        <input type="text" name="sku" value="{{ old('sku', $productVariant->sku) }}"required>
        @error('sku')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Cost Price</label><br>
        <input type="number" name="cost_price" value="{{ old('cost_price', $productVariant->cost_price) }}" step="0.01" min="0">
    </div>
    <br>

    <div>
        <label>Selling Price</label><br>
        <input type="number" name="selling_price" value="{{ old('selling_price', $productVariant->selling_price) }}" step="0.01" min="0">
    </div>
    <br>

    <div>
        <label>Color</label><br>
        <input type="text" name="attributes[color]" value="{{ old('attributes.color', $productVariant->attributes['color'] ?? '') }}">
    </div>
    <br>

    <div>
        <label>Size</label><br>
        <input type="text" name="attributes[size]" value="{{ old('attributes.size', $productVariant->attributes['size'] ?? '') }}">
    </div>
    <br>

    <div>
        <label>
            <input type="checkbox" name="is_default" value="1" {{ old('is_default', $productVariant->is_default) ? 'checked' : '' }}>
            Default Variant
        </label>
    </div>
    <br>

    <div>
        <label>Status</label><br>
        <select name="status">
            <option value="Active" {{ old( 'status', $productVariant->status) === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ old('status', $productVariant->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Update Variant
    </button>

</form>

@endsection --}}








@extends('layouts.app')

@section('title', 'Edit Product Variant')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Product Variant</h1>
            <p class="page-subtitle">
                Update the variant name, SKU, pricing, attributes, and status.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('products.variants.show', $productVariant) }}"
                class="btn btn-secondary"
            >
                ← Back to Variant
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
                <h2 class="card-title">Variant Information</h2>
                <p class="card-subtitle">
                    Update the details of this product variant.
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('products.variants.update', $productVariant) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <div class="form-grid">

                    {{-- Name --}}
                    <div class="form-group">

                        <label for="name" class="form-label">
                            Variant Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $productVariant->name) }}"
                            required
                        >

                        @error('name')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- SKU --}}
                    <div class="form-group">

                        <label for="sku" class="form-label">
                            SKU
                        </label>

                        <input
                            type="text"
                            id="sku"
                            name="sku"
                            class="form-control @error('sku') is-invalid @enderror"
                            value="{{ old('sku', $productVariant->sku) }}"
                            required
                        >

                        @error('sku')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Cost Price --}}
                    <div class="form-group">

                        <label for="cost_price" class="form-label">
                            Cost Price
                        </label>

                        <input
                            type="number"
                            id="cost_price"
                            name="cost_price"
                            class="form-control @error('cost_price') is-invalid @enderror"
                            value="{{ old('cost_price', $productVariant->cost_price) }}"
                            step="0.01"
                            min="0"
                            placeholder="Use product price if empty"
                        >

                        @error('cost_price')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Selling Price --}}
                    <div class="form-group">

                        <label for="selling_price" class="form-label">
                            Selling Price
                        </label>

                        <input
                            type="number"
                            id="selling_price"
                            name="selling_price"
                            class="form-control @error('selling_price') is-invalid @enderror"
                            value="{{ old('selling_price', $productVariant->selling_price) }}"
                            step="0.01"
                            min="0"
                            placeholder="Use product price if empty"
                        >

                        @error('selling_price')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Color --}}
                    <div class="form-group">

                        <label for="attribute_color" class="form-label">
                            Color
                        </label>

                        <input
                            type="text"
                            id="attribute_color"
                            name="attributes[color]"
                            class="form-control @error('attributes.color') is-invalid @enderror"
                            value="{{ old('attributes.color', $productVariant->attributes['color'] ?? '') }}"
                            placeholder="e.g. Red"
                        >

                        @error('attributes.color')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Size --}}
                    <div class="form-group">

                        <label for="attribute_size" class="form-label">
                            Size
                        </label>

                        <input
                            type="text"
                            id="attribute_size"
                            name="attributes[size]"
                            class="form-control @error('attributes.size') is-invalid @enderror"
                            value="{{ old('attributes.size', $productVariant->attributes['size'] ?? '') }}"
                            placeholder="e.g. Medium"
                        >

                        @error('attributes.size')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Status --}}
                    <div class="form-group">

                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-control @error('status') is-invalid @enderror"
                            required
                        >
                            <option
                                value="Active"
                                {{ old('status', $productVariant->status) === 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ old('status', $productVariant->status) === 'Inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>
                        </select>

                        @error('status')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Default Variant --}}
                    <div class="form-group full-width">

                        <label class="form-label">
                            Variant Settings
                        </label>

                        <label
                            style="display:flex; align-items:center; gap:10px; cursor:pointer;"
                        >
                            <input
                                type="checkbox"
                                name="is_default"
                                value="1"
                                {{ old('is_default', $productVariant->is_default) ? 'checked' : '' }}
                            >

                            <span>
                                Set as Default Variant
                            </span>
                        </label>

                        @error('is_default')
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
                        Update Variant
                    </button>
                    
                    <a
                        href="{{ route('products.variants.show', $productVariant) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection