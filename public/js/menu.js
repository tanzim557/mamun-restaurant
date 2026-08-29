// Menu Page Logic
let allCategories = [];
let allMenuItems = [];
let activeCategory = 'all';
let searchKeyword = '';

document.addEventListener('DOMContentLoaded', () => {
    fetchMenuData();

    const searchInput = document.getElementById('menuSearch');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchKeyword = e.target.value.toLowerCase().trim();
            renderMenuItems();
        });
    }
});

async function fetchMenuData() {
    try {
        const [catRes, itemRes] = await Promise.all([
            fetch('/api/menu/categories'),
            fetch('/api/menu/items')
        ]);
        allCategories = await catRes.json();
        allMenuItems = await itemRes.json();
        
        renderCategoryTabs();
        renderMenuItems();
        
        const loading = document.getElementById('menuLoading');
        if (loading) loading.style.display = 'none';
    } catch (e) {
        console.error('Error fetching menu data:', e);
    }
}

function renderCategoryTabs() {
    const container = document.getElementById('categoryTabs');
    if (!container) return;

    let html = `<button class="filter-tab ${activeCategory === 'all' ? 'active' : ''}" onclick="setCategory('all')">All Dishes</button>`;
    allCategories.forEach(cat => {
        html += `<button class="filter-tab ${activeCategory === cat.slug ? 'active' : ''}" onclick="setCategory('${cat.slug}')">${cat.name}</button>`;
    });
    container.innerHTML = html;
}

function setCategory(slug) {
    activeCategory = slug;
    renderCategoryTabs();
    renderMenuItems();
}

const menuFoodImages = {
    'গরু': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80',
    'কালাভুনা': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=600&q=80',
    'নেহারী': 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=600&q=80',
    'হাঁস': 'https://images.unsplash.com/photo-1514944298352-7b0078907869?auto=format&fit=crop&w=600&q=80',
    'মুরগী': 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=600&q=80',
    'মাছ': 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80',
    'ইলিশ': 'https://images.unsplash.com/photo-1534939561126-855b8675edd7?auto=format&fit=crop&w=600&q=80',
    'রুই': 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=600&q=80',
    'ভাত': 'https://images.unsplash.com/photo-1516684732162-798a0062be99?auto=format&fit=crop&w=600&q=80',
    'চা': 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=600&q=80'
};

function getMenuDishImage(item) {
    if (item.image && item.image.trim() !== '') return item.image;
    const name = item.name || '';
    for (const [kw, url] of Object.entries(menuFoodImages)) {
        if (name.includes(kw)) return url;
    }
    return 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=600&q=80';
}

function renderMenuItems() {
    const grid = document.getElementById('menuGrid');
    const empty = document.getElementById('menuEmpty');
    if (!grid) return;

    let filtered = allMenuItems.filter(item => {
        const matchesCat = (activeCategory === 'all') || (item.category && item.category.slug === activeCategory) || (item.categoryId === activeCategory);
        const matchesSearch = !searchKeyword || item.name.toLowerCase().includes(searchKeyword) || (item.description && item.description.toLowerCase().includes(searchKeyword));
        return matchesCat && matchesSearch;
    });

    if (filtered.length === 0) {
        grid.innerHTML = '';
        if (empty) empty.classList.remove('hidden');
        return;
    }

    if (empty) empty.classList.add('hidden');

    grid.innerHTML = filtered.map(item => {
        const imgUrl = getMenuDishImage(item);
        const isAvail = (item.isAvailable === true || item.is_available === true || item.isAvailable === 1 || item.is_available === 1 || item.isAvailable === undefined);
        const isFeat = (item.isFeatured === true || item.is_featured === true || item.isFeatured === 1 || item.is_featured === 1);

        return `
            <div class="card animate-fade-up ${!isAvail ? 'opacity-70' : ''}">
                <div class="card-img" style="position:relative;">
                    <img src="${imgUrl}" alt="${item.name}">
                    <div class="card-badge">৳${item.price}</div>
                    ${!isAvail ? '<div style="position:absolute;inset:0;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center;"><span style="background:#ef4444;color:#fff;padding:4px 12px;border-radius:9999px;font-size:0.8rem;font-weight:800;">স্টক শেষ</span></div>' : ''}
                </div>
                <div class="card-body">
                    <div class="flex items-center justify-between mb-2">
                        <h3 style="font-size:1.3rem;font-weight:800;">${item.name}</h3>
                        ${isFeat ? '<span class="text-xs text-secondary font-bold" style="background:rgba(245,158,11,0.15);padding:2px 8px;border-radius:4px;border:1px solid rgba(245,158,11,0.3);">★ Featured</span>' : ''}
                    </div>
                    <p class="text-muted text-sm line-clamp-2" style="margin-bottom:1.25rem;">${item.description || 'সাতক্ষীরার খাঁটি মসলা ও চুইঝাল দিয়ে রান্না করা সুস্বাদু খাবার।'}</p>
                    <div class="flex items-center justify-between">
                        <span class="card-tag">${item.category ? item.category.name : 'খাবার'}</span>
                        ${isAvail 
                            ? `<a href="/order" class="btn btn-sm btn-primary">অর্ডার করুন</a>`
                            : `<button class="btn btn-sm" style="background:#27272a;color:#71717a;border:1px solid #3f3f46;cursor:not-allowed;" disabled>বর্তমানে নেই</button>`
                        }
                    </div>
                </div>
            </div>
        `;
    }).join('');
}
