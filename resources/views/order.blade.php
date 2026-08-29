@extends('layouts.app')
@section('title', 'অনলাইন খাবার অর্ডার — শ্যামনগর নজরুল হোটেল')

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
    <div class="success-box" style="max-width:520px;margin:0 auto;background:#14141a;border:1px solid rgba(255,255,255,0.1);border-radius:24px;box-shadow:0 30px 60px rgba(0,0,0,0.9);margin-top:3rem;margin-bottom:3rem;padding:2.75rem 2rem;text-align:center;">
        <div class="success-icon" style="width:64px;height:64px;border-radius:50%;background:rgba(34,197,94,0.15);border:2px solid #22c55e;color:#4ade80;margin:0 auto 1.25rem;display:flex;align-items:center;justify-content:center;">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h2 style="font-size:1.85rem;font-weight:900;color:#fff;margin-bottom:0.4rem;">অর্ডার সফল হয়েছে!</h2>
        <p class="text-muted mb-6" style="font-size:0.95rem;">আপনার অর্ডারটি আমাদের কিচেনে পৌঁছে গেছে। খুব শীঘ্রই প্রতিনিধি কল করবেন।</p>
        
        <!-- Short Order ID Card with Copy Button -->
        <div style="background:#1a1a24;border:1px solid rgba(245,158,11,0.3);border-radius:16px;padding:1.25rem;margin-bottom:1.75rem;">
            <p style="font-size:0.78rem;color:#a1a1aa;text-transform:uppercase;letter-spacing:0.05em;font-weight:700;margin-bottom:6px;">আপনার অর্ডার ট্র্যাকিং আইডি</p>
            <div style="display:flex;align-items:center;justify-content:center;gap:10px;">
                <span id="successOrderId" style="font-family:'Outfit',monospace;font-size:1.45rem;color:#f59e0b;font-weight:900;letter-spacing:0.06em;"></span>
                <button type="button" onclick="copyOrderId()" class="btn-copy-id" id="copyIdBtn" title="আইডি কপি করুন">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    <span id="copyBtnText">কপি করুন</span>
                </button>
            </div>
            <p style="font-size:0.75rem;color:#71717a;margin-top:8px;">(এই আইডি অথবা আপনার মোবাইল নম্বর দিয়ে অর্ডার ট্র্যাক করতে পারবেন)</p>
        </div>

        <div style="display:flex;flex-direction:column;gap:0.75rem;">
            <a id="successTrackLink" href="/track" class="btn btn-primary btn-block btn-lg" style="display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>অর্ডার ট্র্যাক করুন</span>
            </a>
            <button class="btn btn-outline-secondary btn-block" onclick="resetOrder()">আরো খাবার অর্ডার করুন</button>
        </div>
    </div>
</div>

<!-- Checkout View -->
<div id="orderCheckout" class="hidden">
    <div class="checkout-page-section">
        <!-- Ambient Glowing Background Spheres -->
        <div class="ambient-glow glow-1" style="top:10%;left:5%;"></div>
        <div class="ambient-glow glow-2" style="bottom:10%;right:5%;"></div>

        <div class="container relative" style="max-width:640px;margin:0 auto;z-index:2;">
            <button onclick="showMenuView()" class="btn-back-menu">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                <span>মেনুতে ফিরে যান</span>
            </button>

            <!-- Stepper Progress Bar -->
            <div class="checkout-stepper">
                <div class="step completed">
                    <span class="step-dot">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                    </span>
                    <span>খাবার পছন্দ</span>
                </div>
                <div class="step-connector active"></div>
                <div class="step current">
                    <span class="step-dot">২</span>
                    <span>ঠিকানা ও কনফার্ম</span>
                </div>
            </div>

            <!-- Main Luxury Checkout Card -->
            <div class="checkout-glass-card">
                <div class="checkout-card-header">
                    <div class="checkout-header-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <div>
                        <h2 class="checkout-title">ডেলিভারি তথ্য ও অর্ডার নিশ্চিতকরণ</h2>
                        <p class="checkout-subtitle">সাতক্ষীরা শহরে দ্রুততম হোম ডেলিভারি</p>
                    </div>
                </div>

                <!-- Order Summary Glass Box -->
                <div id="checkoutSummary" class="checkout-summary-box"></div>

                <!-- Form Fields with Icons -->
                <div class="checkout-form-body">
                    <!-- Customer Name -->
                    <div class="checkout-form-group">
                        <label class="checkout-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span>আপনার নাম *</span>
                        </label>
                        <input type="text" class="checkout-input" id="cName" placeholder="যেমন: তানজিম আহমেদ">
                    </div>

                    <!-- Customer Phone -->
                    <div class="checkout-form-group">
                        <label class="checkout-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>মোবাইল নম্বর *</span>
                        </label>
                        <input type="tel" class="checkout-input" id="cPhone" placeholder="01XXXXXXXXX">
                    </div>

                    <!-- Delivery Area -->
                    <div class="checkout-form-group">
                        <label class="checkout-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/></svg>
                            <span>ডেলিভারি এরিয়া *</span>
                        </label>
                        <select class="checkout-select" id="cArea">
                            <option value="" disabled selected>আপনার এলাকা নির্বাচন করুন</option>
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

                    <!-- Address & GPS Autofill -->
                    <div class="checkout-form-group">
                        <label class="checkout-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>বিস্তারিত ঠিকানা *</span>
                        </label>
                        <textarea class="checkout-textarea" id="cAddress" rows="2" placeholder="বাড়ি/দোকান নং, রোড নং, ল্যান্ডমার্ক..."></textarea>
                        
                        <!-- High-End GPS Autofill Button -->
                        <button type="button" onclick="getLiveLocation()" class="gps-autofill-btn" id="gpsBtn">
                            <div class="gps-pulse-ring">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg>
                            </div>
                            <span>আমার বর্তমান লাইভ লোকেশন বসান (GPS)</span>
                        </button>
                        <p id="gpsStatus" class="gps-status-pill" style="display:none;"></p>
                    </div>

                    <!-- Special Note -->
                    <div class="checkout-form-group">
                        <label class="checkout-label">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            <span>বিশেষ নির্দেশনা (ঐচ্ছিক)</span>
                        </label>
                        <input type="text" class="checkout-input" id="cNote" placeholder="যেমন: ঝাল বেশি দিবেন, সাথে কাঁচা মরিচ ও সালাদ...">
                    </div>

                    <!-- Payment Method Card -->
                    <div class="payment-method-card">
                        <div class="flex items-center gap-3">
                            <div class="payment-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-white text-sm">ক্যাশ অন ডেলিভারি (Cash on Delivery)</h4>
                                <p class="text-muted text-xs">খাবার হাতে পেয়ে গরম ও তাজা চেক করে মূল্য পরিশোধ করুন।</p>
                            </div>
                        </div>
                        <div class="payment-badge">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>সিলেক্টেড</span>
                        </div>
                    </div>

                    <div id="orderError" class="form-error hidden"></div>

                    <!-- Confirm Order CTA -->
                    <button class="btn-confirm-order" id="placeOrderBtn" onclick="placeOrder()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <span>অর্ডার কনফার্ম করুন</span>
                    </button>

                    <!-- Trust Guarantee -->
                    <div class="checkout-guarantee">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span>অর্ডার সম্পন্ন হওয়ার পর আমাদের প্রতিনিধি আপনাকে কল করে নিশ্চিত করবেন।</span>
                    </div>
                </div>
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
