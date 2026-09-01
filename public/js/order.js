// Order Page Logic
let orderItemsList = [];
let orderCategoriesList = [];
let activeOrderCategory = 'all';
let activeOrderSearch = '';

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

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const [catRes, itemRes] = await Promise.all([
            fetch('/api/menu/categories'),
            fetch('/api/menu/items')
        ]);
        orderCategoriesList = await catRes.json();
        orderItemsList = await itemRes.json();

        renderOrderCategories();
        renderOrderGrid();

        document.getElementById('orderLoading')?.classList.add('hidden');
    } catch(e) {
        console.error('Order fetch error:', e);
    }
});

function renderOrderCategories() {
    const container = document.getElementById('orderCatTabs');
    if (!container) return;

    let html = `<button class="cat-pill ${activeOrderCategory === 'all' ? 'active' : ''}" onclick="filterOrderCat('all', this)">🔥 সব পদ</button>`;
    orderCategoriesList.forEach(c => {
        html += `<button class="cat-pill ${activeOrderCategory === c.slug ? 'active' : ''}" onclick="filterOrderCat('${c.slug}', this)">${c.name}</button>`;
    });
    container.innerHTML = html;
}

function filterOrderCat(slug, el) {
    activeOrderCategory = slug;
    document.querySelectorAll('#orderCatTabs .cat-pill').forEach(p => p.classList.remove('active'));
    if (el) el.classList.add('active');
    renderOrderGrid();
}

function filterOrderSearch(val) {
    activeOrderSearch = val.trim();
    const clearBtn = document.getElementById('orderSearchClear');
    if (clearBtn) {
        if (activeOrderSearch) clearBtn.classList.remove('hidden');
        else clearBtn.classList.add('hidden');
    }
    renderOrderGrid();
}

function clearOrderSearch() {
    const input = document.getElementById('orderSearchInput');
    if (input) input.value = '';
    activeOrderSearch = '';
    document.getElementById('orderSearchClear')?.classList.add('hidden');
    renderOrderGrid();
}

function renderOrderGrid() {
    const grid = document.getElementById('orderGrid');
    if (!grid) return;

    let filtered = orderItemsList.filter(item => {
        const matchesCategory = (activeOrderCategory === 'all') || (item.category && item.category.slug === activeOrderCategory) || (item.categoryId === activeOrderCategory);
        const matchesSearch = !activeOrderSearch || (item.name && item.name.toLowerCase().includes(activeOrderSearch.toLowerCase())) || (item.description && item.description.toLowerCase().includes(activeOrderSearch.toLowerCase()));
        return matchesCategory && matchesSearch;
    });

    grid.innerHTML = filtered.map(dish => {
        const img = getDishImg(dish);
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
                    <p class="food-app-card-desc">${dish.description || 'সাতক্ষীরার চুইঝাল স্পেশাল খাঁটি স্বাদের রান্না।'}</p>
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

function copyOrderId() {
    const id = document.getElementById('successOrderId')?.innerText;
    if (!id) return;
    navigator.clipboard.writeText(id).then(() => {
        const btnText = document.getElementById('copyBtnText');
        if (btnText) btnText.textContent = '✓ কপি হয়েছে!';
        setTimeout(() => { if (btnText) btnText.textContent = 'কপি করুন'; }, 2000);
    });
}

function resetOrderView() {
    document.getElementById('orderSuccess')?.classList.add('hidden');
    document.getElementById('orderMainSection')?.classList.remove('hidden');
}
