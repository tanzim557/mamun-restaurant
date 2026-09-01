@extends('layouts.app')
@section('title', 'শ্যামনগর নজরুল হোটেল — সেরা চুইঝালের স্বাদ ও অনলাইন ফুড ডেলিভারি')

@section('content')
<!-- ═══════════════════════════════════════════════════════
     1. DESKTOP LUXURY HERO BANNER (Matches Screenshot Exact)
     ═══════════════════════════════════════════════════════ -->
<section class="desktop-hero-section">
    <!-- Watermark Background Emblem -->
    <div class="hero-watermark-bg"></div>

    <div class="hero-content-wrap">
        <h1 class="hero-main-title">শ্যামনগর নজরুল হোটেল</h1>
        <div class="hero-prop-name">প্রোঃ আল-মামুন</div>
        
        <p class="hero-desc-text">
            এখানে ভাত, মাছ, মুরগী, চুই ঝালের গরুর গোশত, স্পেশাল চুই ঝালের হাঁসের গোশত সুন্দর পরিবেশে খাওয়ার সুব্যবস্থা আছে।
        </p>

        <div class="hero-red-notice">
            বিঃদ্রঃ বিভিন্ন অনুষ্ঠানে খাবার অর্ডার নেওয়া হয়
        </div>

        <div class="hero-info-badges-row">
            <span class="hero-info-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>উকিলবার পকেট গেটের বাহিরে, সাতক্ষীরা</span>
            </span>
            <a href="tel:01918976269" class="hero-info-badge">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                <span>০১৯১৮-৯৭৬২৬৯</span>
            </a>
        </div>

        <div class="hero-cta-buttons">
            <a href="#allDishesSection" class="btn-hero-order-now">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                <span>Order Now</span>
            </a>
            <a href="/menu" class="btn-hero-view-menu">View Menu</a>
        </div>

        <div>
            <span class="hero-gold-chip">সাতক্ষীরার সেরা স্বাদ</span>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════════════
     2. MAIN CONTENT CONTAINER (Interactive Dynamic Feeds)
     ═══════════════════════════════════════════════════════ -->
