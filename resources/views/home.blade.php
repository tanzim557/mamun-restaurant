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
            <p style="margin-top:0.75rem;font-size:0.95rem;opacity:0.85;display:flex;align-items:center;justify-content:center;gap:15px;flex-wrap:wrap;">
                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;color:var(--secondary);"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা</span>
                <span><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;color:var(--primary);"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> ০১৯৮৮-৯৭৬২৬৯</span>
            </p>
        </div>
        <div class="flex gap-4 justify-center" style="flex-wrap:wrap;margin-top:1.5rem;">
            <a href="/order" class="btn btn-primary btn-lg">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span>Order Now</span>
            </a>
            <a href="/menu" class="btn btn-secondary btn-lg">View Menu</a>
        </div>
    </div>
</section>

<!-- Featured Dishes (Luxury Showcase) -->
<section class="section featured-dishes-section">
    <div class="ambient-glow glow-1" style="top:20%;right:-10%;"></div>
    <div class="ambient-glow glow-2" style="bottom:10%;left:-10%;"></div>

    <div class="container relative" style="z-index:2;">
        <div class="section-header">
            <span class="overline">সাতক্ষীরার সেরা স্বাদ</span>
            <h2>জনপ্রিয় স্পেশাল খাবার</h2>
            <p style="color:#a1a1aa;margin-top:0.5rem;">আমাদের সবচেয়ে জনপ্রিয় ও প্রশংসিত স্পেশাল ঐতিহ্যবাহী পদসমূহ</p>
            <div class="divider"></div>
        </div>

        <div class="grid grid-3" id="featuredDishes">
            <!-- JS will populate with luxury cards -->
        </div>

        <div class="text-center mt-8">
            <a href="/menu" class="btn btn-secondary btn-lg" style="display:inline-flex;align-items:center;gap:8px;">
                <span>সম্পূর্ণ মেনু দেখুন</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us / Philosophy (Luxury Showcase) -->
<section class="section philosophy-section">
    <!-- Ambient Animated Background Lights -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>
    <div class="ambient-glow glow-3"></div>

    <div class="container relative" style="z-index:2;">
        <div class="philosophy-glass-box">
            <div class="grid grid-2" style="align-items:center;gap:4rem;">
                <!-- Owner Showcase -->
                <div class="text-center animate-fade owner-showcase">
                    <div class="owner-portrait-wrapper">
                        <div class="owner-aura-ring"></div>
                        <div class="owner-aura-ring-2"></div>
                        <div class="owner-photo">
                            <img src="/images/owner.jpg" alt="মামুন হোটেল প্রতিষ্ঠাতা ও স্বত্বাধিকারী">
                        </div>
                        <div class="owner-badge-floating">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>মাস্টার শেফ & ওনার</span>
                        </div>
                    </div>
                    <h3 class="owner-name">প্রোঃ আল-মামুন</h3>
                    <p class="owner-role">প্রতিষ্ঠাতা ও প্রধান কারিগর</p>
                    <p class="owner-loc">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>সাতক্ষীরা, বাংলাদেশ</span>
                    </p>
                </div>

                <!-- Philosophy Content -->
                <div>
                    <div class="section-tag-gold">
                        <span class="pulse-dot-gold"></span>
                        <span>ঐতিহ্য ও মান</span>
                    </div>
                    <h2 class="philosophy-title">খাবারের প্রতিটি পদে <span class="text-gradient-fire">খাঁটি স্বাদ</span> ও ভালোবাসা</h2>
                    <p class="philosophy-desc">
                        মামুন হোটেলে প্রতিটি রান্না শুধু খাবার নয়, এটি একটি পারিবারিক ঐতিহ্য। আমাদের অভিজ্ঞ বাবুর্চিরা নিজস্ব মসলা ও খাঁটি চুইঝালের সমন্বয়ে প্রস্তুত করেন অনন্য স্বাদের অতুলনীয় সব খাবার।
                    </p>
                    <div class="flex flex-col gap-3">
                        <div class="feature-glass-card">
                            <div class="feature-icon-wrap icon-red">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" y1="17" x2="18" y2="17"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-white">অভিজ্ঞ মাস্টার শেফ</h4>
                                <p class="text-muted text-sm mt-1">বছরের পর বছর ধরে সাতক্ষীরার ঐতিহ্যবাহী রান্নার বিশ্বস্ত হাত।</p>
                            </div>
                        </div>

                        <div class="feature-glass-card">
                            <div class="feature-icon-wrap icon-gold">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-white">১০০% তাজা ও হালাল উপকরণ</h4>
                                <p class="text-muted text-sm mt-1">প্রতিদিন সকালের বাছাইকৃত তাজা মাংস ও দেশি চুইঝাল ব্যবহার করা হয়।</p>
                            </div>
                        </div>

                        <div class="feature-glass-card">
                            <div class="feature-icon-wrap icon-blue">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-white">পরিষ্কার ও দ্রুত পরিবেশন</h4>
                                <p class="text-muted text-sm mt-1">সুন্দর, স্বাস্থ্যকর ও পারিবারিক পরিবেশে আন্তরিক আপ্যায়ন।</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Info Section (Luxury Glass Cards) -->
