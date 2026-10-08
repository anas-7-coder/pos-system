{{-- @extends('layouts.app')

@section('title', 'Tax Class Trash')

@section('content')

<h1>Tax Class Trash</h1>

@if(session('success'))
    <p style="color:green;">
        {{ session('success') }}
    </p>
@endif

<hr>

<table border="1" cellpadding="8">

    <thead>

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Code</th>
            <th>Status</th>
            <th>Deleted At</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

        @forelse($taxClasses as $taxClass)

            <tr>
                <td>{{ $taxClass->id }}</td>
                <td>{{ $taxClass->name }}</td>
                <td>{{ $taxClass->code }}</td>
                <td>{{ $taxClass->status }}</td>
                <td>{{ $taxClass->deleted_at }}</td>
                <td>
                    @can('restore', $taxClass)
                        <form action="{{ route('tax-classes.restore', $taxClass->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')

                            <button type="submit">
                                Restore
                            </button>
                        </form>
                    @endcan
                        &nbsp;
                    @can('forceDelete', $taxClass)
                        <form action="{{ route('tax-classes.force-delete', $taxClass->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Are you sure you want to delete this tax-class?')">
                                Force Delete
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="6">
                    Trash is empty.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $taxClasses->links() }}

@endsection --}}







@extends('layouts.app')

@section('title', 'Tax Class Trash')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Tax Class Trash</h1>
            <p class="page-subtitle">
                Manage deleted tax classes and restore or permanently remove them.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('tax-classes.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Tax Classes
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
                <h2 class="card-title">Deleted Tax Classes</h2>
                <p class="card-subtitle">
                    Restore deleted tax classes or permanently remove them.
                </p>
            </div>
        </div>

        <div class="card-body">

            @if($taxClasses->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Status</th>
                                <th>Deleted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($taxClasses as $taxClass)

                                <tr>

                                    <td>
                                        {{ $taxClass->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $taxClass->name }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $taxClass->code }}
                                    </td>

                                    <td>
                                        @if($taxClass->status === 'Active')
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
                                        {{ $taxClass->deleted_at?->format('d M Y, h:i A') ?? '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('restore', $taxClass)
                                                <form
                                                    action="{{ route('tax-classes.restore', $taxClass->id) }}"
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
                                            @endcan

                                            @can('forceDelete', $taxClass)
                                                <form
                                                    action="{{ route('tax-classes.force-delete', $taxClass->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to permanently delete this tax class? This action cannot be undone.')"
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
                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="pagination-wrapper">
                    {{ $taxClasses->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>Trash Is Empty</h3>

                    <p>
                        There are no deleted tax classes to display.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endsection