<div class="app-container" style="margin-top:2rem;">
    <!-- In-App Search Bar (Mobile screen) -->
    <section class="app-search-section">
        <div class="app-search-box">
            <span class="app-search-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input type="text" id="homeSearchInput" class="app-search-input" placeholder="খাবার খুঁজুন... যেমন: চুইঝাল গরু, হাঁসের মাংস, কালাভুনা" oninput="handleHomeSearch(this.value)">
            <button type="button" id="homeSearchClear" class="app-search-clear hidden" onclick="clearHomeSearch()">✕</button>
        </div>
    </section>

    <!-- Popular Bestsellers Section (Top Featured Cards) -->
    <section class="mb-4" id="bestsellerSection">
        <div class="app-section-header">
            <div class="app-section-title-wrap">
                <span class="app-section-icon">🔥</span>
                <h2 class="app-section-title">আজকের জনপ্রিয় ও স্পেশাল পদ</h2>
            </div>
            <a href="/menu" class="app-section-link">
                <span>সম্পূর্ণ মেনু →</span>
            </a>
        </div>

        <div class="bestseller-grid" id="homeBestsellerRail">
            <div class="skeleton" style="height:320px;"></div>
            <div class="skeleton" style="height:320px;"></div>
            <div class="skeleton" style="height:320px;"></div>
        </div>
    </section>

    <!-- Category Filter Bar (Interactive Category Selector) -->
    <section id="categoryFilterSection" style="margin-top:2.5rem;">
        <div class="category-filter-bar" id="categoryFilterTabs">
            <button type="button" class="category-filter-chip active" onclick="selectFilterTab('all', this)">
                <span>🔥</span> <span>সকল খাবার</span>
            </button>
            <button type="button" class="category-filter-chip" onclick="selectFilterTab('গরু', this)">
                <span>🥩</span> <span>চুইঝাল গরু ও কালাভুনা</span>
            </button>
            <button type="button" class="category-filter-chip" onclick="selectFilterTab('হাঁস', this)">
                <span>🦆</span> <span>চুইঝাল হাঁসের গোশত</span>
            </button>
            <button type="button" class="category-filter-chip" onclick="selectFilterTab('মুরগী', this)">
                <span>🍗</span> <span>দেশি মুরগী ভুনা</span>
            </button>
            <button type="button" class="category-filter-chip" onclick="selectFilterTab('মাছ', this)">
                <span>🐟</span> <span>তাজা মাছ ভাজা</span>
            </button>
            <button type="button" class="category-filter-chip" onclick="selectFilterTab('ভাত', this)">
                <span>🍚</span> <span>ভাত ও ডাল</span>
            </button>
            <button type="button" class="category-filter-chip" onclick="selectFilterTab('নাস্তা', this)">
                <span>☕</span> <span>নেহারী ও চা</span>
            </button>
        </div>
    </section>

    <!-- Full Food Menu Grid -->
    <section class="mb-4" id="allDishesSection">
        <div class="app-section-header">
            <div class="app-section-title-wrap">
                <span class="app-section-icon">🍽️</span>
                <h2 class="app-section-title" id="foodGridTitle">আমাদের সকল খাবারের তালিকা</h2>
            </div>
            <span class="text-muted" style="font-size:0.85rem;" id="foodGridCount">লোড হচ্ছে...</span>
        </div>

        <!-- Food Cards Grid -->
        <div class="food-app-grid" id="homeFoodGrid">
            <div class="skeleton" style="height:280px;"></div>
            <div class="skeleton" style="height:280px;"></div>
            <div class="skeleton" style="height:280px;"></div>
            <div class="skeleton" style="height:280px;"></div>
        </div>

        <!-- Empty search state -->
        <div id="homeFoodEmpty" class="hidden text-center" style="padding:3rem 1rem;">
            <div style="font-size:2.5rem;margin-bottom:0.5rem;">🔍</div>
            <h4 style="font-size:1.1rem;font-weight:800;color:#fff;">কোনো খাবার পাওয়া যায়নি</h4>
            <p class="text-muted text-sm mt-2">অন্য কোনো নামে খুঁজে দেখুন বা ফিল্টার পরিবর্তন করুন।</p>
        </div>
    </section>

    <!-- Restaurant Story & Master Chef Profile -->
    <section class="mb-4" style="margin-top:4rem;">
        <div class="restaurant-app-card">
            <div class="chef-badge-app">
                <img src="/images/owner.jpg" alt="প্রোঃ আল-মামুন" class="chef-avatar-app">
                <div>
                    <span class="promo-badge" style="background:rgba(245,158,11,0.2);color:#fbbf24;border:1px solid rgba(245,158,11,0.3);margin-bottom:2px;">মাস্টার বাবুর্চি & কর্ণধার</span>
                    <h3 style="font-size:1.35rem;font-weight:900;color:#fff;line-height:1.2;">প্রোঃ আল-মামুন</h3>
                    <p class="text-muted text-xs">শ্যামনগর নজরুল হোটেল, সাতক্ষীরা</p>
                </div>
            </div>

            <p style="font-size:0.92rem;color:var(--zinc-300);margin-top:1.25rem;line-height:1.65;">
                সাতক্ষীরার ঐতিহ্যবাহী খাঁটি চুইঝাল দিয়ে রান্না করা সুস্বাদু গরু ও হাঁসের গোশতের নির্ভরযোগ্য প্রতিষ্ঠান। আমাদের প্রতিটি খাবার শতভাগ তাজা ও স্বাস্থ্যসম্মত পরিবেশে প্রস্তুত করা হয়।
            </p>

            <div class="quick-action-pills">
                <a href="tel:01918976269" class="btn-app-action">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>সরাসরি কল দিন</span>
                </a>
                <a href="https://wa.me/8801918976269" target="_blank" class="btn-app-action whatsapp">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>হোয়াটসঅ্যাপ অর্ডার</span>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
let allMenuItems = [];
let filteredDishes = [];
let activeCategoryFilter = 'all';

