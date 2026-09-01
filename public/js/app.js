// ═══════════════════════════════════════════════════════════
// MAMUN RESTAURANT — GLOBAL FOOD APP CORE JAVASCRIPT
// Cart State, Reactive Pill, Bottom Sheet, Toast & GPS
// ═══════════════════════════════════════════════════════════

let globalCart = [];
let restaurantStatus = { isOpen: true, statusMessage: 'খোলা আছে' };

// Initialize on DOM ready
// Real-time synchronization channel
const mamunSyncBus = (typeof BroadcastChannel !== 'undefined') ? new BroadcastChannel('mamun_sync_bus') : null;
if (mamunSyncBus) {
    mamunSyncBus.onmessage = (event) => {
        if (event.data && (event.data.action === 'HOTEL_STATUS_TOGGLED' || event.data.action === 'SETTINGS_CHANGED')) {
            fetchLiveRestaurantStatus();
        }
        if (event.data && event.data.action === 'MENU_UPDATED') {
            if (typeof syncLiveHomeDishes === 'function') syncLiveHomeDishes();
            if (typeof syncLiveCatalog === 'function') syncLiveCatalog();
        }
    };
}

// Storage listener fallback for older browsers
window.addEventListener('storage', (e) => {
    if (e.key === 'mamun_sync_event') {
        fetchLiveRestaurantStatus();
        if (typeof syncLiveHomeDishes === 'function') syncLiveHomeDishes();
        if (typeof syncLiveCatalog === 'function') syncLiveCatalog();
    }
});

window.addEventListener('online', () => {
    fetchLiveRestaurantStatus();
    if (typeof syncLiveHomeDishes === 'function') syncLiveHomeDishes();
    if (typeof syncLiveCatalog === 'function') syncLiveCatalog();
    showToast('🟢 ওয়াইফাই / ইন্টারনেট সংযুক্ত হয়েছে!', 'success');
});

window.onNetworkRestored = () => {
    fetchLiveRestaurantStatus();
    if (typeof syncLiveHomeDishes === 'function') syncLiveHomeDishes();
    if (typeof syncLiveCatalog === 'function') syncLiveCatalog();
    showToast('🟢 ওয়াইফাই / ইন্টারনেট সংযুক্ত হয়েছে!', 'success');
};

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    loadGlobalCart();
    setupSheetListeners();
    fetchLiveRestaurantStatus();
    // Live continuous sync every 2.5 seconds
    setInterval(fetchLiveRestaurantStatus, 2500);
});

async function fetchLiveRestaurantStatus() {
    try {
        const res = await fetch('/api/restaurant/status');
        const data = await res.json();
        const prevOpen = restaurantStatus.isOpen;
        restaurantStatus = data;
        updateRestaurantStatusUI();
        if (prevOpen !== undefined && prevOpen !== data.isOpen) {
            showToast(data.isOpen ? '🟢 হোটেল এখন খোলা হয়েছে! অর্ডার দিতে পারবেন।' : '🔴 হোটেল এখন সাময়িকভাবে বন্ধ করা হয়েছে।', data.isOpen ? 'success' : 'error');
        }
    } catch(e) {}
}

