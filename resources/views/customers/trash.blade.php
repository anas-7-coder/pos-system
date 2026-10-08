{{-- @extends('layouts.app')

@section('title', 'Deleted Customers')

@section('content')

<h1>Deleted Customers</h1>

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
            <th>Deleted At</th>
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
                <td>{{ $customer->deleted_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <form action="{{ route('customers.restore', $customer->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')

                        <button type="submit">
                            Restore
                        </button>
                    </form>
                        &nbsp;
                    <form action="{{ route('customers.force-delete', $customer->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="return confirm('Are you sure you want to delete this customer?')">
                            Force Delete
                        </button>
                    </form>
                </td>
            </tr>
    
        @empty
            <tr>
                <td colspan="11">
                    No deleted customers found.
                </td>
            </tr>

        @endforelse
    </tbody>
</table>

<br>

{{ $customers->links() }}

@endsection --}}








@extends('layouts.app')

@section('title', 'Deleted Customers')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Deleted Customers</h1>
            <p class="page-subtitle">
                Manage deleted customers and restore or permanently remove them.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('customers.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Customers
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
                <h2 class="card-title">Deleted Customers</h2>
                <p class="card-subtitle">
                    Restore deleted customers or permanently remove them.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $customers->total() }} Deleted
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
                                <th>Deleted At</th>
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
                                        {{ $customer->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            <form
                                                action="{{ route('customers.restore', $customer->id) }}"
                                                method="POST"
                                            >
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-success btn-sm"
                                                >
                                                    Restore
                                                </button>
                                            </form>

                                            <form
                                                action="{{ route('customers.force-delete', $customer->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to permanently delete this customer? This action cannot be undone.')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm"
                                                >
                                                    Force Delete
                                                </button>
                                            </form>

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

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted customers to display.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection