{{-- @extends('layouts.app')

@section('title', 'Process Refund')

@section('content')

<h1>Process Refund</h1>

@if(session('error'))
    <p style="color:red">
        {{ session('error') }}
    </p>
@endif

@if($errors->any())
    <div style="color:red">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<hr>

<p><strong>Return:</strong> {{ $saleReturn->return_number }}</p>
<p><strong>Invoice:</strong> {{ $saleReturn->sale->invoice_number }}</p>
<p><strong>Maximum Refund:</strong> {{ number_format($saleReturn->grand_total, 2) }}</p>

<form action="{{ route('sale-returns.refunds.store', $saleReturn) }}" method="POST">
    @csrf

    <div>
        <label for="register_session_id">Register Session</label><br>
        <select name="register_session_id" id="register_session_id" required>
            <option value="">Select Register Session</option>

            @foreach($registerSessions as $session)
                <option value="{{ $session->id }}" {{ old('register_session_id') == $session->id ? 'selected' : '' }}>
                    {{ $session->register->name }} — Session #{{ $session->id }}
                </option>
            @endforeach
        </select>
    </div>
    <br>

    <div>
        <label for="payment_method">Refund Method</label><br>
        <select name="payment_method" id="payment_method" required>
            <option value="">Select Method</option>
            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
            <option value="mobile_wallet" {{ old('payment_method') === 'mobile_wallet' ? 'selected' : '' }}>Mobile Wallet</option>
        </select>
    </div>
    <br>

    <div id="refund-amount-field" style="display:none;">
        <label for="amount">Refund Amount</label><br>
        <input type="number" name="amount" id="amount" step="0.01" min="0.01" max="{{ $saleReturn->grand_total }}" value="{{ old('amount') }}">
        <p>Maximum: {{ number_format($saleReturn->grand_total, 2) }}</p>
    </div>
    <br>

    <div id="reference-field" style="display:none;">
        <label for="reference">Reference</label><br>
        <input type="text" name="reference" id="reference" value="{{ old('reference') }}">
    </div>
    
<hr>
    <button type="submit">
        Process Refund
    </button>
        &nbsp; | &nbsp;
    <a href="{{ route('sale-returns.show', $saleReturn) }}"><button type="button">Cancel</button></a>

</form>

<script>
    const paymentMethod     = document.getElementById('payment_method');
    const refundAmountField = document.getElementById('refund-amount-field');
    const amountInput       = document.getElementById('amount');
    const referenceField    = document.getElementById('reference-field');
    const referenceInput    = document.getElementById('reference');

    function updateRefundFields() {
        if (paymentMethod.value === 'cash') {

            refundAmountField.style.display = 'block';
            amountInput.setAttribute('required', 'required');

            referenceField.style.display = 'none';

            referenceInput.removeAttribute('required');
            referenceInput.value = '';
        } else if (
            paymentMethod.value === 'card' ||
            paymentMethod.value === 'bank_transfer' ||
            paymentMethod.value === 'mobile_wallet'
        ) {

            refundAmountField.style.display = 'none';

            amountInput.removeAttribute('required');
            amountInput.value = '';

            referenceField.style.display = 'block';

            referenceInput.setAttribute('required', 'required');

        } else {

            refundAmountField.style.display = 'none';
            referenceField.style.display = 'none';

            amountInput.removeAttribute('required');
            referenceInput.removeAttribute('required');

            amountInput.value = '';
            referenceInput.value = '';
        }
    }

    paymentMethod.addEventListener('change', updateRefundFields);
    updateRefundFields();

</script>

@endsection --}}








@extends('layouts.app')

