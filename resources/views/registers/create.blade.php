{{-- @extends('layouts.app')

@section('title', 'Create Register')

@section('content')

<h1>Create Register</h1>

<hr>

<form action="{{ route('registers.store') }}" method="POST">
    @csrf

    <div>
        <label>Branch</label><br>
        <select name="branch_id" required>
            <option value="">Select Branch</option>

            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
        <input type="text" name="name" value="{{ old('name') }}">
        @error('name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Code</label><br>
        <input type="text" name="code" value="{{ old('code') }}">
        @error('code')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Device Identifier</label><br>
        <input type="text" name="device_identifier" value="{{ old('device_identifier') }}">
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
            <option value="Active" {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ old('status') === 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
        Create Register
    </button>

</form>

@endsection --}}








@extends('layouts.app')

@section('title', 'Create Register')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Create Register
        </h1>

        <p class="page-subtitle">
            Add a new POS register to a branch.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('registers.index') }}"
            class="btn btn-secondary"
        >
            Back to Registers
        </a>

    </div>

</div>


<div class="card form-card">

    <div class="card-header">

        <h2 class="card-title">
            Register Information
        </h2>

    </div>


    <div class="card-body">

        <form
            action="{{ route('registers.store') }}"
            method="POST"
        >

            @csrf

            <div class="form-grid">


                {{-- Branch --}}
                <div class="form-group full">

                    <label
                        for="branch_id"
                        class="form-label"
                    >
                        Branch
                        <span class="required">*</span>
                    </label>

                    <select
                        name="branch_id"
                        id="branch_id"
                        required
                    >

                        <option value="">
                            Select Branch
                        </option>

                        @foreach($branches as $branch)

                            <option
                                value="{{ $branch->id }}"
                                {{ old('branch_id') == $branch->id ? 'selected' : '' }}
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


                {{-- Register Name --}}
                <div class="form-group">

                    <label
                        for="name"
                        class="form-label"
                    >
                        Name
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter register name"
                        required
                    >

                    @error('name')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Register Code --}}
                <div class="form-group">

                    <label
                        for="code"
                        class="form-label"
                    >
                        Code
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code') }}"
                        placeholder="e.g. REG-LHR-001"
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

                    <label
                        for="device_identifier"
                        class="form-label"
                    >
                        Device Identifier
                    </label>

                    <input
                        type="text"
                        id="device_identifier"
                        name="device_identifier"
                        value="{{ old('device_identifier') }}"
                        placeholder="Enter device identifier"
                    >

                    <div class="form-help">
                        Use a unique identifier for the POS device.
                    </div>

                    @error('device_identifier')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Status --}}
                <div class="form-group">

                    <label
                        for="status"
                        class="form-label"
                    >
                        Status
                        <span class="required">*</span>
                    </label>

                    <select
                        name="status"
                        id="status"
                        required
                    >

                        <option
                            value="Active"
                            {{ old('status', 'Active') === 'Active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            {{ old('status') === 'Inactive' ? 'selected' : '' }}
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


            {{-- Form Actions --}}
            <div
                class="card-footer"
                style="margin:25px -22px -22px;"
            >

                <div class="action-group">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Create Register
                    </button>

                    <a
                        href="{{ route('registers.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection