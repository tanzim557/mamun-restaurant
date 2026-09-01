<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#09090d">
    <meta name="description" content="শ্যামনগর নজরুল হোটেল — সাতক্ষীরার ঐতিহ্যবাহী চুইঝালের খাবারের অনলাইন ডেলিভারি অ্যাপ।">
    <title>@yield('title', 'শ্যামনগর নজরুল হোটেল — ফুড ডেলিভারি')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('head')
</head>
<body>
    <!-- ═══════════════════════════════════════════════════════
         1. FOOD DELIVERY APP TOP BAR
         ═══════════════════════════════════════════════════════ -->
    <header class="app-topbar" id="appTopbar">
        <div class="app-container">
            <div class="app-topbar-inner">
                <!-- Location & Restaurant Status -->
                <a href="/" class="app-topbar-brand">
                    <img src="/images/logo.jpg" alt="নজরুল হোটেল" class="topbar-logo-img">
                    <div class="app-topbar-loc">
                        <div class="loc-deliver-label">
                            <span>ডেলিভারি লোকেশন</span>
                        </div>
                        <div class="loc-deliver-target">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color:var(--primary);flex-shrink:0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>সাতক্ষীরা সদর</span>
                            <span class="loc-status-chip">
                                <span class="pulse-dot-green"></span>
                                <span>খোলা</span>
                            </span>
                        </div>
                    </div>
                </a>

                <!-- Top Right Actions -->
                <div class="app-topbar-actions">
                    <a href="/menu" class="topbar-icon-btn" title="খাবার খুঁজুন" aria-label="Search food">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </a>
                    <button type="button" onclick="openCartSheet()" class="topbar-icon-btn" title="আপনার কার্ট" aria-label="Cart">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        <span class="topbar-badge-count" id="topbarCartBadge" style="display:none;">0</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════════════════════════════════════════════════════
         2. MAIN APP CONTENT VIEWPORT
         ═══════════════════════════════════════════════════════ -->
    <main class="app-main-viewport">
        @yield('content')
    </main>

    <!-- ═══════════════════════════════════════════════════════
         3. FLOATING CART ACTION BAR (STICKY PILL)
         ═══════════════════════════════════════════════════════ -->
    <div class="floating-cart-pill hidden" id="floatingCartPill" onclick="openCartSheet()">
        <div class="cart-pill-left">
            <div class="cart-pill-icon-box">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <div class="cart-pill-items-info">
                <span class="cart-pill-count" id="pillCartCount">০ টি আইটেম</span>
                <span class="cart-pill-total" id="pillCartTotal">৳০</span>
            </div>
        </div>
        <div class="cart-pill-right">
            <span>কার্ট দেখুন</span>
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         4. SLIDE-UP CART BOTTOM SHEET & DIRECT CHECKOUT
         ═══════════════════════════════════════════════════════ -->
    <div class="app-sheet-overlay" id="appSheetOverlay"></div>
    <div class="app-bottom-sheet" id="appCartSheet">
        <div class="sheet-handle-bar"></div>
        <div class="sheet-header">
            <div class="sheet-title-wrap">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color:var(--primary);"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <h3 class="sheet-title">আপনার খাবারের কার্ট</h3>
                <span class="loc-status-chip" id="sheetHeaderCount" style="margin-left:4px;">০ টি পদ</span>
            </div>
            <button type="button" onclick="closeCartSheet()" class="sheet-close-btn" aria-label="Close sheet">&times;</button>
        </div>

        <div class="sheet-body">
            <!-- Empty State -->
            <div id="sheetCartEmpty" class="hidden" style="text-align:center;padding:3rem 1rem;">
                <div style="width:60px;height:60px;border-radius:50%;background:#1e1e26;margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;color:var(--zinc-400);">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
                <h4 style="font-size:1.1rem;font-weight:800;color:#fff;margin-bottom:0.35rem;">আপনার কার্ট খালি!</h4>
                <p class="text-muted text-sm" style="margin-bottom:1.5rem;">মেনু থেকে পছন্দের সুস্বাদু চুইঝালের খাবার যোগ করুন।</p>
                <a href="/menu" onclick="closeCartSheet()" class="food-app-add-btn" style="padding:8px 20px;font-size:0.9rem;">মেনু দেখুন →</a>
            </div>

            <!-- Populated Cart State -->
            <div id="sheetCartContent">
                <!-- Cart Item List -->
                <div id="sheetCartList"></div>

                <!-- Fast Delivery Checkout Form -->
                <div class="sheet-form-card">
                    <h4 class="sheet-form-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color:var(--secondary);"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                        <span>হোম ডেলিভারির তথ্য</span>
                    </h4>

                    <div class="sheet-input-group">
                        <label class="sheet-label">আপনার নাম *</label>
                        <input type="text" id="sheetName" class="sheet-input" placeholder="যেমন: তানজিম আহমেদ">
                    </div>

                    <div class="sheet-input-group">
                        <label class="sheet-label">মোবাইল নম্বর *</label>
                        <input type="tel" id="sheetPhone" class="sheet-input" placeholder="01XXXXXXXXX">
                    </div>

                    <div class="sheet-input-group">
                        <label class="sheet-label">ডেলিভারি এরিয়া *</label>
                        <select id="sheetArea" class="sheet-select">
                            <option value="সাতক্ষীরা সদর">সাতক্ষীরা সদর</option>
                            <option value="পলাশপোল">পলাশপোল</option>
                            <option value="কাটিয়া">কাটিয়া</option>
                            <option value="রসুলপুর">রসুলপুর</option>
                            <option value="ইটাগাছা">ইটাগাছা</option>
                            <option value="আশাশুনি মোড়">আশাশুনি মোড়</option>
                            <option value="তুফান মোড়">তুফান মোড়</option>
                            <option value="জজ কোর্ট / উকিলবার এলাকা">জজ কোর্ট / উকিলবার এলাকা</option>
                            <option value="অন্যান্য এলাকা">অন্যান্য এলাকা</option>
                        </select>
                    </div>

                    <div class="sheet-input-group">
                        <label class="sheet-label">বিস্তারিত ঠিকানা *</label>
                        <textarea id="sheetAddress" class="sheet-textarea" rows="2" placeholder="বাড়ি/রোড নং, এলাকা বা ল্যান্ডমার্ক..."></textarea>
                        
                        <button type="button" onclick="getLiveAppLocation('sheetAddress', 'gpsSheetStatus')" class="gps-pill-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>
                            <span>লাইভ GPS লোকেশন বসান</span>
                        </button>
                        <p id="gpsSheetStatus" style="font-size:0.75rem;margin-top:4px;display:none;"></p>
                    </div>

                    <div class="sheet-input-group">
                        <label class="sheet-label">বিশেষ নোট (ঐচ্ছিক)</label>
                        <input type="text" id="sheetNote" class="sheet-input" placeholder="যেমন: ঝাল বেশি, সাথে কাঁচা মরিচ দিবেন">
                    </div>

                    <!-- Payment Mode -->
                    <div style="background:#14141b;border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:0.75rem;display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span style="font-size:1.1rem;">💵</span>
                            <div>
                                <h5 style="font-size:0.85rem;font-weight:800;color:#fff;">ক্যাশ অন ডেলিভারি</h5>
                                <p style="font-size:0.72rem;color:var(--zinc-400);">খাবার পেয়ে মূল্য পরিশোধ করুন</p>
                            </div>
                        </div>
                        <span class="loc-status-chip">নির্বাচিত</span>
                    </div>

                    <p id="sheetOrderError" class="text-primary text-xs font-bold mt-2 hidden"></p>
                </div>

                <!-- Bill Details -->
                <div class="sheet-bill-box">
                    <div class="sheet-bill-row">
                        <span>খাবারের মোট মূল্য:</span>
                        <span id="sheetSubtotal" class="font-bold text-white">৳০</span>
                    </div>
                    <div class="sheet-bill-row">
                        <span>ডেলিভারি ফি:</span>
                        <span style="color:#4ade80;font-weight:700;">ফ্রি ডেলিভারি</span>
                    </div>
                    <div class="sheet-bill-row total">
                        <span>মোট প্রদেয়:</span>
                        <span id="sheetTotal" style="color:var(--secondary);">৳০</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sheet-footer">
            <button type="button" class="btn-confirm-app" id="sheetOrderBtn" onclick="placeSheetOrder()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>অর্ডার কনফার্ম করুন (ক্যাশ অন ডেলিভারি)</span>
            </button>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════
         5. FIXED APP BOTTOM NAVIGATION BAR
         ═══════════════════════════════════════════════════════ -->
    <nav class="app-bottom-nav">
        <div class="app-bottom-nav-inner">
            <a href="/" class="bottom-nav-item {{ request()->is('/') ? 'active' : '' }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span class="bottom-nav-label">হোম</span>
            </a>

            <a href="/menu" class="bottom-nav-item {{ request()->is('menu') ? 'active' : '' }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                <span class="bottom-nav-label">মেনু</span>
            </a>

            <button type="button" class="bottom-nav-item" onclick="openCartSheet()">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span class="bottom-nav-label">কার্ট</span>
                <span class="bottom-nav-badge" id="bottomNavCartBadge" style="display:none;">0</span>
            </button>

            <a href="/track" class="bottom-nav-item {{ request()->is('track*') ? 'active' : '' }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span class="bottom-nav-label">ট্র্যাকিং</span>
            </a>

            <a href="/contact" class="bottom-nav-item {{ (request()->is('contact') || request()->is('about')) ? 'active' : '' }}">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <span class="bottom-nav-label">সাপোর্ট</span>
            </a>
        </div>
    </nav>

    <!-- Global App Scripts -->
    <script src="/js/app.js"></script>
    @yield('scripts')
</body>
</html>
