{{-- @extends('layouts.app')

@section('title', 'Edit Branch')

@section('content')

<h1>Edit Branch</h1>

<a href="{{ route('branches.index', $branch) }}">Back to Branch</a>

<hr>

<form action="{{ route('branches.update', $branch) }}" method="POST">

    @csrf
    @method('PUT')

    <div>
        <label>Company</label><br>
        <select name="company_id">
            <option value="">Select Company</option>

            @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ old('company_id', $branch->company_id) == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
            @endforeach
        </select>
        @error('company_id')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Name</label><br>
        <input type="text" name="name" value="{{ old('name', $branch->name) }}">
        @error('name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Code</label><br>
        <input type="text" name="code" value="{{ old('code', $branch->code) }}">
        @error('code')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email', $branch->email) }}">
        @error('email')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Phone</label><br>
        <input type="text" name="phone" value="{{ old('phone', $branch->phone) }}">
        @error('phone')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Address</label><br>
        <textarea name="address">{{ old('address', $branch->address) }}</textarea>
        @error('address')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Status</label><br>
        <select name="status">
            <option value="Active" {{ old('status', $branch->status) === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ old('status', $branch->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
        Update Branch
    </button>

</form>

@endsection --}}












@extends('layouts.app')

@section('title', 'Edit Branch')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Edit Branch
        </h1>

        <p class="page-subtitle">
            Update branch information and configuration.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('branches.index') }}" 
            class="btn btn-secondary"
        >
            Back to Branches
        </a>

        @can('view', $branch)

            <a
                href="{{ route('branches.show', $branch) }}"
                class="btn btn-outline-primary"
            >
                View Branch
            </a>

        @endcan

    </div>

</div>


<div class="card form-card">

    <div class="card-header">

        <h2 class="card-title">
            Branch Information
        </h2>

        @if($branch->status === 'Active')

            <span class="badge badge-success">
                Active
            </span>

        @else

            <span class="badge badge-danger">
                Inactive
            </span>

        @endif

    </div>


    <div class="card-body">

        <form
            action="{{ route('branches.update', $branch) }}"
            method="POST"
        >

            @csrf

            @method('PUT')

            <div class="form-grid">


                {{-- Company --}}
                <div class="form-group full">

                    <label
                        for="company_id"
                        class="form-label"
                    >
                        Company
                        <span class="required">*</span>
                    </label>

                    <select
                        name="company_id"
                        id="company_id"
                        required
                    >

                        <option value="">
                            Select Company
                        </option>

                        @foreach($companies as $company)

                            <option
                                value="{{ $company->id }}"
                                {{ old('company_id', $branch->company_id) == $company->id ? 'selected' : '' }}
                            >
                                {{ $company->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('company_id')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Branch Name --}}
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
                        value="{{ old('name', $branch->name) }}"
                        placeholder="Enter branch name"
                        required
                    >

                    @error('name')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Branch Code --}}
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
                        value="{{ old('code', $branch->code) }}"
                        placeholder="e.g. LHR-001"
                        required
                    >

                    @error('code')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Email --}}
                <div class="form-group">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $branch->email) }}"
                        placeholder="branch@example.com"
                    >

                    @error('email')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Phone --}}
                <div class="form-group">

                    <label
                        for="phone"
                        class="form-label"
                    >
                        Phone
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $branch->phone) }}"
                        placeholder="Enter branch phone"
                    >

                    @error('phone')

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
                            {{ old('status', $branch->status) === 'Active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            {{ old('status', $branch->status) === 'Inactive' ? 'selected' : '' }}
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
                        placeholder="Enter branch address"
                    >{{ old('address', $branch->address) }}</textarea>

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
                style="margin: 25px -22px -22px;"
            >

                <div class="action-group">

                    <button
                        type="submit"
                        class="btn btn-primary" 
                    >
                        Update Branch
                    </button>

                    <a
                        href="{{ route('branches.index') }}"
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