{{-- @extends('layouts.app')

@section('title', 'Edit Company')

@section('content')

<h1>Edit Company</h1>

<a href="{{ route('companies.index', $company) }}">
    Back to Company
</a>

<hr>

<form action="{{ route('companies.update', $company) }}" method="POST">

    @csrf
    @method('PUT')

    <div>
        <label>Name</label><br>
        <input type="text" name="name" value="{{ old('name', $company->name) }}">
        @error('name')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Code</label><br>
        <input type="text" name="code" value="{{ old('code', $company->code) }}">
        @error('code')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email', $company->email) }}">
        @error('email')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Phone</label><br>
        <input type="text" name="phone" value="{{ old('phone', $company->phone) }}">
        @error('phone')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Address</label><br>
        <textarea name="address">{{ old('address', $company->address) }}</textarea>
        @error('address')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Tax Number</label><br>
        <input type="text" name="tax_number" value="{{ old('tax_number', $company->tax_number) }}">
        @error('tax_number')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Currency</label><br>
        <input type="text" name="currency" value="{{ old('currency', $company->currency) }}" maxlength="3">
        @error('currency')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Timezone</label><br>
        <input type="text" name="timezone" value="{{ old('timezone', $company->timezone) }}">
        @error('timezone')
            <p style="color:red;">
                {{ $message }}
            </p>
        @enderror
    </div>
    <br>

    <div>
        <label>Status</label><br>
        <select name="status">
            <option value="Active" {{ old('status', $company->status) === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive" {{ old('status', $company->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
        Update Company
    </button>

</form>

@endsection --}}






@extends('layouts.app')

@section('title', 'Edit Company')

@section('content')

<div class="page-header">

    <div>
        <h1 class="page-title">
            Edit Company
        </h1>

        <p class="page-subtitle">
            Update company information and configuration.
        </p>
    </div>

    <div class="page-actions">

        <a
            href="{{ route('companies.index') }}"
            class="btn btn-secondary"
        >
            Back to Companies
        </a>

        @can('view', $company)

            <a
                href="{{ route('companies.show', $company) }}"
                class="btn btn-outline-primary"
            >
                View Company
            </a>

        @endcan

    </div>

</div>


<div class="card form-card">

    <div class="card-header">

        <h2 class="card-title">
            Company Information
        </h2>

        @if($company->status === 'Active')

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
            action="{{ route('companies.update', $company) }}"
            method="POST"
        >

            @csrf

            @method('PUT')

            <div class="form-grid">


                {{-- Company Name --}}
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
                        value="{{ old('name', $company->name) }}"
                        placeholder="Enter company name"
                        required
                    >

                    @error('name')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Company Code --}}
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
                        value="{{ old('code', $company->code) }}"
                        placeholder="e.g. COMP-001"
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
                        value="{{ old('email', $company->email) }}"
                        placeholder="company@example.com"
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
                        value="{{ old('phone', $company->phone) }}"
                        placeholder="Enter phone number"
                    >

                    @error('phone')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Tax Number --}}
                <div class="form-group">

                    <label
                        for="tax_number"
                        class="form-label"
                    >
                        Tax Number
                    </label>

                    <input
                        type="text"
                        id="tax_number"
                        name="tax_number"
                        value="{{ old('tax_number', $company->tax_number) }}"
                        placeholder="Enter tax number"
                    >

                    @error('tax_number')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Currency --}}
                <div class="form-group">

                    <label
                        for="currency"
                        class="form-label"
                    >
                        Currency
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="currency"
                        name="currency"
                        value="{{ old('currency', $company->currency) }}"
                        maxlength="3"
                        placeholder="PKR"
                        required
                    >

                    <div class="form-help">
                        Use a 3-letter currency code.
                    </div>

                    @error('currency')

                        <div class="field-error">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Timezone --}}
                <div class="form-group">

                    <label
                        for="timezone"
                        class="form-label"
                    >
                        Timezone
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="timezone"
                        name="timezone"
                        value="{{ old('timezone', $company->timezone) }}"
                        placeholder="Asia/Karachi"
                        required
                    >

                    @error('timezone')

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
                            {{ old('status', $company->status) === 'Active' ? 'selected' : '' }}
                        >
                            Active
                        </option>

                        <option
                            value="Inactive"
                            {{ old('status', $company->status) === 'Inactive' ? 'selected' : '' }}
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
                        placeholder="Enter company address"
                    >{{ old('address', $company->address) }}</textarea>

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
                        Update Company
                    </button>

                    <a
                        href="{{ route('companies.index') }}"
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