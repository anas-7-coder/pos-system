{{-- @extends('layouts.app')

@section('title', 'Edit Customer Group')

@section('content')

<h1>Edit Customer Group</h1>

@if(session('error'))
    <p style="color: red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('customer-groups.index') }}">Back to Customer Groups</a>

<hr>

<form action="{{ route('customer-groups.update', $customerGroup) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Name</label><br>
        <input type="text" name="name" value="{{ old('name', $customerGroup->name) }}">
        @error('name')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Code</label><br>
        <input type="text" name="code" value="{{ old('code', $customerGroup->code) }}">
        @error('code')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Description</label><br>
        <textarea name="description" rows="3">{{ old('description', $customerGroup->description) }}</textarea>
        @error('description')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Status</label><br>
        <select name="status">
            <option value="Active" {{ old('status', $customerGroup->status) === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ old( 'status', $customerGroup->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Update Customer Group
    </button>

</form>

@endsection --}}






@extends('layouts.app')

@section('title', 'Edit Customer Group')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Customer Group</h1>
            <p class="page-subtitle">
                Update the customer group information and status.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('customer-groups.show', $customerGroup) }}"
                class="btn btn-secondary"
            >
                ← Back to Customer Group
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
                <h2 class="card-title">Customer Group Information</h2>
                <p class="card-subtitle">
                    Make the required changes and save the customer group.
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('customer-groups.update', $customerGroup) }}"
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
                            value="{{ old('name', $customerGroup->name) }}"
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
                            value="{{ old('code', $customerGroup->code) }}"
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
                            required
                        >
                            <option
                                value="Active"
                                {{ old('status', $customerGroup->status) === 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ old('status', $customerGroup->status) === 'Inactive' ? 'selected' : '' }}
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
                            placeholder="Optional customer group description"
                        >{{ old('description', $customerGroup->description) }}</textarea>

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
                        Update Customer Group
                    </button>
                    
                    <a
                        href="{{ route('customer-groups.show', $customerGroup) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a> 

                </div>

            </form>

        </div>

    </div>

@endsection