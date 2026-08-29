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

    grid.innerHTML = filtered.map(item => `
        <div class="card animate-fade-up">
            <div class="card-img">
                ${item.image 
                    ? `<img src="${item.image}" alt="${item.name}">`
                    : `<div style="width:100%;height:100%;background:var(--zinc-100);display:flex;align-items:center;justify-content:center;font-size:3rem;">🍲</div>`}
                <div class="card-badge">৳${item.price}</div>
            </div>
            <div class="card-body">
                <div class="flex items-center justify-between mb-2">
                    <h3 style="font-size:1.3rem;font-weight:800;">${item.name}</h3>
                    ${item.isFeatured ? '<span class="text-xs text-secondary font-bold">⭐ Featured</span>' : ''}
                </div>
                <p class="text-muted text-sm line-clamp-2" style="margin-bottom:1.25rem;">${item.description || ''}</p>
                <div class="flex items-center justify-between">
                    <span class="card-tag">${item.category ? item.category.name : 'Dish'}</span>
                    <a href="/order" class="btn btn-sm btn-primary">Order Now</a>
                </div>
            </div>
        </div>
    `).join('');
}
