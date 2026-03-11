<article class="product-card">
    <a href="{{ route('product.show', $product->slug) }}" class="product-card-image">
        @if($product->main_image)
        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" loading="lazy">
        @else
        <div
            style="width:100%;height:100%;background:var(--sand-light);display:flex;align-items:center;justify-content:center;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--sand)" stroke-width="1">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <circle cx="8.5" cy="8.5" r="1.5" />
                <polyline points="21 15 16 10 5 21" />
            </svg>
        </div>
        @endif

        {{-- Badges --}}
        <div class="product-card-badges">
            @if($product->is_new)
            <span class="badge badge-new">New</span>
            @endif
            @if($product->is_on_sale && $product->discount_percent > 0)
            <span class="badge badge-sale">-{{ $product->discount_percent }}%</span>
            @endif
        </div>

        {{-- Quick add --}}
        <div class="product-card-quick" onclick="event.preventDefault(); addToCart({{ $product->id }}, 1)">
            Add to Cart
        </div>
    </a>

    <div class="product-card-info">
        <p class="product-card-category">{{ $product->category->name ?? '' }}</p>
        <h3 class="product-card-name">
            <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>
        <div class="product-card-price">
            @if($product->is_on_sale && $product->sale_price)
            <span class="price-current price-sale">{{ $currencySymbol }}{{ number_format($product->sale_price, 2)
                }}</span>
            <span class="price-original">{{ $currencySymbol }}{{ number_format($product->price, 2) }}</span>
            @else
            <span class="price-current">{{ $currencySymbol }}{{ number_format($product->price, 2) }}</span>
            @endif
        </div>
    </div>
</article>