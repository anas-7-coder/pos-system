{{-- @extends('layouts.app')

@section('title', 'Set Reorder Level')

@section('content')

<h1>Set Reorder Level</h1>

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('inventory.index') }}">Back to Inventory</a>

<hr>

<form action="{{ route('inventory.reorder-level.store') }}" method="POST">
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
            <p style="color:red;">
                {{ $message }}
            </p>
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
            <p style="color:red;">
                {{ $message }}
            </p>
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
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Reorder Level</label><br>
        <input type="number" name="reorder_level" value="{{ old('reorder_level', 0) }}" min="0" step="0.0001">
        @error('reorder_level')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Save Reorder Level
    </button>

</form>

@endsection --}}








@extends('layouts.app')

@section('title', 'Set Reorder Level')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Set Reorder Level</h1>
            <p class="page-subtitle">
                Configure the minimum stock threshold that triggers low-stock monitoring.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('inventory.index') }}"
               class="btn btn-secondary">
                ← Back to Inventory
            </a>

            <a href="{{ route('inventory.low-stock') }}"
               class="btn btn-outline-primary">
                View Low Stock
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

    <form action="{{ route('inventory.reorder-level.store') }}"
          method="POST">

        @csrf

        {{-- Stock Location --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Stock Location</h2>
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
                            <option value="">
                                Select Warehouse
                            </option>

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
                            <option value="">
                                Select Product
                            </option>

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
                            <option value="">
                                No Variant
                            </option>

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

                        <label for="reorder_level" class="form-label">
                            Reorder Level
                        </label>

                        <input
                            type="number"
                            id="reorder_level"
                            name="reorder_level"
                            value="{{ old('reorder_level', 0) }}"
                            min="0"
                            step="0.0001"
                            class="@error('reorder_level') is-invalid @enderror"
                            placeholder="Enter minimum stock level"
                            required
                        >

                        <div style="font-size:12px; color:#64748b; margin-top:6px;">
                            Low-stock alerts will apply when current stock reaches or falls below this level.
                        </div>

                        @error('reorder_level')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="card">

            <div class="card-body">

                <div class="form-actions">

                    <button type="submit"
                            class="btn btn-primary">
                        Save Reorder Level
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