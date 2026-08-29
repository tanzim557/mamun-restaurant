@extends('layouts.app')
@section('title', 'Contact — মামুন হোটেল')

@section('content')
<section class="hero hero-short" style="margin-top:-64px;">
    <div class="hero-bg" style="background-image:url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1920&q=80');"></div>
    <div class="hero-content"><span class="text-secondary font-bold uppercase tracking-wide text-sm" style="display:block;margin-bottom:0.75rem;">We'd Love to Hear From You</span><h1>Contact Us</h1></div>
</section>
<section class="section"><div class="container"><div class="grid" style="grid-template-columns:2fr 3fr;gap:4rem;">
    <!-- Info -->
    <div>
        <span class="text-primary font-bold uppercase tracking-wide text-sm">Get In Touch</span>
        <h2 style="font-size:2.25rem;margin-top:0.5rem;margin-bottom:0.75rem;">Let's Connect</h2>
        <p class="text-muted mb-8" style="line-height:1.8;">Whether you have a question about our menu, want to plan a special event, or simply want to say hello — we're here for you.</p>
        <div class="flex flex-col gap-4">
            <div class="card" style="box-shadow:var(--shadow);padding:1.25rem;display:flex;gap:1.25rem;align-items:flex-start;">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(245,158,11,0.15);color:#f59e0b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <div><h4 class="font-bold text-lg">Visit Us</h4><p class="text-muted text-sm">উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা</p></div>
            </div>
            <div class="card" style="box-shadow:var(--shadow);padding:1.25rem;display:flex;gap:1.25rem;align-items:flex-start;">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(239,68,68,0.15);color:#ef4444;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div><h4 class="font-bold text-lg">Call Us</h4><p class="text-muted text-sm">০১৯৮৮-৯৭৬২৬৯</p></div>
            </div>
            <div class="card" style="box-shadow:var(--shadow);padding:1.25rem;display:flex;gap:1.25rem;align-items:flex-start;">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(59,130,246,0.15);color:#3b82f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </div>
                <div><h4 class="font-bold text-lg">Email Us</h4><p class="text-muted text-sm">tanzim2713n@gmail.com</p></div>
            </div>
            <div class="card" style="box-shadow:var(--shadow);padding:1.25rem;display:flex;gap:1.25rem;align-items:flex-start;">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(16,185,129,0.15);color:#10b981;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div><h4 class="font-bold text-lg">Opening Hours</h4><p class="text-muted text-sm">Sun - Thu: 5:00 AM - 10:00 PM</p><p class="text-sm" style="color:var(--red);">Friday & Saturday: বন্ধ</p></div>
            </div>
        </div>
    </div>
    <!-- Form -->
    <div style="background:#141418;border:1px solid rgba(255,255,255,0.08);border-radius:var(--radius);box-shadow:var(--shadow-xl);padding:2.5rem;">
        <div id="contactSuccess" class="hidden success-box"><div class="success-icon" style="color:#22c55e;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div><h3 class="font-bold text-2xl mb-2">Message Sent!</h3><p class="text-muted mb-6">Thank you for reaching out. We'll get back to you within 24 hours.</p><button class="btn btn-primary" onclick="document.getElementById('contactSuccess').classList.add('hidden');document.getElementById('contactForm').classList.remove('hidden');">Send Another Message</button></div>
        <form id="contactForm" onsubmit="return submitContact(event)">
            <h3 class="font-bold text-2xl mb-6">Send a Message</h3>
            <div class="grid grid-2 gap-4">
                <div class="form-group"><label class="form-label">Full Name *</label><input type="text" class="form-input" id="ctName" required placeholder="Your name"></div>
                <div class="form-group"><label class="form-label">Email *</label><input type="email" class="form-input" id="ctEmail" required placeholder="your@email.com"></div>
            </div>
            <div class="grid grid-2 gap-4">
                <div class="form-group"><label class="form-label">Phone Number</label><input type="tel" class="form-input" id="ctPhone" placeholder="+880 ..."></div>
                <div class="form-group"><label class="form-label">Subject</label><input type="text" class="form-input" id="ctSubject" placeholder="How can we help?"></div>
            </div>
            <div class="form-group"><label class="form-label">Message *</label><textarea class="form-textarea" id="ctMessage" required placeholder="Write your message here..."></textarea></div>
            <div id="contactError" class="form-error hidden"></div>
            <button type="submit" class="btn btn-primary btn-block btn-lg mt-4" id="contactBtn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                <span>Send Message</span>
            </button>
        </form>
    </div>
</div></div></section>
<section class="map-section">
    <iframe title="Mamun Restaurant Location" src="https://www.openstreetmap.org/export/embed.html?bbox=88.9565%2C22.6585%2C89.1965%2C22.7785&layer=mapnik&marker=22.7185%2C89.0765" loading="lazy"></iframe>
    <div class="map-badge">Mamun Restaurant – Satkhira</div>
</section>
@endsection

@section('scripts')
<script src="/js/contact.js"></script>
@endsection