const CURATED_FOOD_IMAGES = {
    'চুইঝাল': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80',
    'গরু': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80',
    'কালাভুনা': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80',
    'হাঁস': 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=600&q=80',
    'মুরগি': 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=600&q=80',
    'মুরগী': 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=600&q=80',
    'ইলিশ': 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=600&q=80',
    'রুই': 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80',
    'মাছ': 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=600&q=80',
    'ভাত': 'https://images.unsplash.com/photo-1516684732162-798a0062be99?auto=format&fit=crop&w=600&q=80',
    'নেহারী': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80',
    'tikka': 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=600&q=80',
    'biryani': 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?auto=format&fit=crop&w=600&q=80',
    'চা': 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?auto=format&fit=crop&w=600&q=80',
    'lassi': 'https://images.unsplash.com/photo-1553530666-ba11a7da3888?auto=format&fit=crop&w=600&q=80',
    'default': 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=600&q=80'
};

document.addEventListener('DOMContentLoaded', () => {
    loadHomeMenuData();
});

async function loadHomeMenuData() {
    try {
        const res = await fetch('/api/menu/items');
        if (res.ok) {
            allMenuItems = await res.json();
            filteredDishes = [...allMenuItems];
            renderBestsellers();
            renderFoodGrid();
        }
    } catch (err) {
        console.error('Error fetching home menu:', err);
    }
}

function resolveHighResFoodImage(dishName, rawImage) {
    if (rawImage && rawImage.startsWith('http') && !rawImage.includes('error') && rawImage.length > 25) {
        if (!rawImage.includes('?')) {
            return rawImage + '?auto=format&fit=crop&w=600&q=80';
        }
        return rawImage;
    }
    if (rawImage && rawImage.startsWith('/storage/')) {
        return rawImage;
    }

    const n = (dishName || '').toLowerCase();
    if (n.includes('চুইঝাল') || n.includes('গরু') || n.includes('কালাভুনা')) return CURATED_FOOD_IMAGES['কালাভুনা'];
    if (n.includes('হাঁস') || n.includes('duck')) return CURATED_FOOD_IMAGES['হাঁস'];
    if (n.includes('মুরগী') || n.includes('মুরগি') || n.includes('chicken')) return CURATED_FOOD_IMAGES['মুরগি'];
    if (n.includes('ইলিশ')) return CURATED_FOOD_IMAGES['ইলিশ'];
    if (n.includes('রুই') || n.includes('মাছ')) return CURATED_FOOD_IMAGES['মাছ'];
    if (n.includes('ভাত') || n.includes('rice')) return CURATED_FOOD_IMAGES['ভাত'];
    if (n.includes('নেহারী')) return CURATED_FOOD_IMAGES['নেহারী'];
    if (n.includes('tikka')) return CURATED_FOOD_IMAGES['tikka'];
    if (n.includes('biryani') || n.includes('বিরিয়ানি')) return CURATED_FOOD_IMAGES['biryani'];
    if (n.includes('চা') || n.includes('tea')) return CURATED_FOOD_IMAGES['চা'];
    if (n.includes('lassi') || n.includes('লাচ্ছি')) return CURATED_FOOD_IMAGES['lassi'];
    return CURATED_FOOD_IMAGES['default'];
}

function handleHomeSearch(query) {
    const q = query.trim().toLowerCase();
    const clearBtn = document.getElementById('homeSearchClear');
    if (q.length > 0) {
        clearBtn.classList.remove('hidden');
    } else {
        clearBtn.classList.add('hidden');
    }

    if (!q) {
        filteredDishes = activeCategoryFilter === 'all' 
            ? [...allMenuItems] 
            : allMenuItems.filter(i => (i.name + ' ' + (i.category ? i.category.name : '')).toLowerCase().includes(activeCategoryFilter.toLowerCase()));
    } else {
        filteredDishes = allMenuItems.filter(item => {
            const nameMatch = item.name.toLowerCase().includes(q);
            const descMatch = (item.description || '').toLowerCase().includes(q);
            const catMatch = item.category ? item.category.name.toLowerCase().includes(q) : false;
            return nameMatch || descMatch || catMatch;
        });
    }
    renderFoodGrid();
}

function clearHomeSearch() {
    const input = document.getElementById('homeSearchInput');
    input.value = '';
    document.getElementById('homeSearchClear').classList.add('hidden');
    handleHomeSearch('');
}

