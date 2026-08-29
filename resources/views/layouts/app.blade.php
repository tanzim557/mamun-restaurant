<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="শ্যামনগর নজরুল হোটেল — সাতক্ষীরার ঐতিহ্যবাহী চুইঝালের খাবারের ঘর।">
    <title>@yield('title', 'মামুন হোটেল ও রেস্টুরেন্ট — সাতক্ষীরা')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('head')
</head>
<body>
    <!-- Premium Glassmorphic Navbar -->
    <header class="navbar" id="navbar">
        <div class="navbar-inner container">
            <a href="/" class="navbar-brand">
                <div class="logo-wrapper">
                    <img src="/images/logo.jpg" alt="Mamun Hotel Logo" class="brand-logo">
                </div>
                <div class="brand-text">
                    <span class="brand-title">মামুন হোটেল</span>
                    <span class="brand-subtitle">শ্যামনগর নজরুল হোটেল</span>
                </div>
            </a>
            <nav class="navbar-links" id="navLinks">
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <span>হোম</span>
                </a>
                <a href="/menu" class="nav-link {{ request()->is('menu') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                    <span>মেনু</span>
                </a>
                <a href="/order" class="nav-link {{ request()->is('order') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <span>অর্ডার</span>
                </a>
                <a href="/gallery" class="nav-link {{ request()->is('gallery') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <span>গ্যালারি</span>
                </a>
                <a href="/about" class="nav-link {{ request()->is('about') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>আমাদের কথা</span>
                </a>
                <a href="/contact" class="nav-link {{ request()->is('contact') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>যোগাযোগ</span>
                </a>
            </nav>
            <div class="navbar-actions">
                <a href="/order" class="btn-nav-cta">
                    <span class="pulse-dot"></span>
                    <span>অনলাইন অর্ডার</span>
                </a>
                <button class="navbar-toggle" id="navToggle" aria-label="Toggle menu">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Nav -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
        <div class="mobile-nav-panel">
            <button class="mobile-nav-close" id="mobileNavClose">&times;</button>
            <div style="margin-top:2rem;">
                <a href="/"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg> Home</a>
                <a href="/menu"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg> Menu</a>
                <a href="/order"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg> Order</a>
                <a href="/gallery"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/></svg> Gallery</a>
                <a href="/about"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><circle cx="12" cy="12" r="10"/></svg> About</a>
                <a href="/contact"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Contact</a>
            </div>
        </div>
    </div>

    <!-- Main Content (with top padding for fixed navbar) -->
    <main style="padding-top:72px;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-inner">
                <div>
                    <h4>মামুন হোটেল</h4>
                    <p style="color:var(--zinc-500);font-size:0.9rem;line-height:1.7;">শ্যামনগর নজরুল হোটেল — সাতক্ষীরার বিশ্বস্ত খাবারের ঘর। চুইঝালের গরু ও হাঁসের গোশত আমাদের বিশেষত্ব।</p>
                </div>
                <div>
                    <h4>Quick Links</h4>
                    <a href="/menu">Menu</a>
                    <a href="/order">Order Online</a>
                    <a href="/about">About Us</a>
                    <a href="/contact">Contact</a>
                </div>
                <div>
                    <h4>Contact</h4>
                    <p style="display:flex;align-items:center;gap:8px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--secondary);flex-shrink:0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা</p>
                    <p style="display:flex;align-items:center;gap:8px;margin-top:6px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--primary);flex-shrink:0;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> ০১৯৮৮-৯৭৬২৬৯</p>
                    <p style="display:flex;align-items:center;gap:8px;margin-top:6px;"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--blue);flex-shrink:0;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> tanzim2713n@gmail.com</p>
                </div>
                <div>
                    <h4>Opening Hours</h4>
                    <p>Sun - Thu: 5:00 AM - 10:00 PM</p>
                    <p style="color:var(--red);">Friday: বন্ধ</p>
                    <p style="color:var(--red);">Saturday: বন্ধ</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} মামুন হোটেল। All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="/js/app.js"></script>
    @yield('scripts')
</body>
</html>
