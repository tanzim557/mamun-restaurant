@extends('layouts.app')
@section('title', 'শ্যামনগর নজরুল হোটেল — সাতক্ষীরার সেরা চুইঝাল ফুড ডেলিভারি')

@section('content')
<div class="app-container">
    <!-- ═══════════════════════════════════════════════════════
         1. IN-APP LIVE SEARCH BAR
         ═══════════════════════════════════════════════════════ -->
    <section class="app-search-section">
        <div class="app-search-box">
            <span class="app-search-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input type="text" id="homeSearchInput" class="app-search-input" placeholder="খাবার খুঁজুন... যেমন: চুইঝাল গরু, হাঁসের মাংস, কালাভুনা" oninput="handleHomeSearch(this.value)">
            <button type="button" id="homeSearchClear" class="app-search-clear hidden" onclick="clearHomeSearch()">✕</button>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         2. STORY CATEGORY CIRCLES (SWIGGY / INSTAGRAM STYLE)
         ═══════════════════════════════════════════════════════ -->
    <section class="app-stories-section">
        <div class="app-stories-scroll" id="homeCategoryStories">
            <div class="story-circle-item active" onclick="selectStoryCategory('all', this)">
                <div class="story-circle-avatar">
                    <div class="story-circle-img-wrap">
                        <span>🔥</span>
                    </div>
                </div>
                <span class="story-circle-title">সব খাবার</span>
            </div>

            <div class="story-circle-item" onclick="selectStoryCategory('গরু', this)">
                <div class="story-circle-avatar">
                    <div class="story-circle-img-wrap">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=150&q=80" alt="গরুর গোশত">
                    </div>
                </div>
                <span class="story-circle-title">গরুর গোশত</span>
            </div>

            <div class="story-circle-item" onclick="selectStoryCategory('হাঁস', this)">
                <div class="story-circle-avatar">
                    <div class="story-circle-img-wrap">
                        <img src="https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=150&q=80" alt="হাঁসের গোশত">
                    </div>
                </div>
                <span class="story-circle-title">হাঁসের মাংস</span>
            </div>

            <div class="story-circle-item" onclick="selectStoryCategory('মুরগী', this)">
                <div class="story-circle-avatar">
                    <div class="story-circle-img-wrap">
                        <img src="https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=150&q=80" alt="মুরগীর গোশত">
                    </div>
                </div>
                <span class="story-circle-title">দেশি মুরগী</span>
            </div>

            <div class="story-circle-item" onclick="selectStoryCategory('মাছ', this)">
                <div class="story-circle-avatar">
                    <div class="story-circle-img-wrap">
                        <img src="https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=150&q=80" alt="তাজা মাছ">
                    </div>
                </div>
                <span class="story-circle-title">তাজা মাছ</span>
            </div>

            <div class="story-circle-item" onclick="selectStoryCategory('ভাত', this)">
                <div class="story-circle-avatar">
                    <div class="story-circle-img-wrap">
                        <img src="https://images.unsplash.com/photo-1516684732162-798a0062be99?auto=format&fit=crop&w=150&q=80" alt="ভাত ও ডাল">
                    </div>
                </div>
                <span class="story-circle-title">ভাত ও ডাল</span>
            </div>

            <div class="story-circle-item" onclick="selectStoryCategory('স্পেশাল', this)">
                <div class="story-circle-avatar">
                    <div class="story-circle-img-wrap">
                        <span>🌶️</span>
                    </div>
                </div>
                <span class="story-circle-title">চুইঝাল স্পেশাল</span>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         3. PROMO & OFFER BANNERS CAROUSEL
         ═══════════════════════════════════════════════════════ -->
    <section class="app-promo-section">
        <div class="app-promo-carousel">
            <!-- Promo 1 -->
            <div class="promo-slide">
                <div class="promo-card" style="background-image:url('https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80');">
                    <div class="promo-card-overlay"></div>
                    <div class="promo-card-content">
                        <span class="promo-badge">🔥 আজকের স্পেশাল</span>
                        <h3 class="promo-title">চুইঝাল গরুর কালাভুনা</h3>
                        <p class="promo-subtitle">সাতক্ষীরার ঐতিহ্যবাহী খাঁটি ঝাল ও সুবাসে রান্না</p>
                    </div>
                    <button type="button" class="promo-btn-mini" onclick="filterAndScroll('গরু')">
                        <span>অর্ডার করুন</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>

            <!-- Promo 2 -->
            <div class="promo-slide">
                <div class="promo-card" style="background-image:url('https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=800&q=80');">
                    <div class="promo-card-overlay"></div>
                    <div class="promo-card-content">
                        <span class="promo-badge" style="background:var(--gold-gradient);color:#000;">🦆 স্পেশাল স্বাদ</span>
                        <h3 class="promo-title">চুইঝালের হাঁসের গোশত</h3>
                        <p class="promo-subtitle">গরম ভাতের সাথে চুইঝালের হাঁসের ঝোল অতুলনীয়</p>
                    </div>
                    <button type="button" class="promo-btn-mini" onclick="filterAndScroll('হাঁস')">
                        <span>অর্ডার করুন</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                </div>
            </div>

            <!-- Promo 3 -->
            <div class="promo-slide">
                <div class="promo-card" style="background-image:url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80');">
                    <div class="promo-card-overlay"></div>
                    <div class="promo-card-content">
                        <span class="promo-badge" style="background:var(--green);color:#fff;">⚡ দ্রুত ডেলিভারি</span>
                        <h3 class="promo-title">সাতক্ষীরায় হোম ডেলিভারি</h3>
                        <p class="promo-subtitle">গরম গরম খাবার সরাসরি আপনার ঘরে পৌঁছে যাবে</p>
                    </div>
                    <a href="/menu" class="promo-btn-mini">
                        <span>মেনু দেখুন</span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         4. POPULAR BESTSELLERS (HORIZONTAL RAIL)
         ═══════════════════════════════════════════════════════ -->
    <section class="mb-4" id="bestsellerSection">
        <div class="app-section-header">
            <div class="app-section-title-wrap">
                <span class="app-section-icon">🔥</span>
                <h2 class="app-section-title">আজকের জনপ্রিয় খাবার</h2>
            </div>
            <a href="/menu" class="app-section-link">
                <span>সব দেখুন</span>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>

        <div class="app-rail-scroll" id="homeBestsellerRail">
            <div class="skeleton" style="width:170px;height:180px;flex-shrink:0;"></div>
            <div class="skeleton" style="width:170px;height:180px;flex-shrink:0;"></div>
            <div class="skeleton" style="width:170px;height:180px;flex-shrink:0;"></div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         5. FULL FOOD MENU (DUAL-COLUMN MOBILE-FIRST APP GRID)
         ═══════════════════════════════════════════════════════ -->
    <section class="mb-4" id="allDishesSection">
        <div class="app-section-header">
            <div class="app-section-title-wrap">
                <span class="app-section-icon">🍽️</span>
                <h2 class="app-section-title" id="foodGridTitle">সকল খাবার</h2>
            </div>
            <span class="text-muted" style="font-size:0.8rem;" id="foodGridCount">লোড হচ্ছে...</span>
        </div>

        <!-- Food Cards Grid -->
        <div class="food-app-grid" id="homeFoodGrid">
            <div class="skeleton" style="height:240px;"></div>
            <div class="skeleton" style="height:240px;"></div>
            <div class="skeleton" style="height:240px;"></div>
            <div class="skeleton" style="height:240px;"></div>
        </div>

        <!-- Empty search state -->
        <div id="homeFoodEmpty" class="hidden text-center" style="padding:3rem 1rem;">
            <div style="font-size:2.5rem;margin-bottom:0.5rem;">🔍</div>
            <h4 style="font-size:1.1rem;font-weight:800;color:#fff;">কোনো খাবার পাওয়া যায়নি</h4>
            <p class="text-muted text-sm mt-2">অন্য কোনো নামে খুঁজে দেখুন বা ফিল্টার পরিবর্তন করুন।</p>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════════════════
         6. RESTAURANT & CHEF PROFILE CARD (COMPACT APP STYLE)
         ═══════════════════════════════════════════════════════ -->
    <section class="mb-4">
        <div class="restaurant-app-card">
            <div class="chef-badge-app">
                <img src="/images/owner.jpg" alt="প্রোঃ আল-মামুন" class="chef-avatar-app">
                <div>
                    <span class="promo-badge" style="background:rgba(245,158,11,0.2);color:#fbbf24;border:1px solid rgba(245,158,11,0.3);margin-bottom:2px;">মাস্টার শেফ & ওনার</span>
                    <h3 style="font-size:1.15rem;font-weight:900;color:#fff;line-height:1.2;">প্রোঃ আল-মামুন</h3>
                    <p class="text-muted text-xs">শ্যামনগর নজরুল হোটেল, সাতক্ষীরা</p>
                </div>
            </div>

            <p style="font-size:0.82rem;color:var(--zinc-300);margin-top:0.85rem;line-height:1.45;">
                সাতক্ষীরার চুইঝাল দিয়ে রান্না করা ঐতিহ্যবাহী গরু ও হাঁসের গোশতের নির্ভরযোগ্য ঠিকানা। ১০০% তাজা ও স্বাস্থ্যকর উপায়ে পরিবেশিত।
            </p>

            <div class="quick-action-pills">
                <a href="tel:01988976269" class="btn-app-action">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>কল দিন</span>
                </a>
                <a href="https://wa.me/8801988976269" target="_blank" class="btn-app-action whatsapp">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>হোয়াটসঅ্যাপ</span>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
