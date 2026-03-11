@extends('layouts.app')

@section('content')
<div class="section-pad-sm">
    <div class="container">
        <h1 class="display-md" style="margin-bottom:40px;">Your Cart
            <span class="label-caps"
                style="font-size:12px; color:var(--sand); vertical-align:middle; margin-left:12px;">
                {{ array_sum(array_column($cart, 'quantity')) }} {{ Str::plural('item', array_sum(array_column($cart,
                'quantity'))) }}
            </span>
        </h1>

        @if(empty($cart))
        <div class="cart-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="var(--sand-light)" stroke-width="1">
                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <path d="M16 10a4 4 0 01-8 0" />
            </svg>
            <h2 class="display-sm" style="margin:24px 0 12px;">Your cart is empty</h2>
            <p style="color:var(--charcoal-mid); margin-bottom:32px;">Looks like you haven't added anything yet.</p>
            <a href="{{ route('shop') }}" class="btn btn-dark">Continue Shopping</a>
        </div>
        @else
        <div class="cart-layout">
            {{-- Cart items --}}
            <div class="cart-items">
                @foreach($cart as $rowId => $item)
                <div class="cart-item" id="row-{{ $rowId }}">
                    <a href="{{ route('product.show', $item['slug']) }}" class="cart-item-image">
                        @if($item['image'])
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                        @else
                        <div class="cart-image-placeholder"></div>
                        @endif
                    </a>
                    <div class="cart-item-info">
                        <div>
                            <h3 class="cart-item-name">
                                <a href="{{ route('product.show', $item['slug']) }}">{{ $item['name'] }}</a>
                            </h3>
                            @if($item['variant'] && count($item['variant']))
                            <p class="cart-item-variant">
                                @foreach($item['variant'] as $k => $v)
                                {{ ucfirst($k) }}: {{ $v }}@if(!$loop->last), @endif
                                @endforeach
                            </p>
                            @endif
                        </div>
                        <div class="cart-item-controls">
                            <div class="qty-control">
                                <button type="button"
                                    onclick="updateCart('{{ $rowId }}', {{ $item['quantity'] - 1 }})">−</button>
                                <input type="number" value="{{ $item['quantity'] }}" min="0" max="99" class="qty-input"
                                    onchange="updateCart('{{ $rowId }}', this.value)">
                                <button type="button"
                                    onclick="updateCart('{{ $rowId }}', {{ $item['quantity'] + 1 }})">+</button>
                            </div>
                            <button class="cart-remove-btn" onclick="removeFromCart('{{ $rowId }}')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.5">
                                    <polyline points="3 6 5 6 21 6" />
                                    <path
                                        d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="cart-item-price">
                        {{ $currencySymbol }}{{ number_format($item['price'] * $item['quantity'], 2) }}
                        @if($item['quantity'] > 1)
                        <small>{{ $currencySymbol }}{{ number_format($item['price'], 2) }} each</small>
                        @endif
                    </div>
                </div>
                @endforeach

                <div class="cart-actions">
                    <a href="{{ route('shop') }}" class="btn btn-outline">← Continue Shopping</a>
                    <form action="{{ route('cart.clear') }}" method="POST"
                        onsubmit="return confirm('Clear entire cart?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-text-link">Clear cart</button>
                    </form>
                </div>
            </div>

            {{-- Summary --}}
            <div class="cart-summary">
                <h3 class="cart-summary-title">Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="summarySubtotal">{{ $currencySymbol }}{{ number_format($totals['subtotal'], 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span id="summaryShipping">
                        @if($totals['shipping'] == 0)
                        <span style="color:#4CAF50;">Free</span>
                        @else
                        {{ $currencySymbol }}{{ number_format($totals['shipping'], 2) }}
                        @endif
                    </span>
                </div>
                @if($totals['subtotal'] < $totals['freeOver']) <p class="shipping-notice">
                    Add {{ $currencySymbol }}{{ number_format($totals['freeOver'] - $totals['subtotal'], 2) }} more for
                    free shipping
                    </p>
                    @endif
                    <hr class="divider" style="margin:16px 0;">
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span id="summaryTotal">{{ $currencySymbol }}{{ number_format($totals['total'], 2) }}</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-terra btn-full" style="margin-top:24px;">
                        Proceed to Checkout
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </a>
                    <div class="payment-icons">
                        <span class="label-caps" style="font-size:10px; color:var(--sand);">Secure checkout</span>
                    </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="/css/cart.css">
@endpush

@push('scripts')
<script>
    function updateCart(rowId, qty) {
        qty = parseInt(qty);
        if (isNaN(qty) || qty < 0) return;
        fetch('{{ route("cart.update") }}', {
            method:  'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            body: JSON.stringify({ row_id: rowId, quantity: qty }),
        })
        .then(r => r.json())
        .then(d => { if (d.success) location.reload(); });
    }

    function removeFromCart(rowId) {
        fetch('/cart/remove/' + rowId, {
            method:  'DELETE',
            headers: {
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
        })
        .then(r => r.json())
        .then(() => location.reload());
    }
</script>
@endpush