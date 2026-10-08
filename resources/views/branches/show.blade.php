{{-- @extends('layouts.app')

@section('title', 'Branch Details')

@section('content')

<h1>Branch Details</h1>

<a href="{{ route('branches.index') }}">Back to Branches</a> |

@can('viewAny', App\Models\User::class)
    <a href="{{ route('branches.users.index', $branch) }}">Manage Branch Users</a>
@endcan

<hr>

<p><strong>Company:</strong> {{ $branch->company->name }}</p>
<p><strong>Name:</strong> {{ $branch->name }}</p>
<p><strong>Code:</strong> {{ $branch->code }}</p>
<p><strong>Email:</strong> {{ $branch->email ?? '-' }}</p>
<p><strong>Phone:</strong> {{ $branch->phone ?? '-' }}</p>
<p><strong>Address:</strong> {{ $branch->address ?? '-' }}</p>
<p><strong>Status:</strong> {{ $branch->status }}</p>

<hr>

@endsection --}}







@extends('layouts.app')

@section('title', 'Branch Details')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Branch Details
        </h1>

        <p class="page-subtitle">
            View branch information and manage branch users.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('branches.index') }}"
            class="btn btn-secondary"
        >
            Back to Branches
        </a>

        @can('update', $branch)

            <a
                href="{{ route('branches.edit', $branch) }}"
                class="btn btn-primary"
            >
                Edit Branch
            </a>

        @endcan

        @can('viewAny', App\Models\User::class)

            <a
                href="{{ route('branches.users.index', $branch) }}"
                class="btn btn-outline-primary"
            >
                Manage Branch Users
            </a>

        @endcan

    </div>

</div>


<div class="card">

    <div class="card-header">

        <h2 class="card-title">
            Branch Information
        </h2>

        @if($branch->status === 'Active')

            <span class="badge badge-success">
                Active
            </span>

        @else

            <span class="badge badge-danger">
                Inactive
            </span>

        @endif

    </div>


    <div class="card-body">

        <div class="detail-grid">

            {{-- Company --}}
            <div class="detail-item">

                <span class="detail-label">
                    Company
                </span>

                <span class="detail-value">
                    {{ $branch->company->name }}
                </span>

            </div>


            {{-- Branch Name --}}
            <div class="detail-item">

                <span class="detail-label">
                    Branch Name
                </span>

                <span class="detail-value">
                    {{ $branch->name }}
                </span>

            </div>


            {{-- Branch Code --}}
            <div class="detail-item">

                <span class="detail-label">
                    Branch Code
                </span>

                <span class="detail-value">
                    {{ $branch->code }}
                </span>

            </div>


            {{-- Email --}}
            <div class="detail-item">

                <span class="detail-label">
                    Email
                </span>

                <span class="detail-value">
                    {{ $branch->email ?? '-' }}
                </span>

            </div>


            {{-- Phone --}}
            <div class="detail-item">

                <span class="detail-label">
                    Phone
                </span>

                <span class="detail-value">
                    {{ $branch->phone ?? '-' }}
                </span>

            </div>


            {{-- Status --}}
            <div class="detail-item">

                <span class="detail-label">
                    Status
                </span>

                <span class="detail-value">

                    @if($branch->status === 'Active')

                        <span class="badge badge-success">
                            Active
                        </span>

                    @else

                        <span class="badge badge-danger">
                            Inactive
                        </span>

                    @endif

                </span>

            </div>


            {{-- Address --}}
            <div
                class="detail-item"
                style="grid-column: 1 / -1;"
            >

                <span class="detail-label">
                    Address
                </span>

                <span class="detail-value">
                    {{ $branch->address ?? '-' }}
                </span>

            </div>

        </div>

    </div>

</div>

@endsection