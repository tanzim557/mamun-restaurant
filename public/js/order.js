// Order Page Logic: Cart, State, and Checkout
let cart = [];
let menuList = [];
let categories = [];
let selectedCat = 'all';

document.addEventListener('DOMContentLoaded', () => {
    loadCartFromStorage();
    fetchOrderMenu();
});

function loadCartFromStorage() {
    try {
        const saved = localStorage.getItem('mamun_cart');
        if (saved) cart = JSON.parse(saved);
    } catch(e) { cart = []; }
    updateCartUI();
}

function saveCartToStorage() {
    localStorage.setItem('mamun_cart', JSON.stringify(cart));
    updateCartUI();
}

async function fetchOrderMenu() {
    try {
        const [catRes, itemRes] = await Promise.all([
            fetch('/api/menu/categories'),
            fetch('/api/menu/items')
        ]);
        categories = await catRes.json();
        menuList = await itemRes.json();

        renderOrderCategories();
        renderOrderGrid();

        const loading = document.getElementById('orderLoading');
        if (loading) loading.style.display = 'none';
    } catch(e) {
        console.error('Order fetch error:', e);
    }
}

function renderOrderCategories() {
    const container = document.getElementById('orderCatTabs');
    if (!container) return;

    let html = `<button class="filter-tab ${selectedCat === 'all' ? 'active' : ''}" onclick="filterOrderCat('all')">সব আইটেম</button>`;
    categories.forEach(c => {
        html += `<button class="filter-tab ${selectedCat === c.slug ? 'active' : ''}" onclick="filterOrderCat('${c.slug}')">${c.name}</button>`;
    });
    container.innerHTML = html;
}

function filterOrderCat(slug) {
    selectedCat = slug;
    renderOrderCategories();
    renderOrderGrid();
}

const foodPlaceholders = {
    'গরু': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
    'কালাভুনা': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=800&q=80',
    'নেহারী': 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80',
    'হাঁস': 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?auto=format&fit=crop&w=800&q=80',
    'মুরগী': 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=800&q=80',
    'মাছ': 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=800&q=80',
    'ভাত': 'https://images.unsplash.com/photo-1516684732162-798a0062be99?auto=format&fit=crop&w=800&q=80',
    'default': 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80'
};

function getDishImage(item) {
    if (item.image && item.image.trim() !== '') return item.image;
    for (const [key, url] of Object.entries(foodPlaceholders)) {
        if (item.name && item.name.includes(key)) return url;
    }
    return foodPlaceholders['default'];
}

