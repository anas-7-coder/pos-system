@extends('layouts.app')

@section('title', 'User Details')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:25px;flex-wrap:wrap;">
    <div>
        <h1 style="margin:0;">User Details</h1>
        <p style="margin:6px 0 0;color:#6b7280;">
            View account information, role and branch access.
        </p>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">

        <a
            href="{{ route('users.index') }}"
            style="padding:10px 14px;text-decoration:none;border:1px solid #d1d5db;border-radius:8px;"
        >
            ← Back to Users
        </a>

        @can('users.update')
            <a
                href="{{ route('users.edit', $user) }}"
                style="padding:10px 14px;text-decoration:none;background:#111827;color:white;border-radius:8px;"
            >
                Edit User
            </a>
        @endcan

    </div>
</div>

@if(session('success'))
    <div style="padding:12px 15px;margin-bottom:20px;border-radius:8px;background:#dcfce7;color:#166534;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="padding:12px 15px;margin-bottom:20px;border-radius:8px;background:#fee2e2;color:#991b1b;">
        {{ session('error') }}
    </div>
@endif


{{-- Basic Information --}}
<div style="background:white;border:1px solid #e5e7eb;border-radius:12px;padding:25px;margin-bottom:20px;">

    <h2 style="margin:0 0 20px;font-size:20px;">
        Basic Information
    </h2>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

        <div>
            <div style="font-size:13px;color:#6b7280;margin-bottom:5px;">
                Name
            </div>

            <div style="font-weight:600;">
                {{ $user->name }}
            </div>
        </div>

        <div>
            <div style="font-size:13px;color:#6b7280;margin-bottom:5px;">
                Email
            </div>

            <div style="font-weight:600;">
                {{ $user->email }}
            </div>
        </div>

        <div>
            <div style="font-size:13px;color:#6b7280;margin-bottom:5px;">
                Status
            </div>

            @if($user->status === 'Active')
                <span style="padding:5px 9px;border-radius:999px;background:#dcfce7;color:#166534;font-size:12px;">
                    Active
                </span>
            @else
                <span style="padding:5px 9px;border-radius:999px;background:#fee2e2;color:#991b1b;font-size:12px;">
                    Inactive
                </span>
            @endif
        </div>

        <div>
            <div style="font-size:13px;color:#6b7280;margin-bottom:5px;">
                Email Verified
            </div>

            @if($user->email_verified_at)
                <span style="padding:5px 9px;border-radius:999px;background:#dcfce7;color:#166534;font-size:12px;">
                    Verified
                </span>
            @else
                <span style="padding:5px 9px;border-radius:999px;background:#fef3c7;color:#92400e;font-size:12px;">
                    Not Verified
                </span>
            @endif
        </div>

        <div>
            <div style="font-size:13px;color:#6b7280;margin-bottom:5px;">
                Created
            </div>

            <div>
                {{ $user->created_at?->format('d M Y, h:i A') }}
            </div>
        </div>

        <div>
            <div style="font-size:13px;color:#6b7280;margin-bottom:5px;">
                Last Updated
            </div>

            <div>
                {{ $user->updated_at?->format('d M Y, h:i A') }}
            </div>
        </div>

    </div>

</div>


{{-- Roles --}}
<div style="background:white;border:1px solid #e5e7eb;border-radius:12px;padding:25px;margin-bottom:20px;">

    <h2 style="margin:0 0 20px;font-size:20px;">
        Roles
    </h2>

    @forelse($user->roles as $role)

        <div style="display:flex;align-items:center;justify-content:space-between;gap:15px;padding:14px;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:10px;">

            <div>
                <div style="font-weight:600;">
                    {{ $role->display_name ?? $role->name }}
                </div>

                @if($role->description)
                    <div style="margin-top:4px;color:#6b7280;font-size:13px;">
                        {{ $role->description }}
                    </div>
                @endif
            </div>

            <span style="padding:5px 9px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:12px;">
                {{ $role->name }}
            </span>

        </div>

    @empty

        <p style="margin:0;color:#6b7280;">
            No role assigned.
        </p>

    @endforelse

</div>


{{-- Branch Access --}}
<div style="background:white;border:1px solid #e5e7eb;border-radius:12px;padding:25px;margin-bottom:20px;">

    <h2 style="margin:0 0 20px;font-size:20px;">
        Branch Access
    </h2>

    @forelse($user->branches as $branch)

        <div style="padding:14px;border:1px solid #e5e7eb;border-radius:8px;margin-bottom:10px;">

            <div style="font-weight:600;">
                {{ $branch->name }}
            </div>

            @if($branch->code)
                <div style="margin-top:4px;color:#6b7280;font-size:13px;">
                    Code: {{ $branch->code }}
                </div>
            @endif

            @if($branch->status)
                <div style="margin-top:8px;">
                    @if($branch->status === 'Active')
                        <span style="padding:5px 9px;border-radius:999px;background:#dcfce7;color:#166534;font-size:12px;">
                            Active
                        </span>
                    @else
                        <span style="padding:5px 9px;border-radius:999px;background:#fee2e2;color:#991b1b;font-size:12px;">
                            Inactive
                        </span>
                    @endif
                </div>
            @endif

        </div>

    @empty

        <p style="margin:0;color:#6b7280;">
            No branches assigned.
        </p>

    @endforelse

</div>


{{-- Employee --}}
<div style="background:white;border:1px solid #e5e7eb;border-radius:12px;padding:25px;margin-bottom:20px;">

    <h2 style="margin:0 0 20px;font-size:20px;">
        Employee Information
    </h2>

    @if($user->employee)

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

            @if($user->employee->employee_code ?? null)
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:5px;">
                        Employee Code
                    </div>

                    <div style="font-weight:600;">
                        {{ $user->employee->employee_code }}
                    </div>
                </div>
            @endif

            @if($user->employee->phone ?? null)
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:5px;">
                        Phone
                    </div>

                    <div>
                        {{ $user->employee->phone }}
                    </div>
                </div>
            @endif

            @if($user->employee->designation ?? null)
                <div>
                    <div style="font-size:13px;color:#6b7280;margin-bottom:5px;">
                        Designation
                    </div>

                    <div>
                        {{ $user->employee->designation }}
                    </div>
                </div>
            @endif

        </div>

    @else

        <p style="margin:0;color:#6b7280;">
            No employee record is linked to this user.
        </p>

    @endif

</div>

@can('users.delete')
    @if((int) auth()->id() !== (int) $user->getKey())

        <div style="background:white;border:1px solid #fecaca;border-radius:12px;padding:25px;">

            <h2 style="margin:0 0 8px;font-size:20px;color:#991b1b;">
                Danger Zone
            </h2>

            <p style="margin:0 0 20px;color:#6b7280;">
                Deleting this user will move the account to trash.
            </p>

            <form method="POST" action="{{ route('users.destroy', $user) }}">
                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    onclick="return confirm('Move this user to trash?')"
                    style="padding:10px 16px;border:0;background:#dc2626;color:white;border-radius:8px;cursor:pointer;"
                >
                    Move to Trash
                </button>
            </form>

        </div>

    @endif
@endcan

@endsection