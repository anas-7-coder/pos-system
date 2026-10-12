
@extends('layouts.app')

@section('title', 'Cashier Terminal')

@section('content')

<style>
    .pos-page {
        --pos-primary: #4f46e5;
        --pos-primary-dark: #4338ca;
        --pos-ink: #172033;
        --pos-muted: #687386;
        --pos-border: #e5e9f1;
        --pos-surface: #ffffff;
        --pos-bg: #f5f7fb;
        color: var(--pos-ink);
        width: 100%;
        min-width: 0;
        font-size: 14px;
    }

    .pos-page * {
        box-sizing: border-box;
    }

    .pos-page button,
    .pos-page input,
    .pos-page select {
        font: inherit;
    }

    .pos-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .pos-heading {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .pos-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        color: white;
        background: linear-gradient(145deg, #6366f1, #4338ca);
        border-radius: 15px;
        font-size: 25px;
        box-shadow: 0 8px 18px rgba(79, 70, 229, .20);
    }

    .pos-title {
        margin: 0;
        color: var(--pos-ink);
        font-size: 27px;
        font-weight: 800;
        letter-spacing: -.7px;
        line-height: 1.25;
    }

    .pos-subtitle {
        margin: 5px 0 0;
        color: var(--pos-muted);
        font-size: 13px;
        line-height: 1.5;
    }

    .pos-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pos-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 40px;
        padding: 10px 15px;
        color: #344054;
        background: white;
        border: 1px solid #dfe4ed;
        border-radius: 9px;
        text-decoration: none;
        font-weight: 650;
        cursor: pointer;
        transition: background .15s, border-color .15s, transform .15s;
    }

    .pos-button:hover {
        background: #f8f9fc;
        border-color: #cbd3e1;
    }

    .pos-button:focus-visible,
    .pos-page input:focus-visible,
    .pos-page select:focus-visible {
        outline: 3px solid rgba(99, 102, 241, .20);
        outline-offset: 2px;
    }

    .pos-button-primary {
        color: white;
        background: var(--pos-primary);
        border-color: var(--pos-primary);
    }

    .pos-button-primary:hover {
        color: white;
        background: var(--pos-primary-dark);
        border-color: var(--pos-primary-dark);
    }

    .pos-button:disabled {
        opacity: .55;
        cursor: not-allowed;
    }

    .pos-alert {
        margin-bottom: 18px;
        padding: 13px 15px;
        border: 1px solid #f3d08a;
        border-radius: 10px;
        color: #854d0e;
        background: #fffbeb;
        line-height: 1.6;
    }

    .pos-alert-error {
        color: #b42318;
        background: #fff5f5;
        border-color: #f3c7c7;
    }

    .pos-context {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 20px;
    }

    .pos-context-card {
        min-width: 0;
        padding: 17px;
        background: var(--pos-surface);
        border: 1px solid var(--pos-border);
        border-radius: 13px;
        box-shadow: 0 2px 5px rgba(16, 24, 40, .025);
    }

    .pos-field-label {
        display: block;
        margin-bottom: 9px;
        color: #414d61;
        font-size: 12px;
        font-weight: 750;
        letter-spacing: .2px;
    }

    .pos-field {
        display: block;
        width: 100%;
        min-width: 0;
        min-height: 43px;
        padding: 10px 12px;
        color: #263247;
        background: #fff;
        border: 1px solid #dce2ec;
        border-radius: 8px;
        outline: none;
    }

    .pos-field:focus {
        border-color: #818cf8;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .10);
    }

    .pos-field:disabled {
        background: #f2f4f7;
        cursor: not-allowed;
    }

    .pos-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(320px, 370px);
        align-items: start;
        gap: 20px;
    }

    .pos-panel {
        min-width: 0;
        background: var(--pos-surface);
        border: 1px solid var(--pos-border);
        border-radius: 14px;
        box-shadow: 0 3px 10px rgba(16, 24, 40, .035);
        overflow: hidden;
    }

    .pos-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 19px 20px;
        border-bottom: 1px solid var(--pos-border);
    }

    .pos-panel-title {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--pos-ink);
    }

    .pos-panel-caption {
        margin: 5px 0 0;
        color: var(--pos-muted);
        font-size: 12px;
        line-height: 1.5;
    }

    .pos-result-count {
        white-space: nowrap;
        padding: 5px 9px;
        color: #4f46e5;
        background: #eef2ff;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 800;
    }

    .pos-search-wrap {
        padding: 17px 20px;
        border-bottom: 1px solid var(--pos-border);
    }

    .pos-search-row {
        display: flex;
        gap: 9px;
    }

    .pos-search-box {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .pos-search-icon {
        position: absolute;
        top: 50%;
        left: 13px;
        transform: translateY(-50%);
        color: #858fa2;
        font-size: 17px;
        pointer-events: none;
    }

    .pos-search-input {
        width: 100%;
        min-height: 44px;
        padding: 10px 12px 10px 39px;
        background: #f9fafc;
        border: 1px solid #dce2ec;
        border-radius: 9px;
        color: #263247;
        outline: none;
    }

    .pos-search-input:focus {
        background: white;
        border-color: #818cf8;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .10);
    }

    .pos-search-hint {
        margin: 9px 0 0;
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.5;
    }

    .pos-message {
        min-height: 42px;
        margin: 0;
        padding: 12px 20px 0;
        color: var(--pos-muted);
        font-size: 12px;
        line-height: 1.5;
    }

    .pos-message.error {
        color: #b42318;
    }

    .pos-products {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        align-content: start;
        gap: 13px;
        padding: 15px 20px 20px;
        min-height: 300px;
    }

    .pos-product {
        display: flex;
        flex-direction: column;
        min-width: 0;
        padding: 14px;
        border: 1px solid #e5e9f1;
        border-radius: 11px;
        background: white;
        transition: border-color .15s, box-shadow .15s, transform .15s;
    }

    .pos-product:hover {
        border-color: #c7d2fe;
        box-shadow: 0 5px 15px rgba(54, 67, 110, .07);
        transform: translateY(-1px);
    }

    .pos-product-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
    }

    .pos-product-symbol {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 39px;
        height: 39px;
        flex-shrink: 0;
        margin-bottom: 12px;
        color: #4f46e5;
        background: #eef2ff;
        border-radius: 10px;
        font-size: 18px;
    }

    .pos-stock-badge {
        padding: 5px 7px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }

    .pos-stock-ok {
        color: #067647;
        background: #ecfdf3;
    }

    .pos-stock-low {
        color: #b54708;
        background: #fffaeb;
    }

    .pos-stock-out {
        color: #b42318;
        background: #fef3f2;
    }

    .pos-product-name {
        overflow-wrap: anywhere;
        margin: 0 0 5px;
        color: #1e293b;
        font-size: 13px;
        font-weight: 800;
        line-height: 1.45;
    }

    .pos-product-sku {
        min-height: 16px;
        margin: 0 0 12px;
        color: #8a94a6;
        font-size: 11px;
        overflow-wrap: anywhere;
    }

    .pos-variant-label {
        display: block;
        margin-bottom: 7px;
        color: #596579;
        font-size: 11px;
        font-weight: 700;
    }

    .pos-variant-select {
        width: 100%;
        min-height: 34px;
        margin-bottom: 11px;
        padding: 7px;
        background: #fafbfe;
        border: 1px solid #dfe4ed;
        border-radius: 7px;
        font-size: 11px !important;
    }

    .pos-product-spacer {
        flex: 1;
        min-height: 5px;
    }

    .pos-product-price {
        margin: 0 0 12px;
        color: #111827;
        font-size: 18px;
        font-weight: 850;
        letter-spacing: -.35px;
        overflow-wrap: anywhere;
    }

    .pos-add-button {
        width: 100%;
        min-height: 37px;
        padding: 8px 10px;
        color: #fff;
        background: var(--pos-primary);
        border: 1px solid var(--pos-primary);
        border-radius: 8px;
        font-weight: 750;
        cursor: pointer;
        transition: background .15s;
    }

    .pos-add-button:hover {
        background: var(--pos-primary-dark);
    }

    .pos-add-button:disabled {
        color: #7d8798;
        background: #f0f2f6;
        border-color: #e6e9ef;
        cursor: not-allowed;
    }

    .pos-empty {
        grid-column: 1 / -1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 230px;
        padding: 30px 16px;
        color: #7d8798;
        text-align: center;
    }

    .pos-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        margin-bottom: 13px;
        color: #7783a0;
        background: #f2f4f9;
        border-radius: 16px;
        font-size: 23px;
    }

    .pos-empty strong {
        margin-bottom: 5px;
        color: #344054;
        font-size: 14px;
    }

    .pos-empty span {
        max-width: 270px;
        font-size: 12px;
        line-height: 1.6;
    }

    .pos-cart-panel {
        position: sticky;
        top: 18px;
    }

    .pos-cart-header {
        padding-bottom: 16px;
    }

    .pos-cart-heading {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .pos-cart-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 39px;
        height: 39px;
        color: #4f46e5;
        background: #eef2ff;
        border-radius: 10px;
        font-size: 18px;
    }

    .pos-cart-count {
        min-width: 25px;
        height: 25px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 7px;
        color: #4f46e5;
        background: #eef2ff;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
    }

    .pos-cart-body {
        padding: 14px 17px;
    }

    .pos-cart-items {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 390px;
        overflow-y: auto;
    }

    .pos-cart-line {
        padding-bottom: 13px;
        border-bottom: 1px solid #edf0f5;
    }

    .pos-cart-line-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .pos-cart-item-name {
        margin: 0;
        color: #263247;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }

    .pos-cart-item-detail {
        margin: 3px 0 0;
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.5;
    }

    .pos-remove-button {
        padding: 3px 5px;
        color: #c43232;
        background: transparent;
        border: 0;
        border-radius: 5px;
        font-size: 11px;
        font-weight: 750;
        cursor: pointer;
        flex-shrink: 0;
    }

    .pos-remove-button:hover {
        background: #fff1f1;
    }

    .pos-cart-line-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-top: 10px;
    }

    .pos-qty-control {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .pos-qty-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        color: #414d61;
        background: #fff;
        border: 1px solid #dfe4ed;
        border-radius: 7px;
        font-size: 16px;
        cursor: pointer;
    }

    .pos-qty-button:hover {
        background: #f5f6fb;
    }

    .pos-qty-input {
        width: 49px;
        height: 28px;
        padding: 4px;
        text-align: center;
        border: 1px solid #dfe4ed;
        border-radius: 7px;
        font-size: 12px !important;
    }

    .pos-line-total {
        color: #1d2939;
        font-size: 12px;
        font-weight: 850;
        white-space: nowrap;
    }

    .pos-cart-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 175px;
        padding: 20px 10px;
        color: #8a94a6;
        text-align: center;
    }

    .pos-cart-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        margin-bottom: 12px;
        color: #9aa4b5;
        background: #f3f5f9;
        border-radius: 14px;
        font-size: 21px;
    }

    .pos-cart-empty strong {
        margin-bottom: 5px;
        color: #485467;
        font-size: 13px;
    }

    .pos-cart-empty span {
        font-size: 11px;
        line-height: 1.6;
    }

    .pos-cart-footer {
        padding: 16px 17px 18px;
        background: #fafbfe;
        border-top: 1px solid var(--pos-border);
    }

    .pos-summary-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
        color: #667085;
        font-size: 12px;
    }

    .pos-summary-value {
        color: #344054;
        font-weight: 750;
        white-space: nowrap;
    }

    .pos-total-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 4px;
        padding-top: 15px;
        border-top: 1px dashed #d7dce6;
    }

    .pos-total-label {
        color: #273247;
        font-size: 13px;
        font-weight: 850;
    }

    .pos-total-value {
        color: #4338ca;
        font-size: 23px;
        font-weight: 900;
        letter-spacing: -.6px;
        white-space: nowrap;
    }

    .pos-clear-button {
        padding: 5px 8px;
        color: #b42318;
        background: transparent;
        border: 0;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 750;
        cursor: pointer;
    }

    .pos-clear-button:hover {
        background: #fff1f1;
    }

    .pos-checkout-button {
        width: 100%;
        min-height: 46px;
        margin-top: 17px;
        color: #737e90;
        background: #e6e9f0;
        border: 1px solid #e6e9f0;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 800;
        cursor: not-allowed;
    }

    .pos-checkout-note {
        margin: 9px 0 0;
        color: #8a94a6;
        font-size: 10px;
        line-height: 1.6;
        text-align: center;
    }

    .pos-footer-note {
        margin-top: 18px;
        color: #8a94a6;
        font-size: 11px;
        line-height: 1.6;
        text-align: center;
    }

    @media (max-width: 1250px) {
        .pos-main-grid {
            grid-template-columns: minmax(0, 1fr) 330px;
        }

        .pos-products {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 950px) {
        .pos-main-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .pos-cart-panel {
            position: static;
        }

        .pos-cart-items {
            max-height: none;
        }
    }

    @media (max-width: 650px) {
        .pos-title {
            font-size: 23px;
        }

        .pos-heading-icon {
            width: 45px;
            height: 45px;
        }

        .pos-context {
            grid-template-columns: minmax(0, 1fr);
            gap: 10px;
        }

        .pos-context-card {
            padding: 14px;
        }

        .pos-products {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 9px;
            padding: 12px;
        }

        .pos-product {
            padding: 10px;
        }

        .pos-product-price {
            font-size: 16px;
        }

        .pos-panel-header,
        .pos-search-wrap {
            padding-right: 13px;
            padding-left: 13px;
        }

        .pos-message {
            padding-right: 13px;
            padding-left: 13px;
        }

        .pos-header-actions {
            width: 100%;
        }

        .pos-header-actions .pos-button {
            flex: 1;
        }
    }

    @media (max-width: 380px) {
        .pos-products {
            grid-template-columns: minmax(0, 1fr);
        }
    }
</style>

<div class="pos-page">

    {{-- Page heading --}}
    <header class="pos-header">
        <div class="pos-heading">
            <div class="pos-heading-icon" aria-hidden="true">▦</div>

            <div>
                <h1 class="pos-title">Cashier Terminal</h1>
                <p class="pos-subtitle">
                    Select your register, find products and build the customer's order.
                </p>
            </div>
        </div>

        <div class="pos-header-actions">
            <a href="{{ route('sales.index') }}" class="pos-button">
                <span aria-hidden="true">↗</span>
                View Sales
            </a>

            <a href="{{ route('register-sessions.index') }}" class="pos-button">
                <span aria-hidden="true">◷</span>
                Register Sessions
            </a>
        </div>
    </header>

    {{-- Register session warning --}}
    @if ($sessions->isEmpty())
        <div class="pos-alert">
            <strong>No open register session.</strong>
            Open a register session assigned to your account before using the cashier terminal.
            <div style="margin-top: 12px;">
                <a href="{{ route('register-sessions.create') }}"
                   class="pos-button">
                    Open Register
                </a>
            </div>
        </div>
    @endif

    @if ($sessions->isNotEmpty() && $warehouses->isEmpty())
        <div class="pos-alert">
            <strong>No active warehouse available.</strong>
            An active warehouse must belong to the selected register's branch.
        </div>
    @endif

    {{-- Register and customer context --}}
    <section class="pos-context" aria-label="Sale setup">

        <div class="pos-context-card">
            <label for="register_session_id" class="pos-field-label">
                01 / Register session
            </label>

            <select id="register_session_id"
                    class="pos-field"
                    {{ $sessions->isEmpty() ? 'disabled' : '' }}>
                <option value="">Select open session</option>

                @foreach ($sessions as $session)
                    <option value="{{ $session->id }}"
                            data-branch-id="{{ $session->branch_id }}"
                            @selected($loop->first)>
                        {{ $session->register?->name ?? 'Register' }}
                        — Session #{{ $session->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="pos-context-card">
            <label for="warehouse_id" class="pos-field-label">
                02 / Stock warehouse
            </label>

            <select id="warehouse_id"
                    class="pos-field"
                    {{ $warehouses->isEmpty() || $sessions->isEmpty() ? 'disabled' : '' }}>
                <option value="">Select warehouse</option>

                @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}"
                            data-branch-id="{{ $warehouse->branch_id }}">
                        {{ $warehouse->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="pos-context-card">
            <label for="customer_id" class="pos-field-label">
                03 / Customer
            </label>

            <select id="customer_id" class="pos-field">
                <option value="">Walk-in Customer</option>

                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </section>

    {{-- Main catalog and cart --}}
    <div class="pos-main-grid">

        {{-- Product catalog --}}
        <section class="pos-panel">

            <div class="pos-panel-header">
                <div>
                    <h2 class="pos-panel-title">Product Catalog</h2>
                    <p class="pos-panel-caption">
                        Search active products and add them to the current order.
                    </p>
                </div>

                <span id="pos-result-count" class="pos-result-count">
                    0 PRODUCTS
                </span>
            </div>

            <div class="pos-search-wrap">
                <form id="product-search-form" class="pos-search-row">
                    <div class="pos-search-box">
                        <span class="pos-search-icon" aria-hidden="true">⌕</span>

                        <input id="product-search"
                               type="search"
                               maxlength="100"
                               class="pos-search-input"
                               placeholder="Search by product name or SKU..."
                               autocomplete="off"
                               aria-label="Search products">
                    </div>

                    <button type="submit"
                            id="search-button"
                            class="pos-button pos-button-primary">
                        Search
                    </button>
                </form>

                <p class="pos-search-hint">
                    Product prices and stock shown here are previews; checkout must verify them on the server.
                </p>
            </div>

            <p id="pos-message" class="pos-message" role="status" aria-live="polite">
                Select a register session and warehouse to load products.
            </p>

            <div id="product-results" class="pos-products">
                <div class="pos-empty">
                    <div class="pos-empty-icon" aria-hidden="true">⌕</div>
                    <strong>Ready to find products</strong>
                    <span>Select your register and warehouse to load the available catalog.</span>
                </div>
            </div>
        </section>

        {{-- Current order --}}
        <aside class="pos-panel pos-cart-panel">

            <div class="pos-panel-header pos-cart-header">
                <div class="pos-cart-heading">
                    <div class="pos-cart-icon" aria-hidden="true">🛒</div>

                    <div>
                        <h2 class="pos-panel-title">Current Order</h2>
                        <p class="pos-panel-caption">Review quantities and items.</p>
                    </div>
                </div>

                <span id="cart-count" class="pos-cart-count">0</span>
            </div>

            <div class="pos-cart-body">
                <div id="cart-items" class="pos-cart-items">
                    <div class="pos-cart-empty">
                        <div class="pos-cart-empty-icon" aria-hidden="true">＋</div>
                        <strong>Your cart is empty</strong>
                        <span>Add products from the catalog to begin the order.</span>
                    </div>
                </div>
            </div>

            <div class="pos-cart-footer">

                <div class="pos-summary-row">
                    <span>Total quantity</span>
                    <span id="cart-quantity" class="pos-summary-value">0</span>
                </div>

                <div class="pos-summary-row">
                    <span>Items in order</span>
                    <span id="cart-lines" class="pos-summary-value">0</span>
                </div>

                <div class="pos-total-row">
                    <span class="pos-total-label">Estimated subtotal</span>
                    <span id="cart-subtotal" class="pos-total-value">Rs 0.00</span>
                </div>

                <button type="button"
                        id="clear-cart"
                        class="pos-clear-button"
                        style="margin-top: 12px;">
                    Clear current order
                </button>

                <button type="button"
                        id="checkout-button"
                        class="pos-checkout-button"
                        disabled>
                    Checkout — coming next
                </button>

                <p class="pos-checkout-note">
                    Checkout will be enabled after server-side sale, payment,
                    register and inventory validation is connected.
                </p>
            </div>
        </aside>

    </div>

    <p class="pos-footer-note">
        POS Terminal · Displayed totals are estimates and do not create a sale or payment.
    </p>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sessionSelect = document.getElementById('register_session_id');
    const warehouseSelect = document.getElementById('warehouse_id');
    const searchInput = document.getElementById('product-search');
    const searchForm = document.getElementById('product-search-form');
    const searchButton = document.getElementById('search-button');
    const productResults = document.getElementById('product-results');
    const resultCount = document.getElementById('pos-result-count');
    const cartItems = document.getElementById('cart-items');
    const cartCount = document.getElementById('cart-count');
    const cartQuantity = document.getElementById('cart-quantity');
    const cartLines = document.getElementById('cart-lines');
    const cartSubtotal = document.getElementById('cart-subtotal');
    const clearCartButton = document.getElementById('clear-cart');
    const message = document.getElementById('pos-message');

    const searchUrl = @json(route('pos.products.search'));

    let products = [];
    let cart = [];

    const money = amount =>
        'Rs ' + Number(amount || 0).toLocaleString('en-PK', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

    const escapeHtml = value => String(value ?? '').replace(
        /[&<>"']/g,
        character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[character]
    );

    function setMessage(text, isError = false) {
        message.textContent = text;
        message.classList.toggle('error', isError);
    }

    function showEmptyProducts(title, description) {
        productResults.innerHTML = `
            <div class="pos-empty">
                <div class="pos-empty-icon" aria-hidden="true">⌕</div>
                <strong>${escapeHtml(title)}</strong>
                <span>${escapeHtml(description)}</span>
            </div>
        `;
    }

    function showEmptyCart() {
        cartItems.innerHTML = `
            <div class="pos-cart-empty">
                <div class="pos-cart-empty-icon" aria-hidden="true">＋</div>
                <strong>Your cart is empty</strong>
                <span>Add products from the catalog to begin the order.</span>
            </div>
        `;
    }

    function syncWarehouses() {
        const selectedSession = sessionSelect.selectedOptions[0];
        const branchId = selectedSession?.dataset.branchId || '';

        const matchingWarehouses = Array.from(warehouseSelect.options).filter(option => {
            if (!option.value) {
                return false;
            }

            const matches = option.dataset.branchId === branchId;

            option.hidden = !matches;
            option.disabled = !matches;

            return matches;
        });

        if (!matchingWarehouses.some(option => option.value === warehouseSelect.value)) {
            warehouseSelect.value = matchingWarehouses[0]?.value || '';
        }

        warehouseSelect.disabled = matchingWarehouses.length === 0;
        sessionSelect.disabled = sessionSelect.options.length <= 1;

        return matchingWarehouses.length > 0;
    }

    function hasValidContext() {
        return Boolean(sessionSelect.value && warehouseSelect.value);
    }

    async function loadProducts() {
        if (!hasValidContext()) {
            products = [];
            resultCount.textContent = '0 PRODUCTS';
            showEmptyProducts(
                'Select a register and warehouse',
                'Products are displayed only after a valid register session and its warehouse are selected.'
            );

            setMessage('Choose a register session and matching warehouse.');
            return;
        }

        const params = new URLSearchParams({
            register_session_id: sessionSelect.value,
            warehouse_id: warehouseSelect.value,
            q: searchInput.value.trim()
        });

        searchButton.disabled = true;
        searchButton.textContent = 'Loading...';
        setMessage('Loading product catalog...');

        productResults.innerHTML = `
            <div class="pos-empty">
                <strong>Loading products...</strong>
                <span>Please wait while the catalog is loaded.</span>
            </div>
        `;

        try {
            const response = await fetch(`${searchUrl}?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                const firstError = Object.values(result.errors || {}).flat()[0];
                throw new Error(
                    firstError || result.message || 'Unable to load products.'
                );
            }

            products = Array.isArray(result.data) ? result.data : [];

            renderProducts();

            resultCount.textContent = `${products.length} PRODUCTS`;

            setMessage(
                products.length
                    ? `${products.length} product(s) found. Select a product to add it to the order.`
                    : 'No matching active inventory products were found.'
            );
        } catch (error) {
            products = [];
            resultCount.textContent = '0 PRODUCTS';

            showEmptyProducts(
                'Could not load products',
                error.message || 'Please check your register, warehouse and connection, then try again.'
            );

            setMessage(error.message || 'Unable to load products.', true);
        } finally {
            searchButton.disabled = false;
            searchButton.textContent = 'Search';
        }
    }

    function stockClass(stock) {
        stock = Number(stock || 0);

        if (stock <= 0) return 'pos-stock-out';
        if (stock <= 5) return 'pos-stock-low';

        return 'pos-stock-ok';
    }

    function renderProducts() {
        if (!products.length) {
            showEmptyProducts(
                'No products to display',
                'Try a different search or check which products are active in this warehouse.'
            );
            return;
        }

        productResults.innerHTML = products.map(product => {
            const variants = product.variants || [];
            const initial = variants.length ? variants[0] : product;
            const stock = Number(initial.stock || 0);
            const soldOut = stock <= 0 && !product.allow_negative_stock;

            const variantSelect = variants.length
                ? `
                    <label class="pos-variant-label">
                        Product variant
                        <select data-role="variant"
                                data-product-id="${product.id}"
                                class="pos-variant-select">
                            ${variants.map(variant => `
                                <option value="${variant.id}"
                                        data-price="${variant.price}"
                                        data-stock="${variant.stock}">
                                    ${escapeHtml(variant.name)}
                                </option>
                            `).join('')}
                        </select>
                    </label>
                `
                : '';

            return `
                <article class="pos-product" data-product-card="${product.id}">
                    <div class="pos-product-top">
                        <div class="pos-product-symbol" aria-hidden="true">▦</div>

                        <span data-role="stock-badge"
                              class="pos-stock-badge ${stockClass(stock)}">
                            ${stock <= 0 ? 'Out of stock' : `Stock: ${stock}`}
                        </span>
                    </div>

                    <h3 class="pos-product-name">
                        ${escapeHtml(product.name)}
                    </h3>

                    <p class="pos-product-sku">
                        SKU: ${escapeHtml(product.sku || 'N/A')}
                    </p>

                    ${variantSelect}

                    <div class="pos-product-spacer"></div>

                    <p data-role="price" class="pos-product-price">
                        ${money(initial.price)}
                    </p>

                    <button type="button"
                            data-action="add"
                            data-product-id="${product.id}"
                            class="pos-add-button"
                            ${soldOut ? 'disabled' : ''}>
                        ${soldOut ? 'Out of stock' : '+ Add to order'}
                    </button>
                </article>
            `;
        }).join('');
    }

    function addToCart(product, variant = null) {
        const item = variant || product;
        const key = `${product.id}:${variant?.id || 0}`;
        const stock = Number(item.stock || 0);
        const existing = cart.find(row => row.key === key);
        const nextQuantity = (existing?.quantity || 0) + 1;

        if (!product.allow_negative_stock && nextQuantity > stock) {
            setMessage(
                `Insufficient displayed stock for ${product.name}. Available: ${stock}.`,
                true
            );
            return;
        }

        if (existing) {
            existing.quantity = nextQuantity;
        } else {
            cart.push({
                key,
                product_id: product.id,
                product_variant_id: variant?.id || null,
                name: product.name,
                variant_name: variant?.name || null,
                sku: product.sku || '',
                price: Number(item.price || 0),
                stock,
                allow_negative_stock: Boolean(product.allow_negative_stock),
                quantity: 1
            });
        }

        renderCart();
        setMessage(`${product.name} added to the order.`);
    }

    function renderCart() {
        if (!cart.length) {
            showEmptyCart();
        } else {
            cartItems.innerHTML = cart.map(item => `
                <div class="pos-cart-line">
                    <div class="pos-cart-line-top">
                        <div>
                            <p class="pos-cart-item-name">
                                ${escapeHtml(item.name)}
                            </p>

                            ${item.variant_name ? `
                                <p class="pos-cart-item-detail">
                                    ${escapeHtml(item.variant_name)}
                                </p>
                            ` : ''}

                            <p class="pos-cart-item-detail">
                                ${money(item.price)} per unit
                            </p>
                        </div>

                        <button type="button"
                                data-action="remove"
                                data-key="${escapeHtml(item.key)}"
                                class="pos-remove-button">
                            Remove
                        </button>
                    </div>

                    <div class="pos-cart-line-bottom">
                        <div class="pos-qty-control">
                            <button type="button"
                                    data-action="minus"
                                    data-key="${escapeHtml(item.key)}"
                                    class="pos-qty-button"
                                    aria-label="Decrease quantity">
                                −
                            </button>

                            <input type="number"
                                   min="0.001"
                                   step="0.001"
                                   value="${item.quantity}"
                                   data-role="quantity"
                                   data-key="${escapeHtml(item.key)}"
                                   aria-label="Quantity for ${escapeHtml(item.name)}"
                                   class="pos-qty-input">

                            <button type="button"
                                    data-action="plus"
                                    data-key="${escapeHtml(item.key)}"
                                    class="pos-qty-button"
                                    aria-label="Increase quantity">
                                +
                            </button>
                        </div>

                        <span class="pos-line-total">
                            ${money(item.price * item.quantity)}
                        </span>
                    </div>
                </div>
            `).join('');
        }

        const totalQuantity = cart.reduce(
            (sum, item) => sum + item.quantity,
            0
        );

        const subtotal = cart.reduce(
            (sum, item) => sum + item.price * item.quantity,
            0
        );

        cartCount.textContent = cart.length;
        cartLines.textContent = cart.length;
        cartQuantity.textContent = Number(totalQuantity).toLocaleString('en-PK', {
            maximumFractionDigits: 3
        });
        cartSubtotal.textContent = money(subtotal);

        clearCartButton.disabled = cart.length === 0;
    }

    function updateQuantity(key, quantity) {
        const item = cart.find(row => row.key === key);

        if (!item) return;

        if (!Number.isFinite(quantity) || quantity <= 0) {
            cart = cart.filter(row => row.key !== key);
            renderCart();
            return;
        }

        if (!item.allow_negative_stock && quantity > item.stock) {
            setMessage(
                `Only ${item.stock} unit(s) are displayed as available for ${item.name}.`,
                true
            );

            quantity = item.stock;
        }

        if (quantity <= 0) {
            cart = cart.filter(row => row.key !== key);
        } else {
            item.quantity = quantity;
        }

        renderCart();
    }

    searchForm.addEventListener('submit', event => {
        event.preventDefault();
        loadProducts();
    });

    sessionSelect.addEventListener('change', () => {
        syncWarehouses();
        loadProducts();
    });

    warehouseSelect.addEventListener('change', loadProducts);

    productResults.addEventListener('change', event => {
        const select = event.target.closest('[data-role="variant"]');

        if (!select) return;

        const product = products.find(
            row => String(row.id) === select.dataset.productId
        );

        const variant = product?.variants?.find(
            row => String(row.id) === select.value
        );

        if (!product || !variant) return;

        const card = select.closest('[data-product-card]');
        const price = card.querySelector('[data-role="price"]');
        const badge = card.querySelector('[data-role="stock-badge"]');
        const button = card.querySelector('[data-action="add"]');
        const stock = Number(variant.stock || 0);
        const soldOut = stock <= 0 && !product.allow_negative_stock;

        price.textContent = money(variant.price);
        badge.textContent = stock <= 0 ? 'Out of stock' : `Stock: ${stock}`;
        badge.className = `pos-stock-badge ${stockClass(stock)}`;

        button.disabled = soldOut;
        button.textContent = soldOut ? 'Out of stock' : '+ Add to order';
    });

    productResults.addEventListener('click', event => {
        const button = event.target.closest('[data-action="add"]');

        if (!button) return;

        const product = products.find(
            row => String(row.id) === button.dataset.productId
        );

        if (!product) return;

        const card = button.closest('[data-product-card]');
        const variantSelect = card.querySelector('[data-role="variant"]');

        let variant = null;

        if (variantSelect) {
            variant = product.variants.find(
                row => String(row.id) === variantSelect.value
            );
        }

        addToCart(product, variant);
    });

    cartItems.addEventListener('click', event => {
        const button = event.target.closest('[data-action]');

        if (!button) return;

        const item = cart.find(row => row.key === button.dataset.key);

        if (!item) return;

        switch (button.dataset.action) {
            case 'remove':
                cart = cart.filter(row => row.key !== item.key);
                renderCart();
                break;

            case 'minus':
                updateQuantity(item.key, item.quantity - 1);
                break;

            case 'plus':
                updateQuantity(item.key, item.quantity + 1);
                break;
        }
    });

    cartItems.addEventListener('change', event => {
        const input = event.target.closest('[data-role="quantity"]');

        if (input) {
            updateQuantity(input.dataset.key, Number(input.value));
        }
    });

    clearCartButton.addEventListener('click', () => {
        cart = [];
        renderCart();
        setMessage('Current order cleared.');
    });

    // Initial page setup
    syncWarehouses();
    renderCart();

    if (hasValidContext()) {
        loadProducts();
    } else if (sessionSelect.options.length > 1) {
        setMessage('Select an available warehouse to load products.');
    }
});
</script>

@endsection
