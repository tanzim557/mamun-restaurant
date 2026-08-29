@extends('layouts.app')
@section('title', 'অনলাইন অর্ডার — মামুন হোটেল')

@section('content')
<section class="hero hero-short" style="margin-top:-64px;">
    <div class="hero-bg" style="background-image:url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1920&q=80');"></div>
    <div class="hero-content">
        <span class="text-secondary font-bold uppercase tracking-wide text-sm" style="display:inline-block;margin-bottom:0.75rem;background:rgba(245,158,11,0.15);padding:4px 14px;border-radius:9999px;border:1px solid rgba(245,158,11,0.3);">দ্রুত ডেলিভারি</span>
        <h1>অনলাইন অর্ডার</h1>
        <p style="color:rgba(255,255,255,0.7);margin-top:0.5rem;font-size:1.1rem;">আপনার পছন্দের খাবার ঘরে বসেই অর্ডার করুন</p>
    </div>
</section>

<!-- Success View -->
<div id="orderSuccess" class="hidden">
    <div class="success-box" style="max-width:480px;margin:0 auto;background:#141418;border:1px solid rgba(255,255,255,0.08);border-radius:var(--radius);box-shadow:var(--shadow-xl);margin-top:3rem;margin-bottom:3rem;padding:2.5rem;text-align:center;">
        <div class="success-icon" style="color:#22c55e;margin-bottom:1rem;display:flex;justify-content:center;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h2 style="font-size:1.75rem;font-weight:800;margin-bottom:0.5rem;">অর্ডার সফল হয়েছে!</h2>
        <p class="text-muted mb-6">আপনার অর্ডার পাওয়া গেছে। শীঘ্রই আমাদের প্রতিনিধি কনফার্ম করবেন।</p>
        <div style="background:#1e1e24;border:1px solid rgba(255,255,255,0.05);border-radius:var(--radius-sm);padding:1.25rem;text-align:left;margin-bottom:2rem;">
            <p class="text-sm text-muted">অর্ডার ID</p>
            <p id="successOrderId" style="font-family:monospace;font-size:0.85rem;color:#60a5fa;word-break:break-all;font-weight:700;margin-top:4px;"></p>
        </div>
        <button class="btn btn-primary btn-block" onclick="resetOrder()">আরো অর্ডার করুন</button>
    </div>
</div>

<!-- Checkout View -->
<div id="orderCheckout" class="hidden">
    <div class="section" style="padding-top:2rem;">
        <div class="container" style="max-width:580px;margin:0 auto;">
            <button onclick="showMenuView()" class="text-primary font-bold mb-4" style="font-size:0.95rem;display:flex;align-items:center;gap:6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                <span>মেনুতে ফিরে যান</span>
            </button>
            <div style="background:#141418;border:1px solid rgba(255,255,255,0.08);border-radius:var(--radius);box-shadow:var(--shadow-xl);padding:2.5rem;">
                <h2 style="font-size:1.6rem;font-weight:800;margin-bottom:1.5rem;text-align:center;">অর্ডার নিশ্চিত করুন</h2>
                <div id="checkoutSummary" style="background:#1e1e24;border:1px solid rgba(255,255,255,0.05);border-radius:var(--radius-sm);padding:1.25rem;margin-bottom:1.5rem;"></div>
                <div class="form-group"><label class="form-label">নাম *</label><input type="text" class="form-input" id="cName" placeholder="আপনার পুরো নাম"></div>
                <div class="form-group"><label class="form-label">ফোন নম্বর *</label><input type="tel" class="form-input" id="cPhone" placeholder="01XXXXXXXXX"></div>
                
                <div class="form-group">
                    <label class="form-label">এলাকা নির্বাচন করুন *</label>
                    <select class="form-select" id="cArea">
                        <option value="" disabled selected>এলাকা নির্বাচন করুন</option>
                        <option>সাতক্ষীরা সদর</option>
                        <option>পলাশপোল</option>
                        <option>কাটিয়া</option>
                        <option>রসুলপুর</option>
                        <option>ইটাগাছা</option>
                        <option>আশাশুনি মোড়</option>
                        <option>তুফান মোড়</option>
                        <option>জজ কোর্ট / উকিলবার এলাকা</option>
                        <option>অন্যান্য এলাকা</option>
                    </select>
                </div>

                <div class="form-group">
                    <div class="flex justify-between items-center mb-1">
                        <label class="form-label" style="margin-bottom:0;">বিস্তারিত ঠিকানা *</label>
                        <button type="button" onclick="getLiveLocation()" class="btn btn-sm btn-outline-primary" style="padding:0.35rem 0.85rem;font-size:0.78rem;display:flex;align-items:center;gap:6px;" id="gpsBtn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>
                            <span>লাইভ লোকেশন নিন (GPS)</span>
                        </button>
                    </div>
                    <input type="text" class="form-input" id="cAddress" placeholder="বাড়ি/দোকান নং, রোড, ল্যান্ডমার্ক...">
                    <p id="gpsStatus" class="text-xs text-muted mt-1" style="display:none;"></p>
                </div>

                <div class="form-group"><label class="form-label">বিশেষ নির্দেশনা (ঐচ্ছিক)</label><input type="text" class="form-input" id="cNote" placeholder="যেমন: ঝাল বেশি, সাথে সালাদ দিবেন..."></div>
                <div id="orderError" class="form-error hidden"></div>
                <button class="btn btn-primary btn-block btn-lg mt-4" id="placeOrderBtn" onclick="placeOrder()">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>অর্ডার কনফার্ম করুন</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Menu View -->
