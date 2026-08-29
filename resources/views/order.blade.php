@extends('layouts.app')
@section('title', 'অনলাইন অর্ডার — মামুন হোটেল')

@section('content')
<section class="hero hero-short" style="margin-top:-64px;">
    <div class="hero-bg" style="background-image:url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1920&q=80');"></div>
    <div class="hero-content">
        <span class="text-secondary font-bold uppercase tracking-wide text-sm" style="display:block;margin-bottom:0.75rem;">🚀 দ্রুত ডেলিভারি</span>
        <h1>অনলাইন অর্ডার</h1>
        <p style="color:rgba(255,255,255,0.7);margin-top:0.5rem;font-size:1.1rem;">আপনার পছন্দের খাবার ঘরে বসেই অর্ডার করুন</p>
    </div>
</section>

<!-- Success View -->
<div id="orderSuccess" class="hidden">
    <div class="success-box" style="max-width:480px;margin:0 auto;background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-xl);margin-top:3rem;margin-bottom:3rem;">
        <div class="success-icon">✅</div>
        <h2>অর্ডার হয়েছে! 🎉</h2>
        <p class="text-muted mb-6">আপনার অর্ডার পাওয়া গেছে। শীঘ্রই আমরা কনফার্ম করব।</p>
        <div style="background:var(--zinc-50);border-radius:var(--radius-sm);padding:1.25rem;text-align:left;margin-bottom:2rem;">
            <p class="text-sm text-muted">অর্ডার ID</p>
            <p id="successOrderId" style="font-family:monospace;font-size:0.75rem;word-break:break-all;"></p>
        </div>
        <button class="btn btn-primary btn-block" onclick="resetOrder()">আরো অর্ডার করুন</button>
    </div>
</div>

<!-- Checkout View -->
<div id="orderCheckout" class="hidden">
    <div class="section" style="padding-top:2rem;">
        <div class="container" style="max-width:560px;">
            <button onclick="showMenuView()" class="text-primary font-bold mb-4" style="font-size:0.95rem;">← মেনুতে ফিরে যাও</button>
            <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-xl);padding:2rem;">
                <h2 style="font-size:1.5rem;font-weight:800;margin-bottom:1.5rem;">অর্ডার নিশ্চিত করুন</h2>
                <div id="checkoutSummary" style="background:var(--zinc-50);border-radius:var(--radius-sm);padding:1.25rem;margin-bottom:1.5rem;"></div>
                <div class="form-group"><label class="form-label">👤 নাম *</label><input type="text" class="form-input" id="cName" placeholder="আপনার পুরো নাম"></div>
                <div class="form-group"><label class="form-label">📞 ফোন নম্বর *</label><input type="tel" class="form-input" id="cPhone" placeholder="01XXXXXXXXX"></div>
                
                <div class="form-group">
                    <label class="form-label">📍 এলাকা নির্বাচন করুন *</label>
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
                        <label class="form-label" style="margin-bottom:0;">📍 বিস্তারিত ঠিকানা *</label>
                        <button type="button" onclick="getLiveLocation()" class="btn btn-sm btn-outline-primary" style="padding:0.25rem 0.75rem;font-size:0.75rem;" id="gpsBtn">
                            🎯 লাইভ লোকেশন নিন (GPS)
                        </button>
                    </div>
                    <input type="text" class="form-input" id="cAddress" placeholder="বাড়ি/দোকান নং, রোড, ল্যান্ডমার্ক...">
                    <p id="gpsStatus" class="text-xs text-muted mt-1" style="display:none;"></p>
                </div>

                <div class="form-group"><label class="form-label">বিশেষ নির্দেশনা (ঐচ্ছিক)</label><input type="text" class="form-input" id="cNote" placeholder="যেমন: ঝাল বেশি, সাথে সালাদ দিবেন..."></div>
                <div id="orderError" class="form-error hidden"></div>
                <button class="btn btn-primary btn-block btn-lg mt-4" id="placeOrderBtn" onclick="placeOrder()">📦 অর্ডার দিন</button>
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

<!-- Floating Cart -->
<div class="floating-cart hidden" id="floatingCart">
    <button onclick="openCartDrawer()">
        <span>🛒</span>
        <span id="cartCount">0</span> আইটেম
        <span style="border-left:1px solid rgba(255,255,255,0.3);padding-left:1rem;" id="cartTotal">৳0</span>
        <span>›</span>
    </button>
</div>

<!-- Cart Drawer -->
<div class="cart-drawer-overlay" id="cartOverlay" onclick="closeCartDrawer()"></div>
<div class="cart-drawer" id="cartDrawer">
    <div class="cart-drawer-header">
        <h3 class="font-bold text-lg flex items-center gap-2">🛒 আপনার কার্ট</h3>
        <button onclick="closeCartDrawer()" style="font-size:1.5rem;color:var(--zinc-400);">&times;</button>
    </div>
    <div class="cart-drawer-body" id="cartBody"></div>
    <div class="cart-drawer-footer" id="cartFooter" style="display:none;">
        <div class="flex justify-between items-center mb-4">
            <span class="font-bold text-lg">মোট</span>
            <span class="font-bold text-xl text-primary" id="cartDrawerTotal">৳0</span>
        </div>
        <button class="btn btn-primary btn-block btn-lg" onclick="goToCheckout()">অর্ডার করতে এগিয়ে যান ›</button>
    </div>
</div>
@endsection

@section('scripts')
<script src="/js/order.js"></script>
@endsection
