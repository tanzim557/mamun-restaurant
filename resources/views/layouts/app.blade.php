<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="শ্যামনগর নজরুল হোটেল — সাতক্ষীরার ঐতিহ্যবাহী চুইঝালের খাবারের ঘর।">
    <title>@yield('title', 'শ্যামনগর নজরুল হোটেল — সাতক্ষীরা')</title>
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
                    <img src="/images/logo.jpg" alt="শ্যামনগর নজরুল হোটেল" class="brand-logo">
                </div>
                <div class="brand-text">
                    <span class="brand-title">শ্যামনগর নজরুল হোটেল</span>
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
                <a href="/track" class="nav-link {{ request()->is('track*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>অর্ডার ট্র্যাক</span>
                </a>
                <a href="/about" class="nav-link {{ request()->is('about') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    <span>সম্পর্কে</span>
                </a>
                <a href="/contact" class="nav-link {{ request()->is('contact') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>যোগাযোগ</span>
                </a>
            </nav>
            <div class="navbar-actions">
                <a href="/order" class="btn btn-sm btn-primary">অর্ডার করুন</a>
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
                <a href="/track"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Track Order</a>
                <a href="/about"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><circle cx="12" cy="12" r="10"/></svg> About</a>
                <a href="/contact"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:8px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> Contact</a>
            </div>
        </div>
    </div>

    <!-- Main Content (with top padding for fixed navbar) -->
    <main style="padding-top:72px;">
        @yield('content')
    </main>

    <!-- Luxury Footer -->
    <footer class="footer">
        <div class="footer-glow-line"></div>
        <div class="container">
            <div class="footer-inner">
                <!-- Brand & Bio Column -->
                <div class="footer-col-brand">
                    <div class="footer-logo-wrap">
                        <div class="footer-logo-img">
                            <img src="/images/logo.jpg" alt="শ্যামনগর নজরুল হোটেল">
                        </div>
                        <div>
                            <h3 class="footer-brand-title">শ্যামনগর নজরুল হোটেল</h3>
                        </div>
                    </div>
                    <p class="footer-brand-desc">
                        সাতক্ষীরার চুইঝাল দিয়ে রান্না করা ঐতিহ্যবাহী গরু ও হাঁসের গোশতের নির্ভরযোগ্য ঠিকানা। পারিবারিক ও পরিচ্ছন্ন পরিবেশে খাবারের সুব্যবস্থা।
                    </p>
                    <a href="https://wa.me/8801988976269" target="_blank" class="footer-wa-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>হোয়াটসঅ্যাপে যোগাযোগ</span>
                    </a>
                </div>

                <!-- Quick Links Column -->
                <div>
                    <h4 class="footer-heading">কুইক মেনু</h4>
                    <ul class="footer-links-list">
                        <li><a href="/"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg> হোম পেজ</a></li>
                        <li><a href="/menu"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg> খাবারের মেনু</a></li>
                        <li><a href="/order"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg> অনলাইন অর্ডার</a></li>
                        <li><a href="/about"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg> আমাদের কথা</a></li>
                        <li><a href="/contact"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg> যোগাযোগ ও লোকেশন</a></li>
                    </ul>
                </div>

                <!-- Contact Info Column -->
                <div>
                    <h4 class="footer-heading">ঠিকানা ও যোগাযোগ</h4>
                    <div class="footer-contact-cards">
                        <div class="footer-contact-item">
                            <div class="footer-contact-icon icon-loc">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div>
                                <p class="footer-contact-label">লোকেশন</p>
                                <p class="footer-contact-val">উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা</p>
                            </div>
                        </div>

                        <a href="tel:01988976269" class="footer-contact-item">
                            <div class="footer-contact-icon icon-phone">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div>
                                <p class="footer-contact-label">ফোন নম্বর</p>
                                <p class="footer-contact-val text-primary font-bold">০১৯৮৮-৯৭৬২৬৯</p>
                            </div>
                        </a>

                        <a href="mailto:tanzim2713n@gmail.com" class="footer-contact-item">
                            <div class="footer-contact-icon icon-mail">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div>
                                <p class="footer-contact-label">ইমেইল</p>
                                <p class="footer-contact-val">tanzim2713n@gmail.com</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Opening Hours & Badges Column -->
                <div>
                    <h4 class="footer-heading">খোলার সময়সূচী</h4>
                    <div class="footer-hours-box">
                        <div class="hours-row">
                            <span class="hours-day">রবিবার - বৃহস্পতিবার</span>
                            <span class="hours-badge open"><span class="pulse-dot-sm"></span> ৫:০০ AM - ১০:০০ PM</span>
                        </div>
                        <div class="hours-row">
                            <span class="hours-day">শুক্রবার</span>
                            <span class="hours-badge closed">সাপ্তাহিক বন্ধ</span>
                        </div>
                        <div class="hours-row">
                            <span class="hours-day">শনিবার</span>
                            <span class="hours-badge closed">সাপ্তাহিক বন্ধ</span>
                        </div>
                    </div>

                    <div class="footer-trust-badge">
                        <div class="trust-badge-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        </div>
                        <div>
                            <p class="trust-badge-title">১০০% হালাল ও সুস্বাদু</p>
                            <p class="trust-badge-sub">সাতক্ষীরার খাঁটি চুইঝাল স্পেশাল</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} <strong>শ্যামনগর নজরুল হোটেল</strong>। সর্বস্বত্ব সংরক্ষিত।</p>
                <div class="footer-admin-link">
                    <a href="/admin">অ্যাডমিন প্যানেল</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="/js/app.js"></script>
    @yield('scripts')
</body>
</html>