<div id="orderMenu">
    <div class="filter-bar">
        <div class="container">
            <div class="filter-tabs" id="orderCatTabs"></div>
        </div>
    </div>
    <section class="section" style="padding-top:2rem;padding-bottom:8rem;">
        <div class="container">
            <div id="orderLoading"><div class="grid grid-4"><div class="skeleton" style="height:320px;"></div><div class="skeleton" style="height:320px;"></div><div class="skeleton" style="height:320px;"></div><div class="skeleton" style="height:320px;"></div></div></div>
            <div class="grid grid-4" id="orderGrid"></div>
        </div>
    </section>
</div>

<!-- Floating Cart Button -->
<div class="floating-cart hidden" id="floatingCart">
    <button onclick="openCartDrawer()" class="floating-cart-btn">
        <div class="floating-cart-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            <span class="floating-cart-badge" id="cartCount">0</span>
        </div>
        <div class="floating-cart-info">
            <span class="floating-cart-text">কার্ট দেখুন</span>
            <span class="floating-cart-total" id="cartTotal">৳০</span>
        </div>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
</div>

<!-- Cart Drawer -->
<div class="cart-drawer-overlay" id="cartOverlay" onclick="closeCartDrawer()"></div>
<div class="cart-drawer" id="cartDrawer">
    <div class="cart-drawer-header">
        <div class="flex items-center gap-3">
            <div class="cart-header-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <div>
                <h3 class="cart-header-title">আপনার কার্ট</h3>
                <span class="cart-header-count" id="cartHeaderCount">০ টি আইটেম</span>
            </div>
        </div>
        <button onclick="closeCartDrawer()" class="cart-close-btn" aria-label="Close cart">&times;</button>
    </div>
    <div class="cart-drawer-body" id="cartBody"></div>
    <div class="cart-drawer-footer" id="cartFooter" style="display:none;">
        <div class="cart-footer-summary">
            <div class="flex justify-between items-center mb-1">
                <span style="color:#a1a1aa;font-size:0.9rem;">খাবারের মোট মূল্য</span>
                <span class="font-bold text-white text-base" id="cartDrawerSubtotal"></span>
            </div>
            <div class="flex justify-between items-center mb-3">
                <span style="color:#a1a1aa;font-size:0.9rem;">ডেলিভারি এরিয়া</span>
                <span style="color:#4ade80;font-size:0.85rem;font-weight:700;">সাতক্ষীরা সদর</span>
            </div>
            <div class="cart-footer-total-row">
                <span class="cart-footer-total-label">সর্বমোট পরিশোধ:</span>
                <span class="cart-footer-total-val" id="cartDrawerTotal">৳0</span>
            </div>
        </div>
        <button class="btn-checkout-drawer" onclick="goToCheckout()">
            <span>অর্ডার নিশ্চিত করতে এগিয়ে যান</span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script src="/js/order.js"></script>
@endsection
