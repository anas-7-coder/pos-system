{{-- @extends('layouts.app')

@section('title', 'Add Product Price')

@section('content')

<h1>Add Product Price</h1>

<a href="{{ route('products.prices.index', $product) }}">Back To Prices</a>

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<hr>

<p>Product: <strong>{{ $product->name }}</strong></p>


<form action="{{ route('products.prices.store', $product) }}" method="POST">
    @csrf

    <div>
        <label>Variant</label><br>
        <select name="product_variant_id">
            <option value="">All / Default Product</option>

            @foreach($product->variants as $variant)
                <option value="{{ $variant->id }}" {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}>
                    {{ $variant->name }} - {{ $variant->sku }}
                </option>
            @endforeach
        </select>
        @error('product_variant_id')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Branch</label><br>
        <select name="branch_id">
            <option value="">All Branches</option>

            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                    {{ $branch->name }}
                </option>
            @endforeach
        </select>
        @error('branch_id')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Price Type</label><br>
        <select name="price_type">
            <option value="retail" {{ old('price_type') === 'retail' ? 'selected' : '' }}>Retail</option>
            <option value="wholesale" {{ old('price_type') === 'wholesale' ? 'selected' : '' }}>Wholesale</option>
            <option value="special" {{ old('price_type') === 'special' ? 'selected' : '' }}>Special</option>
        </select>
        @error('price_type')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Price</label><br>
        <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01">
        @error('price')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Effective From</label><br>
        <input type="date" name="effective_from" value="{{ old('effective_from', now()->format('Y-m-d')) }}">
        @error('effective_from')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Effective To</label><br>
        <input type="date" name="effective_to" value="{{ old('effective_to') }}">
        @error('effective_to')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Status</label><br>
        <select name="status">
            <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}> Active</option>
            <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Description</label><br>
        <input type="text" name="description" value="{{ old('description') }}">
        @error('description')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Save Price
    </button>

</form>

@endsection --}}








@extends('layouts.app')

@section('title', 'Add Product Price')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Add Product Price</h1>
            <p class="page-subtitle">
                Configure pricing for
                <strong>{{ $product->name }}</strong>
                by branch, variant, and price type.
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

    <div class="card form-card">

        <div class="card-header">
            <div>
                <h2 class="card-title">Product Price Information</h2>
                <p class="card-subtitle">
                    Product:
                    <strong>{{ $product->name }}</strong>
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('products.prices.store', $product) }}"
                method="POST"
            >
                @csrf

                <div class="form-grid">

                    {{-- Variant --}}
                    <div class="form-group">

                        <label for="product_variant_id" class="form-label">
                            Variant
                        </label>

                        <select
                            id="product_variant_id"
                            name="product_variant_id"
                            class="form-control @error('product_variant_id') is-invalid @enderror"
                        >
                            <option value="">
                                All / Default Product
                            </option>

                            @foreach($product->variants as $variant)
                                <option
                                    value="{{ $variant->id }}"
                                    {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}
                                >
                                    {{ $variant->name }} - {{ $variant->sku }}
                                </option>
                            @endforeach
                        </select>

                        @error('product_variant_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Branch --}}
                    <div class="form-group">

                        <label for="branch_id" class="form-label">
                            Branch
                        </label>

                        <select
                            id="branch_id"
                            name="branch_id"
                            class="form-control @error('branch_id') is-invalid @enderror"
                        >
                            <option value="">
                                All Branches
                            </option>

                            @foreach($branches as $branch)
                                <option
                                    value="{{ $branch->id }}"
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                >
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('branch_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Price Type --}}
                    <div class="form-group">

                        <label for="price_type" class="form-label">
                            Price Type
                        </label>

                        <select
                            id="price_type"
                            name="price_type"
                            class="form-control @error('price_type') is-invalid @enderror"
                            required
                        >
                            <option
                                value="retail"
                                {{ old('price_type', 'retail') === 'retail' ? 'selected' : '' }}
                            >
                                Retail
                            </option>

                            <option
                                value="wholesale"
                                {{ old('price_type') === 'wholesale' ? 'selected' : '' }}
                            >
                                Wholesale
                            </option>

                            <option
                                value="special"
                                {{ old('price_type') === 'special' ? 'selected' : '' }}
                            >
                                Special
                            </option>
                        </select>

                        @error('price_type')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Price --}}
                    <div class="form-group">

                        <label for="price" class="form-label">
                            Price
                        </label>

                        <input
                            type="number"
                            id="price"
                            name="price"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price') }}"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            required
                        >

                        @error('price')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Effective From --}}
                    <div class="form-group">

                        <label for="effective_from" class="form-label">
                            Effective From
                        </label>

                        <input
                            type="date"
                            id="effective_from"
                            name="effective_from"
                            class="form-control @error('effective_from') is-invalid @enderror"
                            value="{{ old('effective_from', now()->format('Y-m-d')) }}"
                            required
                        >

                        @error('effective_from')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Effective To --}}
                    <div class="form-group">

                        <label for="effective_to" class="form-label">
                            Effective To
                        </label>

                        <input
                            type="date"
                            id="effective_to"
                            name="effective_to"
                            class="form-control @error('effective_to') is-invalid @enderror"
                            value="{{ old('effective_to') }}"
                        >

                        @error('effective_to')
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
                                {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ old('status') === 'Inactive' ? 'selected' : '' }}
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

                    {{-- Description --}}
                    <div class="form-group full-width">

                        <label for="description" class="form-label">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="4"
                            placeholder="Optional pricing description"
                        >{{ old('description') }}</textarea>

                        @error('description')
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
                        Save Price
                    </button>
                    
                    <a
                        href="{{ route('products.prices.index', $product) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection