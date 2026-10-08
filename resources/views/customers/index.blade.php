{{-- @extends('layouts.app')

@section('title', 'Customers')

@section('content')

<h1>Customers</h1>

@if(session('success'))
    <p style="color: green;">
        {{ session('success') }}
    </p>
@endif

@if(session('error'))
    <p style="color: red;">
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
            <th>Customer Group</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Opening Balance</th>
            <th>Credit Limit</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->code }}</td>
                <td>{{ $customer->customerGroup?->name ?? 'No Group' }}</td>
                <td>{{ $customer->phone ?? '-' }}</td>
                <td>{{ $customer->email ?? '-' }}</td>
                <td>PKR {{ number_format((float) $customer->opening_balance, 2) }}</td>
                <td>PKR {{ number_format((float) $customer->credit_limit, 2) }}</td>
                <td>{{ $customer->status }}</td>
                <td>
                    <a href="{{ route('customers.show', $customer) }}"><button type="button">View</button></a>
                        &nbsp;
                    <a href="{{ route('customers.edit', $customer) }}"><button type="button">Edit</button></a>
                        &nbsp;
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="return confirm('Are you sure you want to delete this customer?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        
        @empty
            <tr>
                <td colspan="10">
                    No customers found.
                </td>
            </tr>
            
        @endforelse 
    </tbody>
</table>

<br>

{{ $customers->links() }}

@endsection --}}







@extends('layouts.app')

@section('title', 'Customers')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Customers</h1>
            <p class="page-subtitle">
                Manage customers, customer groups, balances, and credit limits.
            </p>
        </div>

        <div class="page-actions">

            @can('create', App\Models\Customer::class)
                <a
                    href="{{ route('customers.create') }}"
                    class="btn btn-primary"
                >
                    + Create Customer
                </a>
            @endcan

            <a
                href="{{ route('customers.trash') }}"
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
                <h2 class="card-title">All Customers</h2>
                <p class="card-subtitle">
                    View and manage customer account information.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $customers->total() }} Customers
                </span>
            </div>

        </div>

        <div class="card-body">

            @if($customers->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Customer Group</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Opening Balance</th>
                                <th>Credit Limit</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($customers as $customer)

                                <tr>

                                    <td>
                                        {{ $customer->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $customer->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $customer->code }}
                                    </td>

                                    <td>
                                        {{ $customer->customerGroup?->name ?? 'No Group' }}
                                    </td>

                                    <td>
                                        {{ $customer->phone ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $customer->email ?? '-' }}
                                    </td>

                                    <td>
                                        PKR {{ number_format((float) $customer->opening_balance, 2) }}
                                    </td>

                                    <td>
                                        PKR {{ number_format((float) $customer->credit_limit, 2) }}
                                    </td>

                                    <td>
                                        @if($customer->status === 'Active')
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

                                            @can('view', $customer)
                                                <a
                                                    href="{{ route('customers.show', $customer) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $customer)
                                                <a
                                                    href="{{ route('customers.edit', $customer) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $customer)
                                                <form
                                                    action="{{ route('customers.destroy', $customer) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this customer?')"
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
                    {{ $customers->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Customers Found</h3>

                    <p>
                        There are currently no customers configured.
                    </p>

                    @can('create', App\Models\Customer::class)
                        <a
                            href="{{ route('customers.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Customer
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection