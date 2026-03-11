@extends('layouts.app')

@section('content')
<div class="section-pad">
    <div class="container-sm" style="text-align:center; max-width:620px;">
        <div class="confirm-icon">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--white)" stroke-width="2">
                <polyline points="20 6 9 17 4 12" />
            </svg>
        </div>
        <h1 class="display-md" style="margin-bottom:12px;">Order Confirmed!</h1>
        <p style="color:var(--charcoal-mid); margin-bottom:8px;">Thank you for your purchase, <strong>{{
                $order->customer_name }}</strong>.</p>
        <p style="font-size:13px; color:var(--sand);">A confirmation has been sent to <strong>{{ $order->customer_email
                }}</strong></p>

        <div class="confirm-box">
            <div class="confirm-row">
                <span>Order Number</span>
                <strong style="color:var(--terra);">{{ $order->order_number }}</strong>
            </div>
            <div class="confirm-row">
                <span>Status</span>
                <strong>{{ ucfirst($order->status) }}</strong>
            </div>
            <div class="confirm-row">
                <span>Payment</span>
                <strong>{{ str_replace('_', ' ', ucfirst($order->payment_method)) }}</strong>
            </div>
            <div class="confirm-row">
                <span>Shipping to</span>
                <strong>{{ $order->shipping_city }}, {{ $order->shipping_country }}</strong>
            </div>
            <hr class="divider" style="margin:16px 0;">
            @foreach($order->items as $item)
            <div class="confirm-row" style="font-size:13px;">
                <span>{{ $item->product_name }} ×{{ $item->quantity }}</span>
                <span>{{ $currencySymbol }}{{ number_format($item->line_total, 2) }}</span>
            </div>
            @endforeach
            <hr class="divider" style="margin:16px 0;">
            <div class="confirm-row">
                <span>Subtotal</span>
                <span>{{ $currencySymbol }}{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="confirm-row">
                <span>Shipping</span>
                <span>@if($order->shipping_cost == 0)Free@else{{ $currencySymbol }}{{
                    number_format($order->shipping_cost, 2) }}@endif</span>
            </div>
            <div class="confirm-row" style="font-size:16px; font-weight:500; color:var(--charcoal); margin-top:8px;">
                <span>Total</span>
                <span>{{ $currencySymbol }}{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <div style="display:flex; gap:16px; justify-content:center; margin-top:40px; flex-wrap:wrap;">
            <a href="{{ route('home') }}" class="btn btn-dark">Back to Home</a>
            <a href="{{ route('shop') }}" class="btn btn-outline">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .confirm-icon {
        width: 72px;
        height: 72px;
        background: var(--terra);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 28px;
    }

    .confirm-box {
        background: var(--white);
        border: 1px solid var(--sand-light);
        padding: 28px 32px;
        text-align: left;
        margin-top: 36px;
    }

    .confirm-row {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-bottom: 12px;
        color: var(--charcoal-mid);
    }

    .confirm-row strong,
    .confirm-row span:last-child {
        color: var(--charcoal);
    }
</style>
@endpush