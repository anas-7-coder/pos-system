{{-- @extends('layouts.app')

@section('title', 'Register Session')

@section('content')

<h1>Register Session</h1>

<a href="{{ route('register-sessions.index') }}">
    Back to Sessions
</a>

<hr>

<p><strong>Register:</strong> {{ $registerSession->register->name }}</p>
<p><strong>Branch:</strong> {{ $registerSession->branch->name }}</p>
<p><strong>Cashier:</strong> {{ $registerSession->user->name }}</p>
<p><strong>Status:</strong> {{ $registerSession->status }}</p>
<p><strong>Opening Cash:</strong> {{ $registerSession->opening_cash }}</p>
<p><strong>Expected Cash:</strong> {{ $registerSession->expected_cash ?? '-' }}</p>
<p><strong>Actual Cash:</strong> {{ $registerSession->actual_cash ?? '-' }}</p>
<p><strong>Difference:</strong> {{ $registerSession->difference ?? '-' }}</p>
<p><strong>Opened At:</strong> {{ $registerSession->opened_at }}</p>
<p><strong>Closed At:</strong> {{ $registerSession->closed_at ?? '-' }}</p>

<hr>

@if($registerSession->status === 'Open')
    @can('Close', $registerSession)

        <h2>Close Register</h2>

        <form action="{{ route('register-sessions.close', $registerSession) }}" method="POST">

            @csrf
            @method('PATCH')

            <label>Actual Cash</label>
            <input type="number" name="actual_cash" step="0.01" min="0" required>
            @error('actual_cash')
                <p style="color:red;">
                    {{ $message }}
                </p>
            @enderror
            <br><br>

            <button type="submit">
                Close Register
            </button>

        </form>

    @endcan

@endif

@endsection

 --}}










 @extends('layouts.app')

@section('title', 'Register Session')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Register Session</h1>
            <p class="page-subtitle">
                View register session details and close the session when the cashier is finished.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('register-sessions.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Sessions
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
                <h2 class="card-title">Session Information</h2>
                <p class="card-subtitle">
                    Current cash position and session details.
                </p>
            </div>

            <div>
                @if($registerSession->status === 'Open')
                    <span class="badge badge-success">
                        Open
                    </span>
                @else
                    <span class="badge badge-secondary">
                        Closed
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <span class="detail-label">Session ID</span>
                    <span class="detail-value">
                        {{ $registerSession->id }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Register</span>
                    <span class="detail-value">
                        {{ $registerSession->register->name ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Branch</span>
                    <span class="detail-value">
                        {{ $registerSession->branch->name ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Cashier</span>
                    <span class="detail-value">
                        {{ $registerSession->user->name ?? '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        @if($registerSession->status === 'Open')
                            <span class="badge badge-success">
                                Open
                            </span>
                        @else
                            <span class="badge badge-secondary">
                                Closed
                            </span>
                        @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Opening Cash</span>
                    <span class="detail-value">
                        {{ number_format((float) $registerSession->opening_cash, 2) }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Expected Cash</span>
                    <span class="detail-value">
                        {{ $registerSession->expected_cash !== null
                            ? number_format((float) $registerSession->expected_cash, 2)
                            : '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Actual Cash</span>
                    <span class="detail-value">
                        {{ $registerSession->actual_cash !== null
                            ? number_format((float) $registerSession->actual_cash, 2)
                            : '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Difference</span>
                    <span class="detail-value">
                        @if($registerSession->difference !== null)
                            {{ number_format((float) $registerSession->difference, 2) }}
                        @else
                            -
                        @endif
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Opened At</span>
                    <span class="detail-value">
                        {{ $registerSession->opened_at
                            ? \Carbon\Carbon::parse($registerSession->opened_at)->format('d M Y, h:i A')
                            : '-' }}
                    </span>
                </div>

                <div class="detail-item">
                    <span class="detail-label">Closed At</span>
                    <span class="detail-value">
                        {{ $registerSession->closed_at
                            ? \Carbon\Carbon::parse($registerSession->closed_at)->format('d M Y, h:i A')
                            : '-' }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- Close Register --}}
    @if($registerSession->status === 'Open')

        @can('close', $registerSession)

            <div class="card form-card" style="margin-top: 24px;">

                <div class="card-header">
                    <div>
                        <h2 class="card-title">Close Register</h2>
                        <p class="card-subtitle">
                            Enter the physical cash counted at the end of the session.
                        </p>
                    </div>
                </div>

                <div class="card-body">

                    <form
                        action="{{ route('register-sessions.close', $registerSession) }}"
                        method="POST"
                    >
                        @csrf
                        @method('PATCH')

                        <div class="form-grid">

                            <div class="form-group">

                                <label
                                    for="actual_cash"
                                    class="form-label"
                                >
                                    Actual Cash
                                </label>

                                <input
                                    type="number"
                                    id="actual_cash"
                                    name="actual_cash"
                                    class="form-control @error('actual_cash') is-invalid @enderror"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('actual_cash') }}"
                                    required
                                >

                                @error('actual_cash')
                                    <div class="field-error">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                        <div class="form-actions">

                            <button
                                type="submit"
                                class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to close this register session?')"
                            >
                                Close Register
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        @endcan

    @endif

@endsection