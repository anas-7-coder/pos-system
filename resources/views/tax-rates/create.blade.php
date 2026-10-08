{{-- @extends('layouts.app')

@section('title', 'Create Tax Rate')

@section('content')

<h1>Create Tax Rate</h1>

<hr>

<form action="{{ route('tax-rates.store') }}" method="POST">
    @csrf

    <div>
        <label>Tax Class</label><br>
        <select name="tax_class_id" required>
            <option value="">Select Tax Class</option>

            @foreach($taxClasses as $taxClass)
                <option value="{{ $taxClass->id }}" {{ old('tax_class_id') == $taxClass->id ? 'selected' : '' }}>
                    {{ $taxClass->name }}
                </option>
            @endforeach
        </select>
        @error('tax_class_id')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

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
        <label>Rate (%)</label><br>
        <input type="number" name="rate" value="{{ old('rate', 0) }}" step="0.0001" min="0" max="100" required>
        @error('rate')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Effective From</label><br>
        <input type="date" name="effective_from" value="{{ old('effective_from') }}" required>
        @error('effective_from')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Effective To</label><br>
        <input type="date" name="effective_to" value="{{ old('effective_to') }}">
        @error('effective_to')
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
        Create Tax Rate
    </button>

</form>

@endsection --}}





@extends('layouts.app')

@section('title', 'Create Tax Rate')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Create Tax Rate</h1>
            <p class="page-subtitle">
                Add a new tax rate and define its effective period.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('tax-rates.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Tax Rates
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
                <h2 class="card-title">Tax Rate Information</h2>
                <p class="card-subtitle">
                    Enter the tax rate details below.
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('tax-rates.store') }}"
                method="POST"
            >
                @csrf

                <div class="form-grid">

                    {{-- Tax Class --}}
                    <div class="form-group">

                        <label for="tax_class_id" class="form-label">
                            Tax Class
                        </label>

                        <select
                            id="tax_class_id"
                            name="tax_class_id"
                            class="form-control @error('tax_class_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Tax Class
                            </option>

                            @foreach($taxClasses as $taxClass)
                                <option
                                    value="{{ $taxClass->id }}"
                                    {{ old('tax_class_id') == $taxClass->id ? 'selected' : '' }}
                                >
                                    {{ $taxClass->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('tax_class_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

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

                    {{-- Rate --}}
                    <div class="form-group">

                        <label for="rate" class="form-label">
                            Rate (%)
                        </label>

                        <input
                            type="number"
                            id="rate"
                            name="rate"
                            class="form-control @error('rate') is-invalid @enderror"
                            value="{{ old('rate', 0) }}"
                            step="0.0001"
                            min="0"
                            max="100"
                            required
                        >

                        @error('rate')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Effective From --}}
                    <div class="form-group">

                        <label for="effective_from" class="form-label">
                            Effective From
                        </label>

                        <input
                            type="date"
                            id="effective_from"
                            name="effective_from"
                            class="form-control @error('effective_from') is-invalid @enderror"
                            value="{{ old('effective_from') }}"
                            required
                        >

                        @error('effective_from')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Effective To --}}
                    <div class="form-group">

                        <label for="effective_to" class="form-label">
                            Effective To
                        </label>

                        <input
                            type="date"
                            id="effective_to"
                            name="effective_to"
                            class="form-control @error('effective_to') is-invalid @enderror"
                            value="{{ old('effective_to') }}"
                        >

                        @error('effective_to')
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
                            placeholder="Optional tax rate description"
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
                        Create Tax Rate
                    </button>
                    
                    <a
                        href="{{ route('tax-rates.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection