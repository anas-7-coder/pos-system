{{-- @extends('layouts.app')

@section('title', 'Create Unit')

@section('content')

<h1>Create Unit</h1>

<hr>

<form action="{{ route('units.store') }}" method="POST">
    @csrf

    <div>
        <label>Name</label><br>
        <input type="text" name="name" value="{{ old('name') }}" required>
        @error('name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Short Name</label><br>
        <input type="text" name="short_name" value="{{ old('short_name') }}" required>
        @error('short_name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Code</label><br>
        <input type="text" name="code" value="{{ old('code') }}" required>
        @error('code')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>
            <input type="checkbox" name="allows_decimal" value="1" {{ old('allows_decimal') ? 'checked' : '' }}>
            Allows Decimal Quantity
        </label>
        @error('allows_decimal')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Precision</label><br>
        <input type="number" name="precision" value="{{ old('precision', 1) }}" step="0.0001" min="0.0001" required>
        @error('precision')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Description</label><br>
        <textarea name="description">{{ old('description') }}</textarea>
        @error('description')
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
        Create Unit
    </button>

</form>

@endsection --}}







@extends('layouts.app')

@section('title', 'Create Unit')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Create Unit</h1>
            <p class="page-subtitle">
                Add a new measurement unit for products and inventory.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('units.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Units
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
                <h2 class="card-title">Unit Information</h2>
                <p class="card-subtitle">
                    Enter the measurement unit details below.
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('units.store') }}"
                method="POST"
            >
                @csrf

                <div class="form-grid">

                    {{-- Name --}}
                    <div class="form-group">

                        <label for="name" class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            required
                        >

                        @error('name')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Short Name --}}
                    <div class="form-group">

                        <label for="short_name" class="form-label">
                            Short Name
                        </label>

                        <input
                            type="text"
                            id="short_name"
                            name="short_name"
                            class="form-control @error('short_name') is-invalid @enderror"
                            value="{{ old('short_name') }}"
                            required
                        >

                        @error('short_name')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Code --}}
                    <div class="form-group">

                        <label for="code" class="form-label">
                            Code
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code') }}"
                            required
                        >

                        @error('code')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Precision --}}
                    <div class="form-group">

                        <label for="precision" class="form-label">
                            Precision
                        </label>

                        <input
                            type="number"
                            id="precision"
                            name="precision"
                            class="form-control @error('precision') is-invalid @enderror"
                            value="{{ old('precision', 1) }}"
                            step="0.0001"
                            min="0.0001"
                            required
                        >

                        @error('precision')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Decimal Quantity --}}
                    <div class="form-group full-width">

                        <label class="form-label">
                            Quantity Settings
                        </label>

                        <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                            <input
                                type="checkbox"
                                name="allows_decimal"
                                value="1"
                                {{ old('allows_decimal') ? 'checked' : '' }}
                            >

                            <span>
                                Allows Decimal Quantity
                            </span>
                        </label>

                        @error('allows_decimal')
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

                    {{-- Description --}}
                    <div class="form-group full-width">

                        <label for="description" class="form-label">
                            Description
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="4"
                            placeholder="Optional unit description"
                        >{{ old('description') }}</textarea>

                        @error('description')
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
                        Create Unit
                    </button>
                    
                    <a
                        href="{{ route('units.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection