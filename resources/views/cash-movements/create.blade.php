{{-- @extends('layouts.app')

@section('title', 'Cash Movement')

@section('content')

<h1>Cash Movement</h1>

@if(session('error'))
    <p style="color:red">
        {{ session('error') }}
    </p>
@endif

@if($errors->any())
    <div style="color:red">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<hr>

<form action="{{ route('cash-movements.store') }}" method="POST">
    @csrf

    <div>
        <label for="register_session_id">Register Session</label><br>
        <select name="register_session_id" id="register_session_id" required>
            <option value="">Select Register Session</option>

            @foreach($registerSessions as $session)
                <option value="{{ $session->id }}" {{ old('register_session_id') == $session->id ? 'selected' : '' }}>
                    {{ $session->register->name }} — Session #{{ $session->id }}
                </option>
            @endforeach
        </select>
    </div>
    <br>

    <div>
        <label for="type">Type</label><br>
        <select name="type" id="type" required>
            <option value="">Select Type</option>
            <option value="cash_in" {{ old('type') === 'cash_in' ? 'selected' : '' }}>Cash In</option>
            <option value="cash_out" {{ old('type') === 'cash_out' ? 'selected' : '' }}>Cash Out</option>
        </select>
    </div>
    <br>

    <div>
        <label for="amount">Amount</label><br>
        <input type="number" name="amount" id="amount" step="0.01" min="0.01" value="{{ old('amount') }}" required>
    </div>
    <br>

    <div>
        <label for="reference">Reference</label><br>
        <input type="text" name="reference" id="reference" value="{{ old('reference') }}">
    </div>
    <br>

    <div>
        <label for="notes">Notes</label><br>
        <textarea name="notes" id="notes" rows="3">{{ old('notes') }}</textarea>
    </div>
    <br>
<hr>
    <button type="submit">Record Cash Movement</button>

</form>

@endsection --}}








@extends('layouts.app')

@section('title', 'Cash Movement')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Cash Movement</h1>
            <p class="page-subtitle">
                Record manual cash-in or cash-out transactions for an open register session.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('cash-movements.index') }}"
               class="btn btn-secondary">
                ← Back to Cash Movements
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

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>

            <ul style="margin:10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cash-movements.store') }}"
          method="POST">

        @csrf

        {{-- Movement Information --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Movement Information</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    <div class="form-group">

                        <label for="register_session_id" class="form-label">
                            Register Session
                        </label>

                        <select
                            name="register_session_id"
                            id="register_session_id"
                            class="@error('register_session_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Register Session
                            </option>

                            @foreach($registerSessions as $session)
                                <option
                                    value="{{ $session->id }}"
                                    {{ old('register_session_id') == $session->id ? 'selected' : '' }}
                                >
                                    {{ $session->register->name }}
                                    — Session #{{ $session->id }}
                                </option>
                            @endforeach
                        </select>

                        @error('register_session_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="type" class="form-label">
                            Type
                        </label>

                        <select
                            name="type"
                            id="type"
                            class="@error('type') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Type
                            </option>

                            <option
                                value="cash_in"
                                {{ old('type') === 'cash_in' ? 'selected' : '' }}
                            >
                                Cash In
                            </option>

                            <option
                                value="cash_out"
                                {{ old('type') === 'cash_out' ? 'selected' : '' }}
                            >
                                Cash Out
                            </option>
                        </select>

                        @error('type')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="amount" class="form-label">
                            Amount
                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            value="{{ old('amount') }}"
                            step="0.01"
                            min="0.01"
                            class="@error('amount') is-invalid @enderror"
                            placeholder="Enter amount"
                            required
                        >

                        @error('amount')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="reference" class="form-label">
                            Reference
                        </label>

                        <input
                            type="text"
                            name="reference"
                            id="reference"
                            value="{{ old('reference') }}"
                            class="@error('reference') is-invalid @enderror"
                            placeholder="Optional reference number"
                        >

                        @error('reference')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>
        </div>

        {{-- Notes --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Notes</h2>
            </div>

            <div class="card-body">

                <div class="form-group">

                    <label for="notes" class="form-label">
                        Notes
                    </label>

                    <textarea
                        name="notes"
                        id="notes"
                        rows="4"
                        class="@error('notes') is-invalid @enderror"
                        placeholder="Explain the reason for this cash movement..."
                    >{{ old('notes') }}</textarea>

                    @error('notes')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="card">

            <div class="card-body">

                <div class="form-actions">

                    <button type="submit"
                            class="btn btn-primary">
                        Record Cash Movement
                    </button>

                    <a href="{{ route('cash-movements.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </div>
        </div>

    </form>

@endsection