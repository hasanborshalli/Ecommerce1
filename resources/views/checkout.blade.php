@extends('layouts.app')

@section('content')
<div class="section-pad-sm">
    <div class="container-sm" style="max-width:1080px;">
        <h1 class="display-md" style="margin-bottom:40px;">Checkout</h1>

        <form action="{{ route('checkout.place') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="checkout-grid">

                {{-- ── Left: Shipping + Payment ──────────────────────── --}}
                <div class="checkout-left">

                    {{-- Shipping --}}
                    <div class="checkout-section">
                        <h2 class="checkout-section-title">
                            <span class="checkout-step">1</span>
                            Shipping Information
                        </h2>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="customer_name"
                                    class="form-control {{ $errors->has('customer_name') ? 'error' : '' }}"
                                    value="{{ old('customer_name') }}" placeholder="Jane Doe" required>
                                @error('customer_name')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="customer_email"
                                    class="form-control {{ $errors->has('customer_email') ? 'error' : '' }}"
                                    value="{{ old('customer_email') }}" placeholder="jane@example.com" required>
                                @error('customer_email')<p class="form-error">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="customer_phone" class="form-control"
                                value="{{ old('customer_phone') }}" placeholder="+1 555 000 0000">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Street Address *</label>
                            <input type="text" name="shipping_address"
                                class="form-control {{ $errors->has('shipping_address') ? 'error' : '' }}"
                                value="{{ old('shipping_address') }}" placeholder="123 Main Street, Apt 4B" required>
                            @error('shipping_address')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">City *</label>
                            <input type="text" name="shipping_city" class="form-control"
                                value="{{ old('shipping_city') }}" required>
                            @error('shipping_city')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="checkout-section">
                        <h2 class="checkout-section-title">
                            <span class="checkout-step">2</span>
                            Order Notes <span style="font-size:0.8em; color:var(--sand);">(optional)</span>
                        </h2>
                        <div class="form-group">
                            <textarea name="notes" class="form-control"
                                placeholder="Special instructions, gift message, or delivery notes...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ── Right: Order Summary ───────────────────────────── --}}
                <div class="checkout-right">
                    <div class="checkout-summary">
                        <h3 class="cart-summary-title">Your Order</h3>
                        <div class="checkout-items">
                            @foreach($cart as $item)
                            <div class="checkout-item">
                                <div class="checkout-item-image">
                                    @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    @endif
                                    <span class="checkout-item-qty">{{ $item['quantity'] }}</span>
                                </div>
                                <div class="checkout-item-info">
                                    <p class="checkout-item-name">{{ $item['name'] }}</p>
                                    @if($item['variant'] && count($item['variant']))
                                    <p style="font-size:11px; color:var(--sand);">
                                        @foreach($item['variant'] as $k => $v){{ ucfirst($k) }}: {{ $v
                                        }}@if(!$loop->last), @endif @endforeach
                                    </p>
                                    @endif
                                </div>
                                <span class="checkout-item-price">{{ $currencySymbol }}{{ number_format($item['price'] *
                                    $item['quantity'], 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <hr class="divider" style="margin:16px 0;">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>{{ $currencySymbol }}{{ number_format($totals['subtotal'], 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>@if($totals['shipping'] == 0)<span style="color:#4CAF50;">Free</span>@else{{
                                $currencySymbol }}{{ number_format($totals['shipping'], 2) }}@endif</span>
                        </div>
                        <hr class="divider" style="margin:16px 0;">
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span>{{ $currencySymbol }}{{ number_format($totals['total'], 2) }}</span>
                        </div>
                        <button type="submit" class="btn btn-terra btn-full" style="margin-top:24px; padding:16px;">
                            Place Order
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </button>
                        <p
                            style="font-size:11px; text-align:center; color:var(--sand); margin-top:12px; letter-spacing:0.06em;">
                            🔒 Secure & encrypted checkout
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="/css/checkout.css">
@endpush

@push('scripts')
<script>
    function selectPayment(el) {
        document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
    }
</script>
@endpush