@section('title', 'Process Refund')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Process Refund</h1>
            <p class="page-subtitle">
                Process a refund for the completed sale return using the selected payment method.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('sale-returns.show', $saleReturn) }}"
               class="btn btn-secondary">
                ← Back to Return
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

    @if($errors->any())
        <div class="alert alert-danger">

            <strong>Please correct the following errors:</strong>

            <ul style="margin:10px 0 0 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    @endif

    {{-- Return Information --}}
    <div class="card">

        <div class="card-header">
            <h2>Return Information</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">Return Number</div>
                    <div class="detail-value">
                        <strong>
                            {{ $saleReturn->return_number }}
                        </strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Invoice</div>
                    <div class="detail-value">
                        <a href="{{ route('sales.show', $saleReturn->sale) }}">
                            {{ $saleReturn->sale->invoice_number }}
                        </a>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Customer</div>
                    <div class="detail-value">
                        {{ $saleReturn->sale->customer?->name ?? 'Walk-in Customer' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Return Status</div>
                    <div class="detail-value">
                        <span class="badge badge-success">
                            {{ ucwords(str_replace('_', ' ', $saleReturn->status)) }}
                        </span>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Maximum Refund</div>
                    <div class="detail-value">
                        <strong>
                            PKR {{ number_format((float) $saleReturn->grand_total, 2) }}
                        </strong>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <form action="{{ route('sale-returns.refunds.store', $saleReturn) }}"
          method="POST">

        @csrf

        {{-- Refund Details --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Refund Details</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    {{-- Register Session --}}
                    <div class="form-group">

                        <label for="register_session_id" class="form-label">
                            Register Session
                        </label>

                        <select
                            name="register_session_id"
                            id="register_session_id"
                            class="@error('register_session_id') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Register Session
                            </option>

                            @foreach($registerSessions as $session)
                                <option
                                    value="{{ $session->id }}"
                                    {{ old('register_session_id') == $session->id ? 'selected' : '' }}
                                >
                                    {{ $session->register->name }}
                                    — Session #{{ $session->id }}
                                </option>
                            @endforeach

                        </select>

                        @error('register_session_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Refund Method --}}
                    <div class="form-group">

                        <label for="payment_method" class="form-label">
                            Refund Method
                        </label>

                        <select
                            name="payment_method"
                            id="payment_method"
                            class="@error('payment_method') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Refund Method
                            </option>

                            <option
                                value="cash"
                                {{ old('payment_method') === 'cash' ? 'selected' : '' }}
                            >
                                Cash
                            </option>

                            <option
                                value="card"
                                {{ old('payment_method') === 'card' ? 'selected' : '' }}
                            >
                                Card
                            </option>

                            <option
                                value="bank_transfer"
                                {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}
                            >
                                Bank Transfer
                            </option>

                            <option
                                value="mobile_wallet"
                                {{ old('payment_method') === 'mobile_wallet' ? 'selected' : '' }}
                            >
                                Mobile Wallet
                            </option>
                        </select>

                        @error('payment_method')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>
        </div>

        {{-- Cash Refund --}}
        <div class="card form-card"
             id="refund-amount-field"
             style="display:none;">

            <div class="card-header">
                <h2>Cash Refund</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    <div class="form-group">

                        <label for="amount" class="form-label">
                            Refund Amount
                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            step="0.01"
                            min="0.01"
                            max="{{ $saleReturn->grand_total }}"
                            value="{{ old('amount') }}"
                            class="@error('amount') is-invalid @enderror"
                            placeholder="Enter refund amount"
                        >

                        <div style="font-size:12px; color:#64748b; margin-top:6px;">
                            Maximum refund:
                            PKR {{ number_format((float) $saleReturn->grand_total, 2) }}
                        </div>

                        @error('amount')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>
        </div>

        {{-- Non-Cash Reference --}}
        <div class="card form-card"
             id="reference-field"
             style="display:none;">

            <div class="card-header">
                <h2>Refund Reference</h2>
            </div>

            <div class="card-body">

                <div class="form-group">

                    <label for="reference" class="form-label">
                        Reference
                    </label>

                    <input
                        type="text"
                        name="reference"
                        id="reference"
                        value="{{ old('reference') }}"
                        class="@error('reference') is-invalid @enderror"
                        placeholder="Transaction number, card reference, transfer ID, etc."
                    >

                    @error('reference')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="card">

            <div class="card-body">

                <div class="form-actions">

                    <button type="submit"
                            class="btn btn-primary">
                        Process Refund
                    </button>
                    
                    <a href="{{ route('sale-returns.show', $saleReturn) }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </div>
        </div>

    </form>

    <script>
        const paymentMethod = document.getElementById('payment_method');

        const refundAmountField = document.getElementById('refund-amount-field');
        const amountInput = document.getElementById('amount');

        const referenceField = document.getElementById('reference-field');
        const referenceInput = document.getElementById('reference');

        function updateRefundFields()
        {
            const method = paymentMethod.value;

            if (method === 'cash') {

                refundAmountField.style.display = 'block';
                amountInput.setAttribute('required', 'required');

                referenceField.style.display = 'none';
                referenceInput.removeAttribute('required');
                referenceInput.value = '';

            } else if (
                method === 'card' ||
                method === 'bank_transfer' ||
                method === 'mobile_wallet'
            ) {

                refundAmountField.style.display = 'none';
                amountInput.removeAttribute('required');
                amountInput.value = '';

                referenceField.style.display = 'block';
                referenceInput.setAttribute('required', 'required');

            } else {

                refundAmountField.style.display = 'none';
                referenceField.style.display = 'none';

                amountInput.removeAttribute('required');
                referenceInput.removeAttribute('required');

                amountInput.value = '';
                referenceInput.value = '';
            }
        }

        paymentMethod.addEventListener(
            'change',
            updateRefundFields
        );

        updateRefundFields();
    </script>

@endsection