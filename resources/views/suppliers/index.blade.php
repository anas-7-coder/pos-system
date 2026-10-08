{{-- @extends('layouts.app')

@section('title', 'Suppliers')

@section('content')

<h1>Suppliers</h1>

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
            <th>Name</th>
            <th>Code</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Opening Balance</th>
            <th>Credit Limit</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($suppliers as $supplier)
            <tr>
                <td>{{ $supplier->id }}</td>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->code }}</td>
                <td>{{ $supplier->phone ?? '-' }}</td>
                <td>{{ $supplier->email ?? '-' }}</td>
                <td> PKR {{ number_format( (float) $supplier->opening_balance, 2) }}</td>
                <td> PKR {{ number_format( (float) $supplier->credit_limit, 2) }}</td>
                <td>{{ $supplier->status }}</td>
                <td>
                    <a href="{{ route('suppliers.show', $supplier) }}"><button type="button">View</button></a>
                        &nbsp;
                    <a href="{{ route('suppliers.edit', $supplier) }}"><button type="button">Edit</button></a>
                        &nbsp;
                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="return confirm('Are you sure you want to delete this supplier?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="9">
                    No suppliers found.
                </td>
            </tr>

        @endforelse
    </tbody>
</table>

<br>

{{ $suppliers->links() }}

@endsection --}}







@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Suppliers</h1>
            <p class="page-subtitle">
                Manage supplier accounts, contact information, balances, and credit limits.
            </p>
        </div>

        <div class="page-actions">

            @can('create', App\Models\Supplier::class)
                <a
                    href="{{ route('suppliers.create') }}"
                    class="btn btn-primary"
                >
                    + Create Supplier
                </a>
            @endcan

            <a
                href="{{ route('suppliers.trash') }}"
                class="btn btn-secondary"
            >
                Trash
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

    <div class="card">

        <div class="card-header">

            <div>
                <h2 class="card-title">All Suppliers</h2>
                <p class="card-subtitle">
                    View and manage supplier account information.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $suppliers->total() }} Suppliers
                </span>
            </div>

        </div>

        <div class="card-body">

            @if($suppliers->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Opening Balance</th>
                                <th>Credit Limit</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($suppliers as $supplier)

                                <tr>

                                    <td>
                                        {{ $supplier->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $supplier->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $supplier->code }}
                                    </td>

                                    <td>
                                        {{ $supplier->phone ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $supplier->email ?? '-' }}
                                    </td>

                                    <td>
                                        PKR {{ number_format((float) $supplier->opening_balance, 2) }}
                                    </td>

                                    <td>
                                        PKR {{ number_format((float) $supplier->credit_limit, 2) }}
                                    </td>

                                    <td>
                                        @if($supplier->status === 'Active')
                                            <span class="badge badge-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('view', $supplier)
                                                <a
                                                    href="{{ route('suppliers.show', $supplier) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $supplier)
                                                <a
                                                    href="{{ route('suppliers.edit', $supplier) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $supplier)
                                                <form
                                                    action="{{ route('suppliers.destroy', $supplier) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this supplier?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger btn-sm"
                                                    >
                                                        Delete
                                                    </button>
                                                </form>
                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="pagination-wrapper">
                    {{ $suppliers->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Suppliers Found</h3>

                    <p>
                        There are currently no suppliers configured.
                    </p>

                    @can('create', App\Models\Supplier::class)
                        <a
                            href="{{ route('suppliers.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Supplier
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection