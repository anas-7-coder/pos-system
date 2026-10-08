{{-- @extends('layouts.app')

@section('title', 'Adjust Inventory')

@section('content')

<h1>Adjust Inventory</h1>

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('inventory.index') }}">Back to Inventory</a>

<hr>

<form action="{{ route('inventory.adjust.store') }}" method="POST">
    @csrf

    <div>
        <label>Warehouse</label><br>
        <select name="warehouse_id">
            <option value="">Select Warehouse</option>

            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>
        @error('warehouse_id')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Product</label><br>
        <select name="product_id">
            <option value="">Select Product</option>

            @foreach($products as $product)
                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->name }} - {{ $product->sku }}
                </option>
            @endforeach
        </select>
        @error('product_id')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Variant</label><br>
        <select name="product_variant_id">
            <option value="">No Variant</option>

            @foreach($variants as $variant)
                <option value="{{ $variant->id }}" {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}>
                    {{ $variant->product->name }} - {{ $variant->name }}
                </option>
            @endforeach
        </select>
        @error('product_variant_id')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Adjustment Type</label><br>
        <select name="direction">
            <option value="in" {{ old('direction') === 'in' ? 'selected' : '' }}>Stock In</option>
            <option value="out" {{ old('direction') === 'out' ? 'selected' : '' }}>Stock Out</option>
        </select>
        @error('direction')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Quantity</label><br>
        <input type="number" name="quantity" value="{{ old('quantity') }}" min="0.0001" step="0.0001">
        @error('quantity')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Reason</label><br>
        <textarea name="reason" rows="3">{{ old('reason') }}</textarea>
        @error('reason')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Adjust Stock
    </button>

</form>

@endsection --}}





@extends('layouts.app')

@section('title', 'Adjust Inventory')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Adjust Inventory</h1>
            <p class="page-subtitle">
                Increase or decrease stock for a specific warehouse and product.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('inventory.index') }}"
               class="btn btn-secondary">
                ← Back to Inventory
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

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul style="margin:10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inventory.adjust.store') }}"
          method="POST">

        @csrf

        {{-- Stock Information --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Stock Information</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    <div class="form-group">

                        <label for="warehouse_id" class="form-label">
                            Warehouse
                        </label>

                        <select
                            id="warehouse_id"
                            name="warehouse_id"
                            class="@error('warehouse_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Warehouse</option>

                            @foreach($warehouses as $warehouse)
                                <option
                                    value="{{ $warehouse->id }}"
                                    {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}
                                >
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('warehouse_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="product_id" class="form-label">
                            Product
                        </label>

                        <select
                            id="product_id"
                            name="product_id"
                            class="@error('product_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Product</option>

                            @foreach($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}
                                >
                                    {{ $product->name }} — {{ $product->sku }}
                                </option>
                            @endforeach
                        </select>

                        @error('product_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="product_variant_id" class="form-label">
                            Variant
                        </label>

                        <select
                            id="product_variant_id"
                            name="product_variant_id"
                            class="@error('product_variant_id') is-invalid @enderror"
                        >
                            <option value="">No Variant</option>

                            @foreach($variants as $variant)
                                <option
                                    value="{{ $variant->id }}"
                                    {{ old('product_variant_id') == $variant->id ? 'selected' : '' }}
                                >
                                    {{ $variant->product->name }} — {{ $variant->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('product_variant_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="direction" class="form-label">
                            Adjustment Type
                        </label>

                        <select
                            id="direction"
                            name="direction"
                            class="@error('direction') is-invalid @enderror"
                            required
                        >
                            <option value="in"
                                {{ old('direction', 'in') === 'in' ? 'selected' : '' }}>
                                Stock In
                            </option>

                            <option value="out"
                                {{ old('direction') === 'out' ? 'selected' : '' }}>
                                Stock Out
                            </option>
                        </select>

                        @error('direction')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="quantity" class="form-label">
                            Quantity
                        </label>

                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            value="{{ old('quantity') }}"
                            min="0.0001"
                            step="0.0001"
                            class="@error('quantity') is-invalid @enderror"
                            placeholder="Enter quantity"
                            required
                        >

                        @error('quantity')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>
        </div>

        {{-- Reason --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Adjustment Reason</h2>
            </div>

            <div class="card-body">

                <div class="form-group">

                    <label for="reason" class="form-label">
                        Reason
                    </label>

                    <textarea
                        id="reason"
                        name="reason"
                        rows="4"
                        class="@error('reason') is-invalid @enderror"
                        placeholder="Explain why the inventory is being adjusted..."
                        required
                    >{{ old('reason') }}</textarea>

                    @error('reason')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="card">

            <div class="card-body">

                <div class="form-actions">

                    <button type="submit"
                            class="btn btn-primary">
                        Adjust Stock
                    </button>
                    
                    <a href="{{ route('inventory.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </div>
        </div>

    </form>

@endsection