let homeDishes = @json($menuItems ?? []);
let activeCategory = 'all';
let searchQuery = '';

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

function getDishImg(dish) {
    if (dish.image && dish.image.trim() !== '') return dish.image;
    for (const [k, url] of Object.entries(foodPlaceholders)) {
        if (dish.name && dish.name.includes(k)) return url;
    }
    return foodPlaceholders['default'];
}

function initHomeMenu() {
    if (homeDishes && homeDishes.length > 0) {
        renderBestsellers();
        updateHomeFoodCards();
    }
}

let lastDishesHash = '';

async function syncLiveHomeDishes() {
    try {
        const res = await fetch('/api/menu/items');
        const data = await res.json();
        if (Array.isArray(data) && data.length > 0) {
            const currentHash = JSON.stringify(data.map(d => `${d.id}_${d.price}_${d.isAvailable}_${d.is_available}_${d.name}`));
            if (currentHash !== lastDishesHash) {
                lastDishesHash = currentHash;
                homeDishes = data;
                renderBestsellers();
                updateHomeFoodCards();
            }
        }
    } catch(e) {}
}

document.addEventListener('DOMContentLoaded', async () => {
    initHomeMenu();
    await syncLiveHomeDishes();
    setInterval(syncLiveHomeDishes, 3000);
});

