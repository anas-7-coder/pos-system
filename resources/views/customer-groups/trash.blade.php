{{-- @extends('layouts.app')

@section('title', 'Deleted Customer Groups')

@section('content')

<h1>Deleted Customer Groups</h1>

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
            <th>Customers</th>
            <th>Status</th>
            <th>Deleted At</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($customerGroups as $customerGroup)
            <tr>
                <td>{{ $customerGroup->id }}</td>
                <td>{{ $customerGroup->name }}</td>
                <td>{{ $customerGroup->code }}</td>
                <td>{{ $customerGroup->customers_count }}</td>
                <td>{{ $customerGroup->status }}</td>
                <td>{{ $customerGroup->deleted_at?->format('Y-m-d H:i') }}</td>
                <td>
                    <form action="{{ route('customer-groups.restore', $customerGroup->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')

                        <button type="submit">
                            Restore
                        </button>
                    </form>
                        &nbsp;
                    <form action="{{ route('customer-groups.force-delete', $customerGroup->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="return confirm('Are you sure you want to delete this customer-group?')">
                            Force Delete
                        </button>
                    </form>
                </td>
            </tr>

        @empty
            <tr>
                 <td colspan="7">
                    No deleted customer groups found.
                </td>
            </tr>  

        @endforelse
    </tbody>

</table>

<br>

{{ $customerGroups->links() }}

@endsection --}}





@extends('layouts.app')

@section('title', 'Deleted Customer Groups')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Deleted Customer Groups</h1>
            <p class="page-subtitle">
                Manage deleted customer groups and restore or permanently remove them.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('customer-groups.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Customer Groups
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
                <h2 class="card-title">Deleted Customer Groups</h2>
                <p class="card-subtitle">
                    Restore deleted groups or permanently remove them.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $customerGroups->total() }} Deleted
                </span>
            </div>

        </div>

        <div class="card-body">

            @if($customerGroups->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Customers</th>
                                <th>Status</th>
                                <th>Deleted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($customerGroups as $customerGroup)

                                <tr>

                                    <td>
                                        {{ $customerGroup->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $customerGroup->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $customerGroup->code }}
                                    </td>

                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ $customerGroup->customers_count }}
                                        </span>
                                    </td>

                                    <td>
                                        @if($customerGroup->status === 'Active')
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
                                        {{ $customerGroup->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            <form
                                                action="{{ route('customer-groups.restore', $customerGroup->id) }}"
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
                                                action="{{ route('customer-groups.force-delete', $customerGroup->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to permanently delete this customer group? This action cannot be undone.')"
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
                    {{ $customerGroups->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted customer groups to display.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection