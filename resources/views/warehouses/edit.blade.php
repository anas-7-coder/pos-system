{{-- @extends('layouts.app')

@section('title', 'Edit Warehouse')

@section('content')

<h1>Edit Warehouse</h1>

<a href="{{ route('warehouses.index', $warehouse) }}">Back to Warehouse</a>

<hr>

<form action="{{ route('warehouses.update', $warehouse) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Branch</label><br>
        <select name="branch_id">
            <option value="">Select Branch</option>

            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ old('branch_id', $warehouse->branch_id) == $branch->id ? 'selected' : '' }}>
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
        <input type="text" name="name" value="{{ old('name', $warehouse->name) }}">
        @error('name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Code</label><br>
        <input type="text" name="code" value="{{ old('code', $warehouse->code) }}">
        @error('code')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Address</label><br>
        <textarea name="address">{{ old('address', $warehouse->address) }}</textarea>
        @error('address')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>
            <input type="checkbox" name="is_default" value="1" {{ old('is_default', $warehouse->is_default) ? 'checked' : '' }}>
            Default Warehouse
        </label>
        @error('is_default')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Status</label><br>
        <select name="status">
            <option value="Active" {{ old('status', $warehouse->status) === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ old('status', $warehouse->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
        Update Warehouse
    </button>

</form>

@endsection --}}







@extends('layouts.app')

@section('title', 'Edit Warehouse')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Edit Warehouse
        </h1>

        <p class="page-subtitle">
            Update warehouse information and configuration.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('warehouses.index') }}"
            class="btn btn-secondary"
        >
            Back to Warehouses
        </a>

        @can('view', $warehouse)

            <a
                href="{{ route('warehouses.show', $warehouse) }}"
                class="btn btn-outline-primary"
            >
                View Warehouse
            </a>

        @endcan

    </div>

</div>


<div class="card form-card">

    <div class="card-header">

        <h2 class="card-title">
            Warehouse Information
        </h2>

        <div class="action-group">

            @if($warehouse->is_default)

                <span class="badge badge-primary">
                    Default
                </span>

            @endif

            @if($warehouse->status === 'Active')

                <span class="badge badge-success">
                    Active
                </span>

            @else

                <span class="badge badge-danger">
                    Inactive
                </span>

            @endif

        </div>

    </div>


    <div class="card-body">

        <form
            action="{{ route('warehouses.update', $warehouse) }}"
            method="POST"
        >

            @csrf

            @method('PUT')

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
                                {{ old('branch_id', $warehouse->branch_id) == $branch->id ? 'selected' : '' }}
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


                {{-- Warehouse Name --}}
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
                        value="{{ old('name', $warehouse->name) }}"
                        placeholder="Enter warehouse name"
                        required
                    >

                    @error('name')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Warehouse Code --}}
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
                        value="{{ old('code', $warehouse->code) }}"
                        placeholder="e.g. WH-LHR-001"
                        required
                    >

                    @error('code')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Default Warehouse --}}
                <div class="form-group">

                    <label class="form-label">
                        Warehouse Settings
                    </label>

                    <label
                        style="
                            display:flex;
                            align-items:center;
                            gap:8px;
                            min-height:44px;
                            cursor:pointer;
                        "
                    >

                        <input
                            type="checkbox"
                            name="is_default"
                            value="1"
                            {{ old('is_default', $warehouse->is_default) ? 'checked' : '' }}
                        >

                        <span>
                            Set as default warehouse
                        </span>

                    </label>

                    @error('is_default')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                    <div class="form-help">
                        This warehouse will be used as the default warehouse for its branch.
                    </div>

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
                            {{ old('status', $warehouse->status) === 'Active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            {{ old('status', $warehouse->status) === 'Inactive' ? 'selected' : '' }}
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


                {{-- Address --}}
                <div class="form-group full">

                    <label
                        for="address"
                        class="form-label"
                    >
                        Address
                    </label>

                    <textarea
                        name="address"
                        id="address"
                        rows="4"
                        placeholder="Enter warehouse address"
                    >{{ old('address', $warehouse->address) }}</textarea>

                    @error('address')

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
                        Update Warehouse
                    </button>

                    <a
                        href="{{ route('warehouses.index') }}"
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