function updateRestaurantStatusUI() {
    const chips = document.querySelectorAll('.loc-status-chip');
    chips.forEach(chip => {
        if (!restaurantStatus.isOpen) {
            chip.innerHTML = '<span style="width:6px;height:6px;background:#ef4444;border-radius:50%;display:inline-block;"></span> <span>বন্ধ</span>';
            chip.style.background = 'rgba(239, 68, 68, 0.15)';
            chip.style.borderColor = 'rgba(239, 68, 68, 0.3)';
            chip.style.color = '#f87171';
        } else {
            chip.innerHTML = '<span class="pulse-dot-green"></span> <span>খোলা</span>';
            chip.style.background = 'var(--green-light)';
            chip.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            chip.style.color = '#4ade80';
        }
    });

    // Top Warning Bar if closed
    let closedBanner = document.getElementById('hotelClosedTopBanner');
    if (!restaurantStatus.isOpen) {
        if (!closedBanner) {
            closedBanner = document.createElement('div');
            closedBanner.id = 'hotelClosedTopBanner';
            closedBanner.style.cssText = 'background:#ef4444;color:#fff;text-align:center;padding:0.4rem 1rem;font-size:0.78rem;font-weight:800;position:sticky;top:60px;z-index:90;letter-spacing:0.3px;box-shadow:0 4px 12px rgba(239,68,68,0.3);';
            closedBanner.innerHTML = '⚠️ বর্তমানে হোটেল সাময়িকভাবে বন্ধ আছে। কিছুক্ষণের মধ্যে পুনরায় খোলা হবে।';
            document.body.insertBefore(closedBanner, document.querySelector('main'));
        } else {
            closedBanner.style.display = 'block';
        }
    } else if (closedBanner) {
        closedBanner.style.display = 'none';
    }

    const sheetBtn = document.getElementById('sheetOrderBtn');
    const sheetErr = document.getElementById('sheetOrderError');
    if (sheetBtn) {
        if (!restaurantStatus.isOpen) {
            sheetBtn.disabled = true;
            sheetBtn.style.opacity = '0.6';
            sheetBtn.style.cursor = 'not-allowed';
            sheetBtn.innerHTML = '⚠️ হোটেল এখন সাময়িকভাবে বন্ধ আছে';
            if (sheetErr) {
                sheetErr.textContent = 'সম্মানিত গ্রাহক, বর্তমানে নতুন অর্ডার গ্রহণ বন্ধ রয়েছে।';
                sheetErr.classList.remove('hidden');
            }
        } else {
            sheetBtn.disabled = false;
            sheetBtn.style.opacity = '1';
            sheetBtn.style.cursor = 'pointer';
            sheetBtn.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> <span>অর্ডার কনফার্ম করুন (ক্যাশ অন ডেলিভারি)</span>';
            if (sheetErr) {
                sheetErr.classList.add('hidden');
            }
        }
    }
}

// Load cart from localStorage
function loadGlobalCart() {
    try {
        const saved = localStorage.getItem('mamun_cart');
        if (saved) {
            globalCart = JSON.parse(saved);
        } else {
            globalCart = [];
        }
    } catch(e) {
        globalCart = [];
    }
    syncCartUI();
}

// Save cart to localStorage and update all UI components
function saveGlobalCart() {
    try {
        localStorage.setItem('mamun_cart', JSON.stringify(globalCart));
    } catch(e) {}
    syncCartUI();
}

// Add item to cart
function addToAppCart(id, name, price, image, category = '') {
    const existing = globalCart.find(i => String(i.id) === String(id));
    if (existing) {
        existing.qty += 1;
    } else {
        globalCart.push({
            id: String(id),
            name: name,
            price: parseFloat(price) || 0,
            image: image || '/images/logo.jpg',
            category: category,
            qty: 1
        });
    }
    saveGlobalCart();
    showToast(`✓ ${name} কার্টে যোগ হয়েছে!`, 'success');

    // Trigger local page re-render if available
    if (typeof renderOrderGrid === 'function') renderOrderGrid();
    if (typeof renderMenuGrid === 'function') renderMenuGrid();
    if (typeof updateHomeFoodCards === 'function') updateHomeFoodCards();
}

// Change quantity of item
function changeAppQty(id, delta) {
    const item = globalCart.find(i => String(i.id) === String(id));
    if (!item) return;

    item.qty += delta;
    if (item.qty <= 0) {
        globalCart = globalCart.filter(i => String(i.id) !== String(id));
        showToast(`আইটেম কার্ট থেকে সরানো হয়েছে`, 'error');
    }
    saveGlobalCart();

    // Re-render local page and bottom sheet
    if (typeof renderOrderGrid === 'function') renderOrderGrid();
    if (typeof renderMenuGrid === 'function') renderMenuGrid();
    if (typeof updateHomeFoodCards === 'function') updateHomeFoodCards();
    renderSheetCart();
}

// Synchronize all Cart Badges & Floating Pill
function syncCartUI() {
    const totalCount = globalCart.reduce((sum, i) => sum + (i.qty || 1), 0);
    const totalPrice = globalCart.reduce((sum, i) => sum + ((parseFloat(i.price) || 0) * (i.qty || 1)), 0);

    // Top Bar Badge
    const topBadge = document.getElementById('topbarCartBadge');
    if (topBadge) {
        topBadge.textContent = totalCount;
        topBadge.style.display = totalCount > 0 ? 'flex' : 'none';
    }

    // Bottom Navigation Badge
    const btmBadge = document.getElementById('bottomNavCartBadge');
    if (btmBadge) {
        btmBadge.textContent = totalCount;
        btmBadge.style.display = totalCount > 0 ? 'flex' : 'none';
    }

    // Floating Cart Pill
    const floatingPill = document.getElementById('floatingCartPill');
    const pillCount = document.getElementById('pillCartCount');
    const pillTotal = document.getElementById('pillCartTotal');

    if (floatingPill) {
        if (totalCount > 0) {
            floatingPill.classList.remove('hidden');
            if (pillCount) pillCount.textContent = `${totalCount} টি আইটেম`;
            if (pillTotal) pillTotal.textContent = `৳${totalPrice}`;
        } else {
            floatingPill.classList.add('hidden');
        }
    }
}

// ═══════════════════════════════════════════════════════════
// SLIDE-UP CART BOTTOM SHEET HANDLERS
// ═══════════════════════════════════════════════════════════
function openCartSheet() {
    renderSheetCart();
    const sheet = document.getElementById('appCartSheet');
    const overlay = document.getElementById('appSheetOverlay');
    if (sheet) sheet.classList.add('open');
    if (overlay) overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeCartSheet() {
    const sheet = document.getElementById('appCartSheet');
    const overlay = document.getElementById('appSheetOverlay');
    if (sheet) sheet.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
    document.body.style.overflow = '';
}

function setupSheetListeners() {
    const overlay = document.getElementById('appSheetOverlay');
    if (overlay) {
        overlay.addEventListener('click', closeCartSheet);
    }
}

function renderSheetCart() {
    const list = document.getElementById('sheetCartList');
    const emptyState = document.getElementById('sheetCartEmpty');
    const fullState = document.getElementById('sheetCartContent');
    const subtotalEl = document.getElementById('sheetSubtotal');
    const totalEl = document.getElementById('sheetTotal');
    const countBadge = document.getElementById('sheetHeaderCount');

    if (!list) return;

    const totalCount = globalCart.reduce((sum, i) => sum + (i.qty || 1), 0);
    const totalPrice = globalCart.reduce((sum, i) => sum + ((parseFloat(i.price) || 0) * (i.qty || 1)), 0);

    if (countBadge) countBadge.textContent = `${totalCount} টি পদ`;

    if (globalCart.length === 0) {
        if (emptyState) emptyState.classList.remove('hidden');
        if (fullState) fullState.classList.add('hidden');
        return;
    }

    if (emptyState) emptyState.classList.add('hidden');
    if (fullState) fullState.classList.remove('hidden');

    list.innerHTML = globalCart.map(item => `
        <div class="sheet-cart-item">
            <div class="sheet-cart-item-info">
                <img src="${item.image || '/images/logo.jpg'}" alt="${item.name}" class="sheet-item-img">
                <div>
                    <h4 class="sheet-item-name">${item.name}</h4>
                    <p class="sheet-item-price">৳${item.price} × ${item.qty} = ৳${(item.price * item.qty)}</p>
                </div>
            </div>
            <div class="sheet-stepper">
                <button type="button" class="stepper-btn ${item.qty === 1 ? 'danger' : ''}" onclick="changeAppQty('${item.id}', -1)">
                    ${item.qty === 1 ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>' : '−'}
                </button>
                <span class="stepper-val">${item.qty}</span>
                <button type="button" class="stepper-btn" onclick="changeAppQty('${item.id}', 1)">+</button>
            </div>
        </div>
    `).join('');

    if (subtotalEl) subtotalEl.textContent = `৳${totalPrice}`;
    if (totalEl) totalEl.textContent = `৳${totalPrice}`;
}

// ═══════════════════════════════════════════════════════════
// GPS LIVE LOCATION AUTOFILL
// ═══════════════════════════════════════════════════════════
function getLiveAppLocation(targetInputId = 'sheetAddress', statusId = 'gpsSheetStatus') {
    const status = document.getElementById(statusId);
    const input = document.getElementById(targetInputId);

    if (!navigator.geolocation) {
        if (status) {
            status.textContent = '❌ আপনার ব্রাউজারে GPS সাপোর্ট নেই।';
            status.style.display = 'block';
        }
        return;
    }

    if (status) {
        status.textContent = '📡 লাইভ লোকেশন শনাক্ত করা হচ্ছে...';
        status.style.display = 'block';
        status.style.color = '#60a5fa';
    }

    navigator.geolocation.getCurrentPosition(
        async (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            try {
                const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=bn`);
                const data = await res.json();
                const address = data.display_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`;
                if (input) {
                    input.value = address;
                    input.focus();
                }
                if (status) {
                    status.textContent = '✓ লোকেশন সফলভাবে যোগ করা হয়েছে!';
                    status.style.color = '#4ade80';
                }
            } catch(e) {
                if (input) input.value = `GPS: ${lat.toFixed(5)}, ${lng.toFixed(5)} (সাতক্ষীরা)`;
                if (status) {
                    status.textContent = '✓ GPS স্থানাঙ্ক যোগ করা হয়েছে!';
                    status.style.color = '#4ade80';
                }
            }
        },
        () => {
            if (status) {
                status.textContent = '⚠️ লোকেশন পারমিশন দিন বা হাত দিয়ে ঠিকানা লিখুন।';
                status.style.color = '#f59e0b';
            }
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
    );
}

// ═══════════════════════════════════════════════════════════
// DIRECT ORDER PLACEMENT (FROM SHEET OR CHECKOUT)
// ═══════════════════════════════════════════════════════════
async function placeSheetOrder() {
    if (globalCart.length === 0) {
        showToast('আপনার কার্ট খালি!', 'error');
        return;
    }

    const name = (document.getElementById('sheetName')?.value || '').trim();
    const phone = (document.getElementById('sheetPhone')?.value || '').trim();
    const area = (document.getElementById('sheetArea')?.value || '').trim();
    const address = (document.getElementById('sheetAddress')?.value || '').trim();
    const note = (document.getElementById('sheetNote')?.value || '').trim();
    const btn = document.getElementById('sheetOrderBtn');
    const errEl = document.getElementById('sheetOrderError');

    if (!name) {
        if (errEl) { errEl.textContent = 'দয়া করে আপনার নাম লিখুন।'; errEl.classList.remove('hidden'); }
        showToast('আপনার নাম লিখুন', 'error');
        return;
    }

    if (!phone || phone.length < 11) {
        if (errEl) { errEl.textContent = 'সঠিক ১১ ডিজিটের মোবাইল নম্বর দিন।'; errEl.classList.remove('hidden'); }
        showToast('সঠিক মোবাইল নম্বর দিন', 'error');
        return;
    }

    if (!address) {
        if (errEl) { errEl.textContent = 'ডেলিভারি ঠিকানা দিন।'; errEl.classList.remove('hidden'); }
        showToast('ঠিকানা দিন', 'error');
        return;
    }

    if (errEl) errEl.classList.add('hidden');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="pulse-dot-green"></span> অর্ডার প্লেস হচ্ছে...';
    }

    const fullAddress = area ? `${area}, ${address}` : address;

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                customerName: name,
                phoneNumber: phone,
                address: fullAddress,
                note: note,
                items: globalCart
            })
        });

        const data = await res.json();
        if (data.success && data.order) {
            // Clear cart
            globalCart = [];
            saveGlobalCart();
            closeCartSheet();

            const orderId = data.order.shortId || data.order.id;

            // Broadcast new order event immediately for live admin sync
            if (mamunSyncBus) {
                mamunSyncBus.postMessage({ action: 'ORDER_PLACED', orderId: orderId });
            }
            try {
                localStorage.setItem('mamun_sync_event', 'ORDER_PLACED_' + Date.now());
            } catch(e) {}

            // Redirect to track page with order ID
            window.location.href = `/track?id=${encodeURIComponent(orderId)}`;
        } else {
            throw new Error(data.error || 'অর্ডার করতে সমস্যা হয়েছে।');
        }
    } catch(e) {
        if (errEl) {
            errEl.textContent = e.message || 'অর্ডার পাঠাতে সমস্যা হয়েছে। আবার চেষ্টা করুন।';
            errEl.classList.remove('hidden');
        }
        showToast(e.message || 'সমস্যা হয়েছে', 'error');
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = 'অর্ডার কনফার্ম করুন (ক্যাশ অন ডেলিভারি)';
        }
    }
}

// ═══════════════════════════════════════════════════════════
// TOAST NOTIFICATION SYSTEM
// ═══════════════════════════════════════════════════════════
function showToast(message, type = 'success') {
    let container = document.getElementById('appToastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'appToastContainer';
        container.className = 'app-toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `app-toast ${type}`;
    toast.innerHTML = `
        <div style="width:8px;height:8px;border-radius:50%;background:${type === 'success' ? '#22c55e' : '#ef4444'};"></div>
        <span>${message}</span>
    `;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        setTimeout(() => toast.remove(), 300);
    }, 2400);
}
