@extends('layouts.app')

@section('title', 'Add User')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:25px;flex-wrap:wrap;">
    <div>
        <h1 style="margin:0;">Add User</h1>
        <p style="margin:6px 0 0;color:#6b7280;">
            Create a system user and assign their role and branch access.
        </p>
    </div>

    <a href="{{ route('users.index') }}"
       style="padding:10px 14px;text-decoration:none;border:1px solid #d1d5db;border-radius:8px;">
        ← Back to Users
    </a>
</div>

@if($errors->any())
    <div style="padding:12px 15px;margin-bottom:20px;border-radius:8px;background:#fee2e2;color:#991b1b;">
        <strong>Please fix the following errors:</strong>

        <ul style="margin:8px 0 0 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="background:white;border:1px solid #e5e7eb;border-radius:12px;padding:25px;">

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

            {{-- Name --}}
            <div>
                <label for="name" style="display:block;margin-bottom:7px;font-weight:600;">
                    Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;"
                >
            </div>

            {{-- Email --}}
            <div>
                <label for="email" style="display:block;margin-bottom:7px;font-weight:600;">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;"
                >
            </div>

            {{-- Password --}}
            <div>
                <label for="password" style="display:block;margin-bottom:7px;font-weight:600;">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;"
                >

                <small style="display:block;margin-top:6px;color:#6b7280;">
                    Minimum 8 characters.
                </small>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" style="display:block;margin-bottom:7px;font-weight:600;">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;"
                >
            </div>

            {{-- Role --}}
            <div>
                <label for="role_id" style="display:block;margin-bottom:7px;font-weight:600;">
                    Role
                </label>

                <select
                    id="role_id"
                    name="role_id"
                    required
                    style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;background:white;"
                >
                    <option value="">Select Role</option>

                    @foreach($roles as $role)
                        <option
                            value="{{ $role->id }}"
                            @selected(old('role_id') == $role->id)
                        >
                            {{ $role->display_name ?? $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label for="status" style="display:block;margin-bottom:7px;font-weight:600;">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    required
                    style="width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:8px;background:white;"
                >
                    <option value="Active" @selected(old('status', 'Active') === 'Active')}>
                        Active
                    </option>

                    <option value="Inactive" @selected(old('status') === 'Inactive')}>
                        Inactive
                    </option>
                </select>
            </div>

        </div>

        {{-- Branch Access --}}
        <div style="margin-top:25px;">
            <label style="display:block;margin-bottom:10px;font-weight:600;">
                Branch Access
            </label>

            <div style="border:1px solid #d1d5db;border-radius:8px;padding:15px;background:#f9fafb;">

                @forelse($branches as $branch)

                    <label style="display:flex;align-items:center;gap:10px;margin-bottom:10px;cursor:pointer;">

                        <input
                            type="checkbox"
                            name="branch_ids[]"
                            value="{{ $branch->id }}"
                            @checked(in_array($branch->id, old('branch_ids', [])))
                        >

                        <span>
                            <strong>{{ $branch->name }}</strong>

                            @if($branch->code)
                                <span style="color:#6b7280;">
                                    ({{ $branch->code }})
                                </span>
                            @endif
                        </span>

                    </label>

                @empty
                    <p style="margin:0;color:#6b7280;">
                        No active branches available.
                    </p>
                @endforelse

            </div>

            <small style="display:block;margin-top:7px;color:#6b7280;">
                Select the branches this user is allowed to access.
            </small>
        </div>

        {{-- Actions --}}
        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:25px;">

            <button
                type="submit"
                style="padding:10px 18px;border:0;background:#111827;color:white;border-radius:8px;cursor:pointer;"
            >
                Create User
            </button>
            
            <a
                href="{{ route('users.index') }}"
                style="padding:10px 16px;text-decoration:none;border:1px solid #d1d5db;border-radius:8px;"
            >
                Cancel
            </a>

        </div>

    </form>

</div>

@endsection