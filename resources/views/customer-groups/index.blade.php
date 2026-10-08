{{-- @extends('layouts.app')

@section('title', 'Customer Groups')

@section('content')

<h1>Customer Groups</h1>

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
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>

        @forelse($customerGroups as $customerGroup)
            <tr>
                <td>{{ $customerGroup->id }}
                <td>{{ $customerGroup->name }}</td>
                <td>{{ $customerGroup->code }}</td>
                <td>{{ $customerGroup->customers_count }}</td>
                <td>{{ $customerGroup->status }}</td>
                <td>
                    <a href="{{ route('customer-groups.show', $customerGroup) }}"><button type="button">View</button></a>
                        &nbsp;
                    <a href="{{ route('customer-groups.edit', $customerGroup) }}"><button type="button">Edit</button></a>
                        &nbsp;
                    <form action="{{ route('customer-groups.destroy', $customerGroup) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="return confirm('Are you sure you want to delete this customer-group?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>

        @empty
            <tr>
                 <td colspan="6">
                    No customer groups found.
                </td>
            </tr>

        @endforelse
    </tbody>
</table>

<br>

{{ $customerGroups->links() }}

@endsection --}}









@extends('layouts.app')

@section('title', 'Customer Groups')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Customer Groups</h1>
            <p class="page-subtitle">
                Manage customer groups and view the customers assigned to each group.
            </p>
        </div>

        <div class="page-actions">

            @can('create', App\Models\CustomerGroup::class)
                <a
                    href="{{ route('customer-groups.create') }}"
                    class="btn btn-primary"
                >
                    + Create Customer Group
                </a>
            @endcan

            <a
                href="{{ route('customer-groups.trash') }}"
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
                <h2 class="card-title">All Customer Groups</h2>
                <p class="card-subtitle">
                    View and manage customer group configurations.
                </p>
            </div>

            <div>
                <span class="badge badge-secondary">
                    {{ $customerGroups->total() }} Groups
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

                                        <div class="table-actions">

                                            @can('view', $customerGroup)
                                                <a
                                                    href="{{ route('customer-groups.show', $customerGroup) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                            @can('update', $customerGroup)
                                                <a
                                                    href="{{ route('customer-groups.edit', $customerGroup) }}"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('delete', $customerGroup)
                                                <form
                                                    action="{{ route('customer-groups.destroy', $customerGroup) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this customer group?')"
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
                    {{ $customerGroups->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Customer Groups Found</h3>

                    <p>
                        There are currently no customer groups configured.
                    </p>

                    @can('create', App\Models\CustomerGroup::class)
                        <a
                            href="{{ route('customer-groups.create') }}"
                            class="btn btn-primary"
                        >
                            Create First Customer Group
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection