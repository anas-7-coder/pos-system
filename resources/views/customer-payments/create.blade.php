{{-- @extends('layouts.app')

@section('title', 'Receive Customer Payment')

@section('content')

<h1>Receive Customer Payment</h1>

<hr>

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

<p><strong>Customer:</strong> {{ $customer->name }}</p>
<p><strong>Current Balance:</strong> {{ number_format($currentBalance, 2) }}</p>

<form action="{{ route('customers.payments.store', $customer) }}" method="POST">
    @csrf

    <h3>Payment</h3>

    <div>
        <label for="amount">Payment Amount</label><br>
        <input type="number" name="amount" id="amount" step="0.01" min="0.01" max="{{ $currentBalance }}" value="{{ old('amount') }}" required>
    </div>
    <br>

    <div>
        <label for="payment_method">Payment Method</label><br>
        <select name="payment_method" id="payment_method" required>
            <option value="">Select Method</option>
            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
            <option value="mobile_wallet" {{ old('payment_method') === 'mobile_wallet' ? 'selected' : '' }}>Mobile Wallet</option>
        </select>
    </div>
    <br>

    <div id="cash-fields" style="display:none;">
        <label for="register_session_id">Register Session</label><br>
        <select name="register_session_id" id="register_session_id">
            <option value="">Select Register Session</option>

            @foreach($registerSessions as $session)
                <option value="{{ $session->id }}" {{ old('register_session_id') == $session->id ? 'selected' : '' }}>
                    {{ $session->register->name }} — Session #{{ $session->id }}
                </option>
            @endforeach
        </select>
        <br><br>

        <label for="received_amount">Received Cash</label><br>
        <input type="number" name="received_amount" id="received_amount" step="0.01" min="0" value="{{ old('received_amount') }}">
        <p>Change: <strong id="change_amount">0.00</strong></p>
    </div>

    <div id="reference-field" style="display:none;">
        <label for="reference">Reference</label><br>
        <input type="text" name="reference" id="reference" value="{{ old('reference') }}">
    </div>

    <hr>

    <h3>Invoice Allocation</h3>

    <p>
        Allocate this payment to customer invoices.
        The allocation total must equal the payment amount
        when invoices are selected.
    </p>

    <table border="1" cellpadding="8" cellspacing="0">

        <thead>

            <tr>
                <th>Invoice</th>
                <th>Sale Date</th>
                <th>Invoice Total</th>
                <th>Outstanding</th>
                <th>Allocate</th>
            </tr>

        </thead>

        <tbody>

            @forelse($outstandingSales as $sale)
                <tr>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->sale_date }}</td>
                    <td>{{ number_format($sale->grand_total, 2) }}</td>
                    <td>{{ number_format($sale->outstanding_amount, 2) }}</td>
                    <td>
                        <input type="hidden" name="allocations[{{ $sale->id }}][sale_id]" value="{{ $sale->id }}">
                        <input type="number" name="allocations[{{ $sale->id }}][amount]" step="0.01" min="0" max="{{ $sale->outstanding_amount }}" value="{{ old('allocations.' . $sale->id . '.amount', 0) }}">
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5">
                        No outstanding invoices found.
                    </td>
                </tr>
            @endforelse

        </tbody>

    </table>

    <br>

    <button type="submit">
        Record Customer Payment
    </button>
        &nbsp; | &nbsp;
    <a href="{{ route('customers.receivables', $customer) }}"><button type="button">Cancel</button></a>

</form>

<script>
    const paymentMethod   = document.getElementById('payment_method');
    const amount          = document.getElementById('amount');
    const cashFields      = document.getElementById('cash-fields');
    const registerSession = document.getElementById('register_session_id');
    const receivedAmount  = document.getElementById('received_amount');
    const referenceField  = document.getElementById('reference-field');
    const reference       = document.getElementById('reference');
    const changeAmount    = document.getElementById('change_amount');

    function updateFields() {

        if (paymentMethod.value === 'cash') {
            cashFields.style.display     = 'block';
            registerSession.required     = true;
            receivedAmount.required      = true;
            referenceField.style.display = 'none';
            reference.required           = false;
            reference.value              = '';
        } else if (
            paymentMethod.value === 'card' ||
            paymentMethod.value === 'bank_transfer' ||
            paymentMethod.value === 'mobile_wallet'
        ) {
            cashFields.style.display     = 'none';
            registerSession.required     = false;
            receivedAmount.required      = false;
            referenceField.style.display = 'block';
            reference.required           = true;
        } else {
            cashFields.style.display     = 'none';
            referenceField.style.display = 'none';
            registerSession.required     = false;
            receivedAmount.required      = false;
            reference.required           = false;
            reference.value              = '';
        }

        updateChange();
    }

    function updateChange() {
        const payment            = parseFloat(amount.value) || 0;
        const received           = parseFloat(receivedAmount.value) || 0;
        const change             = Math.max(received - payment, 0);
        changeAmount.textContent = change.toFixed(2);
    }

    paymentMethod.addEventListener('change', updateFields);
    amount.addEventListener('input', updateChange);
    receivedAmount.addEventListener('input', updateChange);
    updateFields();

</script>

@endsection --}}








@extends('layouts.app')

@section('title', 'Receive Customer Payment')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Receive Customer Payment</h1>
            <p class="page-subtitle">
                Record a customer payment and optionally allocate it against outstanding invoices.
            </p>
        </div>

        <div class="page-actions">
            <a href="{{ route('customers.receivables', $customer) }}"
               class="btn btn-secondary">
                ← Back to Customer Ledger
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

    {{-- Customer Summary --}}
    <div class="card">

        <div class="card-header">
            <h2>Customer Information</h2>
        </div>

        <div class="card-body">

            <div class="detail-grid">

                <div class="detail-item">
                    <div class="detail-label">Customer</div>
                    <div class="detail-value">
                        <strong>{{ $customer->name }}</strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Customer Code</div>
                    <div class="detail-value">
                        {{ $customer->code }}
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Current Balance</div>
                    <div class="detail-value">
                        <strong>
                            PKR {{ number_format((float) $currentBalance, 2) }}
                        </strong>
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Credit Limit</div>
                    <div class="detail-value">
                        PKR {{ number_format((float) $customer->credit_limit, 2) }}
                    </div>
                </div>

            </div>

        </div>
    </div>

    <form action="{{ route('customers.payments.store', $customer) }}"
          method="POST">

        @csrf

        {{-- Payment Details --}}
        <div class="card form-card">

            <div class="card-header">
                <h2>Payment Details</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    <div class="form-group">

                        <label for="amount" class="form-label">
                            Payment Amount
                        </label>

                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            value="{{ old('amount') }}"
                            step="0.01"
                            min="0.01"
                            max="{{ $currentBalance }}"
                            class="@error('amount') is-invalid @enderror"
                            placeholder="Enter payment amount"
                            required
                        >

                        <div style="font-size:12px; color:#64748b; margin-top:6px;">
                            Maximum:
                            PKR {{ number_format((float) $currentBalance, 2) }}
                        </div>

                        @error('amount')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

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

        {{-- Cash Details --}}
        <div class="card form-card"
             id="cash-fields"
             style="display:none;">

            <div class="card-header">
                <h2>Cash Payment Details</h2>
            </div>

            <div class="card-body">

                <div class="form-grid">

                    <div class="form-group">

                        <label for="register_session_id" class="form-label">
                            Register Session
                        </label>

                        <select
                            name="register_session_id"
                            id="register_session_id"
                            class="@error('register_session_id') is-invalid @enderror"
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

                    <div class="form-group">

                        <label for="received_amount" class="form-label">
                            Received Cash
                        </label>

                        <input
                            type="number"
                            name="received_amount"
                            id="received_amount"
                            value="{{ old('received_amount') }}"
                            step="0.01"
                            min="0"
                            class="@error('received_amount') is-invalid @enderror"
                            placeholder="Enter cash received"
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
                             style="margin:0; padding:14px 16px;">

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

        {{-- Non-Cash Reference --}}
        <div class="card form-card"
             id="reference-field"
             style="display:none;">

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
                        placeholder="Transaction number, transfer ID, card reference, etc."
                    >

                    @error('reference')
                        <div class="field-error">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>
        </div>

        {{-- Invoice Allocation --}}
        <div class="card">

            <div class="card-header">
                <div>
                    <h2>Invoice Allocation</h2>

                    <p class="page-subtitle" style="margin-top:4px;">
                        Allocate this payment against the customer's outstanding invoices.
                        When invoices are selected, the allocation total should equal the payment amount.
                    </p>
                </div>
            </div>

            <div class="card-body">

                <div class="table-wrapper">

                    <table class="table">

                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Sale Date</th>
                                <th>Invoice Total</th>
                                <th>Outstanding</th>
                                <th>Allocate</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($outstandingSales as $sale)

                                @php
                                    $allocationValue = old(
                                        'allocations.' . $sale->id . '.amount',
                                        0
                                    );
                                @endphp

                                <tr>

                                    <td>
                                        <a href="{{ route('sales.show', $sale) }}"
                                           style="font-weight:600;">
                                            {{ $sale->invoice_number }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ $sale->sale_date }}
                                    </td>

                                    <td>
                                        PKR {{ number_format((float) $sale->grand_total, 2) }}
                                    </td>

                                    <td>
                                        <span class="badge badge-warning">
                                            PKR {{ number_format((float) $sale->outstanding_amount, 2) }}
                                        </span>
                                    </td>

                                    <td style="min-width:170px;">

                                        <input
                                            type="hidden"
                                            name="allocations[{{ $sale->id }}][sale_id]"
                                            value="{{ $sale->id }}"
                                        >

                                        <input
                                            type="number"
                                            name="allocations[{{ $sale->id }}][amount]"
                                            value="{{ $allocationValue }}"
                                            step="0.01"
                                            min="0"
                                            max="{{ $sale->outstanding_amount }}"
                                            class="@error('allocations.' . $sale->id . '.amount') is-invalid @enderror"
                                        >

                                        @error('allocations.' . $sale->id . '.amount')
                                            <div class="field-error">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="table-empty">
                                        No outstanding invoices found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="summary-box"
                     style="margin-top:20px;">

                    <div class="summary-row">
                        <span>Payment Amount</span>
                        <strong>
                            PKR <span id="payment-total">0.00</span>
                        </strong>
                    </div>

                    <div class="summary-row">
                        <span>Allocated Amount</span>
                        <strong>
                            PKR <span id="allocated-total">0.00</span>
                        </strong>
                    </div>

                    <div class="summary-row summary-total">
                        <span>Unallocated Amount</span>
                        <strong>
                            PKR <span id="unallocated-total">0.00</span>
                        </strong>
                    </div>

                </div>

            </div>
        </div>

        {{-- Actions --}}
        <div class="card">

            <div class="card-body">

                <div class="form-actions">

                    <button type="submit"
                            class="btn btn-primary">
                        Record Customer Payment
                    </button>

                    <a href="{{ route('customers.receivables', $customer) }}"
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

        const cashFields = document.getElementById('cash-fields');
        const registerSession = document.getElementById('register_session_id');
        const receivedAmount = document.getElementById('received_amount');

        const referenceField = document.getElementById('reference-field');
        const reference = document.getElementById('reference');

        const changeAmount = document.getElementById('change_amount');

        const paymentTotal = document.getElementById('payment-total');
        const allocatedTotal = document.getElementById('allocated-total');
        const unallocatedTotal = document.getElementById('unallocated-total');

        const allocationInputs = document.querySelectorAll(
            'input[name^="allocations["][name$="[amount]"]'
        );

        function updateFields()
        {
            if (paymentMethod.value === 'cash') {

                cashFields.style.display = 'block';

                registerSession.required = true;
                receivedAmount.required = true;

                referenceField.style.display = 'none';

                reference.required = false;
                reference.value = '';

            } else if (
                paymentMethod.value === 'card' ||
                paymentMethod.value === 'bank_transfer' ||
                paymentMethod.value === 'mobile_wallet'
            ) {

                cashFields.style.display = 'none';

                registerSession.required = false;
                receivedAmount.required = false;

                referenceField.style.display = 'block';

                reference.required = true;

            } else {

                cashFields.style.display = 'none';
                referenceField.style.display = 'none';

                registerSession.required = false;
                receivedAmount.required = false;
                reference.required = false;

                reference.value = '';
            }

            updateChange();
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

        function updateAllocationSummary()
        {
            const payment = parseFloat(amount.value) || 0;

            let allocated = 0;

            allocationInputs.forEach(input => {
                allocated += parseFloat(input.value) || 0;
            });

            const unallocated = Math.max(
                payment - allocated,
                0
            );

            paymentTotal.textContent = payment.toFixed(2);
            allocatedTotal.textContent = allocated.toFixed(2);
            unallocatedTotal.textContent = unallocated.toFixed(2);
        }

        paymentMethod.addEventListener(
            'change',
            updateFields
        );

        amount.addEventListener(
            'input',
            function () {
                updateChange();
                updateAllocationSummary();
            }
        );

        receivedAmount.addEventListener(
            'input',
            updateChange
        );

        allocationInputs.forEach(input => {
            input.addEventListener(
                'input',
                updateAllocationSummary
            );
        });

        updateFields();
        updateAllocationSummary();
    </script>

@endsection