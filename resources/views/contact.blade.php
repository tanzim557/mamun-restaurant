@extends('layouts.app')
@section('title', 'যোগাযোগ ও সাপোর্ট — শ্যামনগর নজরুল হোটেল')

@section('content')
<div class="app-container">
    <div style="max-width:600px;margin:0 auto;padding-bottom:2rem;">
        <div class="app-section-header" style="margin-top:0.5rem;">
            <div class="app-section-title-wrap">
                <span class="app-section-icon">📞</span>
                <h2 class="app-section-title">যোগাযোগ ও হেল্পডেস্ক</h2>
            </div>
        </div>

        <!-- Direct Contact Cards -->
        <div class="restaurant-app-card">
            <h3 style="font-size:1.1rem;font-weight:900;color:#fff;margin-bottom:0.25rem;">শ্যামনগর নজরুল হোটেল</h3>
            <p class="text-secondary font-bold text-xs mb-3">প্রোঃ আল-মামুন • সাতক্ষীরা</p>
            <p class="text-muted text-xs mb-4">যেকোনো প্রশ্ন, বাল্ক খাবার বুকিং বা স্পেশাল অনুষ্ঠানের খাবারের জন্য সরাসরি আমাদের সাথে যোগাযোগ করুন।</p>

            <div class="quick-action-pills">
                <a href="tel:01988976269" class="btn-app-action">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>০১৯৮৮-৯৭৬২৬৯</span>
                </a>
                <a href="https://wa.me/8801988976269" target="_blank" class="btn-app-action whatsapp">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>হোয়াটসঅ্যাপ চ্যাট</span>
                </a>
            </div>
        </div>

        <!-- Location & Timing Card -->
        <div class="restaurant-app-card">
            <h4 style="font-size:0.95rem;font-weight:800;color:#fff;margin-bottom:0.75rem;display:flex;align-items:center;gap:6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color:var(--secondary);"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>রেস্তোরাঁর অবস্থান ও সময়সূচী</span>
            </h4>
            <div style="background:#14141b;border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:0.85rem;margin-bottom:0.75rem;">
                <p style="font-size:0.82rem;color:#fff;font-weight:700;">📍 উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা</p>
                <p style="font-size:0.75rem;color:var(--zinc-400);margin-top:2px;">সাতক্ষীরা জজ কোর্ট সংলগ্ন প্রধান রাস্তা</p>
            </div>

            <div style="background:#14141b;border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:0.85rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.82rem;margin-bottom:4px;">
                    <span style="color:var(--zinc-300);">রবিবার - বৃহস্পতিবার:</span>
                    <span style="color:#4ade80;font-weight:700;">ভোর ৫:০০ - রাত ১০:০০</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.82rem;color:var(--red);">
                    <span>শুক্রবার ও শনিবার:</span>
                    <span>সাপ্তাহিক বন্ধ</span>
                </div>
            </div>
        </div>

        <!-- Quick Message Form -->
        <div class="restaurant-app-card">
            <h4 style="font-size:0.95rem;font-weight:800;color:#fff;margin-bottom:0.75rem;display:flex;align-items:center;gap:6px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color:var(--primary);"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span>বার্তা বা মতামত পাঠান</span>
            </h4>

            <div id="contactSuccess" class="hidden text-center" style="padding:1.5rem 0;">
                <div style="font-size:2rem;color:#4ade80;margin-bottom:0.5rem;">✓</div>
                <h4 style="font-size:1.05rem;font-weight:800;color:#fff;">আপনার বার্তা পাঠানো হয়েছে!</h4>
                <p class="text-muted text-xs mt-1">আমাদের প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবেন।</p>
            </div>

            <form id="contactForm" onsubmit="return submitContact(event)">
                <div class="sheet-input-group">
                    <label class="sheet-label">আপনার নাম *</label>
                    <input type="text" class="sheet-input" id="ctName" required placeholder="আপনার নাম লিখুন">
                </div>
                <div class="sheet-input-group">
                    <label class="sheet-label">মোবাইল নম্বর *</label>
                    <input type="tel" class="sheet-input" id="ctPhone" required placeholder="01XXXXXXXXX">
                </div>
                <div class="sheet-input-group">
                    <label class="sheet-label">বার্তা / খাবারের কোনো বিশেষ চাহিদা *</label>
                    <textarea class="sheet-textarea" id="ctMessage" rows="3" required placeholder="আপনার বার্তা লিখুন..."></textarea>
                </div>
                <button type="submit" class="btn-confirm-app mt-2" id="contactBtn">
                    <span>বার্তা পাঠান</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="/js/contact.js"></script>
@endsection
