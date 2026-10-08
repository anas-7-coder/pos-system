{{-- @extends('layouts.app')

@section('title', 'Create Tax Class')

@section('content')

<h1>Create Tax Class</h1>

<hr>

<form action="{{ route('tax-classes.store') }}" method="POST">
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
        Create Tax Class
    </button>

</form>

@endsection --}}







@extends('layouts.app')

@section('title', 'Create Tax Class')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Create Tax Class</h1>
            <p class="page-subtitle">
                Add a new tax classification for products and tax rates.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('tax-classes.index') }}"
                class="btn btn-secondary"
            >
                ← Back to Tax Classes
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
                <h2 class="card-title">Tax Class Information</h2>
                <p class="card-subtitle">
                    Enter the tax class details below.
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('tax-classes.store') }}"
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
                            placeholder="Optional tax class description"
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
                        Create Tax Class
                    </button>

                    <a
                        href="{{ route('tax-classes.index') }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection