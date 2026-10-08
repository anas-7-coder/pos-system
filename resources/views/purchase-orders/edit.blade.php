{{-- @extends('layouts.app')

@section('title', 'Edit Purchase Order')

@section('content')

<h1>Edit Purchase Order</h1>

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('purchase-orders.index', $purchaseOrder) }}">Back to Purchase Orders</a>

<hr>

<form action="{{ route('purchase-orders.update', $purchaseOrder) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>PO Number</label><br>
        <input type="text" name="po_number" value="{{ old('po_number', $purchaseOrder->po_number) }}">
        @error('po_number')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Supplier</label><br>
        <select name="supplier_id">
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}" {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>
        @error('supplier_id')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Branch</label><br>
        <select name="branch_id">
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ old('branch_id', $purchaseOrder->branch_id) == $branch->id ? 'selected' : '' }}>
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
        <label>Warehouse</label><br>
        <select name="warehouse_id">
            @foreach($warehouses as $warehouse)
                <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $purchaseOrder->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                    {{ $warehouse->name }}
                    - {{ $warehouse->branch->name }}
                </option>
            @endforeach
        </select>
        @error('warehouse_id')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Order Date</label><br>
        <input type="date" name="order_date" value="{{ old('order_date', $purchaseOrder->order_date->format('Y-m-d')) }}">
        @error('order_date')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Expected Date</label><br>
        <input type="date" name="expected_date" value="{{ old('expected_date', $purchaseOrder->expected_date?->format('Y-m-d')) }}">
        @error('expected_date')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <input type="hidden" name="status" value="{{ $purchaseOrder->status }}">

    <h2>Items</h2>

    <div id="items">
        @foreach($purchaseOrder->items as $index => $item)
            <div class="item-row" style="margin-bottom:10px;">
                <select name="items[{{ $index }}][product_id]">
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                            - {{ $product->sku }}
                        </option>
                    @endforeach
                </select>
                <select name="items[{{ $index }}][product_variant_id]">
                    <option value=""> No Variant</option>
                    @foreach($variants as $variant)
                        <option value="{{ $variant->id }}" {{ $item->product_variant_id == $variant->id ? 'selected' : '' }}>
                            {{ $variant->product->name }}
                            - {{ $variant->name }}
                        </option>
                    @endforeach
                </select>

                <input type="number" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}" min="0.0001" step="0.0001">
                <input type="number" name="items[{{ $index }}][unit_cost]" value="{{ $item->unit_cost }}" min="0" step="0.01">
                <input type="number" name="items[{{ $index }}][discount_amount]" value="{{ $item->discount_amount }}" min="0" step="0.01">
                <input type="number" name="items[{{ $index }}][tax_amount]" value="{{ $item->tax_amount }}" min="0" step="0.01">

                <button type="button" onclick="removeItem(this)">
                    Remove
                </button>
            </div>
        @endforeach
    </div>

    <button type="button" onclick="addItem()">
        Add Item
    </button>
    <br><br>

    <div>
        <label>Notes</label><br>
        <textarea name="notes" rows="3">{{ old('notes', $purchaseOrder->notes) }}</textarea>
        @error('notes')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Update Purchase Order
    </button>

</form>

<script>
    let itemIndex = {{ $purchaseOrder->items->count() }};

    function addItem()
    {
        const container = document.getElementById('items');
        const row = document.createElement('div');
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
                        {{ $variant->product->name }}
                        - {{ $variant->name }}
                    </option>
                @endforeach
            </select>

            <input type="number" name="items[${itemIndex}][quantity]" min="0.0001" step="0.0001">
            <input type="number" name="items[${itemIndex}][unit_cost]" min="0" step="0.01">
            <input type="number" name="items[${itemIndex}][discount_amount]" value="0" min="0" step="0.01">
            <input type="number" name="items[${itemIndex}][tax_amount]" value="0" min="0" step="0.01">

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

        if (rows.length > 1) {
            button.parentElement.remove();
        }
    }
</script>

@endsection --}}









@extends('layouts.app')

@section('title', 'Edit Purchase Order')

