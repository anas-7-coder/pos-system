{{-- @extends('layouts.app')

@section('title', 'Deleted Suppliers')

@section('content')

<h1>Deleted Suppliers</h1>

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
            <th>Deleted At</th>
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
                <td>{{ $supplier->deleted_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <form action="{{ route('suppliers.restore', $supplier->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')

                        <button type="submit">
                            Restore
                        </button>
                    </form>
                        &nbsp;
                    <form action="{{ route('suppliers.force-delete', $supplier->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="return confirm('Are you sure you want to delete this supplier?')">
                            Force Delete
                        </button>
                    </form>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="10">
                    No deleted suppliers found.
                </td>
            </tr>                        

        @endforelse
    </tbody>
</table>

<br>

{{ $suppliers->links() }}

@endsection --}}










@extends('layouts.app')

@section('title', 'Deleted Suppliers')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Deleted Suppliers</h1>
            <p class="page-subtitle">
                Manage deleted suppliers and restore or permanently remove them.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('suppliers.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Suppliers
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
                <h2 class="card-title">Deleted Suppliers</h2>
                <p class="card-subtitle">
                    Restore deleted suppliers or permanently remove them.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $suppliers->total() }} Deleted
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
                                <th>Deleted At</th>
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
                                        {{ $supplier->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            <form
                                                action="{{ route('suppliers.restore', $supplier->id) }}"
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
                                                action="{{ route('suppliers.force-delete', $supplier->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to permanently delete this supplier? This action cannot be undone.')"
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
                    {{ $suppliers->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted suppliers to display.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection