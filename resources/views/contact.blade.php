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
            <div class="card" style="box-shadow:var(--shadow);padding:1.25rem;display:flex;gap:1.25rem;align-items:flex-start;"><div style="width:48px;height:48px;border-radius:50%;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.25rem;">📍</div><div><h4 class="font-bold text-lg">Visit Us</h4><p class="text-muted text-sm">Satkhira, Bangladesh</p></div></div>
            <div class="card" style="box-shadow:var(--shadow);padding:1.25rem;display:flex;gap:1.25rem;align-items:flex-start;"><div style="width:48px;height:48px;border-radius:50%;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.25rem;">📞</div><div><h4 class="font-bold text-lg">Call Us</h4><p class="text-muted text-sm">01988976269</p></div></div>
            <div class="card" style="box-shadow:var(--shadow);padding:1.25rem;display:flex;gap:1.25rem;align-items:flex-start;"><div style="width:48px;height:48px;border-radius:50%;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.25rem;">✉️</div><div><h4 class="font-bold text-lg">Email Us</h4><p class="text-muted text-sm">tanzim2713n@gmail.com</p></div></div>
            <div class="card" style="box-shadow:var(--shadow);padding:1.25rem;display:flex;gap:1.25rem;align-items:flex-start;"><div style="width:48px;height:48px;border-radius:50%;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.25rem;">⏰</div><div><h4 class="font-bold text-lg">Opening Hours</h4><p class="text-muted text-sm">Sun - Thu: 5:00 AM - 10:00 PM</p><p class="text-sm" style="color:var(--red);">Friday & Saturday: বন্ধ</p></div></div>
        </div>
    </div>
    <!-- Form -->
    <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-xl);padding:2.5rem;">
        <div id="contactSuccess" class="hidden success-box"><div class="success-icon">✅</div><h3 class="font-bold text-2xl mb-2">Message Sent!</h3><p class="text-muted mb-6">Thank you for reaching out. We'll get back to you within 24 hours.</p><button class="btn btn-primary" onclick="document.getElementById('contactSuccess').classList.add('hidden');document.getElementById('contactForm').classList.remove('hidden');">Send Another Message</button></div>
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
            <button type="submit" class="btn btn-primary btn-block btn-lg mt-4" id="contactBtn">✉️ Send Message</button>
        </form>
    </div>
</div></div></section>
<section class="map-section">
    <iframe title="Mamun Restaurant Location" src="https://www.openstreetmap.org/export/embed.html?bbox=88.9565%2C22.6585%2C89.1965%2C22.7785&layer=mapnik&marker=22.7185%2C89.0765" loading="lazy"></iframe>
    <div class="map-badge">📍 Mamun Restaurant – Satkhira</div>
</section>
@endsection

@section('scripts')
<script src="/js/contact.js"></script>
@endsection
