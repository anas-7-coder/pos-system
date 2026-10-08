{{-- @extends('layouts.app')

@section('title', 'Receive Payment')

@section('content')

<h1>Receive Payment</h1>

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

<h3>Sale Information</h3>

<p><strong>Invoice:</strong> {{ $sale->invoice_number }}</p>
<p><strong>Customer:</strong> {{ $sale->customer?->name ?? 'Walk-in Customer' }}</p>
<p><strong>Total:</strong> {{ number_format($sale->grand_total, 2) }}</p>
<p><strong>Paid:</strong> {{ number_format($paidAmount, 2) }}</p>
<p><strong>Remaining:</strong> {{ number_format($remainingAmount, 2) }}</p>

<hr>

<form action="{{ route('sales.payments.store', $sale) }}" method="POST">
    @csrf

    <div>
        <label for="payment_method">Payment Method</label><br>
        <select name="payment_method" id="payment_method" required>
            <option value="">Select Payment Method</option>
            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
            <option value="mobile_wallet" {{ old('payment_method') === 'mobile_wallet' ? 'selected' : '' }}>Mobile Wallet</option>
        </select>
    </div>
    <br>

    <div>
        <label for="amount">Payment Amount</label><br>
        <input type="number" step="0.01" min="0.01" max="{{ $remainingAmount }}" name="amount" id="amount" value="{{ old('amount', $remainingAmount) }}" required>
    </div>
    <br>

    <div id="cash-fields">
        <label for="received_amount">Received Cash</label><br>
        <input type="number" step="0.01" min="0" name="received_amount" id="received_amount" value="{{ old('received_amount') }}">

        <p><strong>Change:</strong>
            <span id="change_amount">0.00</span>
        </p>
    </div>

    <div id="reference-field">
        <label for="reference">Payment Reference</label><br>
        <input type="text" name="reference" id="reference" value="{{ old('reference') }}">
    </div>
    <br>
<hr>
    <button type="submit">
        Record Payment
    </button>
        &nbsp; | &nbsp;
    <a href="{{ route('sales.show', $sale) }}"><button type="button">Cancel</button></a>

</form>

<script>
    const paymentMethod  = document.getElementById('payment_method');
    const amount         = document.getElementById('amount');
    const receivedAmount = document.getElementById('received_amount');
    const cashFields     = document.getElementById('cash-fields');
    const referenceField = document.getElementById('reference-field');
    const changeAmount   = document.getElementById('change_amount');

    function updatePaymentFields() {

        if (paymentMethod.value === 'cash') {
            cashFields.style.display     = 'block';
            referenceField.style.display = 'none';

            updateChange();

        } else {
            cashFields.style.display     = 'none';
            referenceField.style.display = 'block';

            changeAmount.textContent = '0.00';
        }
    }

    function updateChange() {
        const payment  = parseFloat(amount.value) || 0;
        const received = parseFloat(receivedAmount.value) || 0;
        const change   = Math.max(received - payment, 0);   

        changeAmount.textContent = change.toFixed(2);
    }

    paymentMethod.addEventListener('change', updatePaymentFields);
    amount.addEventListener('input', updateChange);
    receivedAmount.addEventListener('input', updateChange);

    updatePaymentFields();
</script>

@endsection --}}









@extends('layouts.app')