function renderBestsellers() {
    const rail = document.getElementById('homeBestsellerRail');
    if (!rail) return;

    const bestsellers = homeDishes.filter(d => d.isFeatured || (d.price >= 100)).slice(0, 6);
    const list = bestsellers.length ? bestsellers : homeDishes.slice(0, 6);

    rail.innerHTML = list.map(dish => {
        const img = getDishImg(dish);
        return `
            <div class="rail-food-card" onclick="filterAndScroll('${dish.name}')">
                <div class="rail-card-img-wrap">
                    <img src="${img}" alt="${dish.name}" loading="lazy">
                    <span class="rail-card-badge">★ ৪.৯</span>
                </div>
                <div class="rail-card-body">
                    <h4 class="rail-card-title">${dish.name}</h4>
                    <span class="rail-card-price">৳${dish.price}</span>
                </div>
            </div>
        `;
    }).join('');
}

function updateHomeFoodCards() {
    const grid = document.getElementById('homeFoodGrid');
    const empty = document.getElementById('homeFoodEmpty');
    const countEl = document.getElementById('foodGridCount');
    if (!grid) return;

    let filtered = homeDishes.filter(item => {
        const matchesCategory = (activeCategory === 'all') || (item.name && item.name.includes(activeCategory)) || (item.category && item.category.name && item.category.name.includes(activeCategory));
        const matchesSearch = !searchQuery || (item.name && item.name.toLowerCase().includes(searchQuery.toLowerCase())) || (item.description && item.description.toLowerCase().includes(searchQuery.toLowerCase()));
        return matchesCategory && matchesSearch;
    });

    if (countEl) countEl.textContent = `${filtered.length} টি পদ`;

    if (filtered.length === 0) {
        grid.innerHTML = '';
        if (empty) empty.classList.remove('hidden');
        return;
    }

    if (empty) empty.classList.add('hidden');

    grid.innerHTML = filtered.map(dish => {
        const img = getDishImg(dish);
        const cartItem = globalCart.find(ci => String(ci.id) === String(dish.id));
        const qty = cartItem ? cartItem.qty : 0;
        const isSpicy = dish.name.includes('চুইঝাল') || dish.name.includes('ঝাল') || dish.name.includes('কালাভুনা');

        return `
            <div class="food-app-card">
                <div class="food-app-img-wrap" onclick="addToAppCart('${dish.id}', '${dish.name.replace(/'/g, "\\'")}', ${dish.price}, '${img}', '${dish.category?.name || ''}')">
                    <img src="${img}" alt="${dish.name}" loading="lazy">
                    ${isSpicy ? '<span class="food-app-tag-spicy">🌶️ চুইঝাল</span>' : ''}
                    <span class="food-app-rating">★ ৪.৯</span>
                </div>
                <div class="food-app-card-body">
                    <h4 class="food-app-card-title">${dish.name}</h4>
                    <p class="food-app-card-desc">${dish.description || 'সাতক্ষীরার খাঁটি মসলা ও দেশি চুইঝাল দিয়ে সুস্বাদু রান্নাকৃত পদ।'}</p>
                    <div class="food-app-card-footer">
                        <div class="food-app-price">৳${dish.price}</div>
                        <div>
                            ${qty === 0 ? `
                                <button type="button" class="food-app-add-btn" onclick="addToAppCart('${dish.id}', '${dish.name.replace(/'/g, "\\'")}', ${dish.price}, '${img}', '${dish.category?.name || ''}')">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                    <span>যোগ</span>
                                </button>
                            ` : `
                                <div class="food-app-stepper">
                                    <button type="button" class="stepper-btn ${qty === 1 ? 'danger' : ''}" onclick="changeAppQty('${dish.id}', -1)">
                                        ${qty === 1 ? '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>' : '−'}
                                    </button>
                                    <span class="stepper-val">${qty}</span>
                                    <button type="button" class="stepper-btn" onclick="changeAppQty('${dish.id}', 1)">+</button>
                                </div>
                            `}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function selectStoryCategory(cat, el) {
    activeCategory = cat;
    document.querySelectorAll('.story-circle-item').forEach(item => item.classList.remove('active'));
    if (el) el.classList.add('active');

    const titleEl = document.getElementById('foodGridTitle');
    if (titleEl) {
        titleEl.textContent = cat === 'all' ? 'সকল খাবার' : `${cat} এর পদসমূহ`;
    }

    updateHomeFoodCards();

    // Smooth scroll to food grid if on mobile
    const target = document.getElementById('allDishesSection');
    if (target && window.innerWidth < 768) {
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function handleHomeSearch(val) {
    searchQuery = val.trim();
    const clearBtn = document.getElementById('homeSearchClear');
    if (clearBtn) {
        if (searchQuery) clearBtn.classList.remove('hidden');
        else clearBtn.classList.add('hidden');
    }
    updateHomeFoodCards();
}

function clearHomeSearch() {
    const input = document.getElementById('homeSearchInput');
    if (input) input.value = '';
    searchQuery = '';
    document.getElementById('homeSearchClear')?.classList.add('hidden');
    updateHomeFoodCards();
}

function filterAndScroll(keyword) {
    searchQuery = keyword;
    const input = document.getElementById('homeSearchInput');
    if (input) input.value = keyword;
    document.getElementById('homeSearchClear')?.classList.remove('hidden');
    updateHomeFoodCards();
    document.getElementById('allDishesSection')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>
@endsection
