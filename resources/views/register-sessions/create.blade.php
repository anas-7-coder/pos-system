{{-- @extends('layouts.app')

@section('title', 'Open Register')

@section('content')

<h1>Open Register</h1>

<hr>

<form action="{{ route('register-sessions.store') }}" method="POST">
    @csrf

    <div>
        <label>Register</label><br>
        <select name="register_id" required>
            <option value="">Select Register</option>

            @foreach($registers as $register)
                <option value="{{ $register->id }}" {{ old('register_id') == $register->id ? 'selected' : '' }}>
                    {{ $register->name }}
                    -
                    {{ $register->branch->name }}
                </option>
            @endforeach
        </select>
        @error('register_id')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Opening Cash</label><br>
        <input type="number" name="opening_cash" step="0.01" min="0" value="{{ old('opening_cash', 0) }}" required>
        @error('opening_cash')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Open Register
    </button>

</form>

@endsection --}}







@extends('layouts.app')

@section('title', 'Open Register')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Open Register</h1>
            <p class="page-subtitle">
                Start a new register session by selecting a register and entering the opening cash.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('register-sessions.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Register Sessions
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

    <div class="card form-card">

        <div class="card-header">
            <div>
                <h2 class="card-title">Register Session</h2>
                <p class="card-subtitle">
                    Select the register you want to open and enter its starting cash balance.
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('register-sessions.store') }}"
                method="POST"
            >
                @csrf

                <div class="form-grid">

                    {{-- Register --}}
                    <div class="form-group">

                        <label for="register_id" class="form-label">
                            Register
                        </label>

                        <select
                            id="register_id"
                            name="register_id"
                            class="form-control @error('register_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Register
                            </option>

                            @foreach($registers as $register)
                                <option
                                    value="{{ $register->id }}"
                                    {{ old('register_id') == $register->id ? 'selected' : '' }}
                                >
                                    {{ $register->name }}
                                    -
                                    {{ $register->branch->name ?? '-' }}
                                </option>
                            @endforeach
                        </select>

                        @error('register_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Opening Cash --}}
                    <div class="form-group">

                        <label for="opening_cash" class="form-label">
                            Opening Cash
                        </label>

                        <input
                            type="number"
                            id="opening_cash"
                            name="opening_cash"
                            class="form-control @error('opening_cash') is-invalid @enderror"
                            step="0.01"
                            min="0"
                            value="{{ old('opening_cash', 0) }}"
                            required
                        >

                        @error('opening_cash')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="form-actions">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Open Register
                    </button>

                    <a
                        href="{{ route('register-sessions.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>
                   
                </div>

            </form>

        </div>

    </div>

@endsection