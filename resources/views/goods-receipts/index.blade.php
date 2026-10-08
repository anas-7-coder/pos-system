{{-- @extends('layouts.app')

@section('title', 'Goods Receipts')

@section('content')

<h1>Goods Receipts</h1>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

@if(session('error'))
    <p style="color:red;">
        {{ session('error') }}
    </p>
@endif

<hr>

<table border="1" cellpadding="8">

    <thead>
        <tr>
            <th>Receipt Number</th>
            <th>PO Number</th>
            <th>Supplier</th>
            <th>Branch</th>
            <th>Warehouse</th>
            <th>Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($goodsReceipts as $goodsReceipt)

            <tr>
                <td>{{ $goodsReceipt->receipt_number }}</td>
                <td>{{ $goodsReceipt->purchaseOrder->po_number }}</td>
                <td>{{ $goodsReceipt->supplier->name }}</td>
                <td>{{ $goodsReceipt->branch->name }}</td>
                <td>{{ $goodsReceipt->warehouse->name }}</td>
                <td>{{ $goodsReceipt->receipt_date->format('Y-m-d') }}</td>
                <td> PKR {{ number_format( (float) $goodsReceipt->grand_total, 2 ) }}</td>
                <td>{{ ucfirst($goodsReceipt->status) }}</td>
                <td>
                    <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"><button type="button">View</button></a>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="9">
                    No goods receipts found.
                </td>
            </tr>
        @endforelse

    </tbody>

</table>

<br>

{{ $goodsReceipts->links() }}

@endsection --}}






@extends('layouts.app')

@section('title', 'Goods Receipts')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Goods Receipts</h1>
            <p class="page-subtitle">
                Review goods received against purchase orders and track receipt status.
            </p>
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

    <div class="card">

        <div class="card-header">
            <h2>Receipt History</h2>
        </div>

        <div class="card-body">

            <div class="table-wrapper">

                <table class="table">

                    <thead>
                        <tr>
                            <th>Receipt Number</th>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Branch</th>
                            <th>Warehouse</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($goodsReceipts as $goodsReceipt)

                            @php
                                $statusClass = match ($goodsReceipt->status) {
                                    'draft' => 'badge-warning',
                                    'posted' => 'badge-success',
                                    'cancelled' => 'badge-danger',
                                    default => 'badge-secondary',
                                };
                            @endphp

                            <tr>

                                <td>
                                    <strong>
                                        {{ $goodsReceipt->receipt_number }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $goodsReceipt->purchaseOrder->po_number }}
                                </td>

                                <td>
                                    {{ $goodsReceipt->supplier->name }}
                                </td>

                                <td>
                                    {{ $goodsReceipt->branch->name }}
                                </td>

                                <td>
                                    {{ $goodsReceipt->warehouse->name }}
                                </td>

                                <td>
                                    {{ $goodsReceipt->receipt_date->format('Y-m-d') }}
                                </td>

                                <td>
                                    <strong>
                                        PKR {{ number_format((float) $goodsReceipt->grand_total, 2) }}
                                    </strong>
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ ucwords(str_replace('_', ' ', $goodsReceipt->status)) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="table-actions">

                                        @can('view', $goodsReceipt)
                                            <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                                            class="btn btn-outline-primary btn-sm">
                                                View
                                            </a>
                                        @endcan

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="table-empty">
                                    No goods receipts found.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
    </div>

    @if($goodsReceipts->hasPages())
        <div class="pagination-wrapper" style="margin-top:20px;">
            {{ $goodsReceipts->links() }}
        </div>
    @endif

@endsection