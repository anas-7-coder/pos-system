{{-- @extends('layouts.app')

@section('title', 'Edit Sale')

@section('content')

<h1>Edit Sale</h1>

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('sales.index', $sale) }}">Back to Sale</a>

<hr>

<form action="{{ route('sales.update', $sale) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Invoice Number</label><br>
        <input type="text" name="invoice_number" value="{{ old('invoice_number', $sale->invoice_number) }}">
    </div>
    <br>

    <div>
        <label>Customer</label><br>
        <select name="customer_id">
            <option value="">Walk-in Customer</option>

            @foreach($customers as $customer)
                <option value="{{ $customer->id }}" {{ old('customer_id', $sale->customer_id) == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }} - {{ $customer->code }}
                </option>
            @endforeach
        </select>
    </div>
    <br>

    <div>
        <label>Register Session</label><br>
        <select name="register_session_id">

            @foreach($sessions as $session)
                <option value="{{ $session->id }}" {{ old('register_session_id', $sale->register_session_id) == $session->id ? 'selected' : '' }}>
                    Session #{{ $session->id }} - {{ $session->register->name }}
                </option>
            @endforeach
        </select>
    </div>
    <br>

    <div>
        <label>Warehouse</label><br>
        <select name="warehouse_id">

            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $sale->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                </option>
            @endforeach
        </select>
    </div>
    <br>

    <div>
        <label>Sale Date</label><br>
        <input type="datetime-local" name="sale_date" value="{{ old('sale_date', $sale->sale_date->format('Y-m-d\TH:i')) }}">
    </div>
    <br>

    <h2>Items</h2>

    <div id="items">
        @foreach($sale->items as $index => $item)
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
                <input type="number" name="items[{{ $index }}][discount_amount]" value="{{ $item->discount_amount }}" min="0" step="0.01">

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
        <textarea name="notes" rows="3">{{ old('notes', $sale->notes) }}</textarea>
    </div>
    <br>
<hr>
    <button type="submit">
        Update Sale
    </button>

</form>

<script>
    let itemIndex = {{ $sale->items->count() }};

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
            <input type="number" name="items[${itemIndex}][discount_amount]" value="0" min="0" step="0.01">

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

@section('title', 'Edit Sale')

