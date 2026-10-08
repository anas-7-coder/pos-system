{{-- @extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')

<h1>Purchase Orders</h1>

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
            <th>ID</th>
            <th>PO Number</th>
            <th>Supplier</th>
            <th>Branch</th>
            <th>Warehouse</th>
            <th>Order Date</th>
            <th>Grand Total</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($purchaseOrders as $purchaseOrder)
            <tr>
                <td>{{ $purchaseOrder->id }}</td>
                <td>{{ $purchaseOrder->po_number }}</td>
                <td>{{ $purchaseOrder->supplier->name }}</td>
                <td>{{ $purchaseOrder->branch->name }}</td>
                <td>{{ $purchaseOrder->warehouse->name }}</td>
                <td>{{ $purchaseOrder->order_date->format('Y-m-d') }}</td>
                <td> PKR {{ number_format( (float) $purchaseOrder->grand_total, 2 ) }}</td>
                <td>{{ ucfirst($purchaseOrder->status) }}</td>
                <td>
                    <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"><button type="button">View</button></a>
                        @if($purchaseOrder->status === 'draft')
                            &nbsp;
                        <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}"><button type="button">Edit</button></a>
                    @endif
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="9">
                    No purchase orders found.
                </td>
            </tr>

        @endforelse
    </tbody>
</table>

<br>

{{ $purchaseOrders->links() }}

@endsection --}}




@extends('layouts.app')

@section('title', 'Purchase Orders')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Purchase Orders</h1>
            <p class="page-subtitle">
                Manage supplier purchase orders, warehouse deliveries, totals, and order status.
            </p>
        </div>

        <div class="page-actions">

            @can('create', App\Models\PurchaseOrder::class)
                <a
                    href="{{ route('purchase-orders.create') }}"
                    class="btn btn-primary"
                >
                    + Create Purchase Order
                </a>
            @endcan

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

            <div>
                <h2 class="card-title">All Purchase Orders</h2>
                <p class="card-subtitle">
                    View purchase orders and manage draft orders.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $purchaseOrders->total() }} Orders
                </span>
            </div>

        </div>

        <div class="card-body">

            @if($purchaseOrders->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>PO Number</th>
                                <th>Supplier</th>
                                <th>Branch</th>
                                <th>Warehouse</th>
                                <th>Order Date</th>
                                <th>Grand Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($purchaseOrders as $purchaseOrder)

                                <tr>

                                    <td>
                                        {{ $purchaseOrder->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $purchaseOrder->po_number }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $purchaseOrder->supplier->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $purchaseOrder->branch->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $purchaseOrder->warehouse->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $purchaseOrder->order_date?->format('Y-m-d') ?? '-' }}
                                    </td>

                                    <td>
                                        <strong>
                                            PKR {{ number_format((float) $purchaseOrder->grand_total, 2) }}
                                        </strong>
                                    </td>

                                    <td>

                                        @if($purchaseOrder->status === 'draft')
                                            <span class="badge badge-secondary">
                                                Draft
                                            </span>
                                        @elseif($purchaseOrder->status === 'submitted')
                                            <span class="badge badge-warning">
                                                Submitted
                                            </span>
                                        @elseif($purchaseOrder->status === 'approved')
                                            <span class="badge badge-info">
                                                Approved
                                            </span>
                                        @elseif($purchaseOrder->status === 'goods_received')
                                            <span class="badge badge-success">
                                                Goods Received
                                            </span>
                                        @elseif($purchaseOrder->status === 'closed')
                                            <span class="badge badge-success">
                                                Closed
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                {{ ucfirst(str_replace('_', ' ', $purchaseOrder->status)) }}
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('view', $purchaseOrder)
                                                <a
                                                    href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @if($purchaseOrder->status === 'draft')

                                                @can('update', $purchaseOrder)
                                                    <a
                                                        href="{{ route('purchase-orders.edit', $purchaseOrder) }}"
                                                        class="btn btn-outline-primary btn-sm"
                                                    >
                                                        Edit
                                                    </a>
                                                @endcan

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="pagination-wrapper">
                    {{ $purchaseOrders->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Purchase Orders Found</h3>

                    <p>
                        There are currently no purchase orders available.
                    </p>

                    @can('create', App\Models\PurchaseOrder::class)
                        <a
                            href="{{ route('purchase-orders.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Purchase Order
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection