@extends('layouts.app')

@section('content')

{{-- Page header --}}
<div class="shop-header">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            @isset($category)
            <a href="{{ route('shop') }}">Shop</a>
            <span>/</span>
            <span>{{ $category->name }}</span>
            @else
            <span>Shop</span>
            @endisset
        </nav>
        <h1 class="display-lg" style="margin-top:8px;">
            {{ isset($category) ? $category->name : 'All Products' }}
        </h1>
        @isset($category)
        @if($category->description)
        <p style="color:var(--charcoal-mid); margin-top:8px;">{{ $category->description }}</p>
        @endif
        @endisset
    </div>
</div>

<div class="section-pad-sm">
    <div class="container">
        <div class="shop-layout">

            {{-- ── Sidebar Filters ─────────────────────────────── --}}
            <aside class="shop-sidebar">
                <form method="GET" action="{{ route('shop') }}" id="filterForm">
                    {{-- Keep category if set --}}
                    @isset($category)
                    <input type="hidden" name="category" value="{{ $category->slug }}">
                    @endisset

                    <div class="filter-section">
                        <h3 class="filter-title">Categories</h3>
                        <ul class="filter-list">
                            <li>
                                <a href="{{ route('shop') }}"
                                    class="filter-link {{ !isset($category) && !request('category') ? 'active' : '' }}">
                                    All Products
                                </a>
                            </li>
                            @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('shop.category', $cat->slug) }}"
                                    class="filter-link {{ isset($category) && $category->id === $cat->id ? 'active' : '' }}">
                                    {{ $cat->name }}
                                    <span class="filter-count">{{ $cat->activeProducts->count() }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="filter-section">
                        <h3 class="filter-title">Filter</h3>
                        <ul class="filter-list">
                            <li>
                                <label class="filter-checkbox">
                                    <input type="checkbox" name="new" value="1" {{ request()->has('new') ? 'checked' :
                                    '' }} onchange="document.getElementById('filterForm').submit()">
                                    <span>New Arrivals</span>
                                </label>
                            </li>
                            <li>
                                <label class="filter-checkbox">
                                    <input type="checkbox" name="sale" value="1" {{ request()->has('sale') ? 'checked' :
                                    '' }} onchange="document.getElementById('filterForm').submit()">
                                    <span>On Sale</span>
                                </label>
                            </li>
                        </ul>
                    </div>

                    <div class="filter-section">
                        <h3 class="filter-title">Price Range</h3>
                        <div class="price-range">
                            <div class="form-group" style="margin-bottom:12px;">
                                <input type="number" name="min_price" class="form-control" placeholder="Min price"
                                    value="{{ request('min_price') }}" style="font-size:13px;">
                            </div>
                            <div class="form-group" style="margin-bottom:16px;">
                                <input type="number" name="max_price" class="form-control" placeholder="Max price"
                                    value="{{ request('max_price') }}" style="font-size:13px;">
                            </div>
                            <button type="submit" class="btn btn-outline btn-full"
                                style="font-size:11px;">Apply</button>
                        </div>
                    </div>

                    @if(request()->hasAny(['new', 'sale', 'min_price', 'max_price']))
                    <a href="{{ route('shop') }}" class="btn-clear-filters">Clear all filters</a>
                    @endif
                </form>
            </aside>

            {{-- ── Products area ───────────────────────────────── --}}
            <div class="shop-main">

                {{-- Toolbar --}}
                <div class="shop-toolbar">
                    <p class="shop-count">
                        <span>{{ $products->total() }}</span> {{ Str::plural('product', $products->total()) }}
                    </p>
                    <div class="shop-sort">
                        <label class="label-caps" style="font-size:10px; margin-right:8px;">Sort:</label>
                        <select class="form-control" style="width:auto; padding:8px 12px;"
                            onchange="sortChange(this.value)">
                            <option value="default" {{ request('sort','default')==='default' ? 'selected' : '' }}>
                                Featured</option>
                            <option value="newest" {{ request('sort')==='newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_asc" {{ request('sort')==='price_asc' ? 'selected' : '' }}>Price: Low
                                to High</option>
                            <option value="price_desc" {{ request('sort')==='price_desc' ? 'selected' : '' }}>Price:
                                High to Low</option>
                        </select>
                    </div>
                </div>

                {{-- Grid --}}
                @if($products->count())
                <div class="product-grid product-grid-3">
                    @foreach($products as $product)
                    @include('partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                <div class="pagination">
                    {{$products->links('partials.pagination')}}
                </div>
                @endif

                @else
                <div class="shop-empty">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--sand)" stroke-width="1">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <h3>No products found</h3>
                    <p>Try adjusting your filters or <a href="{{ route('shop') }}">browse all products</a>.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="/css/shop.css">
@endpush

@push('scripts')
<script>
    function sortChange(val) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', val);
        window.location.href = url.toString();
    }
</script>
@endpush