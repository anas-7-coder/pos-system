{{-- @extends('layouts.app')

@section('title', 'Edit Stock Transfer')

@section('content')

<h1>Edit Stock Transfer</h1>

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('stock-transfers.index', $stockTransfer) }}">Back to Stock Transfer</a>

<hr>

<form action="{{ route('stock-transfers.update', $stockTransfer) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Transfer Number</label><br>
        <input type="text" name="transfer_number" value="{{ old('transfer_number', $stockTransfer->transfer_number) }}">
        @error('transfer_number')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>From Warehouse</label><br>
        <select name="from_warehouse_id">

            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ old('from_warehouse_id', $stockTransfer->from_warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>
        @error('from_warehouse_id')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>To Warehouse</label><br>
        <select name="to_warehouse_id">

            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ old('to_warehouse_id', $stockTransfer->to_warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>
        @error('to_warehouse_id')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Transfer Date</label><br>
        <input type="date" name="transfer_date" value="{{ old('transfer_date', $stockTransfer->transfer_date->format('Y-m-d')) }}">
        @error('transfer_date')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <h2>Items</h2>
    <div id="items">

        @foreach($stockTransfer->items as $index => $item)
            <div class="item-row" style="margin-bottom:10px;">
                <select name="items[{{ $index }}][product_id]">

                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - {{ $product->sku }}
                        </option>
                    @endforeach
                </select>

                <select name="items[{{ $index }}][product_variant_id]">
                    <option value="">No Variant</option>

                    @foreach($variants as $variant)
                        <option value="{{ $variant->id }}" {{ $item->product_variant_id == $variant->id ? 'selected' : '' }}>
                            {{ $variant->product->name }} - {{ $variant->name }}
                        </option>
                    @endforeach
                </select>

                <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="0.0001" step="0.0001">

                <button type="button" onclick="removeItem(this)">
                    Remove
                </button>
            </div>
        @endforeach
    </div>
    <br>

    <button type="button" onclick="addItem()">
        Add Item
    </button>
    <br><br>

    <div>
        <label>Notes</label><br>
        <textarea name="notes" rows="3">{{ old('notes', $stockTransfer->notes) }}</textarea>
    </div>
    <br>
<hr>
    <button type="submit">
        Update Stock Transfer
    </button>
</form>

<script>
    let itemIndex = {{ $stockTransfer->items->count() }};

    function addItem()
    {
        const container = document.getElementById('items');
        const row       = document.createElement('div');

        row.classList.add('item-row');
        row.style.marginBottom = '10px';

        row.innerHTML = `
            <select name="items[${itemIndex}][product_id]">
                <option value="">Select Product</option>

                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} - {{ $product->sku }}
                    </option>
                @endforeach
            </select>

            <select name="items[${itemIndex}][product_variant_id]">
                <option value="">No Variant</option>

                @foreach($variants as $variant)
                    <option value="{{ $variant->id }}">
                        {{ $variant->product->name }} - {{ $variant->name }}
                    </option>
                @endforeach
            </select>

            <input type="number" name="items[${itemIndex}][quantity]" min="0.0001" step="0.0001">

            <button type="button" onclick="removeItem(this)">
                Remove
            </button>
        `;

        container.appendChild(row);

        itemIndex++;
    }

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

@section('title', 'Edit Stock Transfer')

@section('content')

    @php
        $oldItems = old('items');

        if ($oldItems === null) {
            $oldItems = $stockTransfer->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                ];
            })->values()->toArray();
        }
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Stock Transfer</h1>
            <p class="page-subtitle">
                Update the transfer details and inventory items.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('stock-transfers.index') }}"
               class="btn btn-secondary">
                ← Back to Stock Transfers
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

    <form action="{{ route('stock-transfers.update', $stockTransfer) }}"
          method="POST">

        @csrf
        @method('PUT')

        {{-- Transfer Information --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Transfer Information</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    <div class="form-group">

                        <label for="transfer_number" class="form-label">
                            Transfer Number
                        </label>

                        <input
                            type="text"
                            id="transfer_number"
                            name="transfer_number"
                            value="{{ old('transfer_number', $stockTransfer->transfer_number) }}"
                            class="@error('transfer_number') is-invalid @enderror"
                            required
                        >

                        @error('transfer_number')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="transfer_date" class="form-label">
                            Transfer Date
                        </label>

                        <input
                            type="date"
                            id="transfer_date"
                            name="transfer_date"
                            value="{{ old('transfer_date', $stockTransfer->transfer_date->format('Y-m-d')) }}"
                            class="@error('transfer_date') is-invalid @enderror"
                            required
                        >

                        @error('transfer_date')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="from_warehouse_id" class="form-label">
                            From Warehouse
                        </label>

                        <select
                            id="from_warehouse_id"
                            name="from_warehouse_id"
                            class="@error('from_warehouse_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Source Warehouse
                            </option>

                            @foreach($warehouses as $warehouse)
                                <option
                                    value="{{ $warehouse->id }}"
                                    {{ old('from_warehouse_id', $stockTransfer->from_warehouse_id) == $warehouse->id ? 'selected' : '' }}
                                >
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('from_warehouse_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="to_warehouse_id" class="form-label">
                            To Warehouse
                        </label>

                        <select
                            id="to_warehouse_id"
                            name="to_warehouse_id"
                            class="@error('to_warehouse_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Destination Warehouse
                            </option>

                            @foreach($warehouses as $warehouse)
                                <option
                                    value="{{ $warehouse->id }}"
                                    {{ old('to_warehouse_id', $stockTransfer->to_warehouse_id) == $warehouse->id ? 'selected' : '' }}
                                >
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('to_warehouse_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>
        </div>

        {{-- Transfer Items --}}
        <div class="card">

            <div class="card-header">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:15px;">
                    <div>
                        <h2>Transfer Items</h2>
                        <p class="page-subtitle" style="margin-top:4px;">
                            Update the products, variants, and quantities included in this transfer.
                        </p>
                    </div>

                    <button type="button"
                            class="btn btn-primary"
                            onclick="addItem()">
                        + Add Item
                    </button>
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

                            <div class="form-grid">

                                <div class="form-group">

                                    <label class="form-label">
                                        Product
                                    </label>

                                    <select
                                        name="items[{{ $index }}][product_id]"
                                        required
                                    >
                                        <option value="">
                                            Select Product
                                        </option>

                                        @foreach($products as $product)
                                            <option
                                                value="{{ $product->id }}"
                                                {{ (string) ($item['product_id'] ?? '') === (string) $product->id ? 'selected' : '' }}
                                            >
                                                {{ $product->name }} — {{ $product->sku }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error("items.$index.product_id")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group">

                                    <label class="form-label">
                                        Variant
                                    </label>

                                    <select
                                        name="items[{{ $index }}][product_variant_id]"
                                    >
                                        <option value="">
                                            No Variant
                                        </option>

                                        @foreach($variants as $variant)
                                            <option
                                                value="{{ $variant->id }}"
                                                {{ (string) ($item['product_variant_id'] ?? '') === (string) $variant->id ? 'selected' : '' }}
                                            >
                                                {{ $variant->product->name }} — {{ $variant->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error("items.$index.product_variant_id")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group">

                                    <label class="form-label">
                                        Quantity
                                    </label>

                                    <input
                                        type="number"
                                        name="items[{{ $index }}][quantity]"
                                        value="{{ $item['quantity'] ?? '' }}"
                                        min="0.0001"
                                        step="0.0001"
                                        class="@error("items.$index.quantity") is-invalid @enderror"
                                        placeholder="Enter quantity"
                                        required
                                    >

                                    @error("items.$index.quantity")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="table-empty">
                            No transfer items found.
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
                        placeholder="Add transfer notes or additional details..."
                    >{{ old('notes', $stockTransfer->notes) }}</textarea>

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

                    <a href="{{ route('stock-transfers.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Update Stock Transfer
                    </button>

                </div>

            </div>
        </div>

    </form>

    <script>
        let itemIndex = {{ count($oldItems) }};

        const products = {!! $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
            ];
        })->values()->toJson(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};

        const variants = {!! $variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'product_name' => $variant->product->name,
            ];
        })->values()->toJson(JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!};

        function createOption(value, text)
        {
            const option = document.createElement('option');

            option.value = value;
            option.textContent = text;

            return option;
        }

        function createProductSelect()
        {
            const select = document.createElement('select');

            select.name = `items[${itemIndex}][product_id]`;
            select.required = true;

            select.appendChild(
                createOption('', 'Select Product')
            );

            products.forEach(product => {
                select.appendChild(
                    createOption(
                        product.id,
                        `${product.name} — ${product.sku}`
                    )
                );
            });

            return select;
        }

        function createVariantSelect()
        {
            const select = document.createElement('select');

            select.name = `items[${itemIndex}][product_variant_id]`;

            select.appendChild(
                createOption('', 'No Variant')
            );

            variants.forEach(variant => {
                select.appendChild(
                    createOption(
                        variant.id,
                        `${variant.product_name} — ${variant.name}`
                    )
                );
            });

            return select;
        }

        function createQuantityInput()
        {
            const input = document.createElement('input');

            input.type = 'number';
            input.name = `items[${itemIndex}][quantity]`;
            input.min = '0.0001';
            input.step = '0.0001';
            input.placeholder = 'Enter quantity';
            input.required = true;

            return input;
        }

        function createFormGroup(labelText, element)
        {
            const group = document.createElement('div');

            group.className = 'form-group';

            const label = document.createElement('label');

            label.className = 'form-label';
            label.textContent = labelText;

            group.appendChild(label);
            group.appendChild(element);

            return group;
        }

        function addItem()
        {
            const container = document.getElementById('items-container');

            const card = document.createElement('div');

            card.className = 'item-card';
            card.style.border = '1px solid #e5e7eb';
            card.style.borderRadius = '10px';
            card.style.padding = '20px';
            card.style.marginBottom = '16px';

            const header = document.createElement('div');

            header.style.display = 'flex';
            header.style.justifyContent = 'space-between';
            header.style.alignItems = 'center';
            header.style.marginBottom = '18px';

            const title = document.createElement('h3');

            title.style.margin = '0';
            title.textContent = `Item ${container.children.length + 1}`;

            const removeButton = document.createElement('button');

            removeButton.type = 'button';
            removeButton.className = 'btn btn-danger btn-sm';
            removeButton.textContent = 'Remove';

            removeButton.addEventListener('click', function () {
                removeItem(removeButton);
            });

            header.appendChild(title);
            header.appendChild(removeButton);

            const grid = document.createElement('div');

            grid.className = 'form-grid';

            grid.appendChild(
                createFormGroup(
                    'Product',
                    createProductSelect()
                )
            );

            grid.appendChild(
                createFormGroup(
                    'Variant',
                    createVariantSelect()
                )
            );

            grid.appendChild(
                createFormGroup(
                    'Quantity',
                    createQuantityInput()
                )
            );

            card.appendChild(header);
            card.appendChild(grid);

            container.appendChild(card);

            itemIndex++;

            renumberItems();
        }

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