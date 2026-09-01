let currentTab = 'overview';
let lastOrderCount = null;
let deferredPrompt = null;

let isSoundEnabled = localStorage.getItem('mamun_admin_sound') !== 'false';

let dataStore = {
    menu: [],
    categories: [],
    orders: [],
    employees: [],
    dues: [],
    ledger: [],
    stock: [],
    hotelStatus: { isOpen: true, statusMessage: 'খোলা আছে' }
};

// ── PWA & Service Worker Setup ──
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW registration error:', err));
    });
}

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    const headerBtn = document.getElementById('pwaInstallHeaderBtn');
    const sidebarBtn = document.getElementById('pwaInstallSidebarBtn');
    if (headerBtn) headerBtn.style.display = 'inline-flex';
    if (sidebarBtn) sidebarBtn.style.display = 'flex';
});

function promptInstallApp() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User installed the Admin App');
            }
            deferredPrompt = null;
        });
    } else {
        alert('📲 আপনার ফোনের ব্রাউজার মেনু (থ্রি-ডট ⋮) থেকে "Add to Home screen" বা "Install app" এ চাপ দিয়ে অ্যাপ হিসেবে হোম স্ক্রিনে যোগ করে নিন!');
    }
}

// ── Hotel Operations & Live Status Control ──
async function fetchHotelStatus() {
    try {
        const res = await fetch('/api/restaurant/status');
        const data = await res.json();
        dataStore.hotelStatus = data;
        updateHotelStatusHeader();
    } catch(e) {}
}

async function toggleHotelStatus() {
    const nextState = !dataStore.hotelStatus.isOpen;
    const confirmMsg = nextState 
        ? 'আপনি কি হোটেল চালু করতে চান? গ্রাহকরা এখন অনলাইনে খাবার অর্ডার করতে পারবেন।' 
        : 'আপনি কি হোটেল সাময়িকভাবে বন্ধ করতে চান? নতুন অনলাইন অর্ডার গ্রহণ স্থগিত থাকবে।';

    if (!confirm(confirmMsg)) return;

    dataStore.hotelStatus = { isOpen: nextState, statusMessage: nextState ? 'খোলা আছে' : 'সাময়িকভাবে বন্ধ' };
    updateHotelStatusHeader();
    renderCurrentTab();

    await executeOrQueueApi(
        'TOGGLE_STATUS',
        '/api/admin/restaurant/status',
        'POST',
        { isOpen: nextState },
        () => {}
    );

    if (mamunSyncBus) mamunSyncBus.postMessage({ action: 'HOTEL_STATUS_TOGGLED', isOpen: nextState });
    try { localStorage.setItem('mamun_sync_event', 'HOTEL_STATUS_TOGGLED_' + Date.now()); } catch(e) {}
    alert(nextState ? '✓ হোটেল সফলভাবে চালু করা হয়েছে!' : '✓ হোটেল সফলভাবে বন্ধ করা হয়েছে!');
}

function updateHotelStatusHeader() {
    const btn = document.getElementById('hotelStatusToggleBtn');
    const dot = document.getElementById('hotelStatusDot');
    const text = document.getElementById('hotelStatusText');
    if (!btn || !dot || !text) return;

    if (dataStore.hotelStatus.isOpen) {
        btn.className = 'owner-status-pill open';
        dot.style.background = '#22c55e';
        text.innerText = 'খোলা';
    } else {
        btn.className = 'owner-status-pill closed';
        dot.style.background = '#ef4444';
        text.innerText = 'বন্ধ';
    }
}

// ── Sound Alert Toggle ──
function toggleSoundAlert() {
    isSoundEnabled = !isSoundEnabled;
    localStorage.setItem('mamun_admin_sound', isSoundEnabled);
    updateSoundButton();
    if (isSoundEnabled) {
        playOrderChime();
    }
}

function updateSoundButton() {
    const icon = document.getElementById('soundIcon');
    const btn = document.getElementById('soundToggleBtn');
    if (!icon || !btn) return;
    if (isSoundEnabled) {
        icon.innerText = '🔊';
        btn.title = 'সাউন্ড চালু';
        btn.style.borderColor = 'rgba(234, 179, 8, 0.4)';
    } else {
        icon.innerText = '🔇';
        btn.title = 'সাউন্ড বন্ধ';
        btn.style.borderColor = 'var(--card-border)';
    }
}

// ── Audio Chime for New Orders ──
function playOrderChime() {
    if (!isSoundEnabled) return;
    try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        
        const now = ctx.currentTime;
        // Tone 1: 587.33 Hz (D5)
        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.type = 'triangle';
        osc1.frequency.setValueAtTime(587.33, now);
        gain1.gain.setValueAtTime(0.3, now);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.35);
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        osc1.start(now);
        osc1.stop(now + 0.35);

        // Tone 2: 880 Hz (A5)
        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(880, now + 0.2);
        gain2.gain.setValueAtTime(0.4, now + 0.2);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.7);
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        osc2.start(now + 0.2);
        osc2.stop(now + 0.7);
    } catch(e) {
        console.warn('Audio alert error:', e);
    }
}

