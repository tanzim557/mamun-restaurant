@extends('layouts.app')
@section('title', 'মামুন হোটেল — শ্যামনগর নজরুল হোটেল')

@section('content')
<!-- Hero -->
<section class="hero" style="margin-top:-64px;">
    <div class="hero-bg" style="background-image:url('/images/logo.jpg');"></div>
    <div class="hero-content">
        <h1>শ্যামনগর নজরুল হোটেল</h1>
        <p class="subtitle">প্রোঃ আল-মামুন</p>
        <div class="desc">
            <p>এখানে ভাত, মাছ, মুরগী, চুই ঝালের গরুর গোশত, স্পেশাল চুই ঝালের হাঁসের গোশত সুন্দর পরিবেশে খাওয়ার সুব্যবস্থা আছে।</p>
            <p style="color:var(--primary);font-weight:600;margin-top:0.5rem;">বিঃদ্রঃ বিভিন্ন অনুষ্ঠানে খাবার অর্ডার নেওয়া হয়</p>
            <p style="margin-top:0.75rem;font-size:0.95rem;opacity:0.85;">📍 উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা &nbsp; 📞 ০১৯৮৮-৯৭৬২৬৯</p>
        </div>
        <div class="flex gap-4 justify-center" style="flex-wrap:wrap;">
            <a href="/order" class="btn btn-primary btn-lg">🛒 Order Now</a>
            <a href="/menu" class="btn btn-outline btn-lg">View Menu</a>
        </div>
    </div>
</section>

<!-- Featured Dishes -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="overline">Culinary Masterpieces</span>
            <h2>Featured Dishes</h2>
            <div class="divider"></div>
        </div>
        <div class="grid grid-3" id="featuredDishes">
            <!-- JS will populate -->
        </div>
        <div class="text-center mt-8">
            <a href="/menu" class="text-primary font-bold" style="font-size:1rem;">View Full Menu →</a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section section-alt">
    <div class="container">
        <div class="grid grid-2" style="align-items:center;gap:4rem;">
            <div class="text-center animate-fade">
                <div class="owner-photo">
                    <img src="/images/owner.jpg" alt="মামুন হোটেল মালিক">
                </div>
                <h3 style="margin-top:1.5rem;font-size:1.5rem;">মামুন হোটেল</h3>
                <p class="text-secondary font-bold tracking-wide mt-2">প্রতিষ্ঠাতা ও স্বত্বাধিকারী</p>
                <p class="text-muted text-sm mt-2">Satkhira, Bangladesh</p>
            </div>
            <div>
                <span class="text-secondary font-bold uppercase tracking-wide text-sm">Our Philosophy</span>
                <h2 style="font-size:2.25rem;margin-top:0.5rem;margin-bottom:1.5rem;">Artistry on Every Plate</h2>
                <p class="text-muted text-lg" style="margin-bottom:2rem;line-height:1.8;">
                    At Mamun Restaurant, we believe dining is more than just eating—it is an experience. Our master chefs combine traditional techniques with modern innovation to create dishes that delight all your senses.
                </p>
                <div class="flex flex-col gap-4">
                    <div class="feature-item"><div class="feature-icon">👨‍🍳</div><div><h4 class="font-bold text-lg">Master Chefs</h4><p class="text-muted">Expertly trained culinary professionals with years of experience.</p></div></div>
                    <div class="feature-item"><div class="feature-icon">⭐</div><div><h4 class="font-bold text-lg">Premium Ingredients</h4><p class="text-muted">We source only the freshest, highest quality local and imported ingredients.</p></div></div>
                    <div class="feature-item"><div class="feature-icon">⏰</div><div><h4 class="font-bold text-lg">Impeccable Service</h4><p class="text-muted">Dedicated staff ensuring your dining experience is flawless from start to finish.</p></div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="section section-dark">
    <div class="container">
        <div class="grid grid-2" style="gap:2rem;">
            <div class="glass-card animate-fade-up">
                <div style="font-size:2.5rem;margin-bottom:1rem;">⏰</div>
                <h3 style="font-size:1.75rem;font-weight:800;margin-bottom:1.5rem;">Opening Hours</h3>
                <ul style="list-style:none;font-size:1.1rem;color:var(--zinc-300);">
                    <li style="display:flex;justify-content:space-between;border-bottom:1px solid var(--zinc-700);padding-bottom:0.75rem;margin-bottom:0.75rem;"><span>Sun - Thu</span><span>5:00 AM - 10:00 PM</span></li>
                    <li style="display:flex;justify-content:space-between;border-bottom:1px solid var(--zinc-700);padding-bottom:0.75rem;margin-bottom:0.75rem;color:var(--red);"><span>Friday</span><span>বন্ধ</span></li>
                    <li style="display:flex;justify-content:space-between;color:var(--red);"><span>Saturday</span><span>বন্ধ</span></li>
                </ul>
            </div>
            <div class="glass-card animate-fade-up">
                <div style="font-size:2.5rem;margin-bottom:1rem;">📍</div>
                <h3 style="font-size:1.75rem;font-weight:800;margin-bottom:1.5rem;">Find Us</h3>
                <p style="font-size:1.2rem;color:var(--zinc-300);margin-bottom:0.5rem;">Satkhira, Bangladesh</p>
                <p style="color:var(--zinc-400);margin-bottom:0.25rem;">📞 01988976269</p>
                <p style="color:var(--zinc-400);margin-bottom:2rem;">✉️ tanzim2713n@gmail.com</p>
                <a href="/contact" class="btn btn-outline-primary">Get Directions</a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load featured dishes from API
    fetch('/api/menu/items')
        .then(r => r.json())
        .then(items => {
            const featured = items.filter(i => i.isFeatured).slice(0, 3);
            const fallback = items.slice(0, 3);
            const display = featured.length >= 3 ? featured : fallback;
            const container = document.getElementById('featuredDishes');
            container.innerHTML = display.map((dish, i) => `
                <div class="card animate-fade-up" style="animation-delay:${i * 0.15}s;">
                    <div class="card-img">
                        ${dish.image
                            ? `<img src="${dish.image}" alt="${dish.name}">`
                            : `<div style="width:100%;height:100%;background:var(--zinc-100);display:flex;align-items:center;justify-content:center;font-size:3rem;">🍽️</div>`}
                        <div class="card-badge">৳${dish.price}</div>
                    </div>
                    <div class="card-body">
                        <h3 style="font-size:1.4rem;font-weight:800;margin-bottom:0.75rem;">${dish.name}</h3>
                        <p class="text-muted" style="line-height:1.7;">${dish.description || ''}</p>
                    </div>
                </div>
            `).join('');
        })
        .catch(() => {});
});
</script>
@endsection
