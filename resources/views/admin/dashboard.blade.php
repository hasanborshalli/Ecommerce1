@extends('admin.layout')

@section('breadcrumb', 'Dashboard')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Welcome back — here's what's happening today.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="abtn abtn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Add Product
    </a>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card accent">
        <p class="stat-label">Total Revenue</p>
        <p class="stat-value">{{ $currencySymbol }}{{ number_format($stats['total_revenue'], 0) }}</p>
        <p class="stat-sub">Confirmed orders</p>
    </div>
    <div class="stat-card warning">
        <p class="stat-label">Pending Orders</p>
        <p class="stat-value">{{ $stats['pending_orders'] }}</p>
        <p class="stat-sub">{{ $stats['total_orders'] }} total orders</p>
    </div>
    <div class="stat-card success">
        <p class="stat-label">Products</p>
        <p class="stat-value">{{ $stats['total_products'] }}</p>
        <p class="stat-sub">{{ $stats['total_categories'] }} categories</p>
    </div>
    <div class="stat-card info">
        <p class="stat-label">Unread Messages</p>
        <p class="stat-value">{{ $stats['unread_messages'] }}</p>
        <p class="stat-sub"><a href="{{ route('admin.messages.index') }}" style="color:var(--admin-info);">View all</a>
        </p>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start;">

    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Recent Orders</span>
            <a href="{{ route('admin.orders.index') }}" class="abtn abtn-outline abtn-sm">View All</a>
        </div>
        <div class="table-wrap table-stack-mobile">
            <table>
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td style="font-weight:500; color:var(--admin-accent);">{{ $order->order_number }}</td>
                        <td>
                            <div>{{ $order->customer_name }}</div>
                            <div style="font-size:11px; color:var(--admin-muted);">{{ $order->customer_email }}</div>
                        </td>
                        <td style="font-weight:500;">{{ $currencySymbol }}{{ number_format($order->total, 2) }}</td>
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
                        <td class="td-muted">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="abtn abtn-outline abtn-sm abtn-icon">
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
                        <td colspan="6" style="text-align:center; color:var(--admin-muted); padding:32px;">No orders yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Top Products</span>
        </div>
        <div style="padding:8px 0;">
            @forelse($topProducts as $i => $product)
            <div
                style="display:flex; align-items:center; gap:12px; padding:12px 20px; border-bottom:1px solid var(--admin-border);">
                <span style="font-size:13px; font-weight:600; color:var(--admin-muted); width:18px;">{{ $i + 1 }}</span>
                <div
                    style="width:36px; height:40px; background:var(--admin-bg); border-radius:4px; overflow:hidden; flex-shrink:0;">
                    @if($product->main_image)
                    <img src="{{ asset('storage/' . $product->main_image) }}"
                        style="width:100%;height:100%;object-fit:contain;">
                    @endif
                </div>
                <div style="flex:1; min-width:0;">
                    <p
                        style="font-size:12px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $product->name }}</p>
                    <p style="font-size:11px; color:var(--admin-muted);">{{ $product->order_items_count }} sold</p>
                </div>
                <span style="font-size:12px; font-weight:500; color:var(--admin-accent);">{{ $currencySymbol }}{{
                    number_format($product->price, 0) }}</span>
            </div>
            @empty
            <p style="text-align:center; color:var(--admin-muted); padding:32px; font-size:13px;">No sales yet</p>
            @endforelse
        </div>
    </div>
</div>

@endsection