@section('content')

    @php
        $oldItems = old('items');

        if ($oldItems === null) {
            $oldItems = $purchaseOrder->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_variant_id' => $item->product_variant_id,
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->unit_cost,
                    'discount_amount' => $item->discount_amount,
                    'tax_amount' => $item->tax_amount,
                ];
            })->values()->toArray();
        }
    @endphp

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Purchase Order</h1>
            <p class="page-subtitle">
                Update the purchase order while it is still in draft status.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                ← Back to Purchase Orders
            </a>

            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
               class="btn btn-outline-primary">
                View Purchase Order
            </a>
        </div>
    </div>

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

    <form action="{{ route('purchase-orders.update', $purchaseOrder) }}"
          method="POST">

        @csrf
        @method('PUT')

        {{-- Purchase Order Information --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Purchase Order Information</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    <div class="form-group">
                        <label for="po_number" class="form-label">
                            PO Number
                        </label>

                        <input
                            type="text"
                            id="po_number"
                            name="po_number"
                            value="{{ old('po_number', $purchaseOrder->po_number) }}"
                            class="@error('po_number') is-invalid @enderror"
                            required
                        >

                        @error('po_number')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Status
                        </label>

                        <div>
                            @php
                                $statusClass = match ($purchaseOrder->status) {
                                    'draft' => 'badge-warning',
                                    'submitted' => 'badge-info',
                                    'approved' => 'badge-success',
                                    'goods_received' => 'badge-primary',
                                    'closed' => 'badge-success',
                                    'cancelled' => 'badge-danger',
                                    default => 'badge-secondary',
                                };
                            @endphp

                            <span class="badge {{ $statusClass }}">
                                {{ ucwords(str_replace('_', ' ', $purchaseOrder->status)) }}
                            </span>
                        </div>

                        <input type="hidden"
                               name="status"
                               value="{{ $purchaseOrder->status }}">
                    </div>

                    <div class="form-group">
                        <label for="supplier_id" class="form-label">
                            Supplier
                        </label>

                        <select
                            id="supplier_id"
                            name="supplier_id"
                            class="@error('supplier_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Supplier</option>

                            @foreach($suppliers as $supplier)
                                <option
                                    value="{{ $supplier->id }}"
                                    {{ old('supplier_id', $purchaseOrder->supplier_id) == $supplier->id ? 'selected' : '' }}
                                >
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('supplier_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="branch_id" class="form-label">
                            Branch
                        </label>

                        <select
                            id="branch_id"
                            name="branch_id"
                            class="@error('branch_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Branch</option>

                            @foreach($branches as $branch)
                                <option
                                    value="{{ $branch->id }}"
                                    {{ old('branch_id', $purchaseOrder->branch_id) == $branch->id ? 'selected' : '' }}
                                >
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('branch_id')
                            <div class="field-error">{{ $message }}</div>
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
                            <option value="">Select Warehouse</option>

                            @foreach($warehouses as $warehouse)
                                <option
                                    value="{{ $warehouse->id }}"
                                    {{ old('warehouse_id', $purchaseOrder->warehouse_id) == $warehouse->id ? 'selected' : '' }}
                                >
                                    {{ $warehouse->name }}
                                    — {{ $warehouse->branch->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('warehouse_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="order_date" class="form-label">
                            Order Date
                        </label>

                        <input
                            type="date"
                            id="order_date"
                            name="order_date"
                            value="{{ old('order_date', $purchaseOrder->order_date->format('Y-m-d')) }}"
                            class="@error('order_date') is-invalid @enderror"
                            required
                        >

                        @error('order_date')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="expected_date" class="form-label">
                            Expected Date
                        </label>

                        <input
                            type="date"
                            id="expected_date"
                            name="expected_date"
                            value="{{ old('expected_date', $purchaseOrder->expected_date?->format('Y-m-d')) }}"
                            class="@error('expected_date') is-invalid @enderror"
                        >

                        @error('expected_date')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

            </div>
        </div>

        {{-- Items --}}
        <div class="card">

            <div class="card-header">
                <div style="display:flex; align-items:center; justify-content:space-between; gap:15px;">
                    <div>
                        <h2>Purchase Order Items</h2>
                        <p class="page-subtitle" style="margin-top:4px;">
                            Update products, quantities, costs, discounts, and taxes.
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
                                        <option value="">Select Product</option>

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
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Variant
                                    </label>

                                    <select
                                        name="items[{{ $index }}][product_variant_id]"
                                    >
                                        <option value="">No Variant</option>

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
                                        <div class="field-error">{{ $message }}</div>
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
                                        required
                                    >

                                    @error("items.$index.quantity")
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Unit Cost
                                    </label>

                                    <input
                                        type="number"
                                        name="items[{{ $index }}][unit_cost]"
                                        value="{{ $item['unit_cost'] ?? '' }}"
                                        min="0"
                                        step="0.01"
                                        required
                                    >

                                    @error("items.$index.unit_cost")
                                        <div class="field-error">{{ $message }}</div>
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
                                    >

                                    @error("items.$index.discount_amount")
                                        <div class="field-error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">
                                        Tax
                                    </label>

                                    <input
                                        type="number"
                                        name="items[{{ $index }}][tax_amount]"
                                        value="{{ $item['tax_amount'] ?? 0 }}"
                                        min="0"
                                        step="0.01"
                                    >

                                    @error("items.$index.tax_amount")
                                        <div class="field-error">{{ $message }}</div>
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
                        placeholder="Add any additional notes..."
                    >{{ old('notes', $purchaseOrder->notes) }}</textarea>

                    @error('notes')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="card">

            <div class="card-body">

                <div class="form-actions">

                    <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Update Purchase Order
                    </button>

                </div>

            </div>
        </div>

    </form>

    <script>
        let itemIndex = {{ count($oldItems) }};

        const products = @json(
            $products->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
            ])->values()
        );

        const variants = @json(
            $variants->map(fn ($variant) => [
                'id' => $variant->id,
                'name' => $variant->name,
                'product_name' => $variant->product->name,
            ])->values()
        );

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

        function createFormGroup(labelText, inputElement)
        {
            const group = document.createElement('div');
            group.className = 'form-group';

            const label = document.createElement('label');
            label.className = 'form-label';
            label.textContent = labelText;

            group.appendChild(label);
            group.appendChild(inputElement);

            return group;
        }

        function createNumberInput(name, min, step, value = '')
        {
            const input = document.createElement('input');

            input.type = 'number';
            input.name = `items[${itemIndex}][${name}]`;
            input.min = min;
            input.step = step;
            input.value = value;

            if (name === 'quantity' || name === 'unit_cost') {
                input.required = true;
            }

            return input;
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
                createFormGroup('Product', createProductSelect())
            );

            grid.appendChild(
                createFormGroup('Variant', createVariantSelect())
            );

            grid.appendChild(
                createFormGroup(
                    'Quantity',
                    createNumberInput('quantity', '0.0001', '0.0001')
                )
            );

            grid.appendChild(
                createFormGroup(
                    'Unit Cost',
                    createNumberInput('unit_cost', '0', '0.01')
                )
            );

            grid.appendChild(
                createFormGroup(
                    'Discount',
                    createNumberInput('discount_amount', '0', '0.01', '0')
                )
            );

            grid.appendChild(
                createFormGroup(
                    'Tax',
                    createNumberInput('tax_amount', '0', '0.01', '0')
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