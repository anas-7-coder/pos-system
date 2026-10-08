@extends('layouts.app')

@section('title', 'Inventory Report')

@section('content')

<div class="report-page">

    <div class="report-header">

        <div>
            <h1>Inventory Report</h1>
            <p>Current inventory across your accessible branches.</p>
        </div>

        <a
            href="{{ route('inventory.index') }}"
            class="report-button"
        >
            View Inventory
        </a>

    </div>


    {{-- Summary --}}
    <div class="report-summary-grid">

        <div class="report-card">
            <span>Inventory Items</span>

            <strong>
                {{ number_format($totalItems) }}
            </strong>
        </div>


        <div class="report-card">
            <span>Total Quantity</span>

            <strong>
                {{ number_format($totalQuantity, 2) }}
            </strong>
        </div>


        <div class="report-card">
            <span>Reserved Quantity</span>

            <strong>
                {{ number_format($totalReserved, 2) }}
            </strong>
        </div>


        <div class="report-card">
            <span>Estimated Stock Value</span>

            <strong>
                ₨ {{ number_format($estimatedValue, 2) }}
            </strong>
        </div>

    </div>


    {{-- Low Stock + Warehouse Summary --}}
    <div class="report-two-column">

        {{-- Low Stock --}}
        <div class="report-panel">

            <div class="report-panel-header">

                <div>
                    <h2>Low Stock</h2>
                    <p>Items at or below reorder level.</p>
                </div>

                <strong>
                    {{ number_format($lowStockCount) }}
                </strong>

            </div>


            @forelse($lowStockItems as $item)

                <div class="report-row">

                    <div>

                        <strong>
                            {{ $item->product?->name ?? 'Unknown Product' }}
                        </strong>

                        @if($item->productVariant)
                            <small>
                                Variant #{{ $item->productVariant->id }}
                            </small>
                        @endif

                        <small>
                            {{ $item->warehouse?->name ?? '-' }}
                        </small>

                    </div>

                    <div class="inventory-stock-warning">

                        <strong>
                            {{ number_format($item->quantity, 2) }}
                        </strong>

                        <small>
                            Reorder: {{ number_format($item->reorder_level, 2) }}
                        </small>

                    </div>

                </div>

            @empty

                <div class="empty-state">
                    No low-stock items found.
                </div>

            @endforelse

        </div>


        {{-- Warehouse Summary --}}
        <div class="report-panel">

            <div class="report-panel-header">

                <div>
                    <h2>Warehouse Summary</h2>
                    <p>Inventory grouped by warehouse.</p>
                </div>

            </div>


            @forelse($warehouseSummary as $summary)

                <div class="report-row">

                    <div>

                        <strong>
                            {{ $summary['warehouse']?->name ?? '-' }}
                        </strong>

                        <small>
                            {{ $summary['warehouse']?->branch?->name ?? '-' }}
                        </small>

                    </div>

                    <div class="warehouse-summary-value">

                        <strong>
                            ₨ {{ number_format($summary['value'], 2) }}
                        </strong>

                        <small>
                            {{ number_format($summary['quantity'], 2) }}
                            units
                        </small>

                    </div>

                </div>

            @empty

                <div class="empty-state">
                    No warehouse inventory found.
                </div>

            @endforelse

        </div>

    </div>


    {{-- Inventory Detail --}}
    <div class="report-panel">

        <div class="report-panel-header">

            <div>
                <h2>Inventory Detail</h2>
                <p>Current stock balances.</p>
            </div>

        </div>


        @if($inventory->isEmpty())

            <div class="empty-state">
                No inventory records found.
            </div>

        @else

            <div class="table-wrapper">

                <table class="report-table">

                    <thead>

                        <tr>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Branch</th>
                            <th>Warehouse</th>
                            <th>Quantity</th>
                            <th>Reserved</th>
                            <th>Reorder Level</th>
                            <th class="text-right">Value</th>
                        </tr>

                    </thead>


                    <tbody>

                        @foreach($inventory as $item)

                            @php
                                $costPrice = (float) ($item->product?->cost_price ?? 0);
                                $value = (float) $item->quantity * $costPrice;

                                $isLowStock =
                                    $item->reorder_level > 0 &&
                                    $item->quantity <= $item->reorder_level;
                            @endphp

                            <tr>

                                <td>
                                    {{ $item->product?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->productVariant?->id ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->warehouse?->branch?->name ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->warehouse?->name ?? '-' }}
                                </td>

                                <td class="{{ $isLowStock ? 'low-stock-text' : '' }}">
                                    {{ number_format($item->quantity, 2) }}
                                </td>

                                <td>
                                    {{ number_format($item->reserved_quantity, 2) }}
                                </td>

                                <td>
                                    {{ number_format($item->reorder_level, 2) }}
                                </td>

                                <td class="text-right">
                                    ₨ {{ number_format($value, 2) }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="report-pagination">
                {{ $inventory->links() }}
            </div>

        @endif

    </div>

</div>


<style>

.report-page {
    padding: 24px;
}

.report-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.report-header h1 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.report-header p {
    margin: 6px 0 0;
    color: #6b7280;
}

.report-button {
    display: inline-block;
    border: none;
    background: #111827;
    color: #ffffff;
    padding: 9px 14px;
    border-radius: 7px;
    text-decoration: none;
    cursor: pointer;
}

.report-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 20px;
}

.report-card {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
}

.report-card span {
    display: block;
    color: #6b7280;
    font-size: 13px;
    margin-bottom: 10px;
}

.report-card strong {
    font-size: 24px;
}

.report-two-column {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.report-panel {
    background: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.report-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.report-panel-header h2 {
    margin: 0;
    font-size: 18px;
}

.report-panel-header p {
    margin: 5px 0 0;
    color: #6b7280;
    font-size: 13px;
}

.report-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.report-row:last-child {
    border-bottom: none;
}

.report-row small {
    display: block;
    color: #6b7280;
    margin-top: 4px;
}

.inventory-stock-warning,
.warehouse-summary-value {
    text-align: right;
    white-space: nowrap;
}

.low-stock-text {
    font-weight: 700;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table th,
.report-table td {
    padding: 13px 10px;
    border-bottom: 1px solid #f1f5f9;
    text-align: left;
}

.report-table th {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
}

.report-table td {
    font-size: 14px;
}

.text-right {
    text-align: right !important;
}

.table-wrapper {
    overflow-x: auto;
}

.empty-state {
    text-align: center;
    padding: 25px;
    color: #6b7280;
}

.report-pagination {
    margin-top: 20px;
}

@media (max-width: 900px) {

    .report-summary-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .report-two-column {
        grid-template-columns: 1fr;
    }

}

@media (max-width: 600px) {

    .report-page {
        padding: 15px;
    }

    .report-summary-grid {
        grid-template-columns: 1fr;
    }

    .report-header {
        flex-direction: column;
        gap: 10px;
    }

}

</style>

@endsection