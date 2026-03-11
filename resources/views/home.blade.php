@extends('layouts.app')

@section('content')

{{-- ── HERO ──────────────────────────────────────────────────── --}}
<section class="hero" id="heroSlider">
    @if($slides->count())
    @foreach($slides as $i => $slide)
    <div class="hero-slide {{ $i === 0 ? 'active' : '' }}"
        style="background-image: linear-gradient(to right, rgba(28,28,26,0.55) 0%, rgba(28,28,26,0.1) 60%), url('{{ asset('storage/' . $slide->image) }}')">
        <div class="container">
            <div class="hero-content fade-up fade-up-1">
                <span class="label-caps" style="color:var(--sand); margin-bottom:20px; display:block;">{{ $siteName
                    }}</span>
                <h1 class="display-xl" style="color:var(--ivory); margin-bottom:20px;">{{ $slide->title }}</h1>
                @if($slide->subtitle)
                <p class="hero-subtitle fade-up fade-up-2">{{ $slide->subtitle }}</p>
                @endif
                @if($slide->button_text)
                <a href="{{ $slide->button_link ?? route('shop') }}" class="btn btn-terra fade-up fade-up-3">
                    {{ $slide->button_text }}
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    {{-- Slide controls --}}
    @if($slides->count() > 1)
    <div class="hero-controls">
        <button class="hero-prev" aria-label="Previous">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <polyline points="15 18 9 12 15 6" />
            </svg>
        </button>
        <div class="hero-dots">
            @foreach($slides as $i => $slide)
            <button class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"
                aria-label="Slide {{ $i + 1 }}"></button>
            @endforeach
        </div>
        <button class="hero-next" aria-label="Next">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <polyline points="9 18 15 12 9 6" />
            </svg>
        </button>
    </div>
    @endif

    @else
    {{-- Fallback hero if no slides --}}
    <div class="hero-slide active hero-fallback">
        <div class="container">
            <div class="hero-content fade-up fade-up-1">
                <span class="label-caps" style="color:var(--sand); margin-bottom:20px; display:block;">New
                    Collection</span>
                <h1 class="display-xl" style="color:var(--ivory);">Curated for<br><em>the Refined</em></h1>
                <p class="hero-subtitle fade-up fade-up-2">Discover pieces that speak of quality, craft, and timeless
                    elegance.</p>
                <a href="{{ route('shop') }}" class="btn btn-terra fade-up fade-up-3">
                    Explore Collection
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif
</section>

{{-- ── CATEGORIES ────────────────────────────────────────────── --}}
@if($categories->count())
<section class="section-pad-sm" style="background: var(--white);">
    <div class="container">
        <div class="section-header">
            <span class="label-caps">Explore</span>
            <h2 class="display-md">Shop by Category</h2>
        </div>
        <div class="categories-grid">
            @foreach($categories as $cat)
            <a href="{{ route('shop.category', $cat->slug) }}" class="category-card">
                <div class="category-card-image">
                    @if($cat->image)
                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}" loading="lazy">
                    @else
                    <div class="category-placeholder"></div>
                    @endif
                    <div class="category-card-overlay"></div>
                </div>
                <div class="category-card-info">
                    <span class="category-card-name">{{ $cat->name }}</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.5">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── FEATURED PRODUCTS ─────────────────────────────────────── --}}
@if($featured->count())
<section class="section-pad">
    <div class="container">
        <div class="section-header">
            <span class="label-caps">Handpicked</span>
            <h2 class="display-md">Featured Products</h2>
            <p>Our most-loved pieces, selected for exceptional quality and design.</p>
        </div>
        <div class="product-grid product-grid-4">
            @foreach($featured as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div style="text-align:center; margin-top:48px;">
            <a href="{{ route('shop') }}" class="btn btn-outline">View All Products</a>
        </div>
    </div>
</section>
@endif

{{-- ── PROMO BANNER ──────────────────────────────────────────── --}}
<section class="promo-banner">
    <div class="promo-banner-inner">
        <div class="promo-text">
            <span class="label-caps" style="color:var(--terra); margin-bottom:12px; display:block;">Limited Time</span>
            <h2 class="display-lg" style="color:var(--ivory); margin-bottom:16px;">Sale — Up to 40% Off</h2>
            <p style="color:var(--sand); margin-bottom:32px;">Don't miss our seasonal selection of curated pieces at
                exceptional prices.</p>
            <a href="{{ route('shop') }}?sale" class="btn btn-terra">Shop the Sale</a>
        </div>
    </div>
</section>

{{-- ── NEW ARRIVALS ──────────────────────────────────────────── --}}
@if($newArrivals->count())
<section class="section-pad" style="background: var(--white);">
    <div class="container">
        <div class="section-header">
            <span class="label-caps">Fresh In</span>
            <h2 class="display-md">New Arrivals</h2>
            <p>The latest additions to our curated collection.</p>
        </div>
        <div class="product-grid product-grid-4">
            @foreach($newArrivals as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── BRAND VALUES ──────────────────────────────────────────── --}}
