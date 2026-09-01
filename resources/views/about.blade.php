@extends('layouts.app')
@section('title', 'আমাদের গল্প ও ঐতিহ্য — শ্যামনগর নজরুল হোটেল')

@section('content')
<div class="app-container">
    <div style="max-width:600px;margin:0 auto;padding-bottom:2rem;">
        <div class="app-section-header" style="margin-top:0.5rem;">
            <div class="app-section-title-wrap">
                <span class="app-section-icon">🥘</span>
                <h2 class="app-section-title">আমাদের ঐতিহ্য ও গল্প</h2>
            </div>
        </div>

        <div class="restaurant-app-card text-center">
            <img src="/images/owner.jpg" alt="প্রোঃ আল-মামুন" class="chef-avatar-app" style="width:80px;height:80px;margin:0 auto 0.75rem;">
            <h3 style="font-size:1.25rem;font-weight:900;color:#fff;">প্রোঃ আল-মামুন</h3>
            <p class="text-secondary font-bold text-xs mt-1">প্রতিষ্ঠাতা ও প্রধান বাবুর্চি</p>
            <p class="text-muted text-xs mt-1">শ্যামনগর নজরুল হোটেল, সাতক্ষীরা</p>

            <div style="background:#14141b;border:1px solid rgba(255,255,255,0.06);border-radius:14px;padding:1rem;margin-top:1.25rem;text-align:left;">
                <h4 style="font-size:0.95rem;font-weight:800;color:#fff;margin-bottom:0.4rem;">সাতক্ষীরার খাঁটি চুইঝালের স্বাদ</h4>
                <p style="font-size:0.82rem;color:var(--zinc-300);line-height:1.6;">
                    শ্যামনগর নজরুল হোটেল সাতক্ষীরার ভোজনরসিক মানুষদের জন্য একটি নির্ভরযোগ্য খাবারের ঠিকানা। প্রতিদিন বাছাইকৃত তাজা মাংস ও খাঁটি দেশি চুইঝাল দিয়ে প্রস্তুত করা হয় আমাদের প্রতিটি খাবার পদ।
                </p>
            </div>

            <div class="quick-action-pills" style="margin-top:1rem;">
                <a href="/menu" class="btn-app-action" style="background:var(--fire-gradient);border:none;">
                    <span>খাবারের মেনু দেখুন →</span>
                </a>
                <a href="https://wa.me/8801988976269" target="_blank" class="btn-app-action whatsapp">
                    <span>হোয়াটসঅ্যাপে চ্যাট</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
