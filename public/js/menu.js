// In-App Menu Catalog Logic
let catalogItems = [];
let catalogCategories = [];
let selectedCategory = 'all';
let menuSearchKeyword = '';

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

function getCatalogImg(dish) {
    if (dish.image && dish.image.trim() !== '') return dish.image;
    for (const [k, url] of Object.entries(foodPlaceholders)) {
        if (dish.name && dish.name.includes(k)) return url;
    }
    return foodPlaceholders['default'];
}

let lastCatalogHash = '';

async function syncLiveCatalog() {
    try {
        const [catRes, itemRes] = await Promise.all([
            fetch('/api/menu/categories'),
            fetch('/api/menu/items')
        ]);
        const newCats = await catRes.json();
        const newItems = await itemRes.json();

        const currentHash = JSON.stringify(newItems.map(i => `${i.id}_${i.price}_${i.isAvailable}_${i.is_available}_${i.name}`));
        if (currentHash !== lastCatalogHash) {
            lastCatalogHash = currentHash;
            catalogCategories = newCats;
            catalogItems = newItems;
            renderCategoryPills();
            renderMenuGrid();
        }
        document.getElementById('menuLoading')?.classList.add('hidden');
    } catch(e) {}
}

document.addEventListener('DOMContentLoaded', async () => {
    await syncLiveCatalog();
    setInterval(syncLiveCatalog, 3000);
});

function renderCategoryPills() {
    const pillsContainer = document.getElementById('menuCategoryPills');
    if (!pillsContainer) return;

    let html = `<button class="cat-pill ${selectedCategory === 'all' ? 'active' : ''}" onclick="selectMenuCat('all', this)">🔥 সকল পদ</button>`;
    catalogCategories.forEach(cat => {
        html += `<button class="cat-pill ${selectedCategory === cat.slug ? 'active' : ''}" onclick="selectMenuCat('${cat.slug}', this)">${cat.name}</button>`;
    });

    pillsContainer.innerHTML = html;
}

function selectMenuCat(slug, el) {
    selectedCategory = slug;
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
    if (el) el.classList.add('active');

    const titleEl = document.getElementById('menuSectionTitle');
    if (titleEl) {
        if (slug === 'all') {
            titleEl.textContent = 'সকল খাবারের মেনু';
        } else {
            const found = catalogCategories.find(c => c.slug === slug);
            titleEl.textContent = found ? found.name : 'খাবারের তালিকা';
        }
    }

    renderMenuGrid();
}

function filterMenuSearch(val) {
    menuSearchKeyword = val.trim();
    const clearBtn = document.getElementById('menuSearchClear');
    if (clearBtn) {
        if (menuSearchKeyword) clearBtn.classList.remove('hidden');
        else clearBtn.classList.add('hidden');
    }
    renderMenuGrid();
}

function clearMenuSearch() {
    const input = document.getElementById('menuSearchInput');
    if (input) input.value = '';
    menuSearchKeyword = '';
    document.getElementById('menuSearchClear')?.classList.add('hidden');
    renderMenuGrid();
}

function renderMenuGrid() {
    const grid = document.getElementById('menuGrid');
    const empty = document.getElementById('menuEmpty');
    const countEl = document.getElementById('menuItemCount');
    if (!grid) return;

    let filtered = catalogItems.filter(item => {
        const matchesCategory = (selectedCategory === 'all') || (item.category && item.category.slug === selectedCategory) || (item.categoryId === selectedCategory);
        const matchesSearch = !menuSearchKeyword || (item.name && item.name.toLowerCase().includes(menuSearchKeyword.toLowerCase())) || (item.description && item.description.toLowerCase().includes(menuSearchKeyword.toLowerCase()));
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
        const img = getCatalogImg(dish);
        const cartItem = globalCart.find(ci => String(ci.id) === String(dish.id));
        const qty = cartItem ? cartItem.qty : 0;
        const isSpicy = dish.name.includes('চুইঝাল') || dish.name.includes('ঝাল') || dish.name.includes('কালাভুনা');
        const isAvail = (dish.isAvailable === true || dish.is_available === true || dish.isAvailable === 1 || dish.is_available === 1 || dish.isAvailable === undefined);

        return `
            <div class="food-app-card ${!isAvail ? 'opacity-70' : ''}">
                <div class="food-app-img-wrap" onclick="addToAppCart('${dish.id}', '${dish.name.replace(/'/g, "\\'")}', ${dish.price}, '${img}', '${dish.category?.name || ''}')">
                    <img src="${img}" alt="${dish.name}" loading="lazy">
                    ${isSpicy ? '<span class="food-app-tag-spicy">🌶️ চুইঝাল</span>' : ''}
                    <span class="food-app-rating">★ ৪.৯</span>
                </div>
                <div class="food-app-card-body">
                    <h4 class="food-app-card-title">${dish.name}</h4>
                    <p class="food-app-card-desc">${dish.description || 'সাতক্ষীরার চুইঝাল স্পেশাল ঐতিহ্যবাহী রান্না।'}</p>
                    <div class="food-app-card-footer">
                        <div class="food-app-price">৳${dish.price}</div>
                        <div>
                            ${!isAvail ? `
                                <button type="button" class="food-app-add-btn" style="background:#27272a;color:#71717a;box-shadow:none;cursor:not-allowed;" disabled>স্টক শেষ</button>
                            ` : (qty === 0 ? `
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
                            `)}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}
