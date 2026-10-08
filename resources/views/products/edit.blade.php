{{-- @extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

<h1>Edit Product</h1>

<a href="{{ route('products.index', $product) }}">Back to Product</a>

<hr>

<form action="{{ route('products.update', $product) }}" method="POST">
    @csrf
    @method('PUT')

    <div>

        <label>Category</label><br>
        <select name="category_id" required>

            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Brand</label><br>
        <select name="brand_id">
            <option value="">None</option>

            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
        @error('brand_id')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Unit</label><br>
        <select name="unit_id" required>

            @foreach($units as $unit)
                <option value="{{ $unit->id }}" {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                    {{ $unit->name }}
                    ({{ $unit->short_name }})
                </option>
            @endforeach
        </select>
        @error('unit_id')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Tax Class</label><br>
        <select name="tax_class_id">
            <option value="">None</option>

            @foreach($taxClasses as $taxClass)
                <option value="{{ $taxClass->id }}" {{ old('tax_class_id', $product->tax_class_id) == $taxClass->id ? 'selected' : '' }}>
                    {{ $taxClass->name }}
                </option>
            @endforeach
        </select>
        @error('tax_class_id')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Name</label><br>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
        @error('name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>SKU</label><br>
        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required>
        @error('sku')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Description</label><br>
        <textarea name="description">{{ old('description', $product->description) }}</textarea>
        @error('description')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Product Type</label><br>
        <select name="product_type">
            <option value="stockable" {{ old('product_type', $product->product_type) === 'stockable' ? 'selected' : '' }}>Stockable</option>
            <option value="service" {{ old('product_type', $product->product_type) === 'service' ? 'selected' : '' }}>Service</option>
        </select>
        @error('product_type')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Cost Price</label><br>
        <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" step="0.01" min="0" required>
        @error('cost_price')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Selling Price</label><br>
        <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" step="0.01" min="0" required>
        @error('selling_price')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>
            <input type="checkbox" name="track_inventory" value="1" {{ old('track_inventory', $product->track_inventory) ? 'checked' : '' }}>
            Track Inventory
        </label>
        @error('track_inventory')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>
            <input type="checkbox" name="allow_negative_stock" value="1" {{ old('allow_negative_stock', $product->allow_negative_stock) ? 'checked' : '' }}>
            Allow Negative Stock
        </label>
        @error('allow_negative_stock')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Status</label><br>
        <select name="status">
            <option value="Active" {{ old('status', $product->status) === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ old('status', $product->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
        Update Product
    </button>

</form>

@endsection --}}






@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Product</h1>
            <p class="page-subtitle">
                Update the product details, pricing, inventory configuration, and status.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('products.show', $product) }}"
                class="btn btn-secondary"
            >
                ← Back to Product
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
                <h2 class="card-title">Product Information</h2>
                <p class="card-subtitle">
                    Make the required changes and save the product.
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('products.update', $product) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <div class="form-grid">

                    {{-- Category --}}
                    <div class="form-group">

                        <label for="category_id" class="form-label">
                            Category
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            class="form-control @error('category_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Category
                            </option>

                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Brand --}}
                    <div class="form-group">

                        <label for="brand_id" class="form-label">
                            Brand
                        </label>

                        <select
                            id="brand_id"
                            name="brand_id"
                            class="form-control @error('brand_id') is-invalid @enderror"
                        >
                            <option value="">
                                None
                            </option>

                            @foreach($brands as $brand)
                                <option
                                    value="{{ $brand->id }}"
                                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}
                                >
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('brand_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Unit --}}
                    <div class="form-group">

                        <label for="unit_id" class="form-label">
                            Unit
                        </label>

                        <select
                            id="unit_id"
                            name="unit_id"
                            class="form-control @error('unit_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Unit
                            </option>

                            @foreach($units as $unit)
                                <option
                                    value="{{ $unit->id }}"
                                    {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}
                                >
                                    {{ $unit->name }} ({{ $unit->short_name }})
                                </option>
                            @endforeach
                        </select>

                        @error('unit_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Tax Class --}}
                    <div class="form-group">

                        <label for="tax_class_id" class="form-label">
                            Tax Class
                        </label>

                        <select
                            id="tax_class_id"
                            name="tax_class_id"
                            class="form-control @error('tax_class_id') is-invalid @enderror"
                        >
                            <option value="">
                                None
                            </option>

                            @foreach($taxClasses as $taxClass)
                                <option
                                    value="{{ $taxClass->id }}"
                                    {{ old('tax_class_id', $product->tax_class_id) == $taxClass->id ? 'selected' : '' }}
                                >
                                    {{ $taxClass->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('tax_class_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Name --}}
                    <div class="form-group">

                        <label for="name" class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $product->name) }}"
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
                            value="{{ old('sku', $product->sku) }}"
                            required
                        >

                        @error('sku')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Product Type --}}
                    <div class="form-group">

                        <label for="product_type" class="form-label">
                            Product Type
                        </label>

                        <select
                            id="product_type"
                            name="product_type"
                            class="form-control @error('product_type') is-invalid @enderror"
                        >
                            <option
                                value="stockable"
                                {{ old('product_type', $product->product_type) === 'stockable' ? 'selected' : '' }}
                            >
                                Stockable
                            </option>

                            <option
                                value="service"
                                {{ old('product_type', $product->product_type) === 'service' ? 'selected' : '' }}
                            >
                                Service
                            </option>
                        </select>

                        @error('product_type')
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
                                {{ old('status', $product->status) === 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ old('status', $product->status) === 'Inactive' ? 'selected' : '' }}
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
                            value="{{ old('cost_price', $product->cost_price) }}"
                            step="0.01"
                            min="0"
                            required
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
                            value="{{ old('selling_price', $product->selling_price) }}"
                            step="0.01"
                            min="0"
                            required
                        >

                        @error('selling_price')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Inventory Settings --}}
                    <div class="form-group full-width">

                        <label class="form-label">
                            Inventory Settings
                        </label>

                        <div style="display:flex; flex-wrap:wrap; gap:20px;">

                            <label
                                style="display:flex; align-items:center; gap:10px; cursor:pointer;"
                            >
                                <input
                                    type="checkbox"
                                    name="track_inventory"
                                    value="1"
                                    {{ old('track_inventory', $product->track_inventory) ? 'checked' : '' }}
                                >

                                <span>
                                    Track Inventory
                                </span>
                            </label>

                            <label
                                style="display:flex; align-items:center; gap:10px; cursor:pointer;"
                            >
                                <input
                                    type="checkbox"
                                    name="allow_negative_stock"
                                    value="1"
                                    {{ old('allow_negative_stock', $product->allow_negative_stock) ? 'checked' : '' }}
                                >

                                <span>
                                    Allow Negative Stock
                                </span>
                            </label>

                        </div>

                        @error('track_inventory')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                        @error('allow_negative_stock')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Description --}}
                    <div class="form-group full-width">

                        <label for="description" class="form-label">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="5"
                            placeholder="Optional product description"
                        >{{ old('description', $product->description) }}</textarea>

                        @error('description')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="form-actions">

                    <a
                        href="{{ route('products.show', $product) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Update Product
                    </button>

                </div>

            </form>

        </div>

    </div>

@endsection