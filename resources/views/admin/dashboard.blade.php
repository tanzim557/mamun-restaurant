<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>মালিকের কমান্ড সেন্টার — শ্যামনগর নজরুল হোটেল</title>
    <link rel="stylesheet" href="/css/admin-app.css">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#09090d">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="নজরুল হোটেল অ্যাডমিন">
    <link rel="apple-touch-icon" href="/images/logo.jpg">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Executive Top App Bar -->
    <header class="owner-topbar">
        <div class="owner-brand">
            <div class="owner-avatar-crown">👑</div>
            <div class="owner-title-wrap">
                <h1>নজরুল হোটেল</h1>
                <p>ওনার কমান্ড সেন্টার</p>
            </div>
        </div>

        <div class="owner-top-actions">
            <!-- Master Hotel Open/Closed Switch -->
            <button type="button" id="hotelStatusToggleBtn" onclick="toggleHotelStatus()" class="owner-status-pill open">
                <span id="hotelStatusDot" style="width:7px;height:7px;border-radius:50%;background:#22c55e;"></span>
                <span id="hotelStatusText">খোলা</span>
            </button>

            <!-- Sound Alert Toggle -->
            <button type="button" id="soundToggleBtn" onclick="toggleSoundAlert()" class="owner-icon-btn" title="সাউন্ড অ্যালার্ট">
                <span id="soundIcon">🔊</span>
            </button>

            <!-- Refresh Button -->
            <button type="button" onclick="fetchAllData()" class="owner-icon-btn" title="রিফ্রেশ">
                🔄
            </button>

            <!-- Sign Out -->
            <button type="button" onclick="handleLogout()" class="owner-icon-btn" title="লগআউট" style="color:#f87171;">
                🚪
            </button>
        </div>
    </header>

    <!-- Real-time Offline & Auto-Sync Status Banner -->
    <div id="adminOfflineBanner" style="display:none;background:#f59e0b;color:#000;text-align:center;padding:6px 12px;font-size:0.78rem;font-weight:800;letter-spacing:0.3px;position:sticky;top:60px;z-index:99;box-shadow:0 2px 10px rgba(245,158,11,0.25);">
        ⚡ অফলাইন মোড সক্রিয় — হিসাব জমা হচ্ছে, ওয়াইফাই পেলেই স্বয়ংক্রিয়ভাবে সিঙ্ক হবে।
    </div>

    <!-- Main Mobile App Feed Area -->
    <main class="owner-app-container" id="tabContent">
        <!-- JS Renders Luxury Mobile Views Here -->
    </main>

    <!-- Fixed Admin App Bottom Navigation Bar -->
    <nav class="owner-bottom-nav">
        <button class="owner-nav-tab active" onclick="switchTab('overview', this)">
            <span class="owner-nav-icon">👑</span>
            <span class="owner-nav-label">ড্যাশবোর্ড</span>
        </button>
        <button class="owner-nav-tab" onclick="switchTab('orders', this)">
            <span class="owner-nav-icon">🛵</span>
            <span class="owner-nav-label">অর্ডার রাডার</span>
            <span class="owner-nav-badge" id="pendingBadge" style="display:none;">0</span>
        </button>
        <button class="owner-nav-tab" onclick="switchTab('menu', this)">
            <span class="owner-nav-icon">🍲</span>
            <span class="owner-nav-label">মেনু ও স্টক</span>
        </button>
        <button class="owner-nav-tab" onclick="switchTab('employees', this)">
            <span class="owner-nav-icon">👥</span>
            <span class="owner-nav-label">কর্মী খাতা</span>
        </button>
        <button class="owner-nav-tab" onclick="switchTab('ledger', this)">
            <span class="owner-nav-icon">📒</span>
            <span class="owner-nav-label">আয়-ব্যয়</span>
        </button>
    </nav>

    <!-- Mobile Slide-Up Bottom Sheet Modal -->
    <div class="owner-sheet-overlay" id="adminModal" onclick="if(event.target===this) closeModal()">
        <div class="owner-sheet-modal">
            <div class="owner-sheet-drag"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <h3 id="modalTitle" style="font-size:1.15rem;font-weight:900;color:#fff;">সম্পাদনা</h3>
                <button onclick="closeModal()" style="background:none;border:none;color:#94a3b8;font-size:1.4rem;cursor:pointer;">✕</button>
            </div>
            <div id="modalBody"></div>
        </div>
    </div>

    <script src="/js/admin.js"></script>
</body>
</html>
