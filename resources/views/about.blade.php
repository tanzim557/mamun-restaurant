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
        <div class="flex gap-4 justify-center mt-4 text-muted" style="flex-wrap:wrap;"><span>📍 উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা</span><span>📞 ০১৯৮৮-৯৭৬২৬৯</span></div>
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
    <div class="text-center"><div class="owner-photo"><img src="/images/owner.jpg" alt="প্রোঃ আল-মামুন"></div><h3 class="mt-4 font-bold text-xl">প্রোঃ আল-মামুন</h3><p class="text-secondary font-bold mt-2">প্রতিষ্ঠাতা ও স্বত্বাধিকারী</p><p class="text-muted text-sm mt-2">📍 সাতক্ষীরা, বাংলাদেশ</p></div>
</div></div></section>
<!-- Stats -->
<section class="section section-dark"><div class="container"><div class="grid grid-4">
    <div class="stat-card animate-fade-up"><div class="stat-icon">🍽️</div><p class="stat-value">50+</p><p class="stat-label">মেনু আইটেম</p></div>
    <div class="stat-card animate-fade-up"><div class="stat-icon">👥</div><p class="stat-value">১০,০০০+</p><p class="stat-label">সন্তুষ্ট কাস্টমার</p></div>
    <div class="stat-card animate-fade-up"><div class="stat-icon">❤️</div><p class="stat-value">১০+</p><p class="stat-label">বছরের অভিজ্ঞতা</p></div>
    <div class="stat-card animate-fade-up"><div class="stat-icon">🏅</div><p class="stat-value">১০০%</p><p class="stat-label">হালাল খাবার</p></div>
</div></div></section>
<!-- Values -->
<section class="section section-alt"><div class="container">
    <div class="section-header"><span class="overline">আমাদের মূলনীতি</span><h2>আমরা যা বিশ্বাস করি</h2><div class="divider"></div></div>
    <div class="grid grid-3">
        <div class="value-card"><div class="value-icon">👨‍🍳</div><h3 class="font-bold text-xl mb-4">খাঁটি রান্না</h3><p class="text-muted">প্রতিটি খাবার তৈরিতে আমরা শুধুমাত্র তাজা ও হালাল উপকরণ ব্যবহার করি।</p></div>
        <div class="value-card"><div class="value-icon">❤️</div><h3 class="font-bold text-xl mb-4">ভালোবাসায় রান্না</h3><p class="text-muted">আমাদের বাবুর্চি প্রতিটি রান্নায় ঢেলে দেন তাদের হৃদয়ের ভালোবাসা।</p></div>
        <div class="value-card"><div class="value-icon">⭐</div><h3 class="font-bold text-xl mb-4">কাস্টমার সন্তুষ্টি</h3><p class="text-muted">আপনার হাসিই আমাদের সফলতা। প্রতিটি অতিথিকে আমরা পরিবারের মতো আপ্যায়ন করি।</p></div>
    </div>
</div></section>
<!-- CTA -->
<section class="section section-primary text-center"><div class="container">
    <h2 style="font-size:2.5rem;margin-bottom:1rem;">আসুন, স্বাদ নিন আমাদের</h2>
    <p style="font-size:1.2rem;opacity:0.9;margin-bottom:2.5rem;max-width:560px;margin-left:auto;margin-right:auto;">মামুন হোটেলের প্রতিটি খাবার একটি অনন্য অভিজ্ঞতা।</p>
    <a href="/order" class="btn" style="background:var(--white);color:var(--primary);font-weight:800;font-size:1.1rem;">এখনই অর্ডার করুন</a>
</div></section>
@endsection
