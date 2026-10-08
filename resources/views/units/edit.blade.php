{{-- @extends('layouts.app')

@section('title', 'Edit Unit')

@section('content')

<h1>Edit Unit</h1>

<a href="{{ route('units.index', $unit) }}">Back to Unit</a>

<hr>

<form action="{{ route('units.update', $unit) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Name</label><br>
        <input type="text" name="name" value="{{ old('name', $unit->name) }}" required>
        @error('name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Short Name</label><br>
        <input type="text" name="short_name" value="{{ old('short_name', $unit->short_name) }}" required>
        @error('short_name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Code</label><br>
        <input type="text" name="code" value="{{ old('code', $unit->code) }}" required>
        @error('code')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>
            <input type="checkbox" name="allows_decimal" value="1" {{ old('allows_decimal', $unit->allows_decimal) ? 'checked' : '' }}>
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
        <input type="number" name="precision" value="{{ old('precision', $unit->precision) }}" step="0.0001" min="0.0001" required>
        @error('precision')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Description</label><br>
        <textarea name="description">{{ old('description', $unit->description) }}</textarea>
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
            <option value="Active" {{ old('status', $unit->status) === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ old('status', $unit->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
        Update Unit
    </button>

</form>

@endsection --}}









@extends('layouts.app')

@section('title', 'Edit Unit')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Unit</h1>
            <p class="page-subtitle">
                Update the measurement unit, quantity settings, and status.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('units.show', $unit) }}"
                class="btn btn-secondary"
            >
                ← Back to Unit
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
                    Make the required changes and save the unit.
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('units.update', $unit) }}"
                method="POST"
            >
                @csrf
                @method('PUT')

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
                            value="{{ old('name', $unit->name) }}"
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
                            value="{{ old('short_name', $unit->short_name) }}"
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
                            value="{{ old('code', $unit->code) }}"
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
                            value="{{ old('precision', $unit->precision) }}"
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
                                {{ old('allows_decimal', $unit->allows_decimal) ? 'checked' : '' }}
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
                            required
                        >
                            <option
                                value="Active"
                                {{ old('status', $unit->status) === 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ old('status', $unit->status) === 'Inactive' ? 'selected' : '' }}
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
                        >{{ old('description', $unit->description) }}</textarea>

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
                        Update Unit
                    </button>

                    <a
                        href="{{ route('units.show', $unit) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection