@extends('admin.layout')

@section('breadcrumb', 'Products')

@section('topbar-actions')
<a href="{{ route('admin.products.create') }}" class="topbar-btn">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="12" y1="5" x2="12" y2="19" />
        <line x1="5" y1="12" x2="19" y2="12" />
    </svg>
    New Product
</a>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Products</h1>
        <p class="page-subtitle">{{ $products->total() }} products total</p>
    </div>
</div>

{{-- Filter bar --}}
<form method="GET" action="{{ route('admin.products.index') }}">
    <div class="filter-bar">
        <div class="search-input-wrap">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" name="search" class="aform-control" placeholder="Search products…"
                value="{{ request('search') }}">
        </div>
        <select name="category_id" class="aform-control" style="width:180px;" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id ? 'selected' : '' }}>{{ $cat->name }}
            </option>
            @endforeach
        </select>
        <button type="submit" class="abtn abtn-outline">Filter</button>
        @if(request()->hasAny(['search','category_id']))
        <a href="{{ route('admin.products.index') }}" class="abtn abtn-outline"
            style="color:var(--admin-danger); border-color:rgba(239,68,68,0.3);">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-wrap table-stack-mobile">
        <table>
            <thead>
                <tr>
                    <th style="width:52px;">Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Flags</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div
                            style="width:40px; height:48px; background:var(--admin-bg); border-radius:4px; overflow:hidden;">
                            @if($product->main_image)
                            <img src="{{ asset('storage/' . $product->main_image) }}"
                                style="width:100%;height:100%;object-fit:contain;">
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-weight:500;">{{ $product->name }}</div>
                        @if($product->sku)<div style="font-size:11px; color:var(--admin-muted);">SKU: {{ $product->sku
                            }}</div>@endif
                    </td>
                    <td class="td-muted">{{ $product->category->name ?? '—' }}</td>
                    <td>
                        @if($product->is_on_sale && $product->sale_price)
                        <div style="font-weight:500; color:var(--admin-accent);">{{ $currencySymbol }}{{
                            number_format($product->sale_price,2) }}</div>
                        <div style="font-size:11px; color:var(--admin-muted); text-decoration:line-through;">{{
                            $currencySymbol }}{{ number_format($product->price,2) }}</div>
                        @else
                        <div style="font-weight:500;">{{ $currencySymbol }}{{ number_format($product->price,2) }}</div>
                        @endif
                    </td>
                    <td>
                        <span
                            class="{{ $product->stock > 5 ? 'abadge abadge-green' : ($product->stock > 0 ? 'abadge abadge-yellow' : 'abadge abadge-red') }}">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:4px; flex-wrap:wrap;">
                            @if($product->is_featured)<span class="abadge abadge-purple"
                                style="font-size:10px;">Featured</span>@endif
                            @if($product->is_new)<span class="abadge abadge-blue"
                                style="font-size:10px;">New</span>@endif
                            @if($product->is_on_sale)<span class="abadge abadge-orange"
                                style="font-size:10px;">Sale</span>@endif
                        </div>
                    </td>
                    <td>
                        <button onclick="toggleActive({{ $product->id }}, this)"
                            class="abadge {{ $product->is_active ? 'abadge-green' : 'abadge-gray' }}"
                            style="border:none; cursor:pointer;" data-active="{{ $product->is_active ? '1' : '0' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="abtn abtn-outline abtn-sm abtn-icon" title="Edit">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7" />
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="abtn abtn-danger abtn-sm abtn-icon" title="Delete">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path
                                            d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center; color:var(--admin-muted); padding:48px;">
                        No products found. <a href="{{ route('admin.products.create') }}"
                            style="color:var(--admin-accent);">Create one →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div style="padding:12px 20px; border-top:1px solid var(--admin-border);">
        <div class="admin-pagination">{{ $products->links('partials.pagination') }}</div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    function toggleActive(id, btn) {
    fetch(`/admin/products/${id}/toggle-active`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    }).then(r => r.json()).then(d => {
        btn.className = 'abadge ' + (d.is_active ? 'abadge-green' : 'abadge-gray');
        btn.textContent = d.is_active ? 'Active' : 'Inactive';
    });
}
</script>
@endpush