@section('content')

    @php
        $oldItems = old('items');

        if ($oldItems === null) {
            $oldItems = $sale->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'discount_amount' => $item->discount_amount,
                ];
            })->values()->toArray();
        }

        $statusClass = match ($sale->status) {
            'draft' => 'badge-warning',
            'completed' => 'badge-success',
            'partially_refunded' => 'badge-info',
            'refunded' => 'badge-secondary',
            'cancelled' => 'badge-danger',
            default => 'badge-secondary',
        };
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Sale</h1>
            <p class="page-subtitle">
                Update the sale details and line items while the sale is editable.
            </p>
        </div>

        <div class="page-actions">

            <a href="{{ route('sales.index') }}"
               class="btn btn-secondary">
                ← Back to Sales
            </a>

            <a href="{{ route('sales.show', $sale) }}"
               class="btn btn-outline-primary">
                View Sale
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

    {{-- Sale Information --}}
    <div class="card form-card">

        <div class="card-header">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:15px;">
                <div>
                    <h2>Sale Information</h2>
                </div>

                <span class="badge {{ $statusClass }}">
                    {{ ucwords(str_replace('_', ' ', $sale->status)) }}
                </span>
            </div>
        </div>

        <div class="card-body">

            <div class="form-grid">

                <div class="form-group">
                    <label for="invoice_number" class="form-label">
                        Invoice Number
                    </label>

                    <input
                        type="text"
                        id="invoice_number"
                        name="invoice_number"
                        form="sale-form"
                        value="{{ old('invoice_number', $sale->invoice_number) }}"
                        class="@error('invoice_number') is-invalid @enderror"
                        required
                    >

                    @error('invoice_number')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="customer_id" class="form-label">
                        Customer
                    </label>

                    <select
                        id="customer_id"
                        name="customer_id"
                        form="sale-form"
                        class="@error('customer_id') is-invalid @enderror"
                    >
                        <option value="">
                            Walk-in Customer
                        </option>

                        @foreach($customers as $customer)
                            <option
                                value="{{ $customer->id }}"
                                {{ old('customer_id', $sale->customer_id) == $customer->id ? 'selected' : '' }}
                            >
                                {{ $customer->name }} — {{ $customer->code }}
                            </option>
                        @endforeach
                    </select>

                    @error('customer_id')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="register_session_id" class="form-label">
                        Register Session
                    </label>

                    <select
                        id="register_session_id"
                        name="register_session_id"
                        form="sale-form"
                        class="@error('register_session_id') is-invalid @enderror"
                        required
                    >
                        <option value="">
                            Select Open Register Session
                        </option>

                        @foreach($sessions as $session)
                            <option
                                value="{{ $session->id }}"
                                {{ old('register_session_id', $sale->register_session_id) == $session->id ? 'selected' : '' }}
                            >
                                Session #{{ $session->id }} — {{ $session->register->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('register_session_id')
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
                        form="sale-form"
                        class="@error('warehouse_id') is-invalid @enderror"
                        required
                    >
                        <option value="">
                            Select Warehouse
                        </option>

                        @foreach($warehouses as $warehouse)
                            <option
                                value="{{ $warehouse->id }}"
                                {{ old('warehouse_id', $sale->warehouse_id) == $warehouse->id ? 'selected' : '' }}
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
                    <label for="sale_date" class="form-label">
                        Sale Date
                    </label>

                    <input
                        type="datetime-local"
                        id="sale_date"
                        name="sale_date"
                        form="sale-form"
                        value="{{ old('sale_date', $sale->sale_date->format('Y-m-d\TH:i')) }}"
                        class="@error('sale_date') is-invalid @enderror"
                        required
                    >

                    @error('sale_date')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>

        </div>
    </div>

    {{-- Sale Items --}}
    <div class="card">

        <div class="card-header">

            <div style="display:flex; align-items:center; justify-content:space-between; gap:15px;">

                <div>
                    <h2>Sale Items</h2>

                    <p class="page-subtitle" style="margin-top:4px;">
                        Update products, quantities, and discounts for this sale.
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

            <form id="sale-form"
                  action="{{ route('sales.update', $sale) }}"
                  method="POST">

                @csrf
                @method('PUT')

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
                                        Quantity
                                    </label>

                                    <input
                                        type="number"
                                        name="items[{{ $index }}][quantity]"
                                        value="{{ $item['quantity'] ?? '' }}"
                                        min="0.0001"
                                        step="0.0001"
                                        class="@error("items.$index.quantity") is-invalid @enderror"
                                        required
                                    >

                                    @error("items.$index.quantity")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="form-group">

                                    <label class="form-label">
                                        Discount
                                    </label>

                                    <input
                                        type="number"
                                        name="items[{{ $index }}][discount_amount]"
                                        value="{{ $item['discount_amount'] ?? 0 }}"
                                        min="0"
                                        step="0.01"
                                        class="@error("items.$index.discount_amount") is-invalid @enderror"
                                    >

                                    @error("items.$index.discount_amount")
                                        <div class="field-error">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- Notes --}}
                <div class="card form-card" style="margin-top:20px;">

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
                                placeholder="Add any notes for this sale..."
                            >{{ old('notes', $sale->notes) }}</textarea>

                            @error('notes')
                                <div class="field-error">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>
                </div>

                {{-- Actions --}}
                <div class="card" style="margin-top:20px;">

                    <div class="card-body">

                        <div class="form-actions">

                            <a href="{{ route('sales.show', $sale) }}"
                               class="btn btn-secondary">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary">
                                Update Sale
                            </button>

                        </div>

                    </div>
                </div>

            </form>

        </div>
    </div>

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

        function createNumberInput(name, min, step, value = '')
        {
            const input = document.createElement('input');

            input.type = 'number';
            input.name = `items[${itemIndex}][${name}]`;
            input.min = min;
            input.step = step;
            input.value = value;

            if (name === 'quantity') {
                input.required = true;
            }

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
                    createNumberInput(
                        'quantity',
                        '0.0001',
                        '0.0001'
                    )
                )
            );

            grid.appendChild(
                createFormGroup(
                    'Discount',
                    createNumberInput(
                        'discount_amount',
                        '0',
                        '0.01',
                        '0'
                    )
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