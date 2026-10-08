{{-- @extends('layouts.app')

@section('title', 'Create Purchase Order')

@section('content')

<h1>Create Purchase Order</h1>

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<hr>

<form action="{{ route('purchase-orders.store') }}" method="POST">
    @csrf

    <div>
        <label>PO Number</label><br>
        <input type="text" name="po_number" value="{{ old('po_number') }}">
        @error('po_number')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Supplier</label><br>
        <select name="supplier_id">
            <option value="">Select Supplier</option>
            @foreach($suppliers as $supplier)

                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
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
            <option value="">Select Branch</option>
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
        <label>Warehouse</label><br>
        <select name="warehouse_id">
            <option value="">Select Warehouse</option>
            @foreach($warehouses as $warehouse)

                <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
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
        <input type="date" name="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}">
        @error('order_date')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Expected Date</label><br>
        <input type="date" name="expected_date" value="{{ old('expected_date') }}">
        @error('expected_date')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <input type="hidden" name="status" value="draft">

    <h2>Items</h2>

    <div id="items">
        <div class="item-row">
            <label>Product</label>
            <select name="items[0][product_id]">
                <option value="">Select Product</option>
                @foreach($products as $product)

                    <option value="{{ $product->id }}">
                        {{ $product->name }}
                        - {{ $product->sku }}
                    </option>
                @endforeach
            </select>
                &nbsp;
            <label>Variant</label>
            <select name="items[0][product_variant_id]">
                <option value="">No Variant</option>
                @foreach($variants as $variant)

                    <option value="{{ $variant->id }}">
                        {{ $variant->product->name }}
                        - {{ $variant->name }}
                    </option>
                @endforeach
            </select>
                &nbsp;
            <label>Quantity</label>
            <input type="number" name="items[0][quantity]" min="0.0001" step="0.0001">
                &nbsp;
            <label>Unit Cost</label>
            <input type="number" name="items[0][unit_cost]" min="0" step="0.01">
                &nbsp;
            <label>Discount</label>
            <input type="number" name="items[0][discount_amount]" value="0" min="0" step="0.01">
                &nbsp;
            <label>Tax</label>
            <input type="number" name="items[0][tax_amount]" value="0" min="0" step="0.01">
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
        @error('notes')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Create Purchase Order
    </button>

</form>

<script>
    let itemIndex = 1;

    function addItem()
    {
        const container = document.getElementById('items');
        const row = document.createElement('div');
        row.classList.add('item-row');
        row.style.marginTop = '10px';
        row.innerHTML = `
            <label>Product</label>
            <select name="items[${itemIndex}][product_id]">
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} - {{ $product->sku }}
                    </option>
                @endforeach
            </select>
                &nbsp;
            <label>Variant</label>
            <select name="items[${itemIndex}][product_variant_id]">
                <option value="">No Variant</option>
                @foreach($variants as $variant)
                    <option value="{{ $variant->id }}">
                        {{ $variant->product->name }} - {{ $variant->name }}
                    </option>
                @endforeach
            </select>
                &nbsp;
            <label>Quantity</label>
            <input type="number" name="items[${itemIndex}][quantity]" min="0.0001" step="0.0001">
                &nbsp;
            <label>Unit Cost</label>
            <input type="number" name="items[${itemIndex}][unit_cost]" min="0" step="0.01">
                &nbsp;
            <label>Discount</label>
            <input type="number" name="items[${itemIndex}][discount_amount]" value="0" min="0" step="0.01">
                &nbsp;
            <label>Tax</label>
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

@section('title', 'Create Purchase Order')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Create Purchase Order</h1>
            <p class="page-subtitle">
                Create a purchase order, select the supplier and warehouse, and add the products to be ordered.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('purchase-orders.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Purchase Orders
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

    @php
        $oldItems = old('items', [
            [
                'product_id' => '',
                'product_variant_id' => '',
                'quantity' => '',
                'unit_cost' => '',
                'discount_amount' => 0,
                'tax_amount' => 0,
            ]
        ]);
    @endphp

    <form
        action="{{ route('purchase-orders.store') }}"
        method="POST"
    >
        @csrf

        {{-- Purchase Order Information --}}
        <div class="card form-card">

            <div class="card-header">
                <div>
                    <h2 class="card-title">Purchase Order Information</h2>
                    <p class="card-subtitle">
                        Enter the basic purchasing and delivery information.
                    </p>
                </div>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    {{-- PO Number --}}
                    <div class="form-group">

                        <label for="po_number" class="form-label">
                            PO Number
                        </label>

                        <input
                            type="text"
                            id="po_number"
                            name="po_number"
                            class="form-control @error('po_number') is-invalid @enderror"
                            value="{{ old('po_number') }}"
                            placeholder="Purchase order number"
                        >

                        @error('po_number')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Supplier --}}
                    <div class="form-group">

                        <label for="supplier_id" class="form-label">
                            Supplier
                        </label>

                        <select
                            id="supplier_id"
                            name="supplier_id"
                            class="form-control @error('supplier_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Supplier
                            </option>

                            @foreach($suppliers as $supplier)
                                <option
                                    value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}
                                >
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('supplier_id')
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
                            required
                        >
                            <option value="">
                                Select Branch
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

                    {{-- Warehouse --}}
                    <div class="form-group">

                        <label for="warehouse_id" class="form-label">
                            Warehouse
                        </label>

                        <select
                            id="warehouse_id"
                            name="warehouse_id"
                            class="form-control @error('warehouse_id') is-invalid @enderror"
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
                                    - {{ $warehouse->branch->name ?? '-' }}
                                </option>
                            @endforeach
                        </select>

                        @error('warehouse_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Order Date --}}
                    <div class="form-group">

                        <label for="order_date" class="form-label">
                            Order Date
                        </label>

                        <input
                            type="date"
                            id="order_date"
                            name="order_date"
                            class="form-control @error('order_date') is-invalid @enderror"
                            value="{{ old('order_date', now()->format('Y-m-d')) }}"
                            required
                        >

                        @error('order_date')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Expected Date --}}
                    <div class="form-group">

                        <label for="expected_date" class="form-label">
                            Expected Date
                        </label>

                        <input
                            type="date"
                            id="expected_date"
                            name="expected_date"
                            class="form-control @error('expected_date') is-invalid @enderror"
                            value="{{ old('expected_date') }}"
                        >

                        @error('expected_date')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <input
                    type="hidden"
                    name="status"
                    value="draft"
                >

            </div>

        </div>


        {{-- Purchase Order Items --}}
        <div class="card" style="margin-top: 24px;">

            <div class="card-header">

                <div>
                    <h2 class="card-title">Purchase Order Items</h2>
                    <p class="card-subtitle">
                        Add the products, quantities, costs, discounts, and taxes.
                    </p>
                </div>

                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    onclick="addItem()"
                >
                    + Add Item
                </button>

            </div>

            <div class="card-body">

                <div id="items-container">

                    @foreach($oldItems as $index => $item)

                        <div class="card purchase-item-card item-row" style="margin-bottom: 18px;">

                            <div class="card-header">

                                <div>
                                    <h3 class="card-title">
                                        Item #{{ $index + 1 }}
                                    </h3>
                                </div>

                                @if(count($oldItems) > 1)
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="removeItem(this)"
                                    >
                                        Remove
                                    </button>
                                @endif

                            </div>

                            <div class="card-body">

                                <div class="form-grid">

                                    {{-- Product --}}
                                    <div class="form-group">

                                        <label class="form-label">
                                            Product
                                        </label>

                                        <select
                                            name="items[{{ $index }}][product_id]"
                                            class="form-control @error('items.' . $index . '.product_id') is-invalid @enderror"
                                            required
                                        >
                                            <option value="">
                                                Select Product
                                            </option>

                                            @foreach($products as $product)
                                                <option
                                                    value="{{ $product->id }}"
                                                    {{ ($item['product_id'] ?? '') == $product->id ? 'selected' : '' }}
                                                >
                                                    {{ $product->name }} - {{ $product->sku }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('items.' . $index . '.product_id')
                                            <div class="field-error">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    {{-- Variant --}}
                                    <div class="form-group">

                                        <label class="form-label">
                                            Variant
                                        </label>

                                        <select
                                            name="items[{{ $index }}][product_variant_id]"
                                            class="form-control @error('items.' . $index . '.product_variant_id') is-invalid @enderror"
                                        >
                                            <option value="">
                                                No Variant
                                            </option>

                                            @foreach($variants as $variant)
                                                <option
                                                    value="{{ $variant->id }}"
                                                    {{ ($item['product_variant_id'] ?? '') == $variant->id ? 'selected' : '' }}
                                                >
                                                    {{ $variant->product->name ?? '-' }}
                                                    - {{ $variant->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('items.' . $index . '.product_variant_id')
                                            <div class="field-error">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    {{-- Quantity --}}
                                    <div class="form-group">

                                        <label class="form-label">
                                            Quantity
                                        </label>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][quantity]"
                                            class="form-control @error('items.' . $index . '.quantity') is-invalid @enderror"
                                            value="{{ $item['quantity'] ?? '' }}"
                                            min="0.0001"
                                            step="0.0001"
                                            placeholder="0.0000"
                                            required
                                        >

                                        @error('items.' . $index . '.quantity')
                                            <div class="field-error">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    {{-- Unit Cost --}}
                                    <div class="form-group">

                                        <label class="form-label">
                                            Unit Cost
                                        </label>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][unit_cost]"
                                            class="form-control @error('items.' . $index . '.unit_cost') is-invalid @enderror"
                                            value="{{ $item['unit_cost'] ?? '' }}"
                                            min="0"
                                            step="0.01"
                                            placeholder="0.00"
                                            required
                                        >

                                        @error('items.' . $index . '.unit_cost')
                                            <div class="field-error">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    {{-- Discount --}}
                                    <div class="form-group">

                                        <label class="form-label">
                                            Discount
                                        </label>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][discount_amount]"
                                            class="form-control @error('items.' . $index . '.discount_amount') is-invalid @enderror"
                                            value="{{ $item['discount_amount'] ?? 0 }}"
                                            min="0"
                                            step="0.01"
                                        >

                                        @error('items.' . $index . '.discount_amount')
                                            <div class="field-error">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    {{-- Tax --}}
                                    <div class="form-group">

                                        <label class="form-label">
                                            Tax
                                        </label>

                                        <input
                                            type="number"
                                            name="items[{{ $index }}][tax_amount]"
                                            class="form-control @error('items.' . $index . '.tax_amount') is-invalid @enderror"
                                            value="{{ $item['tax_amount'] ?? 0 }}"
                                            min="0"
                                            step="0.01"
                                        >

                                        @error('items.' . $index . '.tax_amount')
                                            <div class="field-error">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                @error('items')
                    <div class="field-error" style="margin-top: 10px;">
                        {{ $message }}
                    </div>
                @enderror

            </div>

        </div>


        {{-- Notes --}}
        <div class="card" style="margin-top: 24px;">

            <div class="card-header">

                <div>
                    <h2 class="card-title">Additional Notes</h2>
                    <p class="card-subtitle">
                        Add any internal notes related to this purchase order.
                    </p>
                </div>

            </div>

            <div class="card-body">

                <div class="form-group">

                    <label
                        for="notes"
                        class="form-label"
                    >
                        Notes
                    </label>

                    <textarea
                        id="notes"
                        name="notes"
                        class="form-control @error('notes') is-invalid @enderror"
                        rows="4"
                        placeholder="Optional purchase order notes"
                    >{{ old('notes') }}</textarea>

                    @error('notes')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

        </div>


        {{-- Form Actions --}}
        <div class="form-actions" style="margin-top: 24px;">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Create Purchase Order
            </button>
            
            <a
                href="{{ route('purchase-orders.index') }}"
                class="btn btn-secondary"
            >
                Cancel
            </a>

        </div>

    </form>


    <script>
        let itemIndex = {{ count($oldItems) }};

        const productOptions = @json(
            $products->map(fn ($product) => [
                'id' => $product->id,
                'label' => $product->name . ' - ' . $product->sku,
            ])->values()
        );

        const variantOptions = @json(
            $variants->map(fn ($variant) => [
                'id' => $variant->id,
                'label' => ($variant->product->name ?? '-') . ' - ' . $variant->name,
            ])->values()
        );

        function createOptions(options, placeholder)
        {
            let html = `<option value="">${placeholder}</option>`;

            options.forEach(option => {
                html += `
                    <option value="${option.id}">
                        ${escapeHtml(option.label)}
                    </option>
                `;
            });

            return html;
        }

        function escapeHtml(value)
        {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function addItem()
        {
            const container = document.getElementById('items-container');

            const row = document.createElement('div');

            row.className = 'card purchase-item-card item-row';
            row.style.marginBottom = '18px';

            row.innerHTML = `
                <div class="card-header">

                    <div>
                        <h3 class="card-title">
                            Item #${itemIndex + 1}
                        </h3>
                    </div>

                    <button
                        type="button"
                        class="btn btn-danger btn-sm"
                        onclick="removeItem(this)"
                    >
                        Remove
                    </button>

                </div>

                <div class="card-body">

                    <div class="form-grid">

                        <div class="form-group">

                            <label class="form-label">
                                Product
                            </label>

                            <select
                                name="items[${itemIndex}][product_id]"
                                class="form-control"
                                required
                            >
                                ${createOptions(productOptions, 'Select Product')}
                            </select>

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Variant
                            </label>

                            <select
                                name="items[${itemIndex}][product_variant_id]"
                                class="form-control"
                            >
                                ${createOptions(variantOptions, 'No Variant')}
                            </select>

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Quantity
                            </label>

                            <input
                                type="number"
                                name="items[${itemIndex}][quantity]"
                                class="form-control"
                                min="0.0001"
                                step="0.0001"
                                placeholder="0.0000"
                                required
                            >

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Unit Cost
                            </label>

                            <input
                                type="number"
                                name="items[${itemIndex}][unit_cost]"
                                class="form-control"
                                min="0"
                                step="0.01"
                                placeholder="0.00"
                                required
                            >

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Discount
                            </label>

                            <input
                                type="number"
                                name="items[${itemIndex}][discount_amount]"
                                class="form-control"
                                value="0"
                                min="0"
                                step="0.01"
                            >

                        </div>

                        <div class="form-group">

                            <label class="form-label">
                                Tax
                            </label>

                            <input
                                type="number"
                                name="items[${itemIndex}][tax_amount]"
                                class="form-control"
                                value="0"
                                min="0"
                                step="0.01"
                            >

                        </div>

                    </div>

                </div>
            `;

            container.appendChild(row);

            itemIndex++;
        }

        function removeItem(button)
        {
            const rows = document.querySelectorAll('.item-row');

            if (rows.length <= 1) {
                return;
            }

            button.closest('.item-row').remove();

            renumberItems();
        }

        function renumberItems()
        {
            const rows = document.querySelectorAll('.item-row');

            rows.forEach((row, index) => {

                const title = row.querySelector('.card-title');

                if (title) {
                    title.textContent = `Item #${index + 1}`;
                }

            });
        }
    </script>

@endsection