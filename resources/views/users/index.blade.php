@extends('layouts.app')

@section('title', 'Users')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:25px;flex-wrap:wrap;">
    <div>
        <h1 style="margin:0;">Users</h1>
        <p style="margin:6px 0 0;color:#6b7280;">
            Manage system users, roles, status and branch access.
        </p>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap;">
        @can('users.create')
            <a href="{{ route('users.create') }}"
            style="padding:10px 14px;text-decoration:none;background:#111827;color:white;border-radius:8px;">
                + Add User
            </a>
        @endcan
        
        @can('users.trash.view')
            <a href="{{ route('users.trash') }}"
            style="padding:10px 14px;text-decoration:none;border:1px solid #d1d5db;border-radius:8px;">
                User Trash
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

<div style="background:white;border:1px solid #e5e7eb;border-radius:12px;padding:20px;margin-bottom:20px;">
    <form method="GET" action="{{ route('users.index') }}"
          style="display:grid;grid-template-columns:2fr 1fr auto;gap:12px;align-items:end;">

        <div>
            <label for="search" style="display:block;margin-bottom:6px;font-weight:600;">
                Search
            </label>

            <input
                type="text"
                id="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search name or email"
                style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;"
            >
        </div>

        <div>
            <label for="status" style="display:block;margin-bottom:6px;font-weight:600;">
                Status
            </label>

            <select
                id="status"
                name="status"
                style="width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;"
            >
                <option value="">All Statuses</option>
                <option value="Active" @selected(request('status') === 'Active')>
                    Active
                </option>
                <option value="Inactive" @selected(request('status') === 'Inactive')>
                    Inactive
                </option>
            </select>
        </div>

        <div style="display:flex;gap:8px;">
            <button
                type="submit"
                style="padding:10px 15px;border:0;background:#111827;color:white;border-radius:8px;cursor:pointer;"
            >
                Search
            </button>

            <a
                href="{{ route('users.index') }}"
                style="padding:10px 15px;text-decoration:none;border:1px solid #d1d5db;border-radius:8px;"
            >
                Reset
            </a>
        </div>
    </form>
</div>

<div style="background:white;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">

    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                    <th style="padding:14px;text-align:left;">Name</th>
                    <th style="padding:14px;text-align:left;">Email</th>
                    <th style="padding:14px;text-align:left;">Role</th>
                    <th style="padding:14px;text-align:left;">Branches</th>
                    <th style="padding:14px;text-align:left;">Status</th>
                    <th style="padding:14px;text-align:right;">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom:1px solid #f1f5f9;">

                        <td style="padding:14px;font-weight:600;">
                            {{ $user->name }}
                        </td>

                        <td style="padding:14px;">
                            {{ $user->email }}
                        </td>

                        <td style="padding:14px;">
                            @forelse($user->roles as $role)
                                <span style="display:inline-block;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:12px;margin:2px;">
                                    {{ $role->display_name ?? $role->name }}
                                </span>
                            @empty
                                <span style="color:#9ca3af;">No role</span>
                            @endforelse
                        </td>

                        <td style="padding:14px;">
                            @forelse($user->branches as $branch)
                                <span style="display:inline-block;padding:4px 8px;border-radius:999px;background:#f3f4f6;font-size:12px;margin:2px;">
                                    {{ $branch->name }}
                                </span>
                            @empty
                                <span style="color:#9ca3af;">No branch</span>
                            @endforelse
                        </td>

                        <td style="padding:14px;">
                            @if($user->status === 'Active')
                                <span style="padding:5px 9px;border-radius:999px;background:#dcfce7;color:#166534;font-size:12px;">
                                    Active
                                </span>
                            @else
                                <span style="padding:5px 9px;border-radius:999px;background:#fee2e2;color:#991b1b;font-size:12px;">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <td style="padding:14px;text-align:right;">
                            <div style="display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap;">

                                <a
                                    href="{{ route('users.show', $user) }}"
                                    style="padding:7px 10px;text-decoration:none;border:1px solid #d1d5db;border-radius:7px;font-size:13px;"
                                >
                                    View
                                </a>

                                @can('users.update')
                                    <a href="{{ route('users.edit', $user) }}" style="padding:7px 10px;text-decoration:none;border:1px solid #d1d5db;border-radius:7px;font-size:13px;">
                                        Edit
                                    </a>
                                @endcan

                                @can('users.delete')
                                    @if((int) auth()->id() !== (int) $user->getKey())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                onclick="return confirm('Move this user to trash?')"
                                                style="padding:7px 10px;border:0;background:#fee2e2;color:#991b1b;border-radius:7px;font-size:13px;cursor:pointer;"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                @endcan

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding:30px;text-align:center;color:#6b7280;">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div style="padding:15px;border-top:1px solid #e5e7eb;">
            {{ $users->links() }}
        </div>
    @endif

</div>

@endsection