function renderOrderGrid() {
    const grid = document.getElementById('orderGrid');
    if (!grid) return;

    let filtered = menuList.filter(item => {
        if (!item.isAvailable) return false;
        if (selectedCat === 'all') return true;
        return (item.category && item.category.slug === selectedCat) || (item.categoryId === selectedCat);
    });

    grid.innerHTML = filtered.map(item => {
        const cartItem = cart.find(ci => ci.id === item.id);
        const qty = cartItem ? cartItem.qty : 0;
        const imgUrl = getDishImage(item);

        return `
            <div class="luxury-food-card animate-fade-up">
                <div class="food-img-container" style="height:180px;">
                    <img src="${imgUrl}" alt="${item.name}" loading="lazy">
                    <div class="food-badge-price">৳${item.price}</div>
                </div>
                <div class="food-card-content" style="padding:1.25rem;">
                    <h4 style="font-weight:800;font-size:1.1rem;color:#fff;margin-bottom:0.35rem;">${item.name}</h4>
                    <p class="text-muted text-xs line-clamp-2" style="margin-bottom:1rem;min-height:32px;">${item.description || 'সাতক্ষীরার খাঁটি মসলা ও চুইঝাল দিয়ে প্রস্তুত।'}</p>
                    <div>
                        ${qty === 0 ? `
                            <button class="btn-order-card" style="padding:0.6rem 1rem;font-size:0.88rem;" onclick="addToCart('${item.id}', '${item.name.replace(/'/g, "\\'")}', ${item.price}, '${imgUrl}')">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                <span>কার্টে যোগ করুন</span>
                            </button>
                        ` : `
                            <div class="order-grid-qty-box">
                                <button class="cart-qty-btn ${qty === 1 ? 'danger' : ''}" onclick="changeQty('${item.id}', -1)">
                                    ${qty === 1 ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>' : '−'}
                                </button>
                                <span class="order-grid-qty-val">${qty} টি কার্টে</span>
                                <button class="cart-qty-btn" onclick="changeQty('${item.id}', 1)">+</button>
                            </div>
                        `}
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function addToCart(id, name, price, image) {
    const existing = cart.find(i => i.id === id);
    if (existing) {
        existing.qty += 1;
    } else {
        cart.push({ id, name, price, image, qty: 1 });
    }
    saveCartToStorage();
    renderOrderGrid();
    openCartDrawer(); // AUTOMATICALLY OPEN SIDE DRAWER ON ADD!
}

function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    item.qty += delta;
    if (item.qty <= 0) {
        cart = cart.filter(i => i.id !== id);
    }
    saveCartToStorage();
    renderOrderGrid();
    renderCartDrawer();
}

function updateCartUI() {
    const totalCount = cart.reduce((acc, i) => acc + i.qty, 0);
    const totalPrice = cart.reduce((acc, i) => acc + (i.price * i.qty), 0);

    const countEl = document.getElementById('cartCount');
    const totalEl = document.getElementById('cartTotal');
    const drawerTotalEl = document.getElementById('cartDrawerTotal');
    const floatCart = document.getElementById('floatingCart');

    if (countEl) countEl.innerText = totalCount;
    if (totalEl) totalEl.innerText = '৳' + totalPrice;
    if (drawerTotalEl) drawerTotalEl.innerText = '৳' + totalPrice;

    if (floatCart) {
        if (totalCount > 0) floatCart.classList.remove('hidden');
        else floatCart.classList.add('hidden');
    }

    renderCartDrawer();
}

function renderCartDrawer() {
    const body = document.getElementById('cartBody');
    const footer = document.getElementById('cartFooter');
    const headerCount = document.getElementById('cartHeaderCount');
    if (!body) return;

    const totalCount = cart.reduce((acc, i) => acc + i.qty, 0);
    if (headerCount) headerCount.innerText = `${totalCount} টি আইটেম`;

    if (cart.length === 0) {
        body.innerHTML = `
            <div class="cart-empty-state">
                <div class="cart-empty-icon">
                    <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
                <h4 style="font-size:1.2rem;font-weight:700;color:#fff;margin-bottom:0.5rem;">আপনার কার্ট খালি</h4>
                <p style="color:#71717a;font-size:0.9rem;">মেনু থেকে আপনার পছন্দের খাবার কার্টে যোগ করুন।</p>
            </div>
        `;
        if (footer) footer.style.display = 'none';
        return;
    }

    if (footer) footer.style.display = 'block';

    body.innerHTML = cart.map(item => `
        <div class="cart-item-card">
            <img src="${item.image || foodPlaceholders['default']}" class="cart-item-thumbnail" alt="${item.name}">
            <div class="cart-item-details">
                <h4 class="cart-item-name">${item.name}</h4>
                <div class="cart-item-price-row">
                    <span class="cart-item-unit">৳${item.price}</span>
                    <span class="cart-item-calc">× ${item.qty} =</span>
                    <span class="cart-item-subtotal">৳${item.price * item.qty}</span>
                </div>
            </div>
            <div class="cart-qty-pill">
                <button class="cart-qty-btn ${item.qty === 1 ? 'danger' : ''}" onclick="changeQty('${item.id}', -1)" title="${item.qty === 1 ? 'মুছে ফেলুন' : 'কমান'}">
                    ${item.qty === 1 ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>' : '−'}
                </button>
                <span class="cart-qty-number">${item.qty}</span>
                <button class="cart-qty-btn" onclick="changeQty('${item.id}', 1)" title="বাড়ান">+</button>
            </div>
        </div>
    `).join('');
}

function openCartDrawer() {
    const drawer = document.getElementById('cartDrawer');
    const overlay = document.getElementById('cartOverlay');
    if (drawer) drawer.classList.add('open');
    if (overlay) overlay.classList.add('open');
}

function closeCartDrawer() {
    const drawer = document.getElementById('cartDrawer');
    const overlay = document.getElementById('cartOverlay');
    if (drawer) drawer.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
}

function goToCheckout() {
    closeCartDrawer();
    document.getElementById('orderMenu').classList.add('hidden');
    document.getElementById('orderCheckout').classList.remove('hidden');

    const total = cart.reduce((acc, i) => acc + (i.price * i.qty), 0);
    const summary = document.getElementById('checkoutSummary');
    if (summary) {
        summary.innerHTML = `
            <div style="margin-bottom:0.75rem;font-weight:800;color:#fff;font-size:1.05rem;border-bottom:1px solid rgba(255,255,255,0.08);padding-bottom:0.6rem;">অর্ডার সামারি (${cart.length} আইটেম):</div>
            ${cart.map(i => `
                <div class="flex justify-between text-sm py-1.5" style="border-bottom:1px solid rgba(255,255,255,0.04);">
                    <span style="color:#d4d4d8;">${i.name} × ${i.qty}</span>
                    <span class="font-bold text-white">৳${i.price * i.qty}</span>
                </div>
            `).join('')}
            <div class="flex justify-between text-lg font-bold pt-3 mt-2" style="border-top:1px solid rgba(255,255,255,0.1);color:#fff;">
                <span>সর্বমোট:</span>
                <span class="text-primary font-bold text-xl">৳${total}</span>
            </div>
        `;
    }
}

function showMenuView() {
    document.getElementById('orderCheckout').classList.add('hidden');
    document.getElementById('orderMenu').classList.remove('hidden');
}

async function placeOrder() {
    const name = document.getElementById('cName').value.trim();
    const phone = document.getElementById('cPhone').value.trim();
    const area = document.getElementById('cArea').value;
    const address = document.getElementById('cAddress').value.trim();
    const note = document.getElementById('cNote').value.trim();
    const err = document.getElementById('orderError');
    const btn = document.getElementById('placeOrderBtn');

    if (!name || !phone || !area || !address) {
        err.innerText = 'অনুগ্রহ করে সকল আবশ্যকীয় তথ্য (নাম, ফোন, এলাকা, ঠিকানা) পূরণ করুন।';
        err.classList.remove('hidden');
        return;
    }

    err.classList.add('hidden');
    btn.disabled = true;
    btn.innerText = 'অর্ডার প্রক্রিয়াধীন...';

    try {
        const fullAddress = `${area} - ${address}`;
        const res = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                customerName: name,
                phoneNumber: phone,
                address: fullAddress,
                note: note,
                items: cart.map(i => ({ name: i.name, qty: i.qty, price: i.price }))
            })
        });

        const data = await res.json();
        if (res.ok && data.success) {
            cart = [];
            saveCartToStorage();
            document.getElementById('orderCheckout').classList.add('hidden');
            document.getElementById('orderSuccess').classList.remove('hidden');
            document.getElementById('successOrderId').innerText = data.order ? data.order.id : 'N/A';
        } else {
            err.innerText = data.error || 'অর্ডার করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।';
            err.classList.remove('hidden');
        }
    } catch(e) {
        err.innerText = 'অর্ডার ব্যর্থ হয়েছে। নেটওয়ার্ক চেক করুন।';
        err.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.innerText = '📦 অর্ডার দিন';
    }
}

async function getLiveLocation() {
    const btn = document.getElementById('gpsBtn');
    const status = document.getElementById('gpsStatus');
    const addrInput = document.getElementById('cAddress');

    if (!navigator.geolocation) {
        alert('আপনার ব্রাউজারে GPS / লাইভ লোকেশন সাপোর্ট করে না।');
        return;
    }

    btn.disabled = true;
    btn.innerText = '⏳ খোঁজা হচ্ছে...';
    if (status) {
        status.style.display = 'block';
        status.innerText = '📡 আপনার বর্তমান লোকেশন নির্ণয় করা হচ্ছে...';
    }

    navigator.geolocation.getCurrentPosition(
        async (position) => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            const mapsUrl = `https://maps.google.com/?q=${lat},${lon}`;

            try {
                // Reverse geocode using OpenStreetMap Nominatim
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`);
                const data = await res.json();
                const displayName = data.display_name || `Lat: ${lat.toFixed(5)}, Lon: ${lon.toFixed(5)}`;
                
                addrInput.value = `${displayName} [ম্যাপ লিংক: ${mapsUrl}]`;
                if (status) {
                    status.innerHTML = `<span style="color:#22c55e;">✅ লোকেশন পাওয়া গেছে!</span> <a href="${mapsUrl}" target="_blank" style="color:#3b82f6;text-decoration:underline;">ম্যাপে দেখুন</a>`;
                }
            } catch(e) {
                addrInput.value = `লাইভ পিন [ম্যাপ লিংক: ${mapsUrl}]`;
                if (status) {
                    status.innerHTML = `<span style="color:#22c55e;">✅ জিপিএস কোঅর্ডিনেট সেট হয়েছে!</span>`;
                }
            } finally {
                btn.disabled = false;
                btn.innerText = '🎯 লাইভ লোকেশন নিন (GPS)';
            }
        },
        (error) => {
            btn.disabled = false;
            btn.innerText = '🎯 লাইভ লোকেশন নিন (GPS)';
            if (status) {
                status.style.display = 'block';
                status.innerHTML = `<span style="color:#ef4444;">❌ লোকেশন অনুমতি পাওয়া যায়নি (${error.message})। দয়া করে ম্যানুয়ালি লিখুন।</span>`;
            }
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

function resetOrder() {
    document.getElementById('orderSuccess').classList.add('hidden');
    document.getElementById('orderMenu').classList.remove('hidden');
    renderOrderGrid();
}