<section class="section-pad-sm" style="background:var(--ivory-dark);">
    <div class="container">
        <div class="values-grid">
            <div class="value-item fade-up fade-up-1">
                <div class="value-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    </svg>
                </div>
                <h4>Authenticity Guaranteed</h4>
                <p>Every product is sourced directly from verified makers and brands.</p>
            </div>
            <div class="value-item fade-up fade-up-2">
                <div class="value-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.2">
                        <rect x="1" y="3" width="15" height="13" />
                        <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                        <circle cx="5.5" cy="18.5" r="2.5" />
                        <circle cx="18.5" cy="18.5" r="2.5" />
                    </svg>
                </div>
                <h4>Express Delivery</h4>
                <p>Complimentary shipping on all orders over {{ $currencySymbol }}{{
                    number_format((float)\App\Models\SiteSetting::get('free_shipping_over', 100), 0) }}.</p>
            </div>
            <div class="value-item fade-up fade-up-3">
                <div class="value-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.2">
                        <polyline points="23 4 23 10 17 10" />
                        <polyline points="1 20 1 14 7 14" />
                        <path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15" />
                    </svg>
                </div>
                <h4>Easy Returns</h4>
                <p>Hassle-free 30-day returns — no questions asked.</p>
            </div>
            <div class="value-item fade-up fade-up-4">
                <div class="value-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.2">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                    </svg>
                </div>
                <h4>Thoughtfully Curated</h4>
                <p>Each piece is selected for beauty, quality, and lasting value.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── TESTIMONIALS ──────────────────────────────────────────── --}}
@if($testimonials->count())
<section class="section-pad">
    <div class="container">
        <div class="section-header">
            <span class="label-caps">Reviews</span>
            <h2 class="display-md">What Our Customers Say</h2>
        </div>
        <div class="testimonials-grid">
            @foreach($testimonials as $t)
            <div class="testimonial-card">
                <div class="testimonial-stars">
                    @for($i = 0; $i < $t->rating; $i++)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="var(--terra)" stroke="none">
                            <polygon
                                points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                        </svg>
                        @endfor
                </div>
                <p class="testimonial-text">"{{ $t->review }}"</p>
                <div class="testimonial-author">
                    <strong>{{ $t->customer_name }}</strong>
                    @if($t->customer_location)
                    <span>{{ $t->customer_location }}</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── NEWSLETTER ────────────────────────────────────────────── --}}
<section class="newsletter-section">
    <div class="container-sm" style="text-align:center;">
        <span class="label-caps" style="color:var(--terra); margin-bottom:16px; display:block;">Stay Connected</span>
        <h2 class="display-md" style="margin-bottom:16px;">Join the Inner Circle</h2>
        <p style="color:var(--charcoal-mid); margin-bottom:36px;">Be the first to discover new arrivals, exclusive
            offers, and curated stories.</p>
        <form class="newsletter-form" onsubmit="handleNewsletter(event)">
            <input type="email" class="form-control" placeholder="Your email address" required
                style="max-width:400px; display:inline-block;">
            <button type="submit" class="btn btn-dark" style="margin-top:12px;">Subscribe</button>
        </form>
    </div>
</section>

@endsection

@push('styles')
<link rel="stylesheet" href="/css/home.css">
@endpush

@push('scripts')
<script>
    // Hero slider
    const slides = document.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    let current  = 0;
    let timer;

    function goTo(n) {
        slides[current].classList.remove('active');
        dots[current]?.classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current]?.classList.add('active');
    }

    function startTimer() {
        clearInterval(timer);
        timer = setInterval(() => goTo(current + 1), 5000);
    }

    if (slides.length > 1) {
        document.querySelector('.hero-next')?.addEventListener('click', () => { goTo(current + 1); startTimer(); });
        document.querySelector('.hero-prev')?.addEventListener('click', () => { goTo(current - 1); startTimer(); });
        dots.forEach((d, i) => d.addEventListener('click', () => { goTo(i); startTimer(); }));
        startTimer();
    }

    // Newsletter
    function handleNewsletter(e) {
        e.preventDefault();
        showToast('Thank you for subscribing!');
        e.target.reset();
    }

    // Intersection observer for fade-up
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) { e.target.style.animationPlayState = 'running'; }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.fade-up').forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
</script>
@endpush