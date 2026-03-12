<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
    // Safe fallbacks in case ViewServiceProvider is not yet registered
    $siteName = $siteName ?? config('app.name', 'Store');
    $siteLogo = $siteLogo ?? '';
    $siteTagline = $siteTagline ?? '';
    $currencySymbol = $currencySymbol ?? '$';
    $sitePhone = $sitePhone ?? '';
    $siteEmail = $siteEmail ?? '';
    $socialLinks = $socialLinks ?? [];
    $navCategories = $navCategories ?? collect();
    $cartCount = $cartCount ?? 0;
    $meta = $meta ?? [];
    @endphp

    {{-- SEO --}}
    <title>{{ $meta['title'] ?? $siteName }}</title>
    <meta name="description" content="{{ $meta['description'] ?? '' }}">
    @isset($meta['keywords'])
    <meta name="keywords" content="{{ $meta['keywords'] }}">@endisset

    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $meta['title'] ?? $siteName }}">
    <meta property="og:description" content="{{ $meta['description'] ?? '' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @isset($meta['image'])
    <meta property="og:image" content="{{ $meta['image'] }}">@endisset

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $meta['title'] ?? $siteName }}">
    <meta name="twitter:description" content="{{ $meta['description'] ?? '' }}">

    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="/css/app.css">

    @stack('styles')
</head>

<body>

    {{-- Announcement Bar --}}
    <div class="announce-bar">
        <span class="label-caps">Free shipping on orders over {{ $currencySymbol }}{{ number_format((float)
            \App\Models\SiteSetting::get('free_shipping_over', 100), 0) }}</span>
    </div>

    {{-- Navigation --}}
    <div class="nav-wrap" id="navWrap">
        <div class="container">
            <nav class="nav-inner">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="nav-logo">
                    @if($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}">
                    @else
                    {{ $siteName }}
                    @endif
                </a>

                {{-- Desktop Links --}}
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    </li>
                    <li class="nav-dropdown">
                        <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop*') ? 'active' : '' }}">Shop</a>
                        @if($navCategories->count())
                        <div class="nav-dropdown-menu">
                            <a href="{{ route('shop') }}" class="nav-dropdown-item">
                                <span>All Products</span>
                                <small>Browse everything</small>
                            </a>
                            @foreach($navCategories as $cat)
                            <a href="{{ route('shop.category', $cat->slug) }}" class="nav-dropdown-item">
                                <span>{{ $cat->name }}</span>
                                <small>{{ $cat->description }}</small>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </li>
                    <li><a href="{{ route('about') }}"
                            class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                </ul>

                {{-- Actions --}}
                <div class="nav-actions">
                    <a href="{{ route('cart.index') }}" class="nav-icon-btn" aria-label="Cart">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 01-8 0" />
                        </svg>
                        <span class="cart-badge" id="cartBadge">{{ $cartCount ?? 0 }}</span>
                    </a>
                    <button class="nav-hamburger" id="menuToggle" aria-label="Menu">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </nav>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <span class="nav-logo">{{ $siteName }}</span>
            <button id="menuClose" aria-label="Close menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <nav>
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('shop') }}">Shop</a>
            @foreach($navCategories as $cat)
            <a href="{{ route('shop.category', $cat->slug) }}"
                style="padding-left:20px; font-size:1.4rem; color:var(--charcoal-mid);">— {{ $cat->name }}</a>
            @endforeach
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('cart.index') }}">Cart ({{ $cartCount ?? 0 }})</a>
        </nav>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="flash flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="flash flash-error">{{ session('error') }}</div>
    @endif

    {{-- Page Content --}}
    <main>@yield('content')</main>

    {{-- Footer --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-top">
                <div>
                    <div class="footer-brand-name">{{ $siteName }}</div>
                    <p class="footer-about">{{ \App\Models\SiteSetting::get('footer_about', '') }}</p>
                    <div class="footer-social">
                        @if($socialLinks['instagram'] ?? '')
                        <a href="{{ $socialLinks['instagram'] }}" target="_blank" rel="noopener" aria-label="Instagram">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <rect x="2" y="2" width="20" height="20" rx="5" />
                                <circle cx="12" cy="12" r="4" />
                                <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none" />
                            </svg>
                        </a>
                        @endif
                        @if($socialLinks['facebook'] ?? '')
                        <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener" aria-label="Facebook">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                            </svg>
                        </a>
                        @endif
                        @if($socialLinks['tiktok'] ?? '')
                        <a href="{{ $socialLinks['tiktok'] }}" target="_blank" rel="noopener" aria-label="TikTok">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.34 6.34 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z" />
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Shop</h4>
                    <ul>
                        <li><a href="{{ route('shop') }}">All Products</a></li>
                        <li><a href="{{ route('shop') }}?new">New Arrivals</a></li>
                        <li><a href="{{ route('shop') }}?sale">On Sale</a></li>
                        @foreach($navCategories->take(4) as $cat)
                        <li><a href="{{ route('shop.category', $cat->slug) }}">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Information</h4>
                    <ul>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Returns & Exchanges</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <ul>
                        @if($siteEmail)
                        <li><a href="/cdn-cgi/l/email-protection#0c77772c287f65786949616d65602c7171">{{ $siteEmail
                                }}</a></li>
                        @endif
                        @if($sitePhone)
                        <li><a href="tel:{{ $sitePhone }}">{{ $sitePhone }}</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <hr class="divider" style="border-color: rgba(200,184,154,0.15);">
        <div class="container">
            <div class="footer-bottom">
                <p>© {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
                <a href="https://brndnglb.com" target="_blank">
                    <p class="powered-by">Powered by <span>brndng.</span></p>
                </a>
            </div>
        </div>
    </footer>

    <script>
        // Sticky nav shadow
        const nav = document.getElementById('navWrap');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 20);
        });

        // Mobile menu
        document.getElementById('menuToggle').addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.add('open');
            document.body.style.overflow = 'hidden';
        });
        document.getElementById('menuClose').addEventListener('click', () => {
            document.getElementById('mobileMenu').classList.remove('open');
            document.body.style.overflow = '';
        });

        // Update cart badge count
        function refreshCartBadge(count) {
            document.querySelectorAll('#cartBadge').forEach(el => {
                el.textContent = count;
            });
        }

        // Add to cart — Accept: application/json makes Laravel's wantsJson() return true
        function addToCart(productId, quantity, variant) {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const params = new URLSearchParams();
            params.append('product_id', productId);
            params.append('quantity',   quantity || 1);
            params.append('_token',     token);
            if (variant && typeof variant === 'object') {
                Object.entries(variant).forEach(([k, v]) => params.append('variant[' + k + ']', v));
            }
            return fetch('{{ route("cart.add") }}', {
                method:  'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': token,
                },
                body: params.toString(),
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    refreshCartBadge(d.count);
                    showToast('Added to cart ✓');
                } else {
                    showToast(d.message || 'Could not add item');
                }
                return d;
            })
            .catch(() => showToast('Something went wrong'));
        }

        // Toast notification
        function showToast(msg) {
            document.querySelectorAll('.site-toast').forEach(el => el.remove());
            const t = document.createElement('div');
            t.className = 'site-toast';
            t.textContent = msg;
            t.style.cssText = 'position:fixed;bottom:32px;left:50%;transform:translateX(-50%) translateY(10px);background:var(--charcoal);color:var(--ivory);padding:13px 32px;font-family:var(--font-body);font-size:13px;letter-spacing:0.06em;z-index:99999;opacity:0;transition:opacity 0.25s,transform 0.25s;pointer-events:none;border-left:3px solid var(--terra);white-space:nowrap;';
            document.body.appendChild(t);
            requestAnimationFrame(() => requestAnimationFrame(() => {
                t.style.opacity = '1';
                t.style.transform = 'translateX(-50%) translateY(0)';
            }));
            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transform = 'translateX(-50%) translateY(8px)';
                setTimeout(() => t.remove(), 300);
            }, 2800);
        }
    </script>

    @stack('scripts')
</body>

</html>