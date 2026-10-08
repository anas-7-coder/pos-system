{{-- @extends('layouts.app')

@section('title', 'Edit Stock Count')

@section('content')

<h1>Edit Stock Count</h1>

<hr>

<a href="{{ route('stock-counts.show', $stockCount) }}">Back to Stock Counts</a>

<hr>

<form action="{{ route('stock-counts.update', $stockCount) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Count Number</label><br>
        <input type="text" name="count_number" value="{{ old('count_number', $stockCount->count_number) }}">
        @error('count_number')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Warehouse</label><br>
        <select name="warehouse_id">

            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $stockCount->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>
    </div>
    <br>

    <div>
        <label>Count Date</label><br>
        <input type="date" name="count_date" value="{{ old('count_date', $stockCount->count_date->format('Y-m-d')) }}">
    </div>
    <br>

    <h2>Items</h2>

    <div id="items">
        @foreach($stockCount->items as $index => $item)
            <div class="item-row" style="margin-bottom:10px;">
                <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product_id }}">
                <input type="hidden" name="items[{{ $index }}][product_variant_id]" value="{{ $item->product_variant_id }}">
                
                <strong>{{ $item->product->name }}</strong>

                @if($item->productVariant)
                    - {{ $item->productVariant->name }}
                @endif

                <input type="number" name="items[{{ $index }}][counted_quantity]" value="{{ $item->counted_quantity }}" min="0" step="0.0001">

                <button type="button" onclick="removeItem(this)">
                    Remove
                </button>
            </div>
        @endforeach
    </div>
    <br>

    <div>
        <label>Notes</label><br>
        <textarea name="notes" rows="3">{{ old('notes', $stockCount->notes) }}</textarea>
    </div>
    <br>
<hr>
    <button type="submit">
        Update Stock Count
    </button>

</form>

<script>
    function removeItem(button)
    {
        const rows = document.querySelectorAll('.item-row');

        if (rows.length > 1) 
        {
            button.parentElement.remove();
        }
    }
</script>

@endsection --}}




@extends('layouts.app')

@section('title', 'Edit Stock Count')

@section('content')

    @php
        $oldItems = old('items');

        if ($oldItems === null) {
            $oldItems = $stockCount->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->productVariant?->name,
                    'counted_quantity' => $item->counted_quantity,
                ];
            })->values()->toArray();
        }
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Stock Count</h1>
            <p class="page-subtitle">
                Update the physical stock quantities recorded for this stock count.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('stock-counts.show', $stockCount) }}"
               class="btn btn-secondary">
                ← Back to Stock Count
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

    <form action="{{ route('stock-counts.update', $stockCount) }}"
          method="POST">

        @csrf
        @method('PUT')

        {{-- Count Information --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Count Information</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    <div class="form-group">

                        <label for="count_number" class="form-label">
                            Count Number
                        </label>

                        <input
                            type="text"
                            id="count_number"
                            name="count_number"
                            value="{{ old('count_number', $stockCount->count_number) }}"
                            class="@error('count_number') is-invalid @enderror"
                            required
                        >

                        @error('count_number')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

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
                                    {{ old('warehouse_id', $stockCount->warehouse_id) == $warehouse->id ? 'selected' : '' }}
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

                        <label for="count_date" class="form-label">
                            Count Date
                        </label>

                        <input
                            type="date"
                            id="count_date"
                            name="count_date"
                            value="{{ old('count_date', $stockCount->count_date->format('Y-m-d')) }}"
                            class="@error('count_date') is-invalid @enderror"
                            required
                        >

                        @error('count_date')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            Status
                        </label>

                        <div>
                            @php
                                $statusClass = match ($stockCount->status) {
                                    'draft' => 'badge-warning',
                                    'completed', 'posted' => 'badge-success',
                                    'cancelled' => 'badge-danger',
                                    default => 'badge-secondary',
                                };
                            @endphp

                            <span class="badge {{ $statusClass }}">
                                {{ ucwords(str_replace('_', ' ', $stockCount->status)) }}
                            </span>
                        </div>

                    </div>

                </div>

            </div>
        </div>

        {{-- Count Items --}}
        <div class="card">

            <div class="card-header">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:15px;">

                    <div>
                        <h2>Count Items</h2>

                        <p class="page-subtitle" style="margin-top:4px;">
                            Update the actual physical quantity counted for each product.
                        </p>
                    </div>

                </div>
            </div>

            <div class="card-body">

                <div id="items-container">

                    @forelse($oldItems as $index => $item)

                        <div class="item-card"
                             style="border:1px solid #e5e7eb; border-radius:10px; padding:20px; margin-bottom:16px;">

                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">

                                <h3 style="margin:0;">
                                    Item {{ $loop->iteration }}
                                </h3>

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    onclick="removeItem(this)"
                                >
                                    Remove
                                </button>

                            </div>

                            <input
                                type="hidden"
                                name="items[{{ $index }}][product_id]"
                                value="{{ $item['product_id'] ?? '' }}"
                            >

                            <input
                                type="hidden"
                                name="items[{{ $index }}][product_variant_id]"
                                value="{{ $item['product_variant_id'] ?? '' }}"
                            >

                            <div class="form-grid">

                                <div class="form-group">

                                    <label class="form-label">
                                        Product
                                    </label>

                                    <div class="detail-value"
                                         style="padding-top:10px;">
                                        <strong>
                                            {{ $item['product_name'] ?? 'Product' }}
                                        </strong>

                                        @if(!empty($item['product_variant_id']) && !empty($item['variant_name']))
                                            <div style="font-size:12px; color:#64748b; margin-top:4px;">
                                                Variant: {{ $item['variant_name'] }}
                                            </div>
                                        @endif
                                    </div>

                                    @error("items.$index.product_id")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group">

                                    <label class="form-label">
                                        Counted Quantity
                                    </label>

                                    <input
                                        type="number"
                                        name="items[{{ $index }}][counted_quantity]"
                                        value="{{ $item['counted_quantity'] ?? 0 }}"
                                        min="0"
                                        step="0.0001"
                                        class="@error("items.$index.counted_quantity") is-invalid @enderror"
                                        required
                                    >

                                    @error("items.$index.counted_quantity")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="table-empty">
                            No stock count items found.
                        </div>

                    @endforelse

                </div>

            </div>
        </div>

        {{-- Notes --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Notes</h2>
            </div>

            <div class="card-body">

                <div class="form-group">

                    <label for="notes" class="form-label">
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        class="@error('notes') is-invalid @enderror"
                        placeholder="Add notes about this stock count..."
                    >{{ old('notes', $stockCount->notes) }}</textarea>

                    @error('notes')
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

                    <a href="{{ route('stock-counts.show', $stockCount) }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Update Stock Count
                    </button>

                </div>

            </div>
        </div>

    </form>

    <script>
        function removeItem(button)
        {
            const container = document.getElementById('items-container');
            const rows = container.querySelectorAll('.item-card');

            if (rows.length <= 1) {
                return;
            }

            button.closest('.item-card').remove();

            renumberItems();
        }

        function renumberItems()
        {
            const rows = document.querySelectorAll('#items-container .item-card');

            rows.forEach((row, index) => {
                const title = row.querySelector('h3');

                if (title) {
                    title.textContent = `Item ${index + 1}`;
                }
            });
        }
    </script>

@endsection