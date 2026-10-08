{{-- @extends('layouts.app')

@section('title', 'Register Sessions')

@section('content')

<h1>Register Sessions</h1>

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
            <th>Register</th>
            <th>Branch</th>
            <th>Cashier</th>
            <th>Opening Cash</th>
            <th>Actual Cash</th>
            <th>Difference</th>
            <th>Status</th>
            <th>Opened At</th>
            <th>Closed At</th>
            <th>Action</th>
        </tr>

    </thead>

    <tbody>

        @forelse($sessions as $session)

            <tr>
                <td>{{ $session->id }}</td>
                <td>{{ $session->register->name }}</td>
                <td>{{ $session->branch->name }}</td>
                <td>{{ $session->user->name }}</td>
                <td>{{ $session->opening_cash }}</td>
                <td>{{ $session->actual_cash ?? '-' }}</td>
                <td>{{ $session->difference ?? '-' }}</td>
                <td>{{ $session->status }}</td>
                <td>{{ $session->opened_at }}</td>
                <td>{{ $session->closed_at ?? '-' }}</td>
                <td>
                    @can('view', $session)
                        <a href="{{ route('register-sessions.show', $session) }}"><button type="button">View</button></a>
                    @endcan
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="11">
                    No register sessions found.
                </td>
            </tr>

        @endforelse

    </tbody>

</table>

{{ $sessions->links() }}

@endsection --}}





@extends('layouts.app')

@section('title', 'Register Sessions')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Register Sessions</h1>
            <p class="page-subtitle">
                Monitor register openings, cash balances, and session status.
            </p>
        </div>

        <div class="page-actions">
            @can('open', App\Models\RegisterSession::class)
                <a
                    href="{{ route('register-sessions.create') }}"
                    class="btn btn-primary"
                >
                    + Open Register
                </a>
            @endcan
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
                <h2 class="card-title">All Register Sessions</h2>
                <p class="card-subtitle">
                    Review current and completed register sessions.
                </p>
            </div>
        </div>

        <div class="card-body">

            @if($sessions->count())

                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Register</th>
                                <th>Branch</th>
                                <th>Cashier</th>
                                <th>Opening Cash</th>
                                <th>Actual Cash</th>
                                <th>Difference</th>
                                <th>Status</th>
                                <th>Opened At</th>
                                <th>Closed At</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($sessions as $session)

                                <tr>

                                    <td>
                                        {{ $session->id }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $session->register->name ?? '-' }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $session->branch->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $session->user->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ number_format((float) $session->opening_cash, 2) }}
                                    </td>

                                    <td>
                                        {{ $session->actual_cash !== null
                                            ? number_format((float) $session->actual_cash, 2)
                                            : '-' }}
                                    </td>

                                    <td>
                                        @if($session->difference !== null)
                                            {{ number_format((float) $session->difference, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        @if($session->status === 'Open')
                                            <span class="badge badge-success">
                                                Open
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                Closed
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $session->opened_at
                                            ? \Carbon\Carbon::parse($session->opened_at)->format('d M Y, h:i A')
                                            : '-' }}
                                    </td>

                                    <td>
                                        {{ $session->closed_at
                                            ? \Carbon\Carbon::parse($session->closed_at)->format('d M Y, h:i A')
                                            : '-' }}
                                    </td>

                                    <td>

                                        <div class="table-actions">

                                            @can('view', $session)
                                                <a
                                                    href="{{ route('register-sessions.show', $session) }}"
                                                    class="btn btn-secondary btn-sm"
                                                >
                                                    View
                                                </a>
                                            @endcan

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="pagination-wrapper">
                    {{ $sessions->links() }}
                </div>

            @else

                <div class="table-empty">

                    <h3>No Register Sessions Found</h3>

                    <p>
                        There are currently no register sessions to display.
                    </p>

                    @can('open', App\Models\RegisterSession::class)
                        <a
                            href="{{ route('register-sessions.create') }}"
                            class="btn btn-primary"
                        >
                            Open First Register
                        </a>
                    @endcan

                </div>

            @endif

        </div>

    </div>

@endsection