@extends('layouts.app')

@section('content')
<div class="section-pad-sm">
    <div class="container">
        <nav class="breadcrumb" style="margin-bottom:32px;">
            <a href="{{ route('home') }}">Home</a><span>/</span>
            <a href="{{ route('shop') }}">Shop</a><span>/</span>
            @if($product->category)
            <a href="{{ route('shop.category', $product->category->slug) }}">{{ $product->category->name
                }}</a><span>/</span>
            @endif
            <span>{{ $product->name }}</span>
        </nav>

        <div class="product-detail-grid">

            {{-- ── Gallery ─────────────────────────────────────── --}}
            <div class="product-gallery">
                <div class="gallery-main" id="galleryMain">
                    @if($product->main_image)
                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" id="mainImage">
                    @else
                    <div class="gallery-placeholder">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="var(--sand)"
                            stroke-width="1">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                    </div>
                    @endif
                    @if($product->is_on_sale && $product->discount_percent > 0)
                    <span class="badge badge-sale" style="position:absolute;top:16px;left:16px;">-{{
                        $product->discount_percent }}%</span>
                    @endif
                </div>
                @if($product->gallery && count($product->gallery) > 0)
                <div class="gallery-thumbs">
                    <button class="gallery-thumb active"
                        onclick="setImage('{{ asset('storage/' . $product->main_image) }}', this)">
                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="">
                    </button>
                    @foreach($product->gallery as $img)
                    <button class="gallery-thumb" onclick="setImage('{{ asset('storage/' . $img) }}', this)">
                        <img src="{{ asset('storage/' . $img) }}" alt="">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- ── Info ────────────────────────────────────────── --}}
            <div class="product-info">
                @if($product->category)
                <p class="label-caps" style="color:var(--terra); margin-bottom:12px;">{{ $product->category->name }}</p>
                @endif

                <h1 class="display-md" style="margin-bottom:16px; line-height:1.2;">{{ $product->name }}</h1>

                {{-- Price --}}
                <div class="product-price-block">
                    @if($product->is_on_sale && $product->sale_price)
                    <span class="product-price-sale">{{ $currencySymbol }}{{ number_format($product->sale_price, 2)
                        }}</span>
                    <span class="product-price-original">{{ $currencySymbol }}{{ number_format($product->price, 2)
                        }}</span>
                    <span class="badge badge-sale" style="margin-left:8px;">Save {{ $product->discount_percent
                        }}%</span>
                    @else
                    <span class="product-price-sale">{{ $currencySymbol }}{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                @if($product->short_description)
                <p class="product-short-desc">{{ $product->short_description }}</p>
                @endif

                <hr class="divider" style="margin:24px 0;">

                {{-- Add to cart form --}}
                <form id="addToCartForm">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Variants --}}
                    @if($product->variants && count($product->variants))
                    @foreach($product->variants as $variantType => $options)
                    @if(count($options) > 0)
                    <div class="form-group">
                        <label class="form-label">{{ ucfirst($variantType) }}</label>
                        <div class="variant-options" id="variant-{{ $variantType }}">
                            @foreach($options as $opt)
                            <button type="button" class="variant-btn" data-type="{{ $variantType }}"
                                data-value="{{ $opt }}" onclick="selectVariant(this, '{{ $variantType }}')">
                                {{ $opt }}
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="variant[{{ $variantType }}]" id="selected_{{ $variantType }}"
                            required>
                    </div>
                    @endif
                    @endforeach
                    @endif

                    {{-- Quantity --}}
                    <div class="form-group">
                        <label class="form-label">Quantity</label>
                        <div class="qty-control">
                            <button type="button" onclick="changeQty(-1)">−</button>
                            <input type="number" name="quantity" id="qtyInput" value="1" min="1"
                                max="{{ $product->stock }}" class="qty-input">
                            <button type="button" onclick="changeQty(1)">+</button>
                        </div>
                        @if($product->stock <= 5 && $product->stock > 0)
                            <p style="font-size:12px; color:var(--terra); margin-top:6px;">Only {{ $product->stock }}
                                left in stock</p>
                            @endif
                    </div>

                    @if($product->stock > 0)
                    <button type="submit" class="btn btn-dark btn-full" style="margin-top:8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 01-8 0" />
                        </svg>
                        Add to Cart
                    </button>
                    @else
                    <button class="btn btn-outline btn-full" disabled
                        style="opacity:0.5; cursor:not-allowed; margin-top:8px;">
                        Out of Stock
                    </button>
                    @endif
                </form>

                <hr class="divider" style="margin:28px 0;">

                {{-- Meta --}}
                <div class="product-meta">
                    @if($product->sku)
                    <p><span>SKU:</span> {{ $product->sku }}</p>
                    @endif
                    @if($product->category)
                    <p><span>Category:</span> <a href="{{ route('shop.category', $product->category->slug) }}">{{
                            $product->category->name }}</a></p>
                    @endif
                </div>

                {{-- Description accordion --}}
                @if($product->description)
                <div class="product-accordion" style="margin-top:28px;">
                    <button class="accordion-trigger" onclick="toggleAccordion(this)">
                        <span class="label-caps" style="font-size:11px;">Product Details</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </button>
                    <div class="accordion-body">
                        <div class="prose">{!! $product->description !!}</div>
                    </div>
                </div>
                @endif

                <div class="product-accordion">
                    <button class="accordion-trigger" onclick="toggleAccordion(this)">
                        <span class="label-caps" style="font-size:11px;">Shipping & Returns</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </button>
                    <div class="accordion-body">
                        <p>Free standard shipping on orders over {{ $currencySymbol }}{{
                            number_format((float)\App\Models\SiteSetting::get('free_shipping_over', 100), 0) }}. Orders
                            are processed within 1–2 business days. Easy 30-day returns, no questions asked.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Related Products --}}
@if($related->count())
<section class="section-pad-sm" style="background:var(--white);">
    <div class="container">
        <div class="section-header">
            <span class="label-caps">You May Also Like</span>
            <h2 class="display-sm">Related Products</h2>
        </div>
        <div class="product-grid product-grid-4">
            @foreach($related as $rp)
            @include('partials.product-card', ['product' => $rp])
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('styles')
<link rel="stylesheet" href="/css/product.css">
@endpush

@push('scripts')
<script>
    function setImage(src, btn) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
    }

    function changeQty(delta) {
        const input = document.getElementById('qtyInput');
        const max   = parseInt(input.max) || 99;
        input.value = Math.max(1, Math.min(max, parseInt(input.value) + delta));
    }

    function selectVariant(btn, type) {
        document.querySelectorAll(`[data-type="${type}"]`).forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById(`selected_${type}`).value = btn.dataset.value;
    }

    function toggleAccordion(btn) {
        btn.classList.toggle('open');
        const body = btn.nextElementSibling;
        body.classList.toggle('open');
    }

    // Add to cart
    document.getElementById('addToCartForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const data  = new FormData(this);
        const pid   = data.get('product_id');
        const qty   = parseInt(data.get('quantity')) || 1;
        const variant = {};
        for (const [k, v] of data.entries()) {
            if (k.startsWith('variant[')) {
                const key = k.slice(8, -1);
                variant[key] = v;
            }
        }
        addToCart(pid, qty, variant);
    });
</script>
@endpush