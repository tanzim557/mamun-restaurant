@extends('layouts.app')
@section('title', 'আমাদের সম্পর্কে — মামুন হোটেল')

@section('content')
<section class="hero hero-mid" style="margin-top:-64px;">
    <div class="hero-bg" style="background-image:url('https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?auto=format&fit=crop&w=1920&q=80');"></div>
    <div class="hero-content"><span class="text-secondary font-bold uppercase tracking-wide text-sm" style="display:block;margin-bottom:1rem;">আমাদের সম্পর্কে</span><h1>আমাদের গল্প</h1></div>
</section>
<!-- Banner -->
<section class="section"><div class="container text-center">
    <div style="max-width:700px;margin:0 auto;border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-xl);"><img src="/images/logo.jpg" alt="শ্যামনগর নজরুল হোটেল" style="width:100%;"></div>
    <div style="margin-top:2.5rem;max-width:700px;margin-left:auto;margin-right:auto;">
        <h2 style="font-size:2rem;">শ্যামনগর নজরুল হোটেল</h2>
        <p class="text-secondary font-bold text-lg mt-2">প্রোঃ আল-মামুন</p>
        <p class="text-muted text-lg mt-4" style="line-height:1.8;">এখানে ভাত, মাছ, মুরগী, চুই ঝালের গরুর গোশত, স্পেশাল চুই ঝালের হাঁসের গোশত সুন্দর পরিবেশে খাওয়ার সুব্যবস্থা আছে।</p>
        <p class="text-primary font-bold mt-4">বিঃদ্রঃ বিভিন্ন অনুষ্ঠানে খাবার অর্ডার নেওয়া হয়</p>
        <div class="flex gap-4 justify-center mt-4 text-muted" style="flex-wrap:wrap;">
            <span style="display:flex;align-items:center;gap:6px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--secondary);"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা</span>
            <span style="display:flex;align-items:center;gap:6px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--primary);"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> ০১৯৮৮-৯৭৬২৬৯</span>
        </div>
    </div>
</div></section>
<!-- Story -->
<section class="section section-alt"><div class="container"><div class="grid grid-2" style="align-items:center;gap:4rem;">
    <div>
        <span class="text-primary font-bold uppercase tracking-wide text-sm">প্রতিষ্ঠাকাল ২০১৪</span>
        <h2 style="font-size:2.5rem;margin-top:0.5rem;margin-bottom:2rem;line-height:1.3;">ভালোবাসার রান্না,<br>আপনার জন্য</h2>
        <p class="text-muted text-lg" style="line-height:1.8;margin-bottom:1.5rem;">মামুন হোটেল সাতক্ষীরার একটি বিশ্বস্ত খাবারের ঘর। প্রোঃ আল-মামুনের হাত ধরে শুরু হওয়া এই হোটেলটি আজ স্থানীয় মানুষের হৃদয়ে জায়গা করে নিয়েছে।</p>
        <p class="text-muted text-lg" style="line-height:1.8;margin-bottom:2rem;">চুই ঝালের গরু ও হাঁসের গোশত আমাদের বিশেষত্ব — যা খেলে আপনি বারবার ফিরে আসবেন।</p>
        <a href="/menu" class="btn btn-primary">মেনু দেখুন</a>
    </div>
    <div class="text-center"><div class="owner-photo"><img src="/images/owner.jpg" alt="প্রোঃ আল-মামুন"></div><h3 class="mt-4 font-bold text-xl">প্রোঃ আল-মামুন</h3><p class="text-secondary font-bold mt-2">প্রতিষ্ঠাতা ও স্বত্বাধিকারী</p><p class="text-muted text-sm mt-2" style="display:flex;align-items:center;justify-content:center;gap:6px;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> সাতক্ষীরা, বাংলাদেশ</p></div>
</div></div></section>
<!-- Stats -->
<section class="section section-dark"><div class="container"><div class="grid grid-4">
    <div class="stat-card animate-fade-up">
        <div class="stat-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg></div>
        <p class="stat-value">50+</p><p class="stat-label">মেনু আইটেম</p>
    </div>
    <div class="stat-card animate-fade-up">
        <div class="stat-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <p class="stat-value">১০,০০০+</p><p class="stat-label">সন্তুষ্ট কাস্টমার</p>
    </div>
    <div class="stat-card animate-fade-up">
        <div class="stat-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
        <p class="stat-value">১০+</p><p class="stat-label">বছরের অভিজ্ঞতা</p>
    </div>
    <div class="stat-card animate-fade-up">
        <div class="stat-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <p class="stat-value">১০০%</p><p class="stat-label">হালাল খাবার</p>
    </div>
</div></div></section>
<!-- Values -->
<section class="section section-alt"><div class="container">
    <div class="section-header"><span class="overline">আমাদের মূলনীতি</span><h2>আমরা যা বিশ্বাস করি</h2><div class="divider"></div></div>
    <div class="grid grid-3">
        <div class="card" style="padding:2rem;">
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(239,68,68,0.15);color:#ef4444;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/></svg>
            </div>
            <h3 class="font-bold text-xl mb-3">খাঁটি রান্না</h3>
            <p class="text-muted">প্রতিটি খাবার তৈরিতে আমরা শুধুমাত্র তাজা ও হালাল উপকরণ ব্যবহার করি।</p>
        </div>
        <div class="card" style="padding:2rem;">
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(245,158,11,0.15);color:#f59e0b;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <h3 class="font-bold text-xl mb-3">ভালোবাসায় রান্না</h3>
            <p class="text-muted">আমাদের বাবুর্চি প্রতিটি রান্নায় ঢেলে দেন তাদের হৃদয়ের ভালোবাসা।</p>
        </div>
        <div class="card" style="padding:2rem;">
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(59,130,246,0.15);color:#3b82f6;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <h3 class="font-bold text-xl mb-3">কাস্টমার সন্তুষ্টি</h3>
            <p class="text-muted">আপনার হাসিই আমাদের সফলতা। প্রতিটি অতিথিকে আমরা পরিবারের মতো আপ্যায়ন করি।</p>
        </div>
    </div>
</div></section>
<!-- CTA -->
<section class="section section-primary text-center"><div class="container">
    <h2 style="font-size:2.5rem;margin-bottom:1rem;">আসুন, স্বাদ নিন আমাদের</h2>
    <p style="font-size:1.2rem;opacity:0.9;margin-bottom:2.5rem;max-width:560px;margin-left:auto;margin-right:auto;">মামুন হোটেলের প্রতিটি খাবার একটি অনন্য অভিজ্ঞতা।</p>
    <a href="/order" class="btn" style="background:var(--white);color:var(--primary);font-weight:800;font-size:1.1rem;">এখনই অর্ডার করুন</a>
</div></section>
@endsection
