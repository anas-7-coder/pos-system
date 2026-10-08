{{-- @extends('layouts.app')

@section('title', 'Create Stock Count')

@section('content')

<h1>Create Stock Count</h1>

<form action="{{ route('stock-counts.store') }}" method="POST">
    @csrf

    <div>
        <label>Count Number</label><br>
        <input type="text" name="count_number" value="{{ old('count_number') }}">
        @error('count_number')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

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
        <label>Count Date</label><br>
        <input type="date" name="count_date" value="{{ old('count_date', now()->format('Y-m-d')) }}">
        @error('count_date')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <h2>Items</h2>

    <div id="items">
        <div class="item-row" style="margin-bottom:10px;">
            <select name="items[0][product_id]">
                <option value="">Select Product</option>

                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} - {{ $product->sku }}
                    </option>
                @endforeach
            </select>

            <select name="items[0][product_variant_id]">
                <option value="">No Variant</option>

                @foreach($variants as $variant)
                    <option value="{{ $variant->id }}">
                        {{ $variant->product->name }} - {{ $variant->name }}
                    </option>
                @endforeach
            </select>

            <input type="number" name="items[0][counted_quantity]" value="0" min="0" step="0.0001" placeholder="Counted Quantity">

            <button type="button" onclick="removeItem(this)">
                Remove
            </button>
        </div>
    </div>
    <br>

    <button type="button" onclick="addItem()">
        Add Item
    </button>
    <br><br>

    <div>
        <label>Notes</label><br>
        <textarea name="notes" rows="3">{{ old('notes') }}</textarea>
    </div>
    <br>
<hr>
    <button type="submit">
        Create Stock Count
    </button>

</form>

<script>
    let itemIndex = 1;

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

            <input type="number" name="items[${itemIndex}][counted_quantity]" value="0" min="0" step="0.0001">

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

@section('title', 'Create Stock Count')

@section('content')

    @php
        $oldItems = old('items', [
            [
                'product_id' => '',
                'product_variant_id' => '',
                'counted_quantity' => 0,
            ],
        ]);
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Create Stock Count</h1>
            <p class="page-subtitle">
                Record the physical stock count for a warehouse and compare it with system inventory.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('stock-counts.index') }}"
               class="btn btn-secondary">
                ← Back to Stock Counts
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

    <form action="{{ route('stock-counts.store') }}"
          method="POST">

        @csrf

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
                            value="{{ old('count_number') }}"
                            class="@error('count_number') is-invalid @enderror"
                            placeholder="Enter count number"
                            required
                        >

                        @error('count_number')
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
                            value="{{ old('count_date', now()->format('Y-m-d')) }}"
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
                            Enter the actual physical quantity counted for each product.
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

                    @foreach($oldItems as $index => $item)

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
                                        Counted Quantity
                                    </label>

                                    <input
                                        type="number"
                                        name="items[{{ $index }}][counted_quantity]"
                                        value="{{ $item['counted_quantity'] ?? 0 }}"
                                        min="0"
                                        step="0.0001"
                                        class="@error("items.$index.counted_quantity") is-invalid @enderror"
                                        placeholder="Enter counted quantity"
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

                    @endforeach

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
                        placeholder="Add notes about the stock count..."
                    >{{ old('notes') }}</textarea>

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

                    <button type="submit"
                            class="btn btn-primary">
                        Create Stock Count
                    </button>

                    <a href="{{ route('stock-counts.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </div>
        </div>

    </form>

    <script>
        let itemIndex = {{ count($oldItems) }};

        const products = [
            @foreach($products as $product)
                {
                    id: @json($product->id),
                    name: @json($product->name),
                    sku: @json($product->sku)
                },
            @endforeach
        ];

        const variants = [
            @foreach($variants as $variant)
                {
                    id: @json($variant->id),
                    name: @json($variant->name),
                    product_name: @json($variant->product->name)
                },
            @endforeach
        ];

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
            input.name = `items[${itemIndex}][counted_quantity]`;
            input.value = '0';
            input.min = '0';
            input.step = '0.0001';
            input.placeholder = 'Enter counted quantity';
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
                    'Counted Quantity',
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