<section class="section section-dark">
    <div class="container">
        <div class="grid grid-2" style="gap:2.5rem;">
            <div class="glass-card animate-fade-up" style="background:rgba(20,20,25,0.7);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.08);padding:2.5rem;border-radius:20px;box-shadow:0 20px 40px rgba(0,0,0,0.6);">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(245,158,11,0.15);color:#f59e0b;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h3 style="font-size:1.75rem;font-weight:800;margin-bottom:1.5rem;">খোলার সময়সূচী</h3>
                <ul style="list-style:none;font-size:1.05rem;color:var(--zinc-300);">
                    <li style="display:flex;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,0.08);padding-bottom:0.85rem;margin-bottom:0.85rem;"><span>রবিবার - বৃহস্পতিবার</span><span style="color:#4ade80;font-weight:700;">ভোর ৫:০০ - রাত ১০:০০</span></li>
                    <li style="display:flex;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,0.08);padding-bottom:0.85rem;margin-bottom:0.85rem;color:var(--red);"><span>শুক্রবার</span><span>সাপ্তাহিক বন্ধ</span></li>
                    <li style="display:flex;justify-content:space-between;color:var(--red);"><span>শনিবার</span><span>সাপ্তাহিক বন্ধ</span></li>
                </ul>
            </div>

            <div class="glass-card animate-fade-up" style="background:rgba(20,20,25,0.7);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.08);padding:2.5rem;border-radius:20px;box-shadow:0 20px 40px rgba(0,0,0,0.6);">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(239,68,68,0.15);color:#ef4444;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <h3 style="font-size:1.75rem;font-weight:800;margin-bottom:1rem;">যোগাযোগ ও ঠিকানা</h3>
                <p style="font-size:1.15rem;color:#fff;font-weight:700;margin-bottom:0.4rem;">উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা</p>
                <p style="color:var(--zinc-400);margin-bottom:0.4rem;display:flex;align-items:center;gap:8px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#f59e0b;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg> ০১৯৮৮-৯৭৬২৬৯</p>
                <p style="color:var(--zinc-400);margin-bottom:1.75rem;display:flex;align-items:center;gap:8px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#60a5fa;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> tanzim2713n@gmail.com</p>
                <a href="/contact" class="btn btn-primary" style="padding:0.65rem 1.5rem;font-size:0.9rem;">সরাসরি ম্যাপে দেখুন →</a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const foodPlaceholders = {
        'গরু': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
        'কালাভুনা': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
        'নেহারী': 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
        'হাঁস': 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=800&q=80',
        'মুরগী': 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=800&q=80',
        'মাছ': 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=800&q=80',
        'ভাত': 'https://images.unsplash.com/photo-1516684732162-798a0062be99?auto=format&fit=crop&w=800&q=80',
        'ডাল': 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
        'default': 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80'
    };

    function getDishImage(dish) {
        if (dish.image && dish.image.trim() !== '') return dish.image;
        for (const [key, url] of Object.entries(foodPlaceholders)) {
            if (dish.name && dish.name.includes(key)) return url;
        }
        return foodPlaceholders['default'];
    }

    // Load featured dishes from API
    fetch('/api/menu/items')
        .then(r => r.json())
        .then(items => {
            const featured = items.filter(i => i.isFeatured).slice(0, 3);
            const fallback = items.slice(0, 3);
            const display = featured.length >= 3 ? featured : (featured.length > 0 ? featured.concat(fallback.filter(f => !featured.some(fe => fe.id === f.id))).slice(0, 3) : fallback);
            const container = document.getElementById('featuredDishes');
            if (!container) return;

            container.innerHTML = display.map((dish, i) => {
                const imgUrl = getDishImage(dish);
                return `
                    <div class="luxury-food-card animate-fade-up" style="animation-delay:${i * 0.15}s;">
                        <div class="food-img-container">
                            <img src="${imgUrl}" alt="${dish.name}" loading="lazy">
                            <div class="food-badge-price">৳${dish.price}</div>
                            <div class="food-badge-rating">★ স্পেশাল</div>
                        </div>
                        <div class="food-card-content">
                            <div class="flex items-center justify-between mb-1">
                                <span class="food-category-tag">${dish.category ? dish.category.name : 'ঐতিহ্যবাহী পদ'}</span>
                            </div>
                            <h3 class="food-card-title">${dish.name}</h3>
                            <p class="food-card-desc">${dish.description || 'সাতক্ষীরার খাঁটি মসলা ও চুইঝালের অনন্য স্পেশাল স্বাদে প্রস্তুত।'}</p>
                            <div class="food-card-action">
                                <a href="/order" class="btn-order-card">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    <span>অর্ডার করুন</span>
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        })
        .catch(() => {});
});
</script>
@endsection
