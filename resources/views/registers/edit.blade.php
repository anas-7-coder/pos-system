{{-- @extends('layouts.app')

@section('title', 'Edit Register')

@section('content')

<h1>Edit Register</h1>

<a href="{{ route('registers.index', $register) }}">Back to Register</a>

<hr>

<form action="{{ route('registers.update', $register) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Branch</label><br>
        <select name="branch_id" required>
            <option value="">Select Branch</option>

            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ old('branch_id', $register->branch_id) == $branch->id ? 'selected' : '' }}>
                    {{ $branch->name }}
                </option>
            @endforeach
        </select>
        @error('branch_id')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Name</label><br>
        <input type="text" name="name" value="{{ old('name', $register->name) }}">
        @error('name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Code</label><br>
        <input type="text" name="code" value="{{ old('code', $register->code) }}">
        @error('code')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Device Identifier</label><br>
        <input type="text" name="device_identifier" value="{{ old('device_identifier', $register->device_identifier) }}">
        @error('device_identifier')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Status</label><br>
        <select name="status">
            <option value="Active" {{ old('status', $register->status) === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ old('status', $register->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Update Register
    </button>

</form>

@endsection --}}





@extends('layouts.app')

@section('title', 'Edit Register')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Register</h1>
            <p class="page-subtitle">
                Update the register configuration and status.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('registers.show', $register) }}"
                class="btn btn-secondary"
            >
                ← Back to Register
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
                <h2 class="card-title">Register Information</h2>
                <p class="card-subtitle">
                    Make the required changes and save the register.
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('registers.update', $register) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

                <div class="form-grid">

                    {{-- Branch --}}
                    <div class="form-group">

                        <label for="branch_id" class="form-label">
                            Branch
                        </label>

                        <select
                            id="branch_id"
                            name="branch_id"
                            class="form-control @error('branch_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Branch</option>

                            @foreach($branches as $branch)
                                <option
                                    value="{{ $branch->id }}"
                                    {{ old('branch_id', $register->branch_id) == $branch->id ? 'selected' : '' }}
                                >
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('branch_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Name --}}
                    <div class="form-group">

                        <label for="name" class="form-label">
                            Register Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $register->name) }}"
                            required
                        >

                        @error('name')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Code --}}
                    <div class="form-group">

                        <label for="code" class="form-label">
                            Register Code
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code', $register->code) }}"
                            required
                        >

                        @error('code')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Device Identifier --}}
                    <div class="form-group">

                        <label for="device_identifier" class="form-label">
                            Device Identifier
                        </label>

                        <input
                            type="text"
                            id="device_identifier"
                            name="device_identifier"
                            class="form-control @error('device_identifier') is-invalid @enderror"
                            value="{{ old('device_identifier', $register->device_identifier) }}"
                            placeholder="Optional device identifier"
                        >

                        @error('device_identifier')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Status --}}
                    <div class="form-group">

                        <label for="status" class="form-label">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="form-control @error('status') is-invalid @enderror"
                            required
                        >
                            <option
                                value="Active"
                                {{ old('status', $register->status) === 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ old('status', $register->status) === 'Inactive' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>
                        </select>

                        @error('status')
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
                        Update Register
                    </button>
                    
                    <a
                        href="{{ route('registers.show', $register) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection