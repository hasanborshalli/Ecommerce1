@extends('admin.layout')

@section('breadcrumb', 'Orders')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Orders</h1>
        <p class="page-subtitle">{{ $orders->total() }} orders total</p>
    </div>
</div>

{{-- Status tabs --}}
<div class="order-tabs">
    @foreach(['all' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'processing' => 'Processing',
    'shipped' => 'Shipped', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'] as $key => $label)
    <a href="{{ route('admin.orders.index', $key !== 'all' ? ['status' => $key] : []) }}"
        class="order-tab {{ request('status', 'all') === $key ? 'active' : '' }}">
        {{ $label }}
        <span class="order-tab-count">{{ $statusCounts[$key] }}</span>
    </a>
    @endforeach
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.orders.index') }}" style="margin-bottom:16px;">
    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <div class="filter-bar">
        <div class="search-input-wrap">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" name="search" class="aform-control" placeholder="Search by order #, name, email…"
                value="{{ request('search') }}">
        </div>
        <button type="submit" class="abtn abtn-outline">Search</button>
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td style="font-weight:500; color:var(--admin-accent); white-space:nowrap;">{{ $order->order_number
                        }}</td>
                    <td>
                        <div style="font-weight:500;">{{ $order->customer_name }}</div>
                        <div style="font-size:11px; color:var(--admin-muted);">{{ $order->customer_email }}</div>
                    </td>
                    <td class="td-muted">{{ $order->items->count() }} item(s)</td>
                    <td style="font-weight:500;">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</td>
                    <td>
                        <span
                            class="abadge {{ $order->payment_status === 'paid' ? 'abadge-green' : ($order->payment_status === 'refunded' ? 'abadge-red' : 'abadge-yellow') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td>
                        @php
                        $badge = match($order->status) {
                        'pending' => 'abadge-yellow',
                        'confirmed' => 'abadge-blue',
                        'processing' => 'abadge-purple',
                        'shipped' => 'abadge-orange',
                        'delivered' => 'abadge-green',
                        'cancelled' => 'abadge-red',
                        default => 'abadge-gray'
                        };
                        @endphp
                        <span class="abadge {{ $badge }}">{{ ucfirst($order->status) }}</span>
                    </td>
                    <td class="td-muted" style="white-space:nowrap;">{{ $order->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="abtn abtn-outline abtn-sm abtn-icon">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--admin-muted);padding:48px;">No orders found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div style="padding:12px 20px; border-top:1px solid var(--admin-border);">
        <div class="admin-pagination">{{ $orders->links('partials.pagination') }}</div>
    </div>
    @endif
</div>

@endsection

@push('styles')
<style>
    .order-tabs {
        display: flex;
        gap: 2px;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--admin-border);
        flex-wrap: wrap;
    }

    .order-tab {
        padding: 10px 16px;
        font-size: 12px;
        font-weight: 500;
        color: var(--admin-muted);
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .order-tab:hover {
        color: var(--admin-text);
    }

    .order-tab.active {
        color: var(--admin-accent);
        border-bottom-color: var(--admin-accent);
    }

    .order-tab-count {
        background: var(--admin-border);
        border-radius: 20px;
        padding: 1px 7px;
        font-size: 10px;
    }

    .order-tab.active .order-tab-count {
        background: rgba(184, 92, 56, 0.2);
        color: var(--admin-accent);
    }
</style>
@endpush