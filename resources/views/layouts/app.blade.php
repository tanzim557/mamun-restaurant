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
                <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}"><span>🏠 হোম</span></a>
                <a href="/menu" class="nav-link {{ request()->is('menu') ? 'active' : '' }}"><span>📋 মেনু</span></a>
                <a href="/order" class="nav-link {{ request()->is('order') ? 'active' : '' }}"><span>🛒 অর্ডার</span></a>
                <a href="/gallery" class="nav-link {{ request()->is('gallery') ? 'active' : '' }}"><span>🖼️ গ্যালারি</span></a>
                <a href="/about" class="nav-link {{ request()->is('about') ? 'active' : '' }}"><span>ℹ️ আমাদের কথা</span></a>
                <a href="/contact" class="nav-link {{ request()->is('contact') ? 'active' : '' }}"><span>📞 যোগাযোগ</span></a>
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
                <a href="/">🏠 Home</a>
                <a href="/menu">📋 Menu</a>
                <a href="/order">🛒 Order</a>
                <a href="/gallery">🖼️ Gallery</a>
                <a href="/about">ℹ️ About</a>
                <a href="/contact">📞 Contact</a>
            </div>
        </div>
    </div>

    <!-- Main Content (with top padding for fixed navbar) -->
    <main style="padding-top:64px;">
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
                    <p>📍 উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা</p>
                    <p>📞 ০১৯৮৮-৯৭৬২৬৯</p>
                    <p>✉️ tanzim2713n@gmail.com</p>
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