// ── Notification Toast ──
function showNewOrderToast(order) {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99999;
        background: linear-gradient(135deg, #181822 0%, #15151c 100%);
        border: 2px solid #eab308;
        box-shadow: 0 20px 40px rgba(0,0,0,0.8), 0 0 25px rgba(234,179,8,0.3);
        border-radius: 1rem;
        padding: 1.1rem 1.4rem;
        color: #fff;
        max-width: 360px;
        animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: flex-start;
        gap: 12px;
    `;

    toast.innerHTML = `
        <div style="width:38px;height:38px;border-radius:10px;background:rgba(234,179,8,0.2);display:flex;align-items:center;justify-content:center;color:#facc15;font-size:1.3rem;flex-shrink:0;">
            🔔
        </div>
        <div style="flex:1;">
            <div style="font-weight:800;font-size:0.95rem;color:#facc15;margin-bottom:2px;">🚨 নতুন অর্ডার এসেছে!</div>
            <div style="font-size:0.85rem;color:#fff;font-weight:700;">${order.customerName || 'কাস্টমার'} (${order.phoneNumber || ''})</div>
            <div style="font-size:0.78rem;color:#a1a1aa;margin-top:2px;">মোট: ৳${order.totalAmount || 0}</div>
            <div style="margin-top:8px;display:flex;gap:8px;">
                <button onclick="switchTab('orders', document.querySelectorAll('.admin-nav-item')[2]);this.parentElement.parentElement.parentElement.remove();" style="background:#eab308;color:#000;border:none;padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:800;cursor:pointer;">অর্ডার দেখুন</button>
                <button onclick="this.parentElement.parentElement.parentElement.remove();" style="background:rgba(255,255,255,0.1);color:#a1a1aa;border:none;padding:4px 8px;border-radius:6px;font-size:0.75rem;cursor:pointer;">বন্ধ করুন</button>
            </div>
        </div>
    `;

    document.body.appendChild(toast);
    setTimeout(() => {
        if (toast.parentElement) toast.remove();
    }, 12000);
}

// ── OFFLINE STORAGE & AUTO-SYNC ENGINE ──
let isCurrentlyOffline = !navigator.onLine;

function getOfflineQueue() {
    try {
        const saved = localStorage.getItem('mamun_offline_queue');
        return saved ? JSON.parse(saved) : [];
    } catch(e) {
        return [];
    }
}

function saveOfflineQueue(queue) {
    try {
        localStorage.setItem('mamun_offline_queue', JSON.stringify(queue));
    } catch(e) {}
}

function enqueueOfflineAction(action) {
    const queue = getOfflineQueue();
    queue.push({
        id: 'act_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5),
        timestamp: Date.now(),
        ...action
    });
    saveOfflineQueue(queue);
    updateOfflineBanner();
}

function updateOfflineBanner() {
    const banner = document.getElementById('adminOfflineBanner');
    const queue = getOfflineQueue();
    if (!banner) return;
    if (isCurrentlyOffline || queue.length > 0) {
        banner.style.display = 'block';
        banner.innerHTML = isCurrentlyOffline 
            ? `⚡ অফলাইন মোড সক্রিয় ${queue.length > 0 ? `(${queue.length} টি হিসাব পেন্ডিং)` : ''} — ওয়াইফাই পেলেই স্বয়ংক্রিয় সিঙ্ক হবে।`
            : `🔄 ওয়াইফাই সংযুক্ত — ${queue.length} টি অফলাইন হিসাব সার্ভারে সিঙ্ক হচ্ছে...`;
    } else {
        banner.style.display = 'none';
    }
}

function saveCachedDataStore() {
    try {
        localStorage.setItem('mamun_cached_datastore', JSON.stringify(dataStore));
    } catch(e) {}
}

function loadCachedDataStore() {
    try {
        const cached = localStorage.getItem('mamun_cached_datastore');
        if (cached) {
            const parsed = JSON.parse(cached);
            if (parsed && typeof parsed === 'object') {
                if (Array.isArray(parsed.orders)) dataStore.orders = parsed.orders;
                if (Array.isArray(parsed.menu)) dataStore.menu = parsed.menu;
                if (Array.isArray(parsed.categories)) dataStore.categories = parsed.categories;
                if (Array.isArray(parsed.employees)) dataStore.employees = parsed.employees;
                if (Array.isArray(parsed.dues)) dataStore.dues = parsed.dues;
                if (Array.isArray(parsed.ledger)) dataStore.ledger = parsed.ledger;
                if (Array.isArray(parsed.stock)) dataStore.stock = parsed.stock;
                if (parsed.hotelStatus) dataStore.hotelStatus = parsed.hotelStatus;
            }
        }
    } catch(e) {}
}

async function executeOrQueueApi(type, url, method, body, optimisticUpdateFn) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // If offline, queue directly
    if (!navigator.onLine || isCurrentlyOffline) {
        enqueueOfflineAction({ type, url, method, body });
        if (optimisticUpdateFn) optimisticUpdateFn();
        saveCachedDataStore();
        renderCurrentTab();
        showToast('💾 অফলাইনে সংরক্ষিত হয়েছে। ওয়াইফাই পেলেই স্বয়ংক্রিয়ভাবে সিঙ্ক হবে।', 'warning');
        return { offline: true };
    }

    try {
        const res = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(body)
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const data = await res.json().catch(() => ({ success: true }));
        return data;
    } catch(err) {
        // Network failure during request: queue offline!
        isCurrentlyOffline = true;
        enqueueOfflineAction({ type, url, method, body });
        if (optimisticUpdateFn) optimisticUpdateFn();
        saveCachedDataStore();
        renderCurrentTab();
        updateOfflineBanner();
        showToast('💾 নেটওয়ার্ক না থাকায় অফলাইনে সংরক্ষিত হয়েছে। ওয়াইফাই পেলেই সিঙ্ক হবে।', 'warning');
        return { offline: true };
    }
}

let isProcessingQueue = false;
async function processOfflineSyncQueue() {
    if (isProcessingQueue) return;
    const queue = getOfflineQueue();
    if (queue.length === 0) {
        isCurrentlyOffline = false;
        updateOfflineBanner();
        return;
    }

    isProcessingQueue = true;
    updateOfflineBanner();
    showToast(`🔄 ওয়াইফাই সংযুক্ত হয়েছে! অফলাইনের ${queue.length} টি হিসাব সিঙ্ক হচ্ছে...`, 'info');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const remaining = [];

    for (const item of queue) {
        try {
            const res = await fetch(item.url, {
                method: item.method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(item.body)
            });
            if (!res.ok) throw new Error('Sync error ' + res.status);
        } catch(e) {
            remaining.push(item);
        }
    }

    saveOfflineQueue(remaining);
    isProcessingQueue = false;

    if (remaining.length === 0) {
        isCurrentlyOffline = false;
        showToast('✓ সকল অফলাইন তথ্য ও হিসাব সফলভাবে সিঙ্ক সম্পন্ন হয়েছে!', 'success');
        updateOfflineBanner();
        if (mamunSyncBus) mamunSyncBus.postMessage({ action: 'OFFLINE_SYNC_COMPLETED' });
    } else {
        updateOfflineBanner();
    }

    await fetchAllData();
}

window.addEventListener('online', () => {
    isCurrentlyOffline = false;
    processOfflineSyncQueue();
});

window.addEventListener('offline', () => {
    isCurrentlyOffline = true;
    updateOfflineBanner();
    showToast('⚠️ ইন্টারনেট সংযোগ বিচ্ছিন্ন — অফলাইন মোড সক্রিয়!', 'warning');
});

window.onNetworkRestored = () => {
    isCurrentlyOffline = false;
    processOfflineSyncQueue();
};

const mamunSyncBus = (typeof BroadcastChannel !== 'undefined') ? new BroadcastChannel('mamun_sync_bus') : null;
if (mamunSyncBus) {
    mamunSyncBus.onmessage = (event) => {
        if (event.data && (event.data.action === 'ORDER_PLACED' || event.data.action === 'SETTINGS_CHANGED' || event.data.action === 'OFFLINE_SYNC_COMPLETED')) {
            fetchAllData();
        }
    };
}
window.addEventListener('storage', (e) => {
    if (e.key === 'mamun_sync_event') {
        fetchAllData();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    loadCachedDataStore();
    renderCurrentTab();
    updateSoundButton();
    updateOfflineBanner();
    fetchAllData();
    // High-speed real-time polling every 2.5 seconds for instant order sync
    setInterval(fetchAllData, 2500);
});

async function fetchAllData() {
    try {
        const [menuRes, catRes, orderRes, empRes, dueRes, ledgRes, stockRes, statusRes] = await Promise.all([
            fetch('/api/menu/items').then(r => r.json()),
            fetch('/api/menu/categories').then(r => r.json()),
            fetch('/api/orders').then(r => r.json()),
            fetch('/api/admin/employees').then(r => r.json()),
            fetch('/api/admin/customer-dues').then(r => r.json()),
            fetch('/api/ledger').then(r => r.json()),
            fetch('/api/stock').then(r => r.json()),
            fetch('/api/restaurant/status').then(r => r.json())
        ]);

        isCurrentlyOffline = false;
        updateOfflineBanner();

        if (statusRes && typeof statusRes.isOpen !== 'undefined') {
            dataStore.hotelStatus = statusRes;
            updateHotelStatusHeader();
        }

        const prevOrderCount = dataStore.orders.length;
        const newOrders = Array.isArray(orderRes) ? orderRes : [];

        if (lastOrderCount !== null && newOrders.length > lastOrderCount) {
            playOrderChime();
            const latest = newOrders[0] || {};
            showNewOrderToast(latest);
        }
        lastOrderCount = newOrders.length;

        dataStore.menu = Array.isArray(menuRes) ? menuRes : [];
        dataStore.categories = Array.isArray(catRes) ? catRes : [];
        dataStore.orders = newOrders;
        dataStore.employees = Array.isArray(empRes) ? empRes : [];
        dataStore.dues = Array.isArray(dueRes) ? dueRes : [];
        dataStore.ledger = Array.isArray(ledgRes) ? ledgRes : [];
        dataStore.stock = Array.isArray(stockRes) ? stockRes : [];

        saveCachedDataStore();
        updatePendingBadge();
        renderCurrentTab();

        // Process any queued items
        if (getOfflineQueue().length > 0) {
            processOfflineSyncQueue();
        }
    } catch(e) {
        console.warn('Working in offline/cached mode:', e.message);
        isCurrentlyOffline = true;
        loadCachedDataStore();
        updateOfflineBanner();
        updatePendingBadge();
        renderCurrentTab();
    }
}

function updatePendingBadge() {
    const pending = dataStore.orders.filter(o => (o.status || '').toUpperCase() === 'PENDING').length;
    const badge = document.getElementById('pendingBadge');
    if (badge) {
        if (pending > 0) {
            badge.innerText = pending;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }
}

function switchTab(tab, el) {
    currentTab = tab;
    document.querySelectorAll('.owner-nav-tab').forEach(btn => btn.classList.remove('active'));
    if (el) {
        el.classList.add('active');
    } else {
        const tabs = ['overview', 'orders', 'menu', 'employees', 'ledger'];
        const idx = tabs.indexOf(tab);
        const all = document.querySelectorAll('.owner-nav-tab');
        if (idx !== -1 && all[idx]) all[idx].classList.add('active');
    }
    renderCurrentTab();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function renderCurrentTab() {
    const content = document.getElementById('tabContent');
    if (!content) return;

    try {
        if (currentTab === 'overview') renderOverview(content);
        else if (currentTab === 'menu') renderMenuTab(content);
        else if (currentTab === 'orders') renderOrdersTab(content);
        else if (currentTab === 'employees') renderEmployeesTab(content);
        else if (currentTab === 'dues') renderDuesTab(content);
        else if (currentTab === 'ledger') renderLedgerTab(content);
        else if (currentTab === 'stock') renderStockTab(content);
    } catch(err) {
        console.error('Error rendering tab:', err);
        content.innerHTML = `<div style="padding:2rem;color:#f87171;">Render Error: ${err.message}</div>`;
    }
}

// ── 1. Overview Tab (ওনার কমান্ড সেন্টার) ──
function renderOverview(container) {
    const pending = dataStore.orders.filter(o => (o.status || '').toUpperCase() === 'PENDING').length;
    const totalSalaryDue = dataStore.employees.reduce((acc, e) => acc + (parseFloat(e.salaryDue) || 0), 0);
    const totalCustomerDue = dataStore.dues.reduce((acc, d) => acc + ((parseFloat(d.totalDue) || 0) - (parseFloat(d.paidAmount) || 0)), 0);
    
    // Calculate total online sales
    const totalSales = dataStore.orders.reduce((acc, o) => {
        const amt = (parseFloat(o.totalAmount) > 0) ? parseFloat(o.totalAmount) : (o.items || []).reduce((sum, i) => sum + ((i.price || 0) * (i.quantity || i.qty || 1)), 0);
        return acc + amt;
    }, 0);

    const totalIncome = dataStore.ledger.filter(l => l.type === 'INCOME' || l.type === 'DEPOSIT').reduce((acc, l) => acc + (parseFloat(l.amount) || 0), 0);
    const totalExpense = dataStore.ledger.filter(l => l.type === 'EXPENSE' || l.type === 'WITHDRAW').reduce((acc, l) => acc + (parseFloat(l.amount) || 0), 0);
    const netBalance = totalIncome - totalExpense;

    const lowStockItems = dataStore.stock.filter(s => parseFloat(s.quantity) <= parseFloat(s.minQuantity));
    const activeDishes = dataStore.menu.filter(m => (m.isAvailable === true || m.is_available === true || m.isAvailable === 1 || m.is_available === 1)).length;

    container.innerHTML = `
        <!-- Executive Hero Banner -->
        <div class="owner-hero-card">
            <div class="owner-hero-top">
                <span class="owner-badge-tag">
                    <span style="width:6px;height:6px;border-radius:50%;background:#eab308;animation:badgeBeat 1.5s infinite;"></span>
                    <span>স্বত্বাধিকারী কমান্ড সেন্টার • লাইভ</span>
                </span>
                <span style="font-size:0.75rem;color:#94a3b8;font-family:'Outfit',sans-serif;">${new Date().toLocaleDateString('bn-BD', { weekday: 'short', month: 'short', day: 'numeric' })}</span>
            </div>
            <h2 class="owner-greeting-title">নজরুল ইসলাম 👑</h2>
            <p class="owner-greeting-sub">শ্যামনগর নজরুল হোটেল — লাইভ কিচেন, সেলস ও স্টক রাডার সক্রিয়।</p>
        </div>

        <!-- Master Operations & Hotel Open/Closed Control Card -->
        <div style="background:#15151f;border:1.5px solid ${dataStore.hotelStatus.isOpen ? 'rgba(16,185,129,0.35)' : 'rgba(239,68,68,0.35)'};border-radius:1.35rem;padding:1.25rem;margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;gap:12px;box-shadow:0 10px 25px rgba(0,0,0,0.5);">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:48px;height:48px;border-radius:14px;background:${dataStore.hotelStatus.isOpen ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)'};display:flex;align-items:center;justify-content:center;font-size:1.6rem;">
                    ${dataStore.hotelStatus.isOpen ? '🟢' : '🔴'}
                </div>
                <div>
                    <h4 style="font-size:1.05rem;font-weight:900;color:#fff;margin:0;">
                        হোটেল: <span style="color:${dataStore.hotelStatus.isOpen ? '#4ade80' : '#f87171'};">${dataStore.hotelStatus.isOpen ? 'এখন খোলা (Orders ON)' : 'এখন বন্ধ (Orders OFF)'}</span>
                    </h4>
                    <p style="color:#94a3b8;font-size:0.78rem;margin:2px 0 0 0;">
                        ${dataStore.hotelStatus.isOpen ? 'গ্রাহকরা অ্যাপ থেকে খাবার অর্ডার করতে পারছেন।' : 'নতুন অনলাইন অর্ডার গ্রহণ সাময়িক বন্ধ রয়েছে।'}
                    </p>
                </div>
            </div>
            <button onclick="toggleHotelStatus()" class="owner-dock-btn" style="background:${dataStore.hotelStatus.isOpen ? 'rgba(239,68,68,0.2)' : 'rgba(16,185,129,0.2)'};color:${dataStore.hotelStatus.isOpen ? '#f87171' : '#4ade80'};border-color:${dataStore.hotelStatus.isOpen ? 'rgba(239,68,68,0.4)' : 'rgba(16,185,129,0.4)'};padding:0.65rem 1rem;">
                ${dataStore.hotelStatus.isOpen ? '🔴 বন্ধ করুন' : '🟢 চালু করুন'}
            </button>
        </div>

        <!-- 4-Card Luxury KPI HUD -->
        <div class="owner-kpi-grid">
            <!-- KPI 1: Today's Sales -->
            <div class="owner-kpi-card" onclick="switchTab('orders')">
                <div>
                    <div class="owner-kpi-icon-wrap" style="background:rgba(234,179,8,0.15);color:#facc15;">💰</div>
                    <div class="owner-kpi-label">মোট বিক্রয়</div>
                </div>
                <div>
                    <div class="owner-kpi-val" style="color:#facc15;">৳${Math.round(totalSales).toLocaleString()}</div>
                    <div class="owner-kpi-sub">${dataStore.orders.length} টি অনলাইন অর্ডার</div>
                </div>
            </div>

            <!-- KPI 2: Pending Orders -->
            <div class="owner-kpi-card" onclick="switchTab('orders')" style="${pending > 0 ? 'border-color:rgba(239,68,68,0.5);background:linear-gradient(135deg,rgba(239,68,68,0.08) 0%,#121219 100%);' : ''}">
                <div>
                    <div class="owner-kpi-icon-wrap" style="background:rgba(239,68,68,0.15);color:#f87171;">🚨</div>
                    <div class="owner-kpi-label">পেন্ডিং অর্ডার</div>
                </div>
                <div>
                    <div class="owner-kpi-val" style="color:${pending > 0 ? '#f87171' : '#fff'};">${pending} <span style="font-size:0.9rem;font-weight:600;color:#94a3b8;">টি</span></div>
                    <div class="owner-kpi-sub">${pending > 0 ? '🚨 দ্রুত অ্যাকশন দিন' : 'সব ক্লিয়ার আছে'}</div>
                </div>
            </div>

            <!-- KPI 3: Active Stock Menu -->
            <div class="owner-kpi-card" onclick="switchTab('menu')">
                <div>
                    <div class="owner-kpi-icon-wrap" style="background:rgba(59,130,246,0.15);color:#60a5fa;">🍲</div>
                    <div class="owner-kpi-label">সক্রিয় মেনু পদ</div>
                </div>
                <div>
                    <div class="owner-kpi-val" style="color:#60a5fa;">${activeDishes} / ${dataStore.menu.length}</div>
                    <div class="owner-kpi-sub">পদ বর্তমানে স্টকে আছে</div>
                </div>
            </div>

            <!-- KPI 4: Customer Dues -->
            <div class="owner-kpi-card" onclick="switchTab('ledger')">
                <div>
                    <div class="owner-kpi-icon-wrap" style="background:rgba(16,185,129,0.15);color:#4ade80;">📒</div>
                    <div class="owner-kpi-label">খদ্দেরদের বাকি</div>
                </div>
                <div>
                    <div class="owner-kpi-val" style="color:#4ade80;">৳${Math.round(totalCustomerDue).toLocaleString()}</div>
                    <div class="owner-kpi-sub">${dataStore.dues.length} জন বাকি খদ্দের</div>
                </div>
            </div>
        </div>

        <!-- Fast Action Dock Pills -->
        <div class="owner-action-dock">
            <button onclick="openAddMenuModal()" class="owner-dock-btn primary">
                <span>➕ নতুন খাবার যোগ</span>
            </button>
            <button onclick="switchTab('orders')" class="owner-dock-btn">
                <span>🛵 লাইভ অর্ডার রাডার</span>
            </button>
            <button onclick="switchTab('employees')" class="owner-dock-btn">
                <span>👥 কর্মী খাতা</span>
            </button>
            <button onclick="openAddLedgerModal()" class="owner-dock-btn">
                <span>💵 ক্যাশ খরচ লিখুন</span>
            </button>
            <button onclick="openAddDueModal()" class="owner-dock-btn">
                <span>📝 বাকি খাতায় যোগ</span>
            </button>
        </div>

        <!-- Live Orders Stream Section -->
        <div class="owner-section-head">
            <h3 class="owner-section-title">
                <span>🛵 সাম্প্রতিক লাইভ অর্ডারসমূহ</span>
            </h3>
            <button class="owner-section-link" onclick="switchTab('orders')">সব দেখুন (${dataStore.orders.length}) ↗</button>
        </div>

        <div>
            ${dataStore.orders.slice(0, 4).map(o => renderOwnerOrderCardHtml(o)).join('')}
            ${dataStore.orders.length === 0 ? '<div style="text-align:center;padding:2.5rem;color:#64748b;background:#121219;border-radius:1.25rem;">এখনও কোনো অনলাইন অর্ডার আসেনি।</div>' : ''}
        </div>
    `;
}

// ── Helpers: Food Images & Address Formatter ──
const adminFoodImages = {
    'গরু': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=400&q=80',
    'কালাভুনা': 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=400&q=80',
    'নেহারী': 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=400&q=80',
    'হাঁস': 'https://images.unsplash.com/photo-1514944298352-7b0078907869?auto=format&fit=crop&w=400&q=80',
    'মুরগী': 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?auto=format&fit=crop&w=400&q=80',
    'মাছ': 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=400&q=80',
    'ইলিশ': 'https://images.unsplash.com/photo-1534939561126-855b8675edd7?auto=format&fit=crop&w=400&q=80',
    'রুই': 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=400&q=80',
    'ভাত': 'https://images.unsplash.com/photo-1516684732162-798a0062be99?auto=format&fit=crop&w=400&q=80',
    'চা': 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=400&q=80'
};

function getAdminDishImage(item) {
    if (!item) return 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=400&q=80';
    if (item.image && typeof item.image === 'string' && item.image.trim() !== '') return item.image.trim();
    const name = item.name || '';
    for (const [kw, url] of Object.entries(adminFoodImages)) {
        if (name.includes(kw)) return url;
    }
    return 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=400&q=80';
}

function formatAdminAddress(rawAddr) {
    if (!rawAddr) return '<span style="color:#64748b;">ঠিকানা দেওয়া নেই</span>';
    let text = String(rawAddr).trim();
    
    // Extract map URL if embedded
    const mapRegex = /(?:\[(?:ম্যাপ|ম্যাপ লিংক|Google Maps Pin|Google Maps|GPS)\s*:\s*)?(https:\/\/maps\.google\.com\/\?q=[^\]\s\n]+)\]?/i;
    const match = text.match(mapRegex);
    let mapUrl = match ? match[1] : null;
    
    // Clean text by stripping map url and bracketed part
    let cleanText = text.replace(/\[.*?(https:\/\/maps\.google\.com[^\s\]]+).*?\]/gi, '')
                        .replace(/https:\/\/maps\.google\.com\/\?q=[^\s]+/gi, '')
                        .replace(/\s+/g, ' ')
                        .replace(/^[-,\s]+|[-,\s]+$/g, '');
    
    if (!cleanText) cleanText = 'সাতক্ষীরা সদর';

    return `
        <span>${cleanText}</span>
        ${mapUrl ? `
            <a href="${mapUrl}" target="_blank" style="display:inline-flex;align-items:center;gap:3px;background:rgba(59,130,246,0.15);color:#60a5fa;border:1px solid rgba(59,130,246,0.3);padding:2px 6px;border-radius:4px;font-size:0.7rem;font-weight:700;text-decoration:none;margin-left:6px;">
                📍 ম্যাপ ↗
            </a>
        ` : ''}
    `;
}

// ── Helper to render a single mobile order card ──
function renderOwnerOrderCardHtml(o) {
    const total = (parseFloat(o.totalAmount) > 0) ? parseFloat(o.totalAmount) : (o.items || []).reduce((acc, i) => acc + ((i.price || 0) * (i.quantity || i.qty || 1)), 0);
    const shortId = o.shortId || ('MR-' + (o.id || '').substring(0, 6).toUpperCase());
    const status = (o.status || 'PENDING').toUpperCase();
    const customerPhone = o.phoneNumber || o.phone_number || '';
    const customerName = o.customerName || o.customer_name || 'সম্মানিত খদ্দের';

    let badgeText = 'অর্ডার গৃহীত';
    let badgeBg = 'rgba(234, 179, 8, 0.18)';
    let badgeColor = '#facc15';
    let nextStageHtml = '';

    if (status === 'PENDING') {
        badgeText = '⏳ পেন্ডিং';
        badgeBg = 'rgba(234, 179, 8, 0.2)';
        badgeColor = '#facc15';
        nextStageHtml = `<button onclick="updateOrderStatus('${o.id}', 'PREPARING')" class="owner-stage-btn prep">👨‍🍳 কিচেনে পাঠান</button>`;
    } else if (status === 'PREPARING' || status === 'COOKING') {
        badgeText = '👨‍🍳 রান্না হচ্ছে';
        badgeBg = 'rgba(59, 130, 246, 0.2)';
        badgeColor = '#60a5fa';
        nextStageHtml = `<button onclick="updateOrderStatus('${o.id}', 'OUT_FOR_DELIVERY')" class="owner-stage-btn rider">🛵 রাইডারে দিন</button>`;
    } else if (status === 'OUT_FOR_DELIVERY' || status === 'DELIVERING') {
        badgeText = '🛵 ডেলিভারিতে পথে';
        badgeBg = 'rgba(245, 158, 11, 0.2)';
        badgeColor = '#fbbf24';
        nextStageHtml = `<button onclick="updateOrderStatus('${o.id}', 'DELIVERED')" class="owner-stage-btn done">✅ সম্পন্ন হয়েছে</button>`;
    } else if (status === 'DELIVERED') {
        badgeText = '✅ ডেলিভারি সম্পন্ন';
        badgeBg = 'rgba(16, 185, 129, 0.2)';
        badgeColor = '#4ade80';
    } else {
        badgeText = '❌ বাতিল';
        badgeBg = 'rgba(239, 68, 68, 0.2)';
        badgeColor = '#f87171';
    }

    const items = o.items || o.orderItems || o.order_items || [];

    return `
        <div class="owner-order-card ${status === 'PENDING' ? 'pending' : ''}">
            <div class="owner-order-header">
                <div>
                    <span class="owner-order-id">${shortId}</span>
                    <span class="owner-order-time" style="margin-left:8px;">${o.createdAt ? new Date(o.createdAt).toLocaleTimeString('bn-BD', {hour:'2-digit', minute:'2-digit'}) : ''}</span>
                </div>
                <span style="font-size:0.75rem;font-weight:800;background:${badgeBg};color:${badgeColor};padding:3px 10px;border-radius:9999px;">
                    ${badgeText}
                </span>
            </div>

            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                <div class="owner-order-customer">${customerName}</div>
                <div style="display:flex;gap:6px;">
                    ${customerPhone ? `
                        <a href="tel:${customerPhone}" class="owner-icon-btn" style="width:30px;height:30px;font-size:0.85rem;color:#4ade80;" title="কল করুন">📞</a>
                        <a href="https://wa.me/88${customerPhone.replace(/[^0-9]/g, '')}?text=${encodeURIComponent('আসসালামু আলাইকুম ' + customerName + ', নজরুল হোটেল থেকে আপনার অর্ডার ' + shortId + ' প্রস্তুত হচ্ছে।')}" target="_blank" class="owner-icon-btn" style="width:30px;height:30px;font-size:0.85rem;color:#22c55e;" title="হোয়াটসঅ্যাপ">💬</a>
                    ` : ''}
                </div>
            </div>

            <div class="owner-order-address">📍 ${formatAdminAddress(o.address)}</div>

            ${items.length > 0 ? `
                <div class="owner-order-items-box">
                    ${items.map(it => `
                        <div class="owner-order-item-row">
                            <span>${it.name || it.menu_item_name || 'খাবার'} × <strong>${it.quantity || it.qty || 1}</strong></span>
                            <span style="color:#facc15;">৳${((it.price || 0) * (it.quantity || it.qty || 1))}</span>
                        </div>
                    `).join('')}
                </div>
            ` : ''}

            <div class="owner-order-actions-bar">
                <div>
                    <span style="font-size:0.72rem;color:#94a3b8;">মোট বিল:</span>
                    <div class="owner-order-total-price">৳${total}</div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    ${nextStageHtml}
                    <button onclick="deleteOrder('${o.id}')" class="owner-icon-btn" style="width:32px;height:32px;color:#f87171;" title="মুছুন">🗑️</button>
                </div>
            </div>
        </div>
    `;
}

// ── 2. Orders Tab (লাইভ অর্ডার রাডার) ──
let orderFilterStatus = 'ALL';

function renderOrdersTab(container) {
    const filteredOrders = dataStore.orders.filter(o => {
        if (orderFilterStatus === 'ALL') return true;
        return (o.status || '').toUpperCase() === orderFilterStatus;
    });

    const pendingCount = dataStore.orders.filter(o => (o.status || '').toUpperCase() === 'PENDING').length;
    const prepCount = dataStore.orders.filter(o => (o.status || '').toUpperCase() === 'PREPARING' || (o.status || '').toUpperCase() === 'COOKING').length;
    const delivCount = dataStore.orders.filter(o => (o.status || '').toUpperCase() === 'OUT_FOR_DELIVERY' || (o.status || '').toUpperCase() === 'DELIVERING').length;
    const doneCount = dataStore.orders.filter(o => (o.status || '').toUpperCase() === 'DELIVERED').length;

    container.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div>
                <h2 style="font-size:1.25rem;font-weight:900;color:#fff;">🛵 লাইভ অর্ডার রাডার</h2>
                <p style="font-size:0.78rem;color:#94a3b8;">মোট ${dataStore.orders.length} টি অর্ডারের রিয়েল-টাইম ট্র্যাকিং</p>
            </div>
            <button onclick="fetchAllData()" class="owner-dock-btn" style="padding:0.5rem 0.85rem;font-size:0.75rem;">
                🔄 রিফ্রেশ
            </button>
        </div>

        <!-- Filter Chips -->
        <div class="owner-action-dock" style="margin-bottom:1rem;">
            <button onclick="setOrderFilter('ALL', this)" class="owner-dock-btn ${orderFilterStatus === 'ALL' ? 'primary' : ''}">সব (${dataStore.orders.length})</button>
            <button onclick="setOrderFilter('PENDING', this)" class="owner-dock-btn ${orderFilterStatus === 'PENDING' ? 'primary' : ''}">🚨 পেন্ডিং (${pendingCount})</button>
            <button onclick="setOrderFilter('PREPARING', this)" class="owner-dock-btn ${orderFilterStatus === 'PREPARING' ? 'primary' : ''}">👨‍🍳 কিচেনে (${prepCount})</button>
            <button onclick="setOrderFilter('OUT_FOR_DELIVERY', this)" class="owner-dock-btn ${orderFilterStatus === 'OUT_FOR_DELIVERY' ? 'primary' : ''}">🛵 ডেলিভারিতে (${delivCount})</button>
            <button onclick="setOrderFilter('DELIVERED', this)" class="owner-dock-btn ${orderFilterStatus === 'DELIVERED' ? 'primary' : ''}">✅ সম্পন্ন (${doneCount})</button>
        </div>

        <!-- Order Cards Stream -->
        <div>
            ${filteredOrders.map(o => renderOwnerOrderCardHtml(o)).join('')}
            ${filteredOrders.length === 0 ? '<div style="text-align:center;padding:3rem;color:#64748b;background:#121219;border-radius:1.25rem;">এই ক্যাটাগরিতে কোনো অর্ডার নেই।</div>' : ''}
        </div>
    `;
}

function setOrderFilter(status) {
    orderFilterStatus = status;
    renderCurrentTab();
}

// ── 3. Menu Tab (মোবাইল মেনু ও স্টক কার্ডস) ──
let menuSearchKeyword = '';
let menuSelectedCategory = 'ALL';

function renderMenuTab(container) {
    const categories = dataStore.categories || [];
    const filteredMenu = dataStore.menu.filter(item => {
        const matchesCat = menuSelectedCategory === 'ALL' || (item.category_id && String(item.category_id) === String(menuSelectedCategory));
        const matchesSearch = !menuSearchKeyword || (item.name || '').toLowerCase().includes(menuSearchKeyword.toLowerCase());
        return matchesCat && matchesSearch;
    });

    container.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div>
                <h2 style="font-size:1.25rem;font-weight:900;color:#fff;">🍲 মেনু ও স্টক কন্ট্রোল</h2>
                <p style="font-size:0.78rem;color:#94a3b8;">সরাসরি টগল চাপলেই আইটেম স্টকে অন/অফ হবে</p>
            </div>
            <button onclick="openAddMenuModal()" class="owner-dock-btn primary" style="padding:0.6rem 1rem;">
                ➕ নতুন খাবার
            </button>
        </div>

        <!-- Search Bar -->
        <input type="text" class="owner-input" placeholder="🔍 খাবারের নাম খুঁজুন..." value="${menuSearchKeyword}" oninput="menuSearchKeyword=this.value;renderCurrentTab();" style="margin-bottom:0.75rem;">

        <!-- Category Chips -->
        <div class="owner-action-dock" style="margin-bottom:1rem;">
            <button onclick="menuSelectedCategory='ALL';renderCurrentTab();" class="owner-dock-btn ${menuSelectedCategory === 'ALL' ? 'primary' : ''}">সব (${dataStore.menu.length})</button>
            ${categories.map(c => `
                <button onclick="menuSelectedCategory='${c.id}';renderCurrentTab();" class="owner-dock-btn ${String(menuSelectedCategory) === String(c.id) ? 'primary' : ''}">
                    ${c.name}
                </button>
            `).join('')}
        </div>

        <!-- Mobile Menu Cards -->
        <div>
            ${filteredMenu.map(item => {
                const img = getAdminDishImage(item);
                const isAvail = (item.isAvailable === true || item.is_available === true || item.isAvailable === 1 || item.is_available === 1);

                return `
                    <div class="owner-menu-card">
                        <img src="${img}" class="owner-menu-thumb" alt="${item.name}">
                        <div class="owner-menu-info">
                            <div class="owner-menu-name">${item.name}</div>
                            <div class="owner-menu-price">৳${item.price}</div>
                            <div style="display:flex;align-items:center;gap:8px;margin-top:6px;">
                                <span style="font-size:0.72rem;font-weight:800;color:${isAvail ? '#4ade80' : '#f87171'};">
                                    ${isAvail ? '🟢 স্টকে আছে' : '🔴 স্টক শেষ'}
                                </span>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                            <!-- Tactile Toggle Switch -->
                            <label class="owner-toggle-switch" title="স্টক অন / অফ করুন">
                                <input type="checkbox" ${isAvail ? 'checked' : ''} onchange="toggleMenuAvailability('${item.id}', ${isAvail})">
                                <span class="owner-slider"></span>
                            </label>
                            <div style="display:flex;gap:6px;">
                                <button onclick="openEditMenuModal('${item.id}')" class="owner-icon-btn" style="width:30px;height:30px;font-size:0.8rem;" title="এডিট">✏️</button>
                                <button onclick="deleteMenuItem('${item.id}')" class="owner-icon-btn" style="width:30px;height:30px;font-size:0.8rem;color:#f87171;" title="মুছুন">🗑️</button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('')}
            ${filteredMenu.length === 0 ? '<div style="text-align:center;padding:3rem;color:#64748b;background:#121219;border-radius:1.25rem;">কোনো মেনু আইটেম পাওয়া যায়নি।</div>' : ''}
        </div>
    `;
}

// ── 4. Employees Tab (মোবাইল কর্মী ও বেতন খাতা) ──
function renderEmployeesTab(container) {
    const totalSalaries = dataStore.employees.reduce((acc, e) => acc + (parseFloat(e.salary) || 0), 0);
    const totalPaid = dataStore.employees.reduce((acc, e) => acc + (parseFloat(e.salaryPaid) || 0), 0);
    const totalDue = dataStore.employees.reduce((acc, e) => acc + (parseFloat(e.salaryDue) || 0), 0);

    container.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div>
                <h2 style="font-size:1.25rem;font-weight:900;color:#fff;">👥 কর্মী ও বেতন খাতা</h2>
                <p style="font-size:0.78rem;color:#94a3b8;">মোট ${dataStore.employees.length} জন বাবুর্চি ও স্টাফ</p>
            </div>
            <button onclick="openAddEmployeeModal()" class="owner-dock-btn primary" style="padding:0.6rem 1rem;">
                ➕ নতুন কর্মী
            </button>
        </div>

        <!-- Summary Metric Card -->
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.5rem;margin-bottom:1rem;">
            <div class="owner-hero-card" style="padding:0.85rem;margin-bottom:0;text-align:center;">
                <span style="font-size:0.7rem;color:#94a3b8;">মোট বেতন</span>
                <div style="font-size:1.05rem;font-weight:900;color:#fff;margin-top:2px;">৳${Math.round(totalSalaries).toLocaleString()}</div>
            </div>
            <div class="owner-hero-card" style="padding:0.85rem;margin-bottom:0;text-align:center;">
                <span style="font-size:0.7rem;color:#94a3b8;">পরিশোধ</span>
                <div style="font-size:1.05rem;font-weight:900;color:#4ade80;margin-top:2px;">৳${Math.round(totalPaid).toLocaleString()}</div>
            </div>
            <div class="owner-hero-card" style="padding:0.85rem;margin-bottom:0;text-align:center;">
                <span style="font-size:0.7rem;color:#94a3b8;">বকেয়া বাকি</span>
                <div style="font-size:1.05rem;font-weight:900;color:${totalDue > 0 ? '#f87171' : '#4ade80'};margin-top:2px;">৳${Math.round(totalDue).toLocaleString()}</div>
            </div>
        </div>

        <div>
            ${dataStore.employees.map(e => `
                <div class="owner-order-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:42px;height:42px;border-radius:12px;background:rgba(234,179,8,0.15);display:flex;align-items:center;justify-content:center;font-size:1.3rem;">👨‍🍳</div>
                            <div>
                                <h4 style="font-size:1rem;font-weight:800;color:#fff;margin:0;">${e.name}</h4>
                                <span style="font-size:0.72rem;color:#eab308;font-weight:700;">${e.position || 'বাবুর্চি'}</span>
                            </div>
                        </div>
                        ${e.phone ? `<a href="tel:${e.phone}" class="owner-icon-btn" style="color:#4ade80;" title="কল করুন">📞</a>` : ''}
                    </div>

                    <!-- Salary Breakdown Pill Grid -->
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;background:#181824;padding:0.75rem 0.5rem;border-radius:0.85rem;font-size:0.78rem;margin-bottom:0.75rem;text-align:center;gap:4px;border:1px solid rgba(255,255,255,0.06);">
                        <div>
                            <span style="color:#94a3b8;display:block;font-size:0.68rem;">মূল বেতন</span>
                            <strong style="color:#fff;font-size:0.88rem;">৳${Math.round(e.salary || 0)}</strong>
                        </div>
                        <div style="border-left:1px solid rgba(255,255,255,0.08);border-right:1px solid rgba(255,255,255,0.08);">
                            <span style="color:#94a3b8;display:block;font-size:0.68rem;">পরিশোধ করা</span>
                            <strong style="color:#4ade80;font-size:0.88rem;">৳${Math.round(e.salaryPaid || 0)}</strong>
                        </div>
                        <div>
                            <span style="color:#94a3b8;display:block;font-size:0.68rem;">কর্মী পাবে</span>
                            <strong style="color:${(e.salaryDue > 0) ? '#f87171' : '#4ade80'};font-size:0.88rem;">৳${Math.round(e.salaryDue || 0)}</strong>
                        </div>
                    </div>

                    <div style="display:flex;justify-content:flex-end;align-items:center;gap:8px;">
                        <button onclick="openEditEmployeeModal('${e.id}')" class="owner-dock-btn" style="padding:0.45rem 0.85rem;font-size:0.8rem;background:rgba(234,179,8,0.15);color:#facc15;border-color:rgba(234,179,8,0.4);">
                            ✏️ এডিট ও বেতন পে
                        </button>
                        <button onclick="deleteEmployee('${e.id}')" class="owner-icon-btn" style="width:32px;height:32px;color:#f87171;" title="মুছুন">🗑️</button>
                    </div>
                </div>
            `).join('')}
            ${dataStore.employees.length === 0 ? '<div style="text-align:center;padding:3rem;color:#64748b;background:#121219;border-radius:1.25rem;">কোনো কর্মী যোগ করা হয়নি।</div>' : ''}
        </div>
    `;
}

// ── 5. Ledger Tab (আয়-ব্যয় ও বাকি খাতা) ──
let ledgerSubTab = 'LEDGER';

function renderLedgerTab(container) {
    const totalProfit = dataStore.ledger.reduce((acc, l) => acc + (parseFloat(l.netProfit) || 0), 0);
    const totalDue = dataStore.dues.reduce((acc, d) => acc + ((parseFloat(d.totalDue) || 0) - (parseFloat(d.paidAmount) || 0)), 0);

    container.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div>
                <h2 style="font-size:1.25rem;font-weight:900;color:#fff;">📒 হিসাব ও বাকি খাতা</h2>
                <p style="font-size:0.78rem;color:#94a3b8;">দৈনিক ক্যাশ হিসাব এবং কাস্টমারদের বাকি</p>
            </div>
            <button onclick="${ledgerSubTab === 'LEDGER' ? 'openAddLedgerModal()' : 'openAddDueModal()'}" class="owner-dock-btn primary" style="padding:0.6rem 1rem;">
                ➕ ${ledgerSubTab === 'LEDGER' ? 'নতুন হিসাব' : 'নতুন বাকি'}
            </button>
        </div>

        <!-- Segment Control -->
        <div style="display:flex;background:#15151f;padding:4px;border-radius:1rem;margin-bottom:1.25rem;border:1px solid rgba(255,255,255,0.08);">
            <button onclick="ledgerSubTab='LEDGER';renderCurrentTab();" style="flex:1;padding:0.65rem;border-radius:0.75rem;font-size:0.85rem;font-weight:800;border:none;cursor:pointer;background:${ledgerSubTab === 'LEDGER' ? 'var(--gold-gradient)' : 'transparent'};color:${ledgerSubTab === 'LEDGER' ? '#000' : '#94a3b8'};">
                💵 দৈনিক আয়-ব্যয়
            </button>
            <button onclick="ledgerSubTab='DUES';renderCurrentTab();" style="flex:1;padding:0.65rem;border-radius:0.75rem;font-size:0.85rem;font-weight:800;border:none;cursor:pointer;background:${ledgerSubTab === 'DUES' ? 'var(--gold-gradient)' : 'transparent'};color:${ledgerSubTab === 'DUES' ? '#000' : '#94a3b8'};">
                👥 কাস্টমার বাকি (${dataStore.dues.length})
            </button>
        </div>

        ${ledgerSubTab === 'LEDGER' ? `
            <!-- Net Profit Hero -->
            <div class="owner-hero-card" style="padding:1.2rem;margin-bottom:1rem;">
                <span style="font-size:0.75rem;color:#94a3b8;">সর্বমোট নিট লাভ/লোকসান (সমিতি ও খরচ বাদে)</span>
                <div style="font-size:1.75rem;font-weight:900;color:${totalProfit >= 0 ? '#4ade80' : '#f87171'};font-family:'Outfit',sans-serif;margin-top:2px;">
                    ৳${Math.round(totalProfit).toLocaleString()}
                </div>
            </div>

            <!-- Ledger Transactions -->
            <div>
                ${dataStore.ledger.map(l => {
                    const shomiti = parseFloat(l.shomitiExpense) || 0;
                    return `
                        <div class="owner-order-card">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                                <span style="font-weight:800;color:#fff;font-size:0.9rem;">📅 ${l.date || 'তারিখ নেই'}</span>
                                <div style="display:flex;gap:6px;">
                                    <button onclick="openEditLedgerModal('${l.id}')" class="owner-icon-btn" style="width:28px;height:28px;color:#facc15;" title="এডিট">✏️</button>
                                    <button onclick="deleteLedger('${l.id}')" class="owner-icon-btn" style="width:28px;height:28px;color:#f87171;" title="মুছুন">🗑️</button>
                                </div>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:0.82rem;margin-bottom:4px;">
                                <span style="color:#94a3b8;">মোট বিক্রয় ও ক্যাশ:</span>
                                <span style="color:#4ade80;font-weight:800;">+৳${Math.round(l.totalSales || l.totalIncome || 0)}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:0.82rem;margin-bottom:4px;">
                                <span style="color:#94a3b8;">বাজার খরচ:</span>
                                <span style="color:#f87171;font-weight:700;">-৳${Math.round(l.marketExpense || 0)}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:0.82rem;margin-bottom:4px;">
                                <span style="color:#94a3b8;">স্টাফ বেতন ও অন্যান্য:</span>
                                <span style="color:#f87171;font-weight:700;">-৳${Math.round(l.salaryPaid || 0)}</span>
                            </div>
                            ${shomiti > 0 ? `
                                <div style="display:flex;justify-content:space-between;font-size:0.82rem;margin-bottom:4px;background:rgba(234,179,8,0.08);padding:3px 6px;border-radius:6px;">
                                    <span style="color:#facc15;">🏦 সমিতি কিস্তি / খরচ:</span>
                                    <span style="color:#f87171;font-weight:800;">-৳${Math.round(shomiti)}</span>
                                </div>
                            ` : ''}
                            <div style="display:flex;justify-content:space-between;font-size:0.88rem;border-top:1px solid rgba(255,255,255,0.08);padding-top:6px;margin-top:6px;">
                                <span style="font-weight:700;color:#fff;">নিট লাভ (লাভ/লোকসান):</span>
                                <span style="font-weight:900;color:${l.netProfit >= 0 ? '#4ade80' : '#f87171'};font-size:1rem;">৳${Math.round(l.netProfit || 0)}</span>
                            </div>
                        </div>
                    `;
                }).join('')}
                ${dataStore.ledger.length === 0 ? '<div style="text-align:center;padding:3rem;color:#64748b;background:#121219;border-radius:1.25rem;">কোনো হিসাব পাওয়া যায়নি।</div>' : ''}
            </div>
        ` : `
            <!-- Customer Dues Cards -->
            <div class="owner-hero-card" style="padding:1.2rem;margin-bottom:1rem;">
                <span style="font-size:0.75rem;color:#94a3b8;">মোট বকেয়া বাকি</span>
                <div style="font-size:1.75rem;font-weight:900;color:#f87171;font-family:'Outfit',sans-serif;margin-top:2px;">
                    ৳${Math.round(totalDue).toLocaleString()}
                </div>
            </div>

            <div>
                ${dataStore.dues.map(d => {
                    const rem = (parseFloat(d.totalDue) || 0) - (parseFloat(d.paidAmount) || 0);
                    const phone = d.phone || '';
                    return `
                        <div class="owner-order-card" style="border-color:${rem > 0 ? 'rgba(239,68,68,0.3)' : 'rgba(16,185,129,0.3)'};">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                                <h4 style="font-weight:800;color:#fff;font-size:0.95rem;margin:0;">${d.name}</h4>
                                <span style="font-weight:900;color:${rem > 0 ? '#f87171' : '#4ade80'};font-size:0.95rem;">
                                    ${rem > 0 ? `বাকি: ৳${Math.round(rem)}` : 'পরিশোধ সম্পন্ন'}
                                </span>
                            </div>
                            <p style="font-size:0.78rem;color:#94a3b8;margin-bottom:8px;">${d.address || 'সাতক্ষীরা'}</p>
                            
                            <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid rgba(255,255,255,0.08);padding-top:8px;">
                                <span style="font-size:0.75rem;color:#94a3b8;">মোট: ৳${Math.round(d.totalDue)} | জমা: ৳${Math.round(d.paidAmount)}</span>
                                <div style="display:flex;gap:6px;">
                                    <button onclick="openEditDueModal('${d.id}')" class="owner-icon-btn" style="width:30px;height:30px;color:#facc15;font-size:0.85rem;" title="এডিট / টাকা জমা বা বাকি">✏️</button>
                                    ${phone && rem > 0 ? `
                                        <a href="https://wa.me/88${phone.replace(/[^0-9]/g,'')}?text=${encodeURIComponent('আসসালামু আলাইকুম ' + d.name + ', নজরুল হোটেল থেকে আপনার বকেয়া বাকি ৳' + Math.round(rem) + ' টাকা পরিশোধের জন্য অনুরোধ করা হচ্ছে।')}" target="_blank" class="owner-icon-btn" style="width:30px;height:30px;color:#22c55e;font-size:0.85rem;" title="WhatsApp তাগাদা">💬</a>
                                    ` : ''}
                                    <button onclick="deleteDue('${d.id}')" class="owner-icon-btn" style="width:30px;height:30px;color:#f87171;font-size:0.85rem;">🗑️</button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('')}
                ${dataStore.dues.length === 0 ? '<div style="text-align:center;padding:3rem;color:#64748b;background:#121219;border-radius:1.25rem;">কোনো কাস্টমার ডিউ নেই।</div>' : ''}
            </div>
        `}
    `;
}

// ── 6. Dues Tab standalone redirect ──
function renderDuesTab(container) {
    ledgerSubTab = 'DUES';
    renderLedgerTab(container);
}

// ── 7. Stock Tab ──
function renderStockTab(container) {
    container.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
            <div>
                <h2 style="font-size:1.25rem;font-weight:900;color:#fff;">📦 কাঁচামাল স্টক হিসাব</h2>
                <p style="font-size:0.78rem;color:#94a3b8;">মোট ${dataStore.stock.length} টি উপকরণের হিসাব</p>
            </div>
            <button onclick="openAddStockModal()" class="owner-dock-btn primary" style="padding:0.6rem 1rem;">
                ➕ নতুন স্টক
            </button>
        </div>

        <div>
            ${dataStore.stock.map(s => `
                <div class="owner-order-card">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px;">
                        <h4 style="font-weight:800;color:#fff;font-size:0.95rem;margin:0;">${s.name}</h4>
                        <button onclick="deleteStock('${s.id}')" class="owner-icon-btn" style="width:28px;height:28px;color:#f87171;">🗑️</button>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-top:6px;">
                        <span style="color:#94a3b8;">মজুদ: <strong style="color:${s.isLowStock ? '#f87171' : '#4ade80'};">${s.quantity} ${s.unit}</strong></span>
                        <span style="color:#facc15;font-weight:700;">দর: ৳${s.lastPrice}</span>
                    </div>
                </div>
            `).join('')}
            ${dataStore.stock.length === 0 ? '<div style="text-align:center;padding:3rem;color:#64748b;background:#121219;border-radius:1.25rem;">কোনো স্টক উপাদান যোগ করা হয়নি।</div>' : ''}
        </div>
    `;
}

// ── Actions & API Calls ──
async function updateOrderStatus(id, status) {
    await executeOrQueueApi(
        'UPDATE_ORDER_STATUS',
        `/api/admin/orders/${id}`,
        'PATCH',
        { status },
        () => {
            const order = dataStore.orders.find(o => String(o.id) === String(id));
            if (order) order.status = status;
        }
    );
    if (mamunSyncBus) mamunSyncBus.postMessage({ action: 'ORDER_UPDATED', id, status });
    try { localStorage.setItem('mamun_sync_event', 'ORDER_UPDATED_' + Date.now()); } catch(e) {}
    if (navigator.onLine && !isCurrentlyOffline) fetchAllData();
}

async function toggleMenuAvailability(id, current) {
    await executeOrQueueApi(
        'TOGGLE_MENU_AVAILABILITY',
        `/api/admin/menu/${id}`,
        'PATCH',
        { isAvailable: !current },
        () => {
            const item = dataStore.menu.find(m => String(m.id) === String(id));
            if (item) item.isAvailable = !current;
        }
    );
    if (mamunSyncBus) mamunSyncBus.postMessage({ action: 'MENU_UPDATED', id });
    try { localStorage.setItem('mamun_sync_event', 'MENU_UPDATED_' + Date.now()); } catch(e) {}
    if (navigator.onLine && !isCurrentlyOffline) fetchAllData();
}

async function deleteMenuItem(id) { 
    if (confirm('মুছে ফেলতে চান?')) { 
        await fetch(`/api/admin/menu/${id}`, { method: 'DELETE' }); 
        if (mamunSyncBus) mamunSyncBus.postMessage({ action: 'MENU_UPDATED' });
        try { localStorage.setItem('mamun_sync_event', 'MENU_UPDATED_' + Date.now()); } catch(e) {}
        fetchAllData(); 
    } 
}
async function deleteOrder(id) { if (confirm('অর্ডার মুছে ফেলতে চান?')) { await fetch(`/api/admin/orders/${id}`, { method: 'DELETE' }); fetchAllData(); } }
async function deleteEmployee(id) { if (confirm('কর্মী মুছে ফেলতে চান?')) { await fetch(`/api/admin/employees/${id}`, { method: 'DELETE' }); fetchAllData(); } }
async function deleteDue(id) { if (confirm('ডিউ মুছে ফেলতে চান?')) { await fetch(`/api/admin/customer-dues/${id}`, { method: 'DELETE' }); fetchAllData(); } }
async function deleteLedger(id) { if (confirm('হিসাব মুছে ফেলতে চান?')) { await fetch(`/api/ledger/${id}`, { method: 'DELETE' }); fetchAllData(); } }
async function deleteStock(id) { if (confirm('স্টক মুছে ফেলতে চান?')) { await fetch(`/api/stock/${id}`, { method: 'DELETE' }); fetchAllData(); } }

async function handleLogout() {
    await fetch('/api/admin/logout', { method: 'POST' });
    window.location.href = '/admin';
}

// Modal Helpers
function openModal(title, html) {
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalBody').innerHTML = html;
    document.getElementById('adminModal').classList.add('open');
}

function closeModal() {
    document.getElementById('adminModal').classList.remove('open');
}

function previewModalImage(url, previewElId = 'imgPreviewEl') {
    const el = document.getElementById(previewElId);
    if (el && url && url.trim() !== '') {
        el.src = url.trim();
    }
}

async function handleDirectImageUpload(inputEl, targetInputId, previewImgId, statusId) {
    if (!inputEl.files || !inputEl.files[0]) return;
    const file = inputEl.files[0];
    const previewEl = document.getElementById(previewImgId);
    const targetInput = document.getElementById(targetInputId);
    const statusEl = document.getElementById(statusId);

    if (statusEl) {
        statusEl.style.display = 'block';
        statusEl.style.color = '#60a5fa';
        statusEl.innerText = '⏳ ছবি আপলোড হচ্ছে...';
    }

    // Instant local preview
    const reader = new FileReader();
    reader.onload = function(e) {
        if (previewEl) previewEl.src = e.target.result;
    };
    reader.readAsDataURL(file);

    // Upload to server
    const formData = new FormData();
    formData.append('file', file);

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';
        const res = await fetch('/api/admin/upload-image', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            body: formData
        });
        const data = await res.json();

        if (res.ok && data.url) {
            if (targetInput) targetInput.value = data.url;
            if (statusEl) {
                statusEl.style.color = '#4ade80';
                statusEl.innerText = '✅ ছবি সফলভাবে আপলোড হয়েছে!';
            }
        } else {
            // Fallback to data url
            if (targetInput && reader.result) targetInput.value = reader.result;
            if (statusEl) {
                statusEl.style.color = '#4ade80';
                statusEl.innerText = '✅ ছবি যুক্ত হয়েছে!';
            }
        }
    } catch(err) {
        if (targetInput && reader.result) targetInput.value = reader.result;
        if (statusEl) {
            statusEl.style.color = '#4ade80';
            statusEl.innerText = '✅ ছবি যুক্ত হয়েছে!';
        }
    }
}

function openEditMenuModal(id) {
    const item = dataStore.menu.find(m => m.id === id);
    if (!item) return;

    const img = getAdminDishImage(item);
    const isAvail = (item.isAvailable === true || item.is_available === true || item.isAvailable === 1 || item.is_available === 1);
    const isFeat = (item.isFeatured === true || item.is_featured === true || item.isFeatured === 1 || item.is_featured === 1);
    const categories = Array.isArray(dataStore.categories) && dataStore.categories.length > 0 
        ? dataStore.categories 
        : [
            { id: 'cat-1', name: 'ভাত ও তরকারি' },
            { id: 'cat-2', name: 'মাছ' },
            { id: 'cat-3', name: 'মুরগি' },
            { id: 'cat-4', name: 'নাস্তা ও পানীয়' }
        ];

    openModal('খাবারের তথ্য ও মূল্য এডিট 🍲', `
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>🍽️ খাবারের নাম</span>
                <span class="req">*</span>
            </label>
            <input type="text" id="mEditName" class="owner-input" value="${item.name || ''}" required>
        </div>
        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>💰 মূল্য (৳)</span>
                    <span class="req">*</span>
                </label>
                <input type="number" id="mEditPrice" class="owner-input" value="${item.price || 0}" required>
            </div>
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>🏷️ ক্যাটাগরি</span>
                </label>
                <select id="mEditCat" class="owner-form-select">
                    ${categories.map(c => `
                        <option value="${c.id}" ${(item.categoryId === c.id || (item.category && item.category.id === c.id)) ? 'selected' : ''}>${c.name}</option>
                    `).join('')}
                </select>
            </div>
        </div>

        <!-- Direct Image Upload & URL -->
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📷 খাবারের ছবি (ক্যামেরা / ফাইল / লিংক)</span>
            </label>
            
            <div style="background:rgba(255,255,255,0.03);border:2px dashed #3f3f46;border-radius:12px;padding:1.1rem;text-align:center;margin-bottom:0.75rem;cursor:pointer;transition:all 0.2s;" onclick="document.getElementById('mEditFileInput').click()">
                <div style="display:flex;flex-direction:column;align-items:center;gap:6px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;color:#60a5fa;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <span style="font-weight:700;color:#fff;font-size:0.92rem;">ডিভাইস / ফোন থেকে সরাসরি ছবি দিন 📷</span>
                    <span style="font-size:0.75rem;color:#a1a1aa;">যেকোনো ছবি সিলেক্ট করুন</span>
                </div>
            </div>
            <input type="file" id="mEditFileInput" accept="image/*" style="display:none;" onchange="handleDirectImageUpload(this, 'mEditImage', 'imgPreviewEl', 'mEditUploadStatus')">
            <div id="mEditUploadStatus" style="font-size:0.8rem;color:#4ade80;font-weight:700;margin-bottom:6px;display:none;"></div>

            <div style="display:flex;align-items:center;gap:10px;margin-top:6px;">
                <input type="text" id="mEditImage" class="owner-input" value="${item.image || ''}" placeholder="বা ছবির লিংক পেস্ট করুন..." oninput="previewModalImage(this.value, 'imgPreviewEl')" style="margin-bottom:0;flex:1;">
                <img id="imgPreviewEl" src="${img}" style="width:65px;height:48px;border-radius:8px;object-fit:cover;border:1px solid #3f3f46;flex-shrink:0;" alt="Preview">
            </div>
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📝 খাবারের বিশেষ বিবরণ</span>
            </label>
            <textarea id="mEditDesc" class="owner-input" rows="2" style="resize:vertical;">${item.description || ''}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;background:#15151f;padding:0.85rem 1rem;border-radius:12px;border:1px solid rgba(255,255,255,0.08);margin-bottom:1.25rem;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.85rem;font-weight:700;color:#fff;">
                <input type="checkbox" id="mEditAvail" ${isAvail ? 'checked' : ''} style="width:18px;height:18px;">
                <span>🟢 সহজলভ্য</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.85rem;font-weight:700;color:#facc15;">
                <input type="checkbox" id="mEditFeatured" ${isFeat ? 'checked' : ''} style="width:18px;height:18px;">
                <span>★ স্পেশাল</span>
            </label>
        </div>

        <button class="owner-btn-submit" onclick="saveEditMenuItem('${item.id}')">
            ✓ পরিবর্তন সংরক্ষণ করুন
        </button>
    `);
}

async function saveEditMenuItem(id) {
    const name = document.getElementById('mEditName').value.trim();
    const price = document.getElementById('mEditPrice').value;
    const categoryId = document.getElementById('mEditCat').value;
    const image = document.getElementById('mEditImage').value.trim();
    const description = document.getElementById('mEditDesc').value.trim();
    const isAvailable = document.getElementById('mEditAvail').checked;
    const isFeatured = document.getElementById('mEditFeatured').checked;

    if (!name || !price) return alert('অনুগ্রহ করে খাবারের নাম ও মূল্য দিন');

    try {
        const res = await fetch(`/api/admin/menu/${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ name, price, categoryId, image, description, isAvailable, isFeatured })
        });
        if (res.ok) {
            closeModal();
            if (mamunSyncBus) mamunSyncBus.postMessage({ action: 'MENU_UPDATED', id });
            try { localStorage.setItem('mamun_sync_event', 'MENU_UPDATED_' + Date.now()); } catch(e) {}
            fetchAllData();
        } else {
            alert('সংরক্ষণ ব্যর্থ হয়েছে।');
        }
    } catch(e) {
        alert('নেটওয়ার্ক সমস্যা। আবার চেষ্টা করুন।');
    }
}

function openAddMenuModal() {
    const categories = Array.isArray(dataStore.categories) && dataStore.categories.length > 0 
        ? dataStore.categories 
        : [
            { id: 'cat-1', name: 'ভাত ও তরকারি' },
            { id: 'cat-2', name: 'মাছ' },
            { id: 'cat-3', name: 'মুরগি' },
            { id: 'cat-4', name: 'নাস্তা ও পানীয়' }
        ];

    openModal('নতুন মেনু পদ যোগ 🍲', `
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>🍽️ খাবারের নাম</span>
                <span class="req">*</span>
            </label>
            <input type="text" id="mName" class="owner-input" placeholder="যেমন: চুইঝালের খাসির মাংস" required>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>💰 মূল্য (৳)</span>
                    <span class="req">*</span>
                </label>
                <input type="number" id="mPrice" class="owner-input" placeholder="যেমন: 350" required>
            </div>
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>🏷️ ক্যাটাগরি</span>
                </label>
                <select id="mCat" class="owner-form-select">
                    ${categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                </select>
            </div>
        </div>

        <!-- Direct Image Upload & URL -->
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📷 খাবারের ছবি (ক্যামেরা / ফাইল / লিংক)</span>
            </label>
            
            <div style="background:rgba(255,255,255,0.03);border:2px dashed #3f3f46;border-radius:12px;padding:1.1rem;text-align:center;margin-bottom:0.75rem;cursor:pointer;transition:all 0.2s;" onclick="document.getElementById('mAddFileInput').click()">
                <div style="display:flex;flex-direction:column;align-items:center;gap:6px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;color:#60a5fa;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <span style="font-weight:700;color:#fff;font-size:0.92rem;">ডিভাইস / ফোন থেকে সরাসরি ছবি দিন 📷</span>
                    <span style="font-size:0.75rem;color:#a1a1aa;">যেকোনো ছবি সিলেক্ট করুন</span>
                </div>
            </div>
            <input type="file" id="mAddFileInput" accept="image/*" style="display:none;" onchange="handleDirectImageUpload(this, 'mImage', 'addImgPreviewEl', 'mAddUploadStatus')">
            <div id="mAddUploadStatus" style="font-size:0.8rem;color:#4ade80;font-weight:700;margin-bottom:6px;display:none;"></div>

            <div style="display:flex;align-items:center;gap:10px;margin-top:6px;">
                <input type="text" id="mImage" class="owner-input" placeholder="বা ছবির লিংক পেস্ট করুন..." oninput="previewModalImage(this.value, 'addImgPreviewEl')" style="margin-bottom:0;flex:1;">
                <img id="addImgPreviewEl" src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=400&q=80" style="width:65px;height:48px;border-radius:8px;object-fit:cover;border:1px solid #3f3f46;flex-shrink:0;" alt="Preview">
            </div>
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📝 খাবারের বিবরণ</span>
            </label>
            <textarea id="mDesc" class="owner-input" rows="2" placeholder="খাবারের বিশেষত্ব লিখুন..." style="resize:vertical;"></textarea>
        </div>

        <div class="owner-form-group" style="background:#15151f;padding:0.85rem 1rem;border-radius:12px;border:1px solid rgba(255,255,255,0.08);">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.85rem;font-weight:700;color:#facc15;">
                <input type="checkbox" id="mFeatured" style="width:18px;height:18px;">
                <span>★ স্পেশাল মেনু হিসেবে ফিচার করুন (Featured)</span>
            </label>
        </div>

        <button class="owner-btn-submit" onclick="saveNewMenuItem()">
            ✓ নতুন খাবার মেনুতে যোগ করুন
        </button>
    `);
}

async function saveNewMenuItem() {
    const name = document.getElementById('mName').value.trim();
    const price = document.getElementById('mPrice').value;
    const categoryId = document.getElementById('mCat').value;
    const image = document.getElementById('mImage').value.trim();
    const desc = document.getElementById('mDesc').value.trim();
    const isFeatured = document.getElementById('mFeatured').checked;
    if (!name || !price) return alert('নাম ও মূল্য আবশ্যক');

    try {
        const res = await fetch('/api/admin/menu', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ name, price, categoryId, image, description: desc, isFeatured, isAvailable: true })
        });
        if (res.ok) {
            closeModal();
            if (mamunSyncBus) mamunSyncBus.postMessage({ action: 'MENU_UPDATED' });
            try { localStorage.setItem('mamun_sync_event', 'MENU_UPDATED_' + Date.now()); } catch(e) {}
            fetchAllData();
        } else {
            alert('মেনু যোগ করা সম্ভব হয়নি।');
        }
    } catch(e) {
        alert('নেটওয়ার্ক সমস্যা।');
    }
}

// ── EMPLOYEE MODALS & ACTIONS ──
function openAddEmployeeModal() {
    openModal('নতুন কর্মী নিবন্ধন খাতা 👥', `
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>👤 কর্মীর পুরো নাম</span>
                <span class="req">*</span>
            </label>
            <input type="text" id="eName" class="owner-input" placeholder="যেমন: মো: রফিকুল ইসলাম" required>
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>💼 দায়িত্ব ও পদবী</span>
                <span class="req">*</span>
            </label>
            <select id="ePos" class="owner-form-select">
                <option value="প্রধান বাবুর্চি (মাস্টার শেফ)">👨‍🍳 প্রধান বাবুর্চি (মাস্টার শেফ)</option>
                <option value="সহকারী বাবুর্চি">👨‍🍳 সহকারী বাবুর্চি</option>
                <option value="কিচেন হেল্পার">🥣 কিচেন হেল্পার</option>
                <option value="ডেলিভারি রাইডার">🛵 ডেলিভারি রাইডার</option>
                <option value="ওয়েটার ও সার্ভিস">🤵 ওয়েটার ও সার্ভিস</option>
                <option value="হোটেল ম্যানেজার ও ক্যাশিয়ার">💼 হোটেল ম্যানেজার ও ক্যাশিয়ার</option>
            </select>
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📞 মোবাইল নাম্বার</span>
            </label>
            <input type="tel" id="ePhone" class="owner-input" placeholder="017XXXXXXXX">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>💰 মাসিক মূল বেতন (৳)</span>
                    <span class="req">*</span>
                </label>
                <input type="number" id="eSalary" class="owner-input" placeholder="যেমন: 15000" oninput="calcNewEmployeeDue()" required>
            </div>
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>💵 ইতোমধ্যে পরিশোধ (৳)</span>
                </label>
                <input type="number" id="eSalaryPaid" class="owner-input" value="0" oninput="calcNewEmployeeDue()">
            </div>
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>⚠️ বকেয়া পাবে (৳)</span>
            </label>
            <input type="number" id="eSalaryDue" class="owner-input" placeholder="0" style="color:#f87171;font-weight:800;">
        </div>

        <button class="owner-btn-submit" onclick="saveNewEmployee()">
            ✓ কর্মী তথ্য সংরক্ষণ করুন
        </button>
    `);
}

function calcNewEmployeeDue() {
    const sal = parseFloat(document.getElementById('eSalary')?.value) || 0;
    const paid = parseFloat(document.getElementById('eSalaryPaid')?.value) || 0;
    const dueEl = document.getElementById('eSalaryDue');
    if (dueEl) dueEl.value = Math.max(0, sal - paid);
}

async function saveNewEmployee() {
    const name = document.getElementById('eName').value.trim();
    const position = document.getElementById('ePos').value;
    const phone = document.getElementById('ePhone').value.trim();
    const salary = parseFloat(document.getElementById('eSalary').value) || 0;
    const salaryPaid = parseFloat(document.getElementById('eSalaryPaid').value) || 0;
    const salaryDue = parseFloat(document.getElementById('eSalaryDue').value) || Math.max(0, salary - salaryPaid);
    if (!name || !position) return alert('নাম ও পদবী আবশ্যক');

    const tempId = 'emp_off_' + Date.now();
    const newEmp = { id: tempId, name, position, phone, salary, salaryPaid, salaryDue };

    closeModal();

    await executeOrQueueApi(
        'ADD_EMPLOYEE',
        '/api/admin/employees',
        'POST',
        { name, position, phone, salary, salaryPaid, salaryDue },
        () => {
            dataStore.employees.unshift(newEmp);
        }
    );

    if (navigator.onLine && !isCurrentlyOffline) fetchAllData();
}

function openEditEmployeeModal(id) {
    const emp = dataStore.employees.find(e => String(e.id) === String(id));
    if (!emp) return;

    openModal('কর্মী ও বেতন হিসাব এডিট 👥', `
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>👤 কর্মীর পুরো নাম</span>
                <span class="req">*</span>
            </label>
            <input type="text" id="eEditName" class="owner-input" value="${emp.name || ''}" required>
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>💼 দায়িত্ব ও পদবী</span>
            </label>
            <input type="text" id="eEditPos" class="owner-input" value="${emp.position || ''}">
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📞 মোবাইল নাম্বার</span>
            </label>
            <input type="tel" id="eEditPhone" class="owner-input" value="${emp.phone || ''}">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>💰 মাসিক মূল বেতন (৳)</span>
                </label>
                <input type="number" id="eEditSalary" class="owner-input" value="${emp.salary || 0}">
            </div>
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>💵 মোট পরিশোধ (৳)</span>
                </label>
                <input type="number" id="eEditPaid" class="owner-input" value="${emp.salaryPaid || 0}">
            </div>
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>⚠️ কর্মী বর্তমানে বকেয়া পাবে (৳)</span>
            </label>
            <input type="number" id="eEditDue" class="owner-input" value="${emp.salaryDue || 0}" style="color:#f87171;font-weight:800;">
        </div>

        <!-- Quick Pay Box -->
        <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:12px;padding:0.85rem;margin-bottom:1rem;">
            <span style="font-size:0.8rem;font-weight:800;color:#4ade80;display:block;margin-bottom:4px;">⚡ দ্রুত নতুন বেতন / খোরাকী পে করুন:</span>
            <div style="display:flex;gap:8px;">
                <input type="number" id="eQuickPay" class="owner-input" placeholder="টাকার পরিমাণ লিখুন..." style="margin-bottom:0;flex:1;">
                <button type="button" onclick="applyEmployeeQuickPay()" class="owner-dock-btn" style="background:#22c55e;color:#000;font-weight:800;padding:0 1rem;">
                    জমা দিন
                </button>
            </div>
        </div>

        <button class="owner-btn-submit" onclick="saveEditEmployee('${emp.id}')">
            ✓ কর্মীর তথ্য ও বেতন হিসাব সংরক্ষণ করুন
        </button>
    `);
}

function applyEmployeeQuickPay() {
    const payVal = parseFloat(document.getElementById('eQuickPay')?.value) || 0;
    if (payVal <= 0) return alert('অনুগ্রহ করে সঠিক টাকার পরিমাণ দিন');
    const paidEl = document.getElementById('eEditPaid');
    const dueEl = document.getElementById('eEditDue');
    if (paidEl) paidEl.value = (parseFloat(paidEl.value) || 0) + payVal;
    if (dueEl) dueEl.value = Math.max(0, (parseFloat(dueEl.value) || 0) - payVal);
    document.getElementById('eQuickPay').value = '';
    alert('✓ ৳' + payVal + ' টাকা যোগ করা হয়েছে। সংরক্ষণ বাটনে চাপ দিন।');
}

async function saveEditEmployee(id) {
    const name = document.getElementById('eEditName').value.trim();
    const position = document.getElementById('eEditPos').value.trim();
    const phone = document.getElementById('eEditPhone').value.trim();
    const salary = parseFloat(document.getElementById('eEditSalary').value) || 0;
    const salaryPaid = parseFloat(document.getElementById('eEditPaid').value) || 0;
    const salaryDue = parseFloat(document.getElementById('eEditDue').value) || 0;

    closeModal();

    await executeOrQueueApi(
        'EDIT_EMPLOYEE',
        `/api/admin/employees/${id}`,
        'PATCH',
        { name, position, phone, salary, salaryPaid, salaryDue },
        () => {
            const emp = dataStore.employees.find(e => String(e.id) === String(id));
            if (emp) {
                emp.name = name;
                emp.position = position;
                emp.phone = phone;
                emp.salary = salary;
                emp.salaryPaid = salaryPaid;
                emp.salaryDue = salaryDue;
            }
        }
    );

    if (navigator.onLine && !isCurrentlyOffline) fetchAllData();
}

// ── CUSTOMER DUES MODALS & ACTIONS ──
function openAddDueModal() {
    openModal('নতুন কাস্টমার বাকি খাতা 📝', `
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>👤 খদ্দের বা কাস্টমারের নাম</span>
                <span class="req">*</span>
            </label>
            <input type="text" id="dName" class="owner-input" placeholder="যেমন: আলহাজ্ব রফিক সাহেব" required>
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📞 মোবাইল নাম্বার / ঠিকানা</span>
            </label>
            <input type="text" id="dAddr" class="owner-input" placeholder="ফোন নাম্বার বা দোকানের ঠিকানা">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>💰 মোট বাকি (৳)</span>
                    <span class="req">*</span>
                </label>
                <input type="number" id="dDue" class="owner-input" placeholder="যেমন: 1200" required>
            </div>
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>💵 জমা প্রদান (৳)</span>
                </label>
                <input type="number" id="dPaid" class="owner-input" value="0">
            </div>
        </div>

        <button class="owner-btn-submit" onclick="saveNewDue()">
            ✓ বাকি খাতায় সংরক্ষণ করুন
        </button>
    `);
}

async function saveNewDue() {
    const name = document.getElementById('dName').value.trim();
    const address = document.getElementById('dAddr').value.trim();
    const totalDue = parseFloat(document.getElementById('dDue').value) || 0;
    const paidAmount = parseFloat(document.getElementById('dPaid').value) || 0;
    if (!name) return alert('কাস্টমারের নাম আবশ্যক');

    const tempId = 'due_off_' + Date.now();
    const newDue = { id: tempId, name, address, totalDue, paidAmount, createdAt: new Date().toISOString() };

    closeModal();

    await executeOrQueueApi(
        'ADD_DUE',
        '/api/admin/customer-dues',
        'POST',
        { name, address, totalDue, paidAmount },
        () => {
            dataStore.dues.unshift(newDue);
        }
    );

    if (navigator.onLine && !isCurrentlyOffline) fetchAllData();
}

function openEditDueModal(id) {
    const due = dataStore.dues.find(d => String(d.id) === String(id));
    if (!due) return;
    const curRem = (parseFloat(due.totalDue) || 0) - (parseFloat(due.paidAmount) || 0);

    openModal('কাস্টমার বাকি ও জমা আপডেট 📝', `
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>👤 কাস্টমারের নাম</span>
                <span class="req">*</span>
            </label>
            <input type="text" id="dEditName" class="owner-input" value="${due.name || ''}" required>
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📞 ফোন নাম্বার / ঠিকানা</span>
            </label>
            <input type="text" id="dEditAddr" class="owner-input" value="${due.address || due.phone || ''}">
        </div>

        <div style="background:#181824;padding:0.75rem 1rem;border-radius:12px;margin-bottom:1rem;border:1px solid rgba(255,255,255,0.08);">
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:4px;">
                <span style="color:#94a3b8;">সর্বমোট বাকি নেওয়া:</span>
                <strong style="color:#fff;">৳<span id="dDisplayTotal">${Math.round(due.totalDue || 0)}</span></strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;margin-bottom:4px;">
                <span style="color:#94a3b8;">এ পর্যন্ত জমা পরিশোধ:</span>
                <strong style="color:#4ade80;">৳<span id="dDisplayPaid">${Math.round(due.paidAmount || 0)}</span></strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.95rem;border-top:1px solid rgba(255,255,255,0.08);padding-top:6px;margin-top:6px;">
                <span style="font-weight:700;color:#fff;">বর্তমান মোট বকেয়া:</span>
                <strong style="color:${curRem > 0 ? '#f87171' : '#4ade80'};font-size:1.1rem;">৳<span id="dDisplayRem">${Math.round(curRem)}</span></strong>
            </div>
        </div>

        <!-- Action 1: Quick Payment Received (টাকা জমা নেওয়া) -->
        <div style="background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:12px;padding:0.85rem;margin-bottom:0.75rem;">
            <span style="font-size:0.8rem;font-weight:800;color:#4ade80;display:block;margin-bottom:4px;">💵 নতুন টাকা জমা পেলে এখানে লিখুন (বাকি কমবে):</span>
            <div style="display:flex;gap:8px;">
                <input type="number" id="dAddPaymentInput" class="owner-input" placeholder="জমা দেওয়া টাকার পরিমাণ..." style="margin-bottom:0;flex:1;">
                <button type="button" onclick="applyDuePayment()" class="owner-dock-btn" style="background:#22c55e;color:#000;font-weight:800;padding:0 0.85rem;">
                    জমা নিন
                </button>
            </div>
        </div>

        <!-- Action 2: Additional Due (আরও নতুন বাকি নেওয়া) -->
        <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);border-radius:12px;padding:0.85rem;margin-bottom:1rem;">
            <span style="font-size:0.8rem;font-weight:800;color:#f87171;display:block;margin-bottom:4px;">➕ আরও নতুন বাকি নিলে এখানে লিখুন (বাকি বাড়বে):</span>
            <div style="display:flex;gap:8px;">
                <input type="number" id="dAddDueInput" class="owner-input" placeholder="নতুন বাকি টাকার পরিমাণ..." style="margin-bottom:0;flex:1;">
                <button type="button" onclick="applyAdditionalDue()" class="owner-dock-btn" style="background:#ef4444;color:#fff;font-weight:800;padding:0 0.85rem;">
                    যোগ করুন
                </button>
            </div>
        </div>

        <!-- Hidden inputs storing the latest totalDue & paidAmount -->
        <input type="hidden" id="dEditTotal" value="${due.totalDue || 0}">
        <input type="hidden" id="dEditPaid" value="${due.paidAmount || 0}">

        <button class="owner-btn-submit" onclick="saveEditDue('${due.id}')">
            ✓ কাস্টমার বাকি তথ্য সংরক্ষণ করুন
        </button>
    `);
}

function applyDuePayment() {
    const pay = parseFloat(document.getElementById('dAddPaymentInput')?.value) || 0;
    if (pay <= 0) return alert('সঠিক টাকার পরিমাণ দিন');
    const paidEl = document.getElementById('dEditPaid');
    const totalEl = document.getElementById('dEditTotal');
    const newPaid = (parseFloat(paidEl.value) || 0) + pay;
    paidEl.value = newPaid;
    
    document.getElementById('dDisplayPaid').innerText = Math.round(newPaid);
    document.getElementById('dDisplayRem').innerText = Math.max(0, Math.round((parseFloat(totalEl.value) || 0) - newPaid));
    document.getElementById('dAddPaymentInput').value = '';
    alert('✓ ৳' + pay + ' টাকা জমা হিসেবে যোগ হয়েছে! সংরক্ষণ বাটনে চাপ দিন।');
}

function applyAdditionalDue() {
    const addDue = parseFloat(document.getElementById('dAddDueInput')?.value) || 0;
    if (addDue <= 0) return alert('সঠিক টাকার পরিমাণ দিন');
    const totalEl = document.getElementById('dEditTotal');
    const paidEl = document.getElementById('dEditPaid');
    const newTotal = (parseFloat(totalEl.value) || 0) + addDue;
    totalEl.value = newTotal;

    document.getElementById('dDisplayTotal').innerText = Math.round(newTotal);
    document.getElementById('dDisplayRem').innerText = Math.round(newTotal - (parseFloat(paidEl.value) || 0));
    document.getElementById('dAddDueInput').value = '';
    alert('✓ ৳' + addDue + ' টাকা অতিরিক্ত বাকি যোগ হয়েছে! সংরক্ষণ বাটনে চাপ দিন।');
}

async function saveEditDue(id) {
    const name = document.getElementById('dEditName').value.trim();
    const address = document.getElementById('dEditAddr').value.trim();
    const totalDue = parseFloat(document.getElementById('dEditTotal').value) || 0;
    const paidAmount = parseFloat(document.getElementById('dEditPaid').value) || 0;

    closeModal();

    await executeOrQueueApi(
        'EDIT_DUE',
        `/api/admin/customer-dues/${id}`,
        'PATCH',
        { name, address, totalDue, paidAmount },
        () => {
            const due = dataStore.dues.find(d => String(d.id) === String(id));
            if (due) {
                due.name = name;
                due.address = address;
                due.totalDue = totalDue;
                due.paidAmount = paidAmount;
            }
        }
    );

    if (navigator.onLine && !isCurrentlyOffline) fetchAllData();
}

// ── DAILY LEDGER MODALS & ACTIONS ──
function openAddLedgerModal() {
    const today = new Date().toISOString().split('T')[0];
    openModal('দৈনিক আয়-ব্যয় ক্যাশ খাতা 📒', `
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📅 হিসাবের তারিখ</span>
            </label>
            <input type="date" id="lDate" class="owner-input" value="${today}">
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>💰 সারাদিনের মোট বিক্রয় ও ক্যাশ জমা (৳)</span>
                <span class="req">*</span>
            </label>
            <input type="number" id="lSales" class="owner-input" placeholder="যেমন: 18500" required>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>🛒 দৈনিক বাজার খরচ (৳)</span>
                </label>
                <input type="number" id="lMarket" class="owner-input" placeholder="0">
            </div>
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>💵 স্টাফ বেতন ও অন্যান্য (৳)</span>
                </label>
                <input type="number" id="lSalary" class="owner-input" placeholder="0">
            </div>
        </div>

        <!-- Shomiti Expense Field (সমিতির কিস্তি / খরচ) -->
        <div class="owner-form-group" style="background:rgba(234,179,8,0.08);border:1px solid rgba(234,179,8,0.25);padding:0.75rem 1rem;border-radius:12px;">
            <label class="owner-form-label" style="color:#facc15;">
                <span>🏦 সমিতি কিস্তি / সমিতি খরচ (৳)</span>
            </label>
            <input type="number" id="lShomiti" class="owner-input" placeholder="0" value="0" style="margin-bottom:0;">
            <span style="font-size:0.72rem;color:#94a3b8;margin-top:4px;display:block;">* এই খরচটিও নিট লাভ থেকে স্বয়ংক্রিয়ভাবে বাদ যাবে।</span>
        </div>

        <button class="owner-btn-submit" onclick="saveNewLedger()">
            ✓ দৈনিক হিসাব সংরক্ষণ করুন
        </button>
    `);
}

async function saveNewLedger() {
    const date = document.getElementById('lDate').value;
    const totalSales = parseFloat(document.getElementById('lSales').value) || 0;
    const marketExpense = parseFloat(document.getElementById('lMarket').value) || 0;
    const salaryPaid = parseFloat(document.getElementById('lSalary').value) || 0;
    const shomitiExpense = parseFloat(document.getElementById('lShomiti').value) || 0;

    const tempId = 'ledg_off_' + Date.now();
    const newEntry = {
        id: tempId,
        date,
        totalSales,
        totalIncome: totalSales,
        marketExpense,
        salaryPaid,
        shomitiExpense,
        netProfit: totalSales - (marketExpense + salaryPaid + shomitiExpense)
    };

    closeModal();

    await executeOrQueueApi(
        'ADD_LEDGER',
        '/api/ledger',
        'POST',
        { date, totalSales, marketExpense, salaryPaid, shomitiExpense },
        () => {
            dataStore.ledger.unshift(newEntry);
        }
    );

    if (navigator.onLine && !isCurrentlyOffline) fetchAllData();
}

function openEditLedgerModal(id) {
    const l = dataStore.ledger.find(item => String(item.id) === String(id));
    if (!l) return;

    openModal('দৈনিক হিসাব সংশোধন 📒', `
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📅 হিসাবের তারিখ</span>
            </label>
            <input type="date" id="lEditDate" class="owner-input" value="${l.date || ''}">
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>💰 মোট বিক্রয় ও ক্যাশ জমা (৳)</span>
                <span class="req">*</span>
            </label>
            <input type="number" id="lEditSales" class="owner-input" value="${l.totalSales || l.totalIncome || 0}">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>🛒 দৈনিক বাজার খরচ (৳)</span>
                </label>
                <input type="number" id="lEditMarket" class="owner-input" value="${l.marketExpense || 0}">
            </div>
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>💵 স্টাফ বেতন ও অন্যান্য (৳)</span>
                </label>
                <input type="number" id="lEditSalary" class="owner-input" value="${l.salaryPaid || 0}">
            </div>
        </div>

        <!-- Shomiti Expense -->
        <div class="owner-form-group" style="background:rgba(234,179,8,0.08);border:1px solid rgba(234,179,8,0.25);padding:0.75rem 1rem;border-radius:12px;">
            <label class="owner-form-label" style="color:#facc15;">
                <span>🏦 সমিতি কিস্তি / সমিতি খরচ (৳)</span>
            </label>
            <input type="number" id="lEditShomiti" class="owner-input" value="${l.shomitiExpense || 0}" style="margin-bottom:0;">
        </div>

        <button class="owner-btn-submit" onclick="saveEditLedger('${l.id}')">
            ✓ সংশোধিত হিসাব সংরক্ষণ করুন
        </button>
    `);
}

async function saveEditLedger(id) {
    const date = document.getElementById('lEditDate').value;
    const totalSales = parseFloat(document.getElementById('lEditSales').value) || 0;
    const marketExpense = parseFloat(document.getElementById('lEditMarket').value) || 0;
    const salaryPaid = parseFloat(document.getElementById('lEditSalary').value) || 0;
    const shomitiExpense = parseFloat(document.getElementById('lEditShomiti').value) || 0;

    closeModal();

    await executeOrQueueApi(
        'EDIT_LEDGER',
        '/api/ledger',
        'POST',
        { date, totalSales, marketExpense, salaryPaid, shomitiExpense },
        () => {
            const entry = dataStore.ledger.find(item => String(item.id) === String(id));
            if (entry) {
                entry.date = date;
                entry.totalSales = totalSales;
                entry.totalIncome = totalSales;
                entry.marketExpense = marketExpense;
                entry.salaryPaid = salaryPaid;
                entry.shomitiExpense = shomitiExpense;
                entry.netProfit = totalSales - (marketExpense + salaryPaid + shomitiExpense);
            }
        }
    );

    if (navigator.onLine && !isCurrentlyOffline) fetchAllData();
}

function openAddStockModal() {
    openModal('কাঁচামাল ও কিচেন স্টক খাতা 📦', `
        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>📦 উপাদান বা মালের নাম</span>
                <span class="req">*</span>
            </label>
            <input type="text" id="sName" class="owner-input" placeholder="যেমন: চুইঝাল, বাসমতি চাল, সয়াবিন তেল" required>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>⚖️ মজুদের পরিমাণ</span>
                    <span class="req">*</span>
                </label>
                <input type="number" id="sQty" class="owner-input" placeholder="যেমন: 25" required>
            </div>
            <div class="owner-form-group">
                <label class="owner-form-label">
                    <span>🏷️ একক (Unit)</span>
                </label>
                <select id="sUnit" class="owner-form-select">
                    <option value="কেজি">কেজি (KG)</option>
                    <option value="লিটার">লিটার (Ltr)</option>
                    <option value="পিস">পিস (Piece)</option>
                    <option value="প্যাকেট">প্যাকেট (Pkt)</option>
                    <option value="বস্তা">বস্তা (Sack)</option>
                </select>
            </div>
        </div>

        <div class="owner-form-group">
            <label class="owner-form-label">
                <span>💰 সর্বশেষ ক্রয়মূল্য / দর (৳)</span>
            </label>
            <input type="number" id="sPrice" class="owner-input" placeholder="যেমন: 850">
        </div>

        <button class="owner-btn-submit" onclick="saveNewStock()">
            ✓ স্টক ইনভেন্টরি সংরক্ষণ করুন
        </button>
    `);
}

async function saveNewStock() {
    const name = document.getElementById('sName').value;
    const quantity = document.getElementById('sQty').value;
    const unit = document.getElementById('sUnit').value;
    const lastPrice = document.getElementById('sPrice').value;

    await fetch('/api/stock', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ name, quantity, unit, lastPrice })
    });
    closeModal();
    fetchAllData();
}
