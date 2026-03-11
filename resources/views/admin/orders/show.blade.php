@extends('admin.layout')

@section('breadcrumb', 'Order Detail')

@section('topbar-actions')
<a href="{{ route('admin.orders.index') }}" class="topbar-btn topbar-btn-outline">← Back to Orders</a>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">{{ $order->order_number }}</h1>
        <p class="page-subtitle">Placed on {{ $order->created_at->format('d M Y, H:i') }}</p>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 320px; gap:20px; align-items:start;">

    {{-- Left --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Items --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Order Items</span></div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    @if($item->product?->main_image)
                                    <img src="{{ asset('storage/'.$item->product->main_image) }}"
                                        style="width:36px;height:40px;object-fit:contain;border-radius:4px;border:1px solid var(--admin-border);">
                                    @endif
                                    <span style="font-weight:500;">{{ $item->product_name }}</span>
                                </div>
                            </td>
                            <td class="td-muted" style="font-size:12px;">
                                @if($item->variant && count($item->variant))
                                @foreach($item->variant as $k => $v){{ ucfirst($k) }}: {{ $v }}@if(!$loop->last),
                                @endif @endforeach
                                @else —
                                @endif
                            </td>
                            <td>{{ $currencySymbol }}{{ number_format($item->product_price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td style="font-weight:500;">{{ $currencySymbol }}{{ number_format($item->line_total, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:16px 20px; border-top:1px solid var(--admin-border);">
                <div
                    style="display:flex; justify-content:flex-end; flex-direction:column; gap:8px; max-width:240px; margin-left:auto;">
                    <div style="display:flex; justify-content:space-between; font-size:13px; color:var(--admin-muted);">
                        <span>Subtotal</span><span>{{ $currencySymbol }}{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13px; color:var(--admin-muted);">
                        <span>Shipping</span>
                        <span>@if($order->shipping_cost == 0)<span
                                style="color:var(--admin-success);">Free</span>@else{{ $currencySymbol }}{{
                            number_format($order->shipping_cost, 2) }}@endif</span>
                    </div>
                    @if($order->discount > 0)
                    <div
                        style="display:flex; justify-content:space-between; font-size:13px; color:var(--admin-success);">
                        <span>Discount</span><span>-{{ $currencySymbol }}{{ number_format($order->discount, 2) }}</span>
                    </div>
                    @endif
                    <div
                        style="display:flex; justify-content:space-between; font-size:15px; font-weight:600; border-top:1px solid var(--admin-border); padding-top:8px;">
                        <span>Total</span><span>{{ $currencySymbol }}{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Customer & Shipping --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Customer & Shipping</span></div>
            <div class="card-body" style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
                <div>
                    <p class="aform-label">Customer</p>
                    <p style="font-weight:500;">{{ $order->customer_name }}</p>
                    <p style="font-size:13px; color:var(--admin-muted);">{{ $order->customer_email }}</p>
                    @if($order->customer_phone)
                    <p style="font-size:13px; color:var(--admin-muted);">{{ $order->customer_phone }}</p>
                    @endif
                </div>
                <div>
                    <p class="aform-label">Shipping Address</p>
                    <p style="font-size:13px; line-height:1.7;">
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}@if($order->shipping_state), {{ $order->shipping_state }}@endif<br>
                        @if($order->shipping_zip){{ $order->shipping_zip }},@endif {{ $order->shipping_country }}
                    </p>
                </div>
            </div>
            @if($order->notes)
            <div style="padding:16px 20px; border-top:1px solid var(--admin-border);">
                <p class="aform-label">Order Notes</p>
                <p style="font-size:13px; color:var(--admin-muted);">{{ $order->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Right: Status Management --}}
    <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="card">
            <div class="card-header"><span class="card-title">Order Status</span></div>
            <div class="card-body">
                <form action="{{ route('admin.orders.status', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="aform-group">
                        <label class="aform-label">Fulfillment Status</label>
                        <select name="status" class="aform-control" onchange="this.form.submit()">
                            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="aform-group" style="margin-bottom:0;">
                        <label class="aform-label">Payment Status</label>
                        <select name="payment_status" class="aform-control" onchange="this.form.submit()">
                            @foreach(['unpaid','paid','refunded'] as $s)
                            <option value="{{ $s }}" {{ $order->payment_status === $s ? 'selected' : '' }}>{{
                                ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">Payment</span></div>
            <div class="card-body" style="display:flex; flex-direction:column; gap:8px;">
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:var(--admin-muted);">Method</span>
                    <span>{{ str_replace('_', ' ', ucfirst($order->payment_method ?? '—')) }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:var(--admin-muted);">Status</span>
                    <span
                        class="abadge {{ $order->payment_status === 'paid' ? 'abadge-green' : ($order->payment_status === 'refunded' ? 'abadge-red' : 'abadge-yellow') }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
                <div
                    style="display:flex; justify-content:space-between; font-size:14px; font-weight:600; padding-top:8px; border-top:1px solid var(--admin-border);">
                    <span>Amount Due</span>
                    <span>{{ $currencySymbol }}{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection