{{-- @extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')

<h1>Edit Customer</h1>

@if(session('error'))
    <p style="color: red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('customers.index') }}">Back to Customers</a>

<hr>

<form action="{{ route('customers.update', $customer) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label>Name</label><br>
        <input type="text" name="name" value="{{ old('name', $customer->name) }}">
        @error('name')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Customer Code</label><br>
        <input type="text" name="code" value="{{ old('code', $customer->code) }}">
        @error('code')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Customer Group</label><br>
        <select name="customer_group_id">
            <option value="">No Group</option>

            @foreach($customerGroups as $customerGroup)
                <option value="{{ $customerGroup->id }}" {{ old('customer_group_id', $customer->customer_group_id) == $customerGroup->id ? 'selected' : '' }}>
                    {{ $customerGroup->name }}
                </option>
            @endforeach
        </select>
        @error('customer_group_id')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Phone</label><br>
        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}">
        @error('phone')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Email</label><br>
        <input type="email" name="email" value="{{ old('email', $customer->email) }}">
        @error('email')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Address</label><br>
        <textarea name="address" rows="3">{{ old('address', $customer->address) }}</textarea>
        @error('address')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Tax Number</label><br>
        <input type="text" name="tax_number" value="{{ old('tax_number', $customer->tax_number) }}">
        @error('tax_number')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Opening Balance</label><br>
        <input type="number" name="opening_balance" value="{{ old('opening_balance', $customer->opening_balance) }}" min="0" step="0.01">
        @error('opening_balance')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Credit Limit</label><br>
        <input type="number" name="credit_limit" value="{{ old('credit_limit', $customer->credit_limit) }}" min="0" step="0.01">
        @error('credit_limit')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Status</label><br>
        <select name="status">
            <option value="Active" {{ old('status', $customer->status) === 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Inactive"{{ old('status', $customer->status) === 'Inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>

    <div>
        <label>Notes</label><br>
        <textarea name="notes" rows="3">{{ old('notes', $customer->notes) }}</textarea>
        @error('notes')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>
    <br>
<hr>
    <button type="submit">
        Update Customer
    </button>

</form>

@endsection --}}





@extends('layouts.app')

@section('title', 'Edit Customer')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Customer</h1>
            <p class="page-subtitle">
                Update the customer's contact, account, credit, and status information.
            </p>
        </div>

        <div class="page-actions">
            <a
                href="{{ route('customers.show', $customer) }}"
                class="btn btn-secondary"
            >
                ← Back to Customer
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
                <h2 class="card-title">Customer Information</h2>
                <p class="card-subtitle">
                    Make the required changes and save the customer.
                </p>
            </div>
        </div>

        <div class="card-body">

            <form
                action="{{ route('customers.update', $customer) }}"
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
                            value="{{ old('name', $customer->name) }}"
                            required
                        >

                        @error('name')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Customer Code --}}
                    <div class="form-group">

                        <label for="code" class="form-label">
                            Customer Code
                        </label>

                        <input
                            type="text"
                            id="code"
                            name="code"
                            class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code', $customer->code) }}"
                            required
                        >

                        @error('code')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Customer Group --}}
                    <div class="form-group">

                        <label for="customer_group_id" class="form-label">
                            Customer Group
                        </label>

                        <select
                            id="customer_group_id"
                            name="customer_group_id"
                            class="form-control @error('customer_group_id') is-invalid @enderror"
                        >
                            <option value="">
                                No Group
                            </option>

                            @foreach($customerGroups as $customerGroup)
                                <option
                                    value="{{ $customerGroup->id }}"
                                    {{ old('customer_group_id', $customer->customer_group_id) == $customerGroup->id ? 'selected' : '' }}
                                >
                                    {{ $customerGroup->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('customer_group_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Phone --}}
                    <div class="form-group">

                        <label for="phone" class="form-label">
                            Phone
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $customer->phone) }}"
                            placeholder="Phone number"
                        >

                        @error('phone')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Email --}}
                    <div class="form-group">

                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $customer->email) }}"
                            placeholder="customer@example.com"
                        >

                        @error('email')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Tax Number --}}
                    <div class="form-group">

                        <label for="tax_number" class="form-label">
                            Tax Number
                        </label>

                        <input
                            type="text"
                            id="tax_number"
                            name="tax_number"
                            class="form-control @error('tax_number') is-invalid @enderror"
                            value="{{ old('tax_number', $customer->tax_number) }}"
                            placeholder="Optional tax number"
                        >

                        @error('tax_number')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Opening Balance --}}
                    <div class="form-group">

                        <label for="opening_balance" class="form-label">
                            Opening Balance
                        </label>

                        <input
                            type="number"
                            id="opening_balance"
                            name="opening_balance"
                            class="form-control @error('opening_balance') is-invalid @enderror"
                            value="{{ old('opening_balance', $customer->opening_balance) }}"
                            min="0"
                            step="0.01"
                        >

                        @error('opening_balance')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Credit Limit --}}
                    <div class="form-group">

                        <label for="credit_limit" class="form-label">
                            Credit Limit
                        </label>

                        <input
                            type="number"
                            id="credit_limit"
                            name="credit_limit"
                            class="form-control @error('credit_limit') is-invalid @enderror"
                            value="{{ old('credit_limit', $customer->credit_limit) }}"
                            min="0"
                            step="0.01"
                        >

                        @error('credit_limit')
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
                                {{ old('status', $customer->status) === 'Active' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                {{ old('status', $customer->status) === 'Inactive' ? 'selected' : '' }}
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
                    <div class="form-group full-width">

                        <label for="address" class="form-label">
                            Address
                        </label>

                        <textarea
                            id="address"
                            name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            rows="3"
                            placeholder="Customer address"
                        >{{ old('address', $customer->address) }}</textarea>

                        @error('address')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Notes --}}
                    <div class="form-group full-width">

                        <label for="notes" class="form-label">
                            Notes
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            class="form-control @error('notes') is-invalid @enderror"
                            rows="4"
                            placeholder="Optional customer notes"
                        >{{ old('notes', $customer->notes) }}</textarea>

                        @error('notes')
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
                        Update Customer
                    </button>
                    
                    <a
                        href="{{ route('customers.show', $customer) }}"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection