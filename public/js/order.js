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
        if (selectedCat === 'all') return true;
        return (item.category && item.category.slug === selectedCat) || (item.categoryId === selectedCat);
    });

    grid.innerHTML = filtered.map(item => {
        const cartItem = cart.find(ci => ci.id === item.id);
        const qty = cartItem ? cartItem.qty : 0;
        const imgUrl = getDishImage(item);
        const isAvail = (item.isAvailable === true || item.is_available === true || item.isAvailable === 1 || item.is_available === 1 || item.isAvailable === undefined);

        return `
            <div class="luxury-food-card animate-fade-up ${!isAvail ? 'opacity-70' : ''}">
                <div class="food-img-container" style="height:180px;position:relative;">
                    <img src="${imgUrl}" alt="${item.name}" loading="lazy">
                    <div class="food-badge-price">৳${item.price}</div>
                    ${!isAvail ? '<div style="position:absolute;inset:0;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center;"><span style="background:#ef4444;color:#fff;padding:4px 12px;border-radius:9999px;font-size:0.8rem;font-weight:800;">স্টক শেষ</span></div>' : ''}
                </div>
                <div class="food-card-content" style="padding:1.25rem;">
                    <h4 style="font-weight:800;font-size:1.1rem;color:#fff;margin-bottom:0.35rem;">${item.name}</h4>
                    <p class="text-muted text-xs line-clamp-2" style="margin-bottom:1rem;min-height:32px;">${item.description || 'সাতক্ষীরার খাঁটি মসলা ও চুইঝাল দিয়ে প্রস্তুত।'}</p>
                    <div>
                        ${!isAvail ? `
                            <button class="btn-order-card" style="padding:0.6rem 1rem;font-size:0.88rem;background:#27272a;color:#71717a;border:1px solid #3f3f46;cursor:not-allowed;" disabled>
                                <span>স্টক শেষ</span>
                            </button>
                        ` : (qty === 0 ? `
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
                        `)}
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
    window.scrollTo({ top: 0, behavior: 'smooth' });

    const total = cart.reduce((acc, i) => acc + (i.price * i.qty), 0);
    const summary = document.getElementById('checkoutSummary');
    if (summary) {
        summary.innerHTML = `
            <div class="summary-header">
                <div class="flex items-center gap-2">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#f59e0b;"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <span style="font-weight:800;color:#fff;font-size:1.05rem;">অর্ডার বিবরণী</span>
                </div>
                <span class="summary-badge">${cart.length} টি আইটেম</span>
            </div>
            <div class="summary-items-list">
                ${cart.map(i => `
                    <div class="summary-item-row">
                        <div class="flex items-center gap-2">
                            <span class="summary-item-qty">${i.qty}×</span>
                            <span class="summary-item-name">${i.name}</span>
                        </div>
                        <span class="summary-item-price">৳${i.price * i.qty}</span>
                    </div>
                `).join('')}
            </div>
            <div class="summary-footer-box">
                <div class="flex justify-between items-center text-xs text-muted mb-1">
                    <span>ডেলিভারি চার্জ</span>
                    <span style="color:#4ade80;font-weight:700;">ফ্রি (সাতক্ষীরা সদর)</span>
                </div>
                <div class="flex justify-between items-center pt-2" style="border-top:1px dashed rgba(255,255,255,0.1);">
                    <span class="summary-total-label">সর্বমোট পরিশোধযোগ্য:</span>
                    <span class="summary-total-val">৳${total}</span>
                </div>
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
        err.innerText = 'অনুগ্রহ করে আবশ্যকীয় তথ্যগুলো (নাম, মোবাইল নম্বর, এলাকা ও বিস্তারিত ঠিকানা) পূরণ করুন।';
        err.classList.remove('hidden');
        return;
    }

    err.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = `<svg class="spinner-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10"/></svg><span>অর্ডার প্রক্রিয়াধীন...</span>`;

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

            const shortId = data.order.shortId || ('MR-' + (data.order.id ? data.order.id.substring(0, 6).toUpperCase() : ''));
            const fullId = data.order ? data.order.id : '';
            
            const idEl = document.getElementById('successOrderId');
            if (idEl) {
                idEl.innerText = shortId;
                idEl.dataset.fullId = fullId;
            }

            const trackLink = document.getElementById('successTrackLink');
            if (trackLink) {
                trackLink.href = `/track?id=${encodeURIComponent(shortId)}`;
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            err.innerText = data.error || 'অর্ডার করতে সমস্যা হয়েছে। আবার চেষ্টা করুন।';
            err.classList.remove('hidden');
        }
    } catch(e) {
        err.innerText = 'অর্ডার প্রক্রিয়া ব্যর্থ হয়েছে। ইন্টারনেট সংযোগ চেক করে পুনরায় চেষ্টা করুন।';
        err.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg><span>অর্ডার কনফার্ম করুন</span>`;
    }
}

function copyOrderId() {
    const idEl = document.getElementById('successOrderId');
    if (!idEl) return;
    const textToCopy = idEl.innerText.trim();

    navigator.clipboard.writeText(textToCopy).then(() => {
        const btnText = document.getElementById('copyBtnText');
        if (btnText) {
            btnText.innerText = 'কপি হয়েছে!';
            setTimeout(() => {
                btnText.innerText = 'কপি করুন';
            }, 2500);
        }
    }).catch(() => {
        alert('আইডি: ' + textToCopy);
    });
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
    btn.innerHTML = `<div class="gps-pulse-ring"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10"/></svg></div><span>লোকেশন নির্ণয় করা হচ্ছে...</span>`;
    if (status) {
        status.style.display = 'inline-flex';
        status.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#f59e0b;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>স্যাটেলাইট থেকে লোকেশন কোঅর্ডিনেট স্ক্যান হচ্ছে...</span>`;
    }

    navigator.geolocation.getCurrentPosition(
        async (position) => {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            const mapsUrl = `https://maps.google.com/?q=${lat},${lon}`;

            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`);
                const data = await res.json();
                
                let road = data.address?.road || data.address?.neighbourhood || '';
                let area = data.address?.suburb || data.address?.residential || data.address?.village || data.address?.town || '';
                let city = data.address?.city || data.address?.county || data.address?.state_district || 'সাতক্ষীরা';

                let parts = [road, area, city].filter(Boolean).filter((v, i, a) => a.indexOf(v) === i);
                let cleanAddr = parts.length > 0 ? parts.join(', ') : (data.display_name ? data.display_name.split(',').slice(0, 3).join(',') : 'লাইভ লোকেশন');

                addrInput.value = `${cleanAddr} [ম্যাপ: ${mapsUrl}]`;
                if (status) {
                    status.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#4ade80;"><polyline points="20 6 9 17 4 12"/></svg><span style="color:#4ade80;font-weight:700;">লাইভ লোকেশন যুক্ত হয়েছে: ${cleanAddr}</span>`;
                }
            } catch(e) {
                addrInput.value = `লাইভ জিপিএস [ম্যাপ: ${mapsUrl}]`;
                if (status) {
                    status.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#4ade80;"><polyline points="20 6 9 17 4 12"/></svg><span style="color:#4ade80;font-weight:700;">জিপিএস কোঅর্ডিনেট যুক্ত হয়েছে!</span>`;
                }
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<div class="gps-pulse-ring"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg></div><span>আমার বর্তমান লাইভ লোকেশন বসান (GPS)</span>`;
            }
        },
        (error) => {
            btn.disabled = false;
            btn.innerHTML = `<div class="gps-pulse-ring"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="22" y1="12" x2="18" y2="12"/><line x1="6" y1="12" x2="2" y2="12"/><line x1="12" y1="6" x2="12" y2="2"/><line x1="12" y1="22" x2="12" y2="18"/></svg></div><span>আমার বর্তমান লাইভ লোকেশন বসান (GPS)</span>`;
            if (status) {
                status.style.display = 'inline-flex';
                status.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#f87171;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg><span style="color:#f87171;">লোকেশন অ্যাক্সেস পাওয়া যায়নি। অনুগ্রহ করে ম্যানুয়ালি লিখুন।</span>`;
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
