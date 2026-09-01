@extends('layouts.app')
@section('title', 'মোবাইল অ্যাপ ডাউনলোড — শ্যামনগর নজরুল হোটেল')

@section('content')
<div class="app-container" style="padding-top:2rem;padding-bottom:4rem;max-width:960px;">
    <!-- Page Header -->
    <div style="text-align:center;margin-bottom:2.5rem;">
        <span class="promo-badge" style="background:linear-gradient(135deg,#f59e0b,#ef4444);color:#fff;padding:4px 14px;font-size:0.85rem;border-radius:9999px;">
            📱 অ্যান্ড্রয়েড মোবাইল অ্যাপ
        </span>
        <h1 style="font-size:2.2rem;font-weight:900;color:#fff;margin-top:0.75rem;line-height:1.2;">
            শ্যামনগর নজরুল হোটেল অ্যাপ ডাউনলোড
        </h1>
        <p style="color:#a1a1aa;font-size:0.95rem;max-width:560px;margin:0.5rem auto 0;line-height:1.6;">
            নিচের বাটনগুলোতে চাপ দিয়ে সরাসরি আপনার অ্যান্ড্রয়েড ফোনে অ্যাপ দুটি ডাউনলোড ও ইনস্টল করে নিন।
        </p>
    </div>

    <!-- 2 APK Download Cards Grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:1.5rem;margin-bottom:3rem;">
        <!-- 1. Customer App Card -->
        <div style="background:linear-gradient(180deg, rgba(28,28,40,0.9) 0%, rgba(16,16,24,0.98) 100%);border:1px solid rgba(245,158,11,0.25);border-radius:24px;padding:2rem 1.5rem;display:flex;flex-direction:column;align-items:center;text-align:center;box-shadow:0 12px 32px rgba(0,0,0,0.6);position:relative;">
            <div style="position:absolute;top:16px;right:16px;background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);color:#4ade80;font-size:0.72rem;font-weight:800;padding:3px 10px;border-radius:9999px;">
                কাস্টমার ভার্সন v2.1
            </div>

            <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg, #ef4444, #f97316);display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;box-shadow:0 8px 20px rgba(239,68,68,0.35);">
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
            </div>

            <h3 style="font-size:1.35rem;font-weight:900;color:#fff;margin-bottom:0.4rem;">
                কাস্টমার ফুড ডেলিভারি অ্যাপ
            </h3>
            <p style="color:#94a3b8;font-size:0.85rem;line-height:1.5;margin-bottom:1.5rem;">
                খাবারের ছবি দেখে সহজে অর্ডার করুন, লাইভ স্ট্যাটাস ট্র্যাক করুন এবং অফলাইনেও নিরাপদে খাবার ব্রাউজ করুন।
            </p>

            <a href="/download/customer" class="btn-hero-order-now" style="width:100%;justify-content:center;padding:12px 20px;font-size:1rem;margin-bottom:1rem;text-decoration:none;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>ডাউনলোড কাস্টমার APK</span>
            </a>

            <div style="font-size:0.75rem;color:#71717a;">
                সাইজ: ~৫.২ MB • অ্যান্ড্রয়েড ৫.০+ সাপোর্টেড
            </div>
        </div>

        <!-- 2. Admin App Card -->
        <div style="background:linear-gradient(180deg, rgba(28,28,40,0.9) 0%, rgba(16,16,24,0.98) 100%);border:1px solid rgba(234,179,8,0.3);border-radius:24px;padding:2rem 1.5rem;display:flex;flex-direction:column;align-items:center;text-align:center;box-shadow:0 12px 32px rgba(0,0,0,0.6);position:relative;">
            <div style="position:absolute;top:16px;right:16px;background:rgba(234,179,8,0.15);border:1px solid rgba(234,179,8,0.3);color:#fbbf24;font-size:0.72rem;font-weight:800;padding:3px 10px;border-radius:9999px;">
                মালিক ও ম্যানেজার v2.1
            </div>

            <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg, #f59e0b, #d97706);display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;box-shadow:0 8px 20px rgba(245,158,11,0.35);">
                <span style="font-size:2rem;">👑</span>
            </div>

            <h3 style="font-size:1.35rem;font-weight:900;color:#fff;margin-bottom:0.4rem;">
                অ্যাডমিন ও ওনার কন্ট্রোল অ্যাপ
            </h3>
            <p style="color:#94a3b8;font-size:0.85rem;line-height:1.5;margin-bottom:1.5rem;">
                নতুন অর্ডারের সাউন্ড অ্যালার্ট, মেনু ও দাম কন্ট্রোল, আয়-ব্যয় লেজার খাতা, বাকি খাতা ও কর্মী খাতা।
            </p>

            <a href="/download/admin" class="btn-hero-order-now" style="width:100%;justify-content:center;padding:12px 20px;font-size:1rem;margin-bottom:1rem;background:linear-gradient(135deg,#f59e0b,#d97706);color:#000;font-weight:900;text-decoration:none;box-shadow:0 6px 20px rgba(245,158,11,0.4);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span>ডাউনলোড অ্যাডমিন APK</span>
            </a>

            <div style="font-size:0.75rem;color:#71717a;">
                সাইজ: ~৫.২ MB • আল-মামুন ও হোটেল ম্যানেজমেন্ট
            </div>
        </div>
    </div>

    <!-- Step-by-Step Installation Guide -->
    <div style="background:rgba(20,20,30,0.8);border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:2rem;margin-top:2rem;">
        <h3 style="color:#fff;font-size:1.2rem;font-weight:900;margin-bottom:1.25rem;display:flex;align-items:center;gap:8px;">
            <span>💡</span> <span>ফোনে ইনস্টল করার সহজ ৩টি ধাপ:</span>
        </h3>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:1.25rem;">
            <div style="display:flex;gap:12px;">
                <div style="width:32px;height:32px;border-radius:50%;background:rgba(239,68,68,0.2);color:#ef4444;font-weight:900;display:flex;align-items:center;justify-content:center;flex-shrink:0;">১</div>
                <div>
                    <h4 style="color:#fff;font-size:0.95rem;font-weight:800;margin-bottom:2px;">ডাউনলোড করুন</h4>
                    <p style="color:#94a3b8;font-size:0.82rem;line-height:1.4;">উপরের ডাউনলোড বাটনে চাপ দিন। ব্রাউজারে "Download anyway" এলে চাপ দিন।</p>
                </div>
            </div>

            <div style="display:flex;gap:12px;">
                <div style="width:32px;height:32px;border-radius:50%;background:rgba(245,158,11,0.2);color:#fbbf24;font-weight:900;display:flex;align-items:center;justify-content:center;flex-shrink:0;">২</div>
                <div>
                    <h4 style="color:#fff;font-size:0.95rem;font-weight:800;margin-bottom:2px;">ফাইল ওপেন করুন</h4>
                    <p style="color:#94a3b8;font-size:0.82rem;line-height:1.4;">ডাউনলোড শেষ হলে নোটিফিকেশন বার থেকে APK ফাইলে চাপ দিন বা ফাইল ম্যানেজারের Download ফোল্ডারে যান।</p>
                </div>
            </div>

            <div style="display:flex;gap:12px;">
                <div style="width:32px;height:32px;border-radius:50%;background:rgba(34,197,94,0.2);color:#4ade80;font-weight:900;display:flex;align-items:center;justify-content:center;flex-shrink:0;">৩</div>
                <div>
                    <h4 style="color:#fff;font-size:0.95rem;font-weight:800;margin-bottom:2px;">ইনস্টল সম্পন্ন</h4>
                    <p style="color:#94a3b8;font-size:0.82rem;line-height:1.4;">"Install" এ চাপ দিন। ইনস্টল সম্পন্ন হলে সরাসরি অ্যাপ ওপেন করে ব্যবহার করুন!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