function selectFilterTab(cat, btn) {
    document.querySelectorAll('.category-filter-chip').forEach(c => c.classList.remove('active'));
    if (btn) btn.classList.add('active');
    activeCategoryFilter = cat;

    if (cat === 'all') {
        filteredDishes = [...allMenuItems];
        document.getElementById('foodGridTitle').innerText = 'আমাদের সকল খাবারের তালিকা';
    } else {
        filteredDishes = allMenuItems.filter(i => {
            const text = (i.name + ' ' + (i.description || '') + ' ' + (i.category ? i.category.name : '')).toLowerCase();
            return text.includes(cat.toLowerCase());
        });
        document.getElementById('foodGridTitle').innerText = `${cat} সমৃদ্ধ স্পেশাল পদ`;
    }
    renderFoodGrid();
}

function renderBestsellers() {
    const rail = document.getElementById('homeBestsellerRail');
    if (!rail) return;

    const featured = allMenuItems.filter(i => i.isFeatured);
    const displayItems = featured.length > 0 ? featured.slice(0, 4) : allMenuItems.slice(0, 4);

    if (displayItems.length === 0) {
        rail.innerHTML = '<p class="text-muted text-xs">কোনো খাবার পাওয়া যায়নি</p>';
        return;
    }

    rail.innerHTML = displayItems.map(item => {
        const imgUrl = resolveHighResFoodImage(item.name, item.image);
        return `
            <div class="bestseller-card">
                <div class="bestseller-img-wrap">
                    <img src="${imgUrl}" alt="${item.name}" loading="lazy" onerror="this.src='${CURATED_FOOD_IMAGES['default']}'">
                    <span class="bestseller-pill">🔥 স্পেশাল পদ</span>
                    <span class="bestseller-rating">⭐ ৪.৯ (১২০+)</span>
                </div>
                <div class="bestseller-info">
                    <h4 class="bestseller-title">${item.name}</h4>
                    <p class="bestseller-desc">${item.description || 'সাতক্ষীরার ঐতিহ্যবাহী খাঁটি চুইঝাল দিয়ে তৈরি সেরা স্বাদের খাবার।'}</p>
                    <div class="bestseller-bottom">
                        <span class="bestseller-price">৳${item.price}</span>
                        <button type="button" class="bestseller-add-btn" onclick="addToCartDirect('${item.id}', '${item.name.replace(/'/g, "\\'")}', ${item.price})">
                            <span>+ যোগ করুন</span>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function renderFoodGrid() {
    const grid = document.getElementById('homeFoodGrid');
    const empty = document.getElementById('homeFoodEmpty');
    const countEl = document.getElementById('foodGridCount');
    if (!grid) return;

    if (filteredDishes.length === 0) {
        grid.classList.add('hidden');
        empty.classList.remove('hidden');
        if (countEl) countEl.innerText = '০ টি খাবার';
        return;
    }

    grid.classList.remove('hidden');
    empty.classList.add('hidden');
    if (countEl) countEl.innerText = `${filteredDishes.length} টি খাবার পাওয়া গেছে`;

    grid.innerHTML = filteredDishes.map(item => {
        const imgUrl = resolveHighResFoodImage(item.name, item.image);
        const isAvail = item.isAvailable !== false && item.is_available !== 0;

        return `
            <div class="food-app-card ${!isAvail ? 'opacity-50' : ''}">
                <div class="food-card-img-box">
                    <img src="${imgUrl}" alt="${item.name}" loading="lazy" onerror="this.src='${CURATED_FOOD_IMAGES['default']}'">
                    ${item.isFeatured ? '<span class="food-card-badge">🔥 স্পেশাল</span>' : ''}
                    ${!isAvail ? '<span class="food-card-badge" style="background:#ef4444;color:#fff;">শেষ হয়ে গেছে</span>' : ''}
                </div>
                <div class="food-card-content">
                    <h3 class="food-card-title">${item.name}</h3>
                    <p class="food-card-desc">${item.description || 'সাতক্ষীরার খাঁটি চুইঝাল দিয়ে রান্না করা সুস্বাদু খাবার।'}</p>
                    <div class="food-card-footer">
                        <span class="food-card-price">৳${item.price}</span>
                        ${isAvail ? `
                            <button type="button" class="food-app-add-btn" onclick="addToCartDirect('${item.id}', '${item.name.replace(/'/g, "\\'")}', ${item.price})">
                                <span>+ যোগ করুন</span>
                            </button>
                        ` : `
                            <span style="font-size:0.78rem;color:#f87171;font-weight:700;">স্টক নেই</span>
                        `}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}
</script>
@endsection
