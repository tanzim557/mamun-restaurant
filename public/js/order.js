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

        return `
            <div class="card animate-fade-up" style="display:flex;flex-direction:column;">
                <div class="card-img" style="height:180px;">
                    ${item.image 
                        ? `<img src="${item.image}" alt="${item.name}">` 
                        : `<div style="width:100%;height:100%;background:var(--zinc-100);display:flex;align-items:center;justify-content:center;font-size:2.5rem;">🍛</div>`}
                    <div class="card-badge" style="font-size:0.8rem;padding:0.25rem 0.75rem;">৳${item.price}</div>
                </div>
                <div class="card-body" style="flex:1;display:flex;flex-direction:column;justify-content:space-between;padding:1rem;">
                    <div>
                        <h4 style="font-weight:700;font-size:1rem;margin-bottom:0.25rem;">${item.name}</h4>
                        <p class="text-muted text-xs line-clamp-2" style="margin-bottom:0.75rem;">${item.description || ''}</p>
                    </div>
                    <div>
                        ${qty === 0 ? `
                            <button class="btn btn-primary btn-block btn-sm" onclick="addToCart('${item.id}', '${item.name.replace(/'/g, "\\'")}', ${item.price}, '${item.image || ''}')">
                                ➕ যোগ করুন
                            </button>
                        ` : `
                            <div class="flex items-center justify-between bg-zinc-100" style="padding:0.25rem 0.5rem;border-radius:var(--radius-sm);">
                                <button class="cart-qty-btn ${qty === 1 ? 'danger' : ''}" onclick="changeQty('${item.id}', -1)">${qty === 1 ? '🗑️' : '−'}</button>
                                <span style="font-weight:700;font-size:0.9rem;">${qty}</span>
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
    if (!body) return;

    if (cart.length === 0) {
        body.innerHTML = `
            <div class="text-center" style="padding:4rem 1rem;color:var(--zinc-400);">
                <div style="margin-bottom:0.75rem;opacity:0.4;display:flex;justify-content:center;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                </div>
                <p>আপনার কার্ট খালি। কিছু আইটেম যোগ করুন।</p>
            </div>
        `;
        if (footer) footer.style.display = 'none';
        return;
    }

    if (footer) footer.style.display = 'block';

    body.innerHTML = cart.map(item => `
        <div class="cart-item">
            ${item.image ? `<img src="${item.image}" class="cart-item-img" alt="${item.name}">` : `<div class="cart-item-img" style="background:var(--zinc-800);display:flex;align-items:center;justify-content:center;color:var(--zinc-400);"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg></div>`}
            <div style="flex:1;">
                <h4 style="font-weight:700;font-size:0.9rem;">${item.name}</h4>
                <p class="text-primary font-bold text-sm">৳${item.price} × ${item.qty} = ৳${item.price * item.qty}</p>
            </div>
            <div class="cart-qty-controls">
                <button class="cart-qty-btn ${item.qty === 1 ? 'danger' : ''}" onclick="changeQty('${item.id}', -1)">
                    ${item.qty === 1 ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>' : '−'}
                </button>
                <span style="font-weight:700;font-size:0.85rem;min-width:18px;text-align:center;">${item.qty}</span>
                <button class="cart-qty-btn" onclick="changeQty('${item.id}', 1)">+</button>
            </div>
        </div>
    `).join('');
}

function openCartDrawer() {
    document.getElementById('cartDrawer').classList.add('open');
    document.getElementById('cartOverlay').classList.add('open');
}

function closeCartDrawer() {
    document.getElementById('cartDrawer').classList.remove('open');
    document.getElementById('cartOverlay').classList.remove('open');
}

function goToCheckout() {
    closeCartDrawer();
    document.getElementById('orderMenu').classList.add('hidden');
    document.getElementById('orderCheckout').classList.remove('hidden');

    const total = cart.reduce((acc, i) => acc + (i.price * i.qty), 0);
    const summary = document.getElementById('checkoutSummary');
    if (summary) {
        summary.innerHTML = `
            <div style="margin-bottom:0.75rem;font-weight:700;border-bottom:1px solid var(--zinc-200);padding-bottom:0.5rem;">অর্ডার সামারি (${cart.length} আইটেম):</div>
            ${cart.map(i => `<div class="flex justify-between text-sm py-1"><span>${i.name} × ${i.qty}</span><span class="font-bold">৳${i.price * i.qty}</span></div>`).join('')}
            <div class="flex justify-between text-base font-bold pt-2 border-t mt-2"><span>সর্বমোট:</span><span class="text-primary">৳${total}</span></div>
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
