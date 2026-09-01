@extends('layouts.app')
@section('title', 'অনলাইন ফুড অর্ডার ও চেকআউট — শ্যামনগর নজরুল হোটেল')

@section('content')
<div class="app-container">
    <!-- Success Screen -->
    <div id="orderSuccess" class="hidden">
        <div style="max-width:480px;margin:2rem auto;background:#14141c;border:1px solid rgba(255,255,255,0.1);border-radius:24px;padding:2.5rem 1.5rem;text-align:center;box-shadow:0 20px 50px rgba(0,0,0,0.8);">
            <div style="width:64px;height:64px;border-radius:50%;background:rgba(34,197,94,0.15);border:2px solid #22c55e;color:#4ade80;margin:0 auto 1.25rem;display:flex;align-items:center;justify-content:center;">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h2 style="font-size:1.6rem;font-weight:900;color:#fff;margin-bottom:0.4rem;">অর্ডার সফল হয়েছে!</h2>
            <p class="text-muted text-sm mb-4">আপনার খাবারের অর্ডারটি কিচেনে পৌঁছে গেছে। খুব শীঘ্রই প্রতিনিধি কল করে কনফার্ম করবেন।</p>
            
            <div style="background:#1a1a24;border:1px solid rgba(245,158,11,0.3);border-radius:16px;padding:1.25rem;margin-bottom:1.5rem;">
                <p style="font-size:0.75rem;color:#a1a1aa;text-transform:uppercase;font-weight:700;margin-bottom:6px;">আপনার ট্র্যাকিং আইডি</p>
                <div style="display:flex;align-items:center;justify-content:center;gap:10px;">
                    <span id="successOrderId" style="font-family:'Outfit',monospace;font-size:1.4rem;color:#f59e0b;font-weight:900;letter-spacing:0.06em;"></span>
                    <button type="button" onclick="copyOrderId()" style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);color:#fff;padding:4px 10px;border-radius:8px;font-size:0.75rem;font-weight:700;display:flex;align-items:center;gap:4px;">
                        <span id="copyBtnText">কপি করুন</span>
                    </button>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                <a id="successTrackLink" href="/track" class="btn-confirm-app" style="text-decoration:none;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>অর্ডার লাইভ ট্র্যাক করুন</span>
                </a>
                <button type="button" class="btn-app-action" onclick="resetOrderView()">আরো খাবার অর্ডার করুন</button>
            </div>
        </div>
    </div>

    <!-- Direct In-App Ordering Page -->
    <div id="orderMainSection">
        <!-- Search & Category Sticky Header -->
        <section class="app-search-section">
            <div class="app-search-box">
                <span class="app-search-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
                <input type="text" id="orderSearchInput" class="app-search-input" placeholder="খাবারের নাম লিখে খুঁজুন..." oninput="filterOrderSearch(this.value)">
                <button type="button" id="orderSearchClear" class="app-search-clear hidden" onclick="clearOrderSearch()">✕</button>
            </div>
        </section>

        <!-- Category Pills -->
        <div class="app-cat-sticky-bar">
            <div class="app-cat-pills" id="orderCatTabs">
                <button class="cat-pill active" onclick="filterOrderCat('all', this)">🔥 সব পদ</button>
            </div>
        </div>

        <!-- Food Grid -->
        <section class="mt-4 mb-4">
            <div id="orderLoading" class="food-app-grid">
                <div class="skeleton" style="height:240px;"></div>
                <div class="skeleton" style="height:240px;"></div>
                <div class="skeleton" style="height:240px;"></div>
                <div class="skeleton" style="height:240px;"></div>
            </div>
            <div class="food-app-grid" id="orderGrid"></div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script src="/js/order.js"></script>
@endsection