@section('title', 'Receive Payment')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Receive Payment</h1>
            <p class="page-subtitle">
                Record a payment against this sale and complete the outstanding balance.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('sales.show', $sale) }}"
               class="btn btn-secondary">
                ← Back to Sale
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

    {{-- Sale Information --}}
    <div class="card">

        <div class="card-header">
            <h2>Sale Information</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">Invoice</div>
                    <div class="detail-value">
                        <strong>{{ $sale->invoice_number }}</strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Customer</div>
                    <div class="detail-value">
                        {{ $sale->customer?->name ?? 'Walk-in Customer' }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Sale Total</div>
                    <div class="detail-value">
                        <strong>
                            PKR {{ number_format((float) $sale->grand_total, 2) }}
                        </strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Paid</div>
                    <div class="detail-value">
                        PKR {{ number_format((float) $paidAmount, 2) }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Remaining</div>
                    <div class="detail-value">
                        <strong>
                            PKR {{ number_format((float) $remainingAmount, 2) }}
                        </strong>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Payment Form --}}
    <form action="{{ route('sales.payments.store', $sale) }}"
          method="POST">

        @csrf

        <div class="card form-card">

            <div class="card-header">
                <h2>Payment Details</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    {{-- Payment Method --}}
                    <div class="form-group">

                        <label for="payment_method" class="form-label">
                            Payment Method
                        </label>

                        <select
                            name="payment_method"
                            id="payment_method"
                            class="@error('payment_method') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Select Payment Method
                            </option>

                            <option value="cash"
                                {{ old('payment_method') === 'cash' ? 'selected' : '' }}>
                                Cash
                            </option>

                            <option value="card"
                                {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                                Card
                            </option>

                            <option value="bank_transfer"
                                {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>
                                Bank Transfer
                            </option>

                            <option value="mobile_wallet"
                                {{ old('payment_method') === 'mobile_wallet' ? 'selected' : '' }}>
                                Mobile Wallet
                            </option>
                        </select>

                        @error('payment_method')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Payment Amount --}}
                    <div class="form-group">

                        <label for="amount" class="form-label">
                            Payment Amount
                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            value="{{ old('amount', $remainingAmount) }}"
                            min="0.01"
                            max="{{ $remainingAmount }}"
                            step="0.01"
                            class="@error('amount') is-invalid @enderror"
                            required
                        >

                        <div style="font-size:12px; color:#64748b; margin-top:6px;">
                            Maximum payment:
                            PKR {{ number_format((float) $remainingAmount, 2) }}
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

        {{-- Cash Details --}}
        <div class="card form-card"
             id="cash-fields">

            <div class="card-header">
                <h2>Cash Details</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    <div class="form-group">

                        <label for="received_amount" class="form-label">
                            Received Cash
                        </label>

                        <input
                            type="number"
                            name="received_amount"
                            id="received_amount"
                            value="{{ old('received_amount') }}"
                            min="0"
                            step="0.01"
                            class="@error('received_amount') is-invalid @enderror"
                        >

                        @error('received_amount')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label class="form-label">
                            Change
                        </label>

                        <div class="summary-box"
                             style="padding:14px 16px; margin:0;">

                            <div class="summary-row summary-total"
                                 style="border-bottom:0; padding:0;">

                                <span>
                                    Change Due
                                </span>

                                <strong>
                                    PKR <span id="change_amount">0.00</span>
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>

        {{-- Reference --}}
        <div class="card form-card"
             id="reference-field">

            <div class="card-header">
                <h2>Payment Reference</h2>
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
                        Record Payment
                    </button>

                    <a href="{{ route('sales.show', $sale) }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                </div>

            </div>
        </div>

    </form>

    <script>
        const paymentMethod = document.getElementById('payment_method');
        const amount = document.getElementById('amount');
        const receivedAmount = document.getElementById('received_amount');

        const cashFields = document.getElementById('cash-fields');
        const referenceField = document.getElementById('reference-field');

        const changeAmount = document.getElementById('change_amount');

        function updatePaymentFields()
        {
            if (paymentMethod.value === 'cash') {

                cashFields.style.display = 'block';
                referenceField.style.display = 'none';

                updateChange();

            } else {

                cashFields.style.display = 'none';
                referenceField.style.display = 'block';

                changeAmount.textContent = '0.00';
            }
        }

        function updateChange()
        {
            const payment = parseFloat(amount.value) || 0;
            const received = parseFloat(receivedAmount.value) || 0;

            const change = Math.max(
                received - payment,
                0
            );

            changeAmount.textContent = change.toFixed(2);
        }

        paymentMethod.addEventListener(
            'change',
            updatePaymentFields
        );

        amount.addEventListener(
            'input',
            updateChange
        );

        receivedAmount.addEventListener(
            'input',
            updateChange
        );

        updatePaymentFields();
    </script>

@endsection