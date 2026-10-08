@extends('layouts.app')

@section('title', 'User Trash')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:25px;flex-wrap:wrap;">

    <div>
        <h1 style="margin:0;">User Trash</h1>

        <p style="margin:6px 0 0;color:#6b7280;">
            Restore deleted users or permanently remove them.
        </p>
    </div>

    <a
        href="{{ route('users.index') }}"
        style="padding:10px 14px;text-decoration:none;border:1px solid #d1d5db;border-radius:8px;"
    >
        ← Back to Users
    </a>

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


<div style="background:white;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">

    <div style="overflow-x:auto;">

        <table style="width:100%;border-collapse:collapse;">

            <thead>

                <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">

                    <th style="padding:14px;text-align:left;">
                        Name
                    </th>

                    <th style="padding:14px;text-align:left;">
                        Email
                    </th>

                    <th style="padding:14px;text-align:left;">
                        Role
                    </th>

                    <th style="padding:14px;text-align:left;">
                        Branches
                    </th>

                    <th style="padding:14px;text-align:left;">
                        Deleted At
                    </th>

                    <th style="padding:14px;text-align:right;">
                        Actions
                    </th>

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

                                <span
                                    style="display:inline-block;padding:4px 8px;border-radius:999px;background:#eef2ff;color:#3730a3;font-size:12px;margin:2px;"
                                >
                                    {{ $role->display_name ?? $role->name }}
                                </span>

                            @empty

                                <span style="color:#9ca3af;">
                                    No role
                                </span>

                            @endforelse

                        </td>


                        <td style="padding:14px;">

                            @forelse($user->branches as $branch)

                                <span
                                    style="display:inline-block;padding:4px 8px;border-radius:999px;background:#f3f4f6;font-size:12px;margin:2px;"
                                >
                                    {{ $branch->name }}
                                </span>

                            @empty

                                <span style="color:#9ca3af;">
                                    No branch
                                </span>

                            @endforelse

                        </td>


                        <td style="padding:14px;color:#6b7280;">
                            {{ $user->deleted_at?->format('d M Y, h:i A') }}
                        </td>


                        <td style="padding:14px;text-align:right;">

                            <div
                                style="display:flex;justify-content:flex-end;gap:8px;flex-wrap:wrap;"
                            >

                                @can('users.restore')
                                    <form
                                        method="POST"
                                        action="{{ route('users.restore', $user->getKey()) }}"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            style="padding:7px 10px;border:0;background:#dcfce7;color:#166534;border-radius:7px;font-size:13px;cursor:pointer;"
                                        >
                                            Restore
                                        </button>
                                    </form>
                                @endcan


                                @can('users.force_delete')
                                    <form
                                        method="POST"
                                        action="{{ route('users.force-delete', $user->getKey()) }}"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Permanently delete this user? This action cannot be undone.')"
                                            style="padding:7px 10px;border:0;background:#fee2e2;color:#991b1b;border-radius:7px;font-size:13px;cursor:pointer;"
                                        >
                                            Delete Permanently
                                        </button>
                                    </form>
                                @endcan

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            style="padding:40px;text-align:center;color:#6b7280;"
                        >
                            User trash is empty.
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