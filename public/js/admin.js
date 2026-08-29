let currentTab = 'overview';
let dataStore = {
    menu: [],
    categories: [],
    orders: [],
    employees: [],
    dues: [],
    ledger: [],
    stock: []
};

document.addEventListener('DOMContentLoaded', () => {
    fetchAllData();
});

async function fetchAllData() {
    try {
        const [menuRes, catRes, orderRes, empRes, dueRes, ledgRes, stockRes] = await Promise.all([
            fetch('/api/menu/items').then(r => r.json()).catch(() => []),
            fetch('/api/menu/categories').then(r => r.json()).catch(() => []),
            fetch('/api/orders').then(r => r.json()).catch(() => []),
            fetch('/api/admin/employees').then(r => r.json()).catch(() => []),
            fetch('/api/admin/customer-dues').then(r => r.json()).catch(() => []),
            fetch('/api/ledger').then(r => r.json()).catch(() => []),
            fetch('/api/stock').then(r => r.json()).catch(() => [])
        ]);

        dataStore.menu = Array.isArray(menuRes) ? menuRes : [];
        dataStore.categories = Array.isArray(catRes) ? catRes : [];
        dataStore.orders = Array.isArray(orderRes) ? orderRes : [];
        dataStore.employees = Array.isArray(empRes) ? empRes : [];
        dataStore.dues = Array.isArray(dueRes) ? dueRes : [];
        dataStore.ledger = Array.isArray(ledgRes) ? ledgRes : [];
        dataStore.stock = Array.isArray(stockRes) ? stockRes : [];

        updatePendingBadge();
        renderCurrentTab();
    } catch(e) {
        console.error('Error fetching admin data:', e);
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
    document.querySelectorAll('.admin-nav-item').forEach(btn => btn.classList.remove('active'));
    if (el) el.classList.add('active');

    const titles = {
        overview: 'Overview',
        menu: 'Menu Items',
        orders: 'Online Orders',
        employees: 'Employees',
        dues: 'Customer Dues',
        ledger: 'ব্যক্তিগত আয়-ব্যয়',
        stock: 'Stock Items'
    };
    const titleEl = document.getElementById('tabTitle');
    if (titleEl) titleEl.innerText = titles[tab] || 'Dashboard';
    renderCurrentTab();
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

// ── 1. Overview Tab ──
function renderOverview(container) {
    const pending = dataStore.orders.filter(o => (o.status || '').toUpperCase() === 'PENDING').length;
    const totalSalaryDue = dataStore.employees.reduce((acc, e) => acc + (parseFloat(e.salaryDue) || 0), 0);
    const totalCustomerDue = dataStore.dues.reduce((acc, d) => acc + ((parseFloat(d.totalDue) || 0) - (parseFloat(d.paidAmount) || 0)), 0);
    
    // Calculate total online sales
    const totalSales = dataStore.orders.reduce((acc, o) => {
        const amt = (parseFloat(o.totalAmount) > 0) ? parseFloat(o.totalAmount) : (o.items || []).reduce((sum, i) => sum + ((i.price || 0) * (i.quantity || i.qty || 1)), 0);
        return acc + amt;
    }, 0);

    // Calculate Ledger Income/Expense
    const totalIncome = dataStore.ledger.filter(l => l.type === 'INCOME' || l.type === 'DEPOSIT').reduce((acc, l) => acc + (parseFloat(l.amount) || 0), 0);
    const totalExpense = dataStore.ledger.filter(l => l.type === 'EXPENSE' || l.type === 'WITHDRAW').reduce((acc, l) => acc + (parseFloat(l.amount) || 0), 0);
    const netBalance = totalIncome - totalExpense;

    // Check low stock
    const lowStockItems = dataStore.stock.filter(s => parseFloat(s.quantity) <= parseFloat(s.minQuantity));

    container.innerHTML = `
        <!-- Atmospheric Ambient Welcome Banner -->
        <div style="background:linear-gradient(135deg, rgba(220,38,38,0.14) 0%, rgba(245,158,11,0.08) 50%, rgba(20,20,28,0.95) 100%);border:1px solid rgba(245,158,11,0.25);border-radius:1.5rem;padding:1.6rem 1.8rem;margin-bottom:1.75rem;position:relative;overflow:hidden;box-shadow:0 20px 40px rgba(0,0,0,0.5);">
            <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;background:radial-gradient(circle, rgba(245,158,11,0.2) 0%, transparent 70%);border-radius:50%;pointer-events:none;"></div>
            
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;position:relative;z-index:2;">
                <div>
                    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(34,197,94,0.12);border:1px solid rgba(34,197,94,0.3);padding:4px 12px;border-radius:9999px;font-size:0.78rem;font-weight:700;color:#4ade80;margin-bottom:0.75rem;">
                        <span class="pulse-beacon"></span>
                        <span>মামুন হোটেল • লাইভ কিচেন ও ডেলিভারি সিস্টেম সক্রিয়</span>
                    </div>
                    <h2 style="font-size:1.6rem;font-weight:900;color:#fff;margin:0 0 0.4rem 0;letter-spacing:-0.02em;">স্বাগতম, অ্যাডমিন ড্যাশবোর্ড 👑</h2>
                    <p style="color:#a1a1aa;font-size:0.85rem;margin:0;">রিয়েল-টাইম অর্ডার ট্র্যাকিং, মেনু আপডেট, হিসাব ও কর্মী পরিচালনা নিয়ন্ত্রণ কেন্দ্র।</p>
                </div>
                
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <button onclick="openAddMenuModal()" class="quick-action-btn primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span>নতুন খাবার যোগ</span>
                    </button>
                    <button onclick="switchTab('orders', document.querySelectorAll('.admin-nav-item')[2])" class="quick-action-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        <span>অর্ডার তালিকা</span>
                    </button>
                    <button onclick="openAddEmployeeModal()" class="quick-action-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        <span>কর্মী যোগ</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- 4 Animated KPI / Stat Cards -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(230px, 1fr));gap:1.25rem;margin-bottom:1.75rem;">
            <!-- KPI 1: Menu Items -->
            <div class="stat-box" onclick="switchTab('menu', document.querySelectorAll('.admin-nav-item')[1])" style="cursor:pointer;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:rgba(59,130,246,0.15);color:#60a5fa;border:1px solid rgba(59,130,246,0.3);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
                    </div>
                    <span style="font-size:0.72rem;font-weight:700;color:#60a5fa;background:rgba(59,130,246,0.1);padding:3px 8px;border-radius:6px;">মেনু লিস্ট</span>
                </div>
                <h3 style="font-size:1.85rem;font-weight:900;color:#fff;margin:0 0 4px 0;font-family:'Outfit',sans-serif;">${dataStore.menu.length} <span style="font-size:1rem;color:#a1a1aa;font-weight:600;">টি পদ</span></h3>
                <p style="color:#71717a;font-size:0.78rem;margin:0;">${dataStore.menu.filter(m => m.isAvailable !== false).length} টি সক্রিয় স্টকে আছে</p>
            </div>

            <!-- KPI 2: Pending Orders -->
            <div class="stat-box" onclick="switchTab('orders', document.querySelectorAll('.admin-nav-item')[2])" style="cursor:pointer;${pending > 0 ? 'border-color:rgba(245,158,11,0.4);background:linear-gradient(180deg, rgba(245,158,11,0.06) 0%, #15151c 100%);' : ''}">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.3);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    ${pending > 0 
                        ? '<span style="font-size:0.72rem;font-weight:800;color:#facc15;background:rgba(234,179,8,0.2);border:1px solid rgba(234,179,8,0.4);padding:3px 8px;border-radius:6px;animation:beaconPulse 1.5s infinite;">🚨 অ্যাকশন দিন</span>'
                        : '<span style="font-size:0.72rem;font-weight:700;color:#4ade80;background:rgba(34,197,94,0.1);padding:3px 8px;border-radius:6px;">সব ক্লিয়ার</span>'
                    }
                </div>
                <h3 style="font-size:1.85rem;font-weight:900;color:#facc15;margin:0 0 4px 0;font-family:'Outfit',sans-serif;">${pending} <span style="font-size:1rem;color:#a1a1aa;font-weight:600;">টি পেন্ডিং</span></h3>
                <p style="color:#71717a;font-size:0.78rem;margin:0;">অনলাইন অর্ডার দ্রুত কিচেনে পাঠান</p>
            </div>

            <!-- KPI 3: Total Online Sales -->
            <div class="stat-box" style="cursor:pointer;" onclick="switchTab('orders', document.querySelectorAll('.admin-nav-item')[2])">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.3);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <span style="font-size:0.72rem;font-weight:700;color:#4ade80;background:rgba(34,197,94,0.1);padding:3px 8px;border-radius:6px;">বিক্রয়</span>
                </div>
                <h3 style="font-size:1.85rem;font-weight:900;color:#4ade80;margin:0 0 4px 0;font-family:'Outfit',sans-serif;">৳${Math.round(totalSales).toLocaleString()}</h3>
                <p style="color:#71717a;font-size:0.78rem;margin:0;">মোট ${dataStore.orders.length} টি অর্ডার থেকে আয়</p>
            </div>

            <!-- KPI 4: Total Dues -->
            <div class="stat-box" onclick="switchTab('dues', document.querySelectorAll('.admin-nav-item')[4])" style="cursor:pointer;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.75rem;">
                    <div style="width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:rgba(239,68,68,0.15);color:#f87171;border:1px solid rgba(239,68,68,0.3);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    </div>
                    <span style="font-size:0.72rem;font-weight:700;color:#f87171;background:rgba(239,68,68,0.1);padding:3px 8px;border-radius:6px;">বাকি ও বেতন</span>
                </div>
                <h3 style="font-size:1.85rem;font-weight:900;color:#f87171;margin:0 0 4px 0;font-family:'Outfit',sans-serif;">৳${Math.round(totalCustomerDue + totalSalaryDue).toLocaleString()}</h3>
                <p style="color:#71717a;font-size:0.78rem;margin:0;">কাস্টমার বাকি: ৳${Math.round(totalCustomerDue)} | বেতন: ৳${Math.round(totalSalaryDue)}</p>
            </div>
        </div>

        <!-- 2-Column Section: Orders & Financial Widgets -->
        <div style="display:grid;grid-template-columns:2.1fr 1fr;gap:1.5rem;align-items:start;">
            <!-- Left: Recent Online Orders -->
            <div class="stat-box" style="padding:1.5rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;">
                    <h3 style="font-size:1.15rem;font-weight:800;color:#fff;display:flex;align-items:center;gap:10px;margin:0;">
                        <div style="width:34px;height:34px;border-radius:8px;background:rgba(220,38,38,0.2);display:flex;align-items:center;justify-content:center;color:#ef4444;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        </div>
                        <span>সাম্প্রতিক অনলাইন অর্ডারসমূহ</span>
                    </h3>
                    <button onclick="switchTab('orders', document.querySelectorAll('.admin-nav-item')[2])" style="background:transparent;border:none;color:#60a5fa;font-size:0.82rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                        <span>সব অর্ডার দেখুন ↗</span>
                    </button>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>অর্ডার ও কাস্টমার</th>
                                <th>ঠিকানা ও জিপিএস</th>
                                <th>মোট টাকা</th>
                                <th>স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${dataStore.orders.slice(0, 5).map(o => {
                                const total = (parseFloat(o.totalAmount) > 0) ? parseFloat(o.totalAmount) : (o.items || []).reduce((acc, i) => acc + ((i.price || 0) * (i.quantity || i.qty || 1)), 0);
                                const shortId = o.shortId || ('MR-' + (o.id || '').substring(0, 6).toUpperCase());
                                const st = (o.status || 'PENDING').toUpperCase();

                                let badgeColor = 'rgba(234,179,8,0.15)';
                                let textColor = '#facc15';
                                let borderC = 'rgba(234,179,8,0.3)';

                                if (st === 'PREPARING') { badgeColor = 'rgba(59,130,246,0.15)'; textColor = '#60a5fa'; borderC = 'rgba(59,130,246,0.3)'; }
                                else if (st === 'OUT_FOR_DELIVERY') { badgeColor = 'rgba(168,85,247,0.15)'; textColor = '#c084fc'; borderC = 'rgba(168,85,247,0.3)'; }
                                else if (st === 'DELIVERED') { badgeColor = 'rgba(34,197,94,0.15)'; textColor = '#4ade80'; borderC = 'rgba(34,197,94,0.3)'; }
                                else if (st === 'CANCELLED') { badgeColor = 'rgba(239,68,68,0.15)'; textColor = '#f87171'; borderC = 'rgba(239,68,68,0.3)'; }

                                return `
                                    <tr>
                                        <td>
                                            <a href="/track?id=${shortId}" target="_blank" style="color:#60a5fa;font-family:'Outfit',sans-serif;font-weight:800;font-size:0.8rem;text-decoration:none;display:inline-block;margin-bottom:2px;">${shortId} ↗</a>
                                            <div style="color:#fff;font-weight:700;font-size:0.92rem;">${o.customerName || 'N/A'}</div>
                                            <div style="color:#a1a1aa;font-size:0.78rem;">${o.phoneNumber || ''}</div>
                                        </td>
                                        <td>
                                            ${formatAdminAddress(o.address)}
                                        </td>
                                        <td style="color:#f59e0b;font-weight:900;font-size:1rem;font-family:'Outfit',sans-serif;">
                                            ৳${total}
                                        </td>
                                        <td>
                                            <span style="padding:0.35rem 0.8rem;border-radius:9999px;font-size:0.75rem;font-weight:800;background:${badgeColor};color:${textColor};border:1px solid ${borderC};white-space:nowrap;">
                                                ${st}
                                            </span>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                            ${dataStore.orders.length === 0 ? '<tr><td colspan="4" style="padding:2.5rem;text-align:center;color:#71717a;">এখনও কোনো অনলাইন অর্ডার আসেনি।</td></tr>' : ''}
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Column: Quick Financials & Inventory Alerts -->
            <div style="display:flex;flex-direction:column;gap:1.5rem;">
                <!-- Financial Overview Widget -->
                <div class="stat-box" style="padding:1.4rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                        <h4 style="font-size:1rem;font-weight:800;color:#fff;margin:0;display:flex;align-items:center;gap:8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            <span>হিসাব-নিকাশ সারসংক্ষেপ</span>
                        </h4>
                        <button onclick="switchTab('ledger', document.querySelectorAll('.admin-nav-item')[5])" style="background:none;border:none;color:#60a5fa;font-size:0.75rem;font-weight:700;cursor:pointer;">বিস্তারিত ↗</button>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:0.75rem;">
                        <div style="display:flex;justify-content:space-between;align-items:center;background:#1c1c24;padding:0.75rem 1rem;border-radius:10px;border:1px solid rgba(255,255,255,0.05);">
                            <span style="color:#a1a1aa;font-size:0.82rem;">মোট জমা / আয়</span>
                            <strong style="color:#4ade80;font-family:'Outfit',sans-serif;font-size:0.95rem;">+৳${totalIncome.toLocaleString()}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;background:#1c1c24;padding:0.75rem 1rem;border-radius:10px;border:1px solid rgba(255,255,255,0.05);">
                            <span style="color:#a1a1aa;font-size:0.82rem;">মোট খরচ / ব্যয়</span>
                            <strong style="color:#f87171;font-family:'Outfit',sans-serif;font-size:0.95rem;">-৳${totalExpense.toLocaleString()}</strong>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;background:rgba(245,158,11,0.08);padding:0.85rem 1rem;border-radius:10px;border:1px solid rgba(245,158,11,0.25);">
                            <span style="color:#fbbf24;font-size:0.85rem;font-weight:700;">ক্যাশ ব্যালেন্স (নিট)</span>
                            <strong style="color:#fbbf24;font-family:'Outfit',sans-serif;font-size:1.1rem;font-weight:900;">৳${netBalance.toLocaleString()}</strong>
                        </div>
                    </div>
                </div>

                <!-- Stock Inventory Health Widget -->
                <div class="stat-box" style="padding:1.4rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                        <h4 style="font-size:1rem;font-weight:800;color:#fff;margin:0;display:flex;align-items:center;gap:8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                            <span>স্টক সতর্কতা (Inventory)</span>
                        </h4>
                        <button onclick="switchTab('stock', document.querySelectorAll('.admin-nav-item')[6])" style="background:none;border:none;color:#60a5fa;font-size:0.75rem;font-weight:700;cursor:pointer;">স্টক দেখুন ↗</button>
                    </div>

                    ${lowStockItems.length > 0 ? `
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            ${lowStockItems.slice(0, 3).map(s => `
                                <div style="display:flex;justify-content:space-between;align-items:center;background:rgba(239,68,68,0.1);padding:0.6rem 0.85rem;border-radius:8px;border:1px solid rgba(239,68,68,0.25);">
                                    <span style="color:#fca5a5;font-weight:700;font-size:0.85rem;">${s.name}</span>
                                    <span style="color:#f87171;font-size:0.78rem;font-weight:800;">বাকি: ${s.quantity} ${s.unit}</span>
                                </div>
                            `).join('')}
                        </div>
                    ` : `
                        <div style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.25);border-radius:10px;padding:1rem;text-align:center;">
                            <div style="font-size:1.2rem;margin-bottom:4px;">✨</div>
                            <p style="color:#4ade80;font-size:0.82rem;font-weight:700;margin:0;">সব কাঁচামাল পর্যাপ্ত পরিমাণে স্টকে রয়েছে!</p>
                        </div>
                    `}
                </div>
            </div>
        </div>
    `;
}

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
    if (item.image && item.image.trim() !== '') return item.image;
    const name = item.name || '';
    for (const [kw, url] of Object.entries(adminFoodImages)) {
        if (name.includes(kw)) return url;
    }
    return 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=400&q=80';
}

// ── 2. Menu Tab ──
function renderMenuTab(container) {
    container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 style="font-size:1.15rem;font-weight:800;color:#fff;">মেনু তালিকা ও মূল্য ব্যবস্থাপনা</h3>
                <p style="color:#a1a1aa;font-size:0.8rem;">মোট ${dataStore.menu.length} টি মেনু আইটেম (মূল্য, ছবি বা স্টক পরিবর্তন করতে "এডিট" চাপুন)</p>
            </div>
            <button class="btn btn-sm btn-primary" onclick="openAddMenuModal()" style="display:inline-flex;align-items:center;gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>নতুন আইটেম যোগ</span>
            </button>
        </div>
        <div class="table-wrap" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:0.875rem;">
                <thead style="background:#27272a;">
                    <tr>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">ছবি</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">খাবারের নাম</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">ক্যাটাগরি</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">মূল্য (৳)</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">স্টক স্ট্যাটাস</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">অ্যাকশন</th>
                    </tr>
                </thead>
                <tbody>
                    ${dataStore.menu.map(item => {
                        const img = getAdminDishImage(item);
                        const isAvail = (item.isAvailable === true || item.is_available === true || item.isAvailable === 1 || item.is_available === 1);
                        const isFeat = (item.isFeatured === true || item.is_featured === true || item.isFeatured === 1 || item.is_featured === 1);

                        return `
                            <tr style="border-top:1px solid #27272a;">
                                <td style="padding:0.75rem 1rem;">
                                    <img src="${img}" style="width:48px;height:48px;border-radius:10px;object-fit:cover;border:1px solid rgba(255,255,255,0.1);" alt="${item.name}">
                                </td>
                                <td style="padding:0.75rem 1rem;">
                                    <strong style="color:#fff;font-size:0.95rem;">${item.name}</strong> 
                                    ${isFeat ? '<span style="background:rgba(245,158,11,0.15);color:#fbbf24;border:1px solid rgba(245,158,11,0.3);padding:2px 6px;border-radius:4px;font-size:0.7rem;font-weight:800;margin-left:4px;">★ Featured</span>' : ''}
                                    ${item.description ? `<p style="font-size:0.75rem;color:#71717a;margin-top:2px;">${item.description}</p>` : ''}
                                </td>
                                <td style="padding:0.75rem 1rem;">
                                    <span style="background:#27272a;border:1px solid #3f3f46;padding:0.25rem 0.6rem;border-radius:6px;font-size:0.75rem;color:#cbd5e1;font-weight:600;">${item.category ? item.category.name : 'সাধারণ'}</span>
                                </td>
                                <td style="padding:0.75rem 1rem;color:#f59e0b;font-weight:900;font-size:1.05rem;font-family:'Outfit',sans-serif;">
                                    ৳${item.price}
                                </td>
                                <td style="padding:0.75rem 1rem;">
                                    <button onclick="toggleMenuAvailability('${item.id}', ${isAvail})" style="background:${isAvail ? 'rgba(34,197,94,0.15)' : 'rgba(239,68,68,0.15)'};color:${isAvail ? '#4ade80' : '#f87171'};border:1px solid ${isAvail ? 'rgba(34,197,94,0.35)' : 'rgba(239,68,68,0.35)'};padding:0.35rem 0.8rem;border-radius:9999px;font-size:0.78rem;font-weight:800;cursor:pointer;display:inline-flex;align-items:center;gap:5px;" title="ক্লিক করে স্টক অন/অফ করুন">
                                        <span>${isAvail ? '🟢 Active' : '🔴 স্টক শেষ'}</span>
                                    </button>
                                </td>
                                <td style="padding:0.75rem 1rem;">
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <button onclick="openEditMenuModal('${item.id}')" style="background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.35);color:#60a5fa;padding:0.4rem 0.75rem;border-radius:8px;cursor:pointer;font-size:0.8rem;font-weight:700;display:inline-flex;align-items:center;gap:4px;" title="এডিট করুন">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                            <span>এডিট</span>
                                        </button>
                                        <button onclick="deleteMenuItem('${item.id}')" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.35);color:#f87171;padding:0.4rem 0.6rem;border-radius:8px;cursor:pointer;" title="মুছে ফেলুন">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }).join('')}
                    ${dataStore.menu.length === 0 ? '<tr><td colspan="6" style="padding:2.5rem;text-align:center;color:#71717a;">কোনো মেনু আইটেম পাওয়া যায়নি।</td></tr>' : ''}
                </tbody>
            </table>
        </div>
    `;
}

function formatAdminAddress(rawAddr) {
    if (!rawAddr) return '<span style="color:#71717a;">ঠিকানা নেই</span>';
    let text = rawAddr.trim();
    
    // Extract map URL
    const mapRegex = /(?:\[(?:ম্যাপ|ম্যাপ লিংক|Google Maps Pin|Google Maps|GPS)\s*:\s*)?(https:\/\/maps\.google\.com\/\?q=[^\]\s\n]+)\]?/i;
    const match = text.match(mapRegex);
    let mapUrl = match ? match[1] : null;
    
    // Clean text by stripping map url and bracketed part
    let cleanText = text.replace(/\[.*?(https:\/\/maps\.google\.com[^\s\]]+).*?\]/gi, '')
                        .replace(/https:\/\/maps\.google\.com\/\?q=[^\s]+/gi, '')
                        .replace(/\s+/g, ' ')
                        .replace(/^[-,\s]+|[-,\s]+$/g, '');
    
    if (!cleanText) cleanText = 'লাইভ জিপিএস লোকেশন';

    return `
        <div style="line-height:1.4;">
            <span style="color:#cbd5e1;">${cleanText}</span>
            ${mapUrl ? `
                <div style="margin-top:6px;">
                    <a href="${mapUrl}" target="_blank" style="display:inline-flex;align-items:center;gap:4px;background:rgba(59,130,246,0.15);color:#60a5fa;border:1px solid rgba(59,130,246,0.3);padding:3px 8px;border-radius:6px;font-size:0.75rem;font-weight:700;text-decoration:none;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>লাইভ ম্যাপ পিন ↗</span>
                    </a>
                </div>
            ` : ''}
        </div>
    `;
}

// ── 3. Orders Tab ──
function renderOrdersTab(container) {
    container.innerHTML = `
        <div class="table-wrap" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:0.875rem;">
                <thead style="background:#27272a;">
                    <tr>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Order ID</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Customer</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Phone & Address</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Items</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Total</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Live Status</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${dataStore.orders.map(o => {
                        const shortId = o.shortId || ('MR-' + (o.id ? o.id.substring(0, 6).toUpperCase() : ''));
                        const total = (parseFloat(o.totalAmount) > 0) ? parseFloat(o.totalAmount) : (o.items || []).reduce((acc, i) => acc + ((i.price || 0) * (i.quantity || i.qty || 1)), 0);
                        const status = (o.status || 'PENDING').toUpperCase();

                        return `
                            <tr style="border-top:1px solid #27272a;">
                                <td style="padding:0.85rem 1rem;">
                                    <a href="/track?id=${shortId}" target="_blank" style="font-family:'Outfit',monospace;color:#f59e0b;font-weight:800;font-size:0.9rem;text-decoration:none;display:inline-flex;align-items:center;gap:4px;" title="গ্রাহকের ট্র্যাকিং ভিউ দেখুন">
                                        <span>${shortId}</span>
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                    </a>
                                </td>
                                <td style="padding:0.85rem 1rem;">
                                    <strong style="color:#fff;font-size:0.95rem;">${o.customerName || 'N/A'}</strong>
                                    ${o.note ? `<p style="font-size:0.75rem;color:#f59e0b;margin-top:2px;">📝 ${o.note}</p>` : ''}
                                </td>
                                <td style="padding:0.85rem 1rem;font-size:0.82rem;color:#a1a1aa;max-width:260px;">
                                    <div style="color:#fff;font-weight:700;margin-bottom:3px;">📞 ${o.phoneNumber || 'N/A'}</div>
                                    ${formatAdminAddress(o.address)}
                                </td>
                                <td style="padding:0.85rem 1rem;font-size:0.8rem;color:#e4e4e7;">
                                    ${(o.items || []).map(i => `<div style="margin-bottom:2px;">• <strong>${i.name || i.menu_item_name}</strong> × ${i.quantity || i.qty}</div>`).join('')}
                                </td>
                                <td style="padding:0.85rem 1rem;color:#f59e0b;font-weight:800;font-size:1.05rem;font-family:'Outfit',sans-serif;">
                                    ৳${total}
                                </td>
                                <td style="padding:0.85rem 1rem;">
                                    <select onchange="updateOrderStatus('${o.id}', this.value)" style="background:#27272a;color:#fff;border:1px solid #3f3f46;padding:0.4rem 0.6rem;border-radius:8px;font-size:0.8rem;font-weight:700;cursor:pointer;">
                                        <option value="PENDING" ${status === 'PENDING' ? 'selected' : ''}>⏳ অর্ডার গৃহীত (PENDING)</option>
                                        <option value="PREPARING" ${status === 'PREPARING' || status === 'COOKING' ? 'selected' : ''}>👨‍🍳 রান্না চলছে (PREPARING)</option>
                                        <option value="OUT_FOR_DELIVERY" ${status === 'OUT_FOR_DELIVERY' || status === 'SHIPPING' ? 'selected' : ''}>🛵 ডেলিভারিতে বের হয়েছে (ON THE WAY)</option>
                                        <option value="DELIVERED" ${status === 'DELIVERED' ? 'selected' : ''}>✅ ডেলিভারি সম্পন্ন (DELIVERED)</option>
                                        <option value="CANCELLED" ${status === 'CANCELLED' ? 'selected' : ''}>❌ বাতিল (CANCELLED)</option>
                                    </select>
                                </td>
                                <td style="padding:0.85rem 1rem;">
                                    <button onclick="deleteOrder('${o.id}')" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#f87171;padding:0.35rem 0.6rem;border-radius:6px;cursor:pointer;" title="অর্ডার মুছুন">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('')}
                    ${dataStore.orders.length === 0 ? '<tr><td colspan="7" style="padding:2.5rem;text-align:center;color:#71717a;">কোনো নতুন অর্ডার নেই।</td></tr>' : ''}
                </tbody>
            </table>
        </div>
    `;
}

// ── 4. Employees Tab ──
function renderEmployeesTab(container) {
    container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <p style="color:#a1a1aa;font-size:0.875rem;">${dataStore.employees.length} জন কর্মী</p>
            <button class="btn btn-sm btn-primary" onclick="openAddEmployeeModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>নতুন কর্মী যোগ</span>
            </button>
        </div>
        <div class="grid grid-3">
            ${dataStore.employees.map(e => `
                <div class="stat-box" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;padding:1.25rem;">
                    <div class="flex justify-between items-center mb-2">
                        <h4 style="font-weight:700;font-size:1.1rem;color:#fff;">${e.name}</h4>
                        <button onclick="deleteEmployee('${e.id}')" style="color:#ef4444;font-size:0.85rem;" title="Delete">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                    <p style="color:#a1a1aa;font-size:0.75rem;margin-bottom:0.75rem;">${e.position} • ${e.phone || 'N/A'}</p>
                    <div class="flex justify-between" style="background:#27272a;padding:0.75rem;border-radius:8px;font-size:0.85rem;">
                        <span>বেতন: <strong style="color:#fff;">৳${e.salary}</strong></span>
                        <span style="color:${e.salaryDue > 0 ? '#f87171' : '#4ade80'};">বাকি: <strong>৳${e.salaryDue}</strong></span>
                    </div>
                </div>
            `).join('')}
            ${dataStore.employees.length === 0 ? '<div style="grid-column:span 3;text-align:center;color:#71717a;padding:3rem;">কোনো কর্মী পাওয়া যায়নি।</div>' : ''}
        </div>
    `;
}

// ── 5. Dues Tab ──
function renderDuesTab(container) {
    container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <p style="color:#a1a1aa;font-size:0.875rem;">${dataStore.dues.length} জন কাস্টমার ডিউ</p>
            <button class="btn btn-sm btn-primary" onclick="openAddDueModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>নতুন ডিউ যোগ</span>
            </button>
        </div>
        <div class="grid grid-3">
            ${dataStore.dues.map(d => {
                const rem = (parseFloat(d.totalDue) || 0) - (parseFloat(d.paidAmount) || 0);
                return `
                    <div class="stat-box" style="background:#18181b;border:1px solid ${rem > 0 ? 'rgba(239,68,68,0.3)' : 'rgba(34,197,94,0.3)'};border-radius:1rem;padding:1.25rem;">
                        <div class="flex justify-between items-center mb-2">
                            <h4 style="font-weight:700;color:#fff;">${d.name}</h4>
                            <button onclick="deleteDue('${d.id}')" style="color:#ef4444;" title="Delete">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                        <p style="color:#a1a1aa;font-size:0.75rem;margin-bottom:0.75rem;">${(d.address || d.phone || 'N/A').split('\n').join(', ')}</p>
                        <div class="flex justify-between" style="font-size:0.75rem;color:#a1a1aa;margin-bottom:0.5rem;">
                            <span>মোট: ৳${Math.round(d.totalDue)}</span>
                            <span>পরিশোধ: ৳${Math.round(d.paidAmount)}</span>
                        </div>
                        <p style="font-weight:700;font-size:0.875rem;color:${rem > 0 ? '#f87171' : '#4ade80'};text-align:right;">
                            ${rem > 0 ? `বাকি: ৳${Math.round(rem)}` : 'পরিশোধ সম্পন্ন'}
                        </p>
                    </div>
                `;
            }).join('')}
            ${dataStore.dues.length === 0 ? '<div style="grid-column:span 3;text-align:center;color:#71717a;padding:3rem;">কোনো কাস্টমার ডিউ নেই।</div>' : ''}
        </div>
    `;
}

// ── 6. Ledger Tab ──
function renderLedgerTab(container) {
    const totalProfit = dataStore.ledger.reduce((acc, l) => acc + (parseFloat(l.netProfit) || 0), 0);

    container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <div>
                <p style="color:#a1a1aa;font-size:0.75rem;">সর্বমোট নিট লাভ/লোকসান:</p>
                <h2 style="font-size:1.5rem;font-weight:800;color:${totalProfit >= 0 ? '#4ade80' : '#f87171'};">৳${Math.round(totalProfit).toLocaleString()}</h2>
            </div>
            <button class="btn btn-sm btn-primary" onclick="openAddLedgerModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>নতুন দৈনিক হিসাব</span>
            </button>
        </div>
        <div class="table-wrap" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:0.875rem;">
                <thead style="background:#27272a;">
                    <tr>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">তারিখ</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">আয় (বিক্রয় + আদায়)</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">ব্যয় (বাজার+বেতন+অন্যান্য)</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">লাভ/লস</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${dataStore.ledger.map(l => `
                        <tr style="border-top:1px solid #27272a;">
                            <td style="padding:0.75rem 1rem;"><strong style="color:#fff;">${l.date}</strong></td>
                            <td style="padding:0.75rem 1rem;color:#4ade80;font-weight:700;">৳${Math.round(l.totalIncome || 0)}</td>
                            <td style="padding:0.75rem 1rem;color:#f87171;font-weight:700;">৳${Math.round(l.totalExpense || 0)}</td>
                            <td style="padding:0.75rem 1rem;font-weight:800;color:${l.netProfit >= 0 ? '#4ade80' : '#f87171'};">৳${Math.round(l.netProfit || 0)}</td>
                            <td style="padding:0.75rem 1rem;">
                                <button onclick="deleteLedger('${l.id}')" style="color:#ef4444;" title="Delete">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </td>
                        </tr>
                    `).join('')}
                    ${dataStore.ledger.length === 0 ? '<tr><td colspan="5" style="padding:2rem;text-align:center;color:#71717a;">কোনো হিসাব পাওয়া যায়নি।</td></tr>' : ''}
                </tbody>
            </table>
        </div>
    `;
}

// ── 7. Stock Tab ──
function renderStockTab(container) {
    const totalVal = dataStore.stock.reduce((acc, s) => acc + ((parseFloat(s.quantity) || 0) * (parseFloat(s.lastPrice) || 0)), 0);

    container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <div>
                <p style="color:#a1a1aa;font-size:0.75rem;">স্টকের আনুমানিক মোট মূল্য:</p>
                <h2 style="font-size:1.5rem;font-weight:800;color:#60a5fa;">৳${Math.round(totalVal).toLocaleString()}</h2>
            </div>
            <button class="btn btn-sm btn-primary" onclick="openAddStockModal()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>নতুন স্টক যোগ</span>
            </button>
        </div>
        <div class="table-wrap" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:0.875rem;">
                <thead style="background:#27272a;">
                    <tr>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">আইটেম</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">বর্তমান পরিমাণ</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">সর্বনিম্ন সীমা</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">দর</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${dataStore.stock.map(s => `
                        <tr style="border-top:1px solid #27272a;">
                            <td style="padding:0.75rem 1rem;"><strong style="color:#fff;">${s.name}</strong> ${s.isLowStock ? '<span style="color:#f87171;font-size:0.75rem;">[Low Stock]</span>' : ''}</td>
                            <td style="padding:0.75rem 1rem;font-weight:700;color:${s.isLowStock ? '#f87171' : '#4ade80'};">${s.quantity} ${s.unit}</td>
                            <td style="padding:0.75rem 1rem;color:#71717a;">${s.minQuantity} ${s.unit}</td>
                            <td style="padding:0.75rem 1rem;color:#ef4444;font-weight:700;">৳${s.lastPrice}</td>
                            <td style="padding:0.75rem 1rem;">
                                <button onclick="deleteStock('${s.id}')" style="color:#ef4444;" title="Delete">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </td>
                        </tr>
                    `).join('')}
                    ${dataStore.stock.length === 0 ? '<tr><td colspan="5" style="padding:2rem;text-align:center;color:#71717a;">কোনো স্টক আইটেম নেই।</td></tr>' : ''}
                </tbody>
            </table>
        </div>
    `;
}

// ── Actions & API Calls ──
async function updateOrderStatus(id, status) {
    await fetch(`/api/admin/orders/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ status })
    });
    fetchAllData();
}

async function toggleMenuAvailability(id, current) {
    await fetch(`/api/admin/menu/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ isAvailable: !current })
    });
    fetchAllData();
}

async function deleteMenuItem(id) { if (confirm('মুছে ফেলতে চান?')) { await fetch(`/api/admin/menu/${id}`, { method: 'DELETE' }); fetchAllData(); } }
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

    openModal('খাবারের তথ্য ও মূল্য এডিট', `
        <div class="form-group mb-3">
            <label class="form-label" style="font-weight:700;color:#cbd5e1;margin-bottom:4px;display:block;">খাবারের নাম *</label>
            <input type="text" id="mEditName" class="form-input" value="${item.name || ''}" style="width:100%;padding:0.75rem 1rem;background:#27272a;border:1px solid #3f3f46;border-radius:8px;color:#fff;" required>
        </div>
        
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#cbd5e1;margin-bottom:4px;display:block;">মূল্য (৳) *</label>
                <input type="number" id="mEditPrice" class="form-input" value="${item.price || 0}" style="width:100%;padding:0.75rem 1rem;background:#27272a;border:1px solid #3f3f46;border-radius:8px;color:#fff;" required>
            </div>
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#cbd5e1;margin-bottom:4px;display:block;">ক্যাটাগরি</label>
                <select id="mEditCat" class="form-input" style="width:100%;padding:0.75rem 1rem;background:#27272a;border:1px solid #3f3f46;border-radius:8px;color:#fff;">
                    ${categories.map(c => `
                        <option value="${c.id}" ${(item.categoryId === c.id || (item.category && item.category.id === c.id)) ? 'selected' : ''}>${c.name}</option>
                    `).join('')}
                </select>
            </div>
        </div>

        <!-- Direct Image Upload & URL -->
        <div class="form-group mb-3">
            <label class="form-label" style="font-weight:700;color:#cbd5e1;margin-bottom:6px;display:block;">খাবারের ছবি (Direct Upload / Link)</label>
            
            <div style="background:rgba(255,255,255,0.03);border:2px dashed #3f3f46;border-radius:12px;padding:1.1rem;text-align:center;margin-bottom:0.75rem;cursor:pointer;transition:all 0.2s;" onclick="document.getElementById('mEditFileInput').click()">
                <div style="display:flex;flex-direction:column;align-items:center;gap:6px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;color:#60a5fa;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <span style="font-weight:700;color:#fff;font-size:0.92rem;">ডিভাইস / ফোন থেকে সরাসরি ছবি দিন 📷</span>
                    <span style="font-size:0.75rem;color:#a1a1aa;">যেকোনো ছবি (JPG, PNG, WebP, ক্যামেরা ফটো) সিলেক্ট করুন</span>
                </div>
            </div>
            <input type="file" id="mEditFileInput" accept="image/*" style="display:none;" onchange="handleDirectImageUpload(this, 'mEditImage', 'imgPreviewEl', 'mEditUploadStatus')">
            <div id="mEditUploadStatus" style="font-size:0.8rem;color:#4ade80;font-weight:700;margin-bottom:6px;display:none;"></div>

            <div style="display:flex;align-items:center;gap:10px;margin-top:6px;">
                <input type="text" id="mEditImage" class="form-input" value="${item.image || ''}" placeholder="বা ছবির লিংক পেস্ট করুন..." oninput="previewModalImage(this.value, 'imgPreviewEl')" style="flex:1;padding:0.65rem 0.85rem;background:#27272a;border:1px solid #3f3f46;border-radius:8px;color:#fff;font-size:0.82rem;">
                <img id="imgPreviewEl" src="${img}" style="width:65px;height:48px;border-radius:8px;object-fit:cover;border:1px solid #3f3f46;flex-shrink:0;" alt="Preview">
            </div>
        </div>

        <div class="form-group mb-3">
            <label class="form-label" style="font-weight:700;color:#cbd5e1;margin-bottom:4px;display:block;">খাবারের বিবরণ (Description)</label>
            <textarea id="mEditDesc" class="form-input" rows="2" style="width:100%;padding:0.75rem 1rem;background:#27272a;border:1px solid #3f3f46;border-radius:8px;color:#fff;resize:vertical;">${item.description || ''}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;background:#27272a;padding:0.85rem 1rem;border-radius:10px;border:1px solid #3f3f46;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.85rem;font-weight:700;color:#fff;">
                <input type="checkbox" id="mEditAvail" ${isAvail ? 'checked' : ''} style="width:18px;height:18px;">
                <span>🟢 সহজলভ্য (Available)</span>
            </label>
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.85rem;font-weight:700;color:#facc15;">
                <input type="checkbox" id="mEditFeatured" ${isFeat ? 'checked' : ''} style="width:18px;height:18px;">
                <span>★ স্পেশাল (Featured)</span>
            </label>
        </div>

        <button class="btn btn-primary btn-block" style="width:100%;padding:0.9rem;border-radius:10px;font-size:1rem;font-weight:800;" onclick="saveEditMenuItem('${item.id}')">পরিবর্তন সংরক্ষণ করুন</button>
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

    openModal('নতুন মেনু আইটেম যোগ', `
        <div class="form-group mb-3">
            <label class="form-label" style="font-weight:700;color:#cbd5e1;margin-bottom:4px;display:block;">খাবারের নাম *</label>
            <input type="text" id="mName" class="form-input" placeholder="যেমন: চুইঝালের খাসি" style="width:100%;padding:0.75rem 1rem;background:#27272a;border:1px solid #3f3f46;border-radius:8px;color:#fff;" required>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;">
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#cbd5e1;margin-bottom:4px;display:block;">মূল্য (৳) *</label>
                <input type="number" id="mPrice" class="form-input" placeholder="যেমন: 350" style="width:100%;padding:0.75rem 1rem;background:#27272a;border:1px solid #3f3f46;border-radius:8px;color:#fff;" required>
            </div>
            <div class="form-group">
                <label class="form-label" style="font-weight:700;color:#cbd5e1;margin-bottom:4px;display:block;">ক্যাটাগরি</label>
                <select id="mCat" class="form-input" style="width:100%;padding:0.75rem 1rem;background:#27272a;border:1px solid #3f3f46;border-radius:8px;color:#fff;">
                    ${categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                </select>
            </div>
        </div>

        <!-- Direct Image Upload & URL -->
        <div class="form-group mb-3">
            <label class="form-label" style="font-weight:700;color:#cbd5e1;margin-bottom:6px;display:block;">খাবারের ছবি (Direct Upload / Link)</label>
            
            <div style="background:rgba(255,255,255,0.03);border:2px dashed #3f3f46;border-radius:12px;padding:1.1rem;text-align:center;margin-bottom:0.75rem;cursor:pointer;transition:all 0.2s;" onclick="document.getElementById('mAddFileInput').click()">
                <div style="display:flex;flex-direction:column;align-items:center;gap:6px;">
                    <div style="width:40px;height:40px;border-radius:50%;background:rgba(59,130,246,0.15);display:flex;align-items:center;justify-content:center;color:#60a5fa;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </div>
                    <span style="font-weight:700;color:#fff;font-size:0.92rem;">ডিভাইস / ফোন থেকে সরাসরি ছবি দিন 📷</span>
                    <span style="font-size:0.75rem;color:#a1a1aa;">যেকোনো ছবি (JPG, PNG, WebP, ক্যামেরা ফটো) সিলেক্ট করুন</span>
                </div>
            </div>
            <input type="file" id="mAddFileInput" accept="image/*" style="display:none;" onchange="handleDirectImageUpload(this, 'mImage', 'addImgPreviewEl', 'mAddUploadStatus')">
            <div id="mAddUploadStatus" style="font-size:0.8rem;color:#4ade80;font-weight:700;margin-bottom:6px;display:none;"></div>

            <div style="display:flex;align-items:center;gap:10px;margin-top:6px;">
                <input type="text" id="mImage" class="form-input" placeholder="বা ছবির লিংক পেস্ট করুন..." oninput="previewModalImage(this.value, 'addImgPreviewEl')" style="flex:1;padding:0.65rem 0.85rem;background:#27272a;border:1px solid #3f3f46;border-radius:8px;color:#fff;font-size:0.82rem;">
                <img id="addImgPreviewEl" src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=400&q=80" style="width:65px;height:48px;border-radius:8px;object-fit:cover;border:1px solid #3f3f46;flex-shrink:0;" alt="Preview">
            </div>
        </div>

        <div class="form-group mb-3">
            <label class="form-label" style="font-weight:700;color:#cbd5e1;margin-bottom:4px;display:block;">বিবরণ</label>
            <textarea id="mDesc" class="form-input" rows="2" placeholder="খাবারের বিশেষত্ব..." style="width:100%;padding:0.75rem 1rem;background:#27272a;border:1px solid #3f3f46;border-radius:8px;color:#fff;resize:vertical;"></textarea>
        </div>
        <div class="form-group mb-4" style="background:#27272a;padding:0.75rem 1rem;border-radius:10px;border:1px solid #3f3f46;">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:0.85rem;font-weight:700;color:#facc15;">
                <input type="checkbox" id="mFeatured" style="width:18px;height:18px;">
                <span>★ স্পেশাল মেনু হিসেবে ফিচার করুন (Featured)</span>
            </label>
        </div>
        <button class="btn btn-primary btn-block" style="width:100%;padding:0.9rem;border-radius:10px;font-size:1rem;font-weight:800;" onclick="saveNewMenuItem()">সংরক্ষণ করুন</button>
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
            fetchAllData();
        } else {
            alert('মেনু যোগ করা সম্ভব হয়নি।');
        }
    } catch(e) {
        alert('নেটওয়ার্ক সমস্যা।');
    }
}

function openAddEmployeeModal() {
    openModal('নতুন কর্মী যোগ', `
        <div class="form-group"><label class="form-label">নাম</label><input type="text" id="eName" class="form-input"></div>
        <div class="form-group"><label class="form-label">পদবী</label><input type="text" id="ePos" class="form-input"></div>
        <div class="form-group"><label class="form-label">ফোন</label><input type="text" id="ePhone" class="form-input"></div>
        <div class="form-group"><label class="form-label">বেতন</label><input type="number" id="eSalary" class="form-input"></div>
        <button class="btn btn-primary btn-block mt-4" onclick="saveNewEmployee()">সংরক্ষণ করুন</button>
    `);
}

async function saveNewEmployee() {
    const name = document.getElementById('eName').value;
    const position = document.getElementById('ePos').value;
    const phone = document.getElementById('ePhone').value;
    const salary = document.getElementById('eSalary').value;
    if (!name || !position) return alert('নাম ও পদবী আবশ্যক');

    await fetch('/api/admin/employees', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ name, position, phone, salary })
    });
    closeModal();
    fetchAllData();
}

function openAddDueModal() {
    openModal('নতুন কাস্টমার ডিউ যোগ', `
        <div class="form-group"><label class="form-label">নাম</label><input type="text" id="dName" class="form-input"></div>
        <div class="form-group"><label class="form-label">ফোন / ঠিকানা</label><input type="text" id="dAddr" class="form-input"></div>
        <div class="form-group"><label class="form-label">মোট বাকি (৳)</label><input type="number" id="dDue" class="form-input"></div>
        <div class="form-group"><label class="form-label">জমা (৳)</label><input type="number" id="dPaid" class="form-input" value="0"></div>
        <button class="btn btn-primary btn-block mt-4" onclick="saveNewDue()">সংরক্ষণ করুন</button>
    `);
}

async function saveNewDue() {
    const name = document.getElementById('dName').value;
    const address = document.getElementById('dAddr').value;
    const totalDue = document.getElementById('dDue').value;
    const paidAmount = document.getElementById('dPaid').value;

    await fetch('/api/admin/customer-dues', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ name, address, totalDue, paidAmount })
    });
    closeModal();
    fetchAllData();
}

function openAddLedgerModal() {
    const today = new Date().toISOString().split('T')[0];
    openModal('নতুন দৈনিক হিসাব যোগ', `
        <div class="form-group"><label class="form-label">তারিখ</label><input type="date" id="lDate" class="form-input" value="${today}"></div>
        <div class="form-group"><label class="form-label">মোট বিক্রয় (৳)</label><input type="number" id="lSales" class="form-input" value="0"></div>
        <div class="form-group"><label class="form-label">বাজার খরচ (৳)</label><input type="number" id="lMarket" class="form-input" value="0"></div>
        <div class="form-group"><label class="form-label">বেতন প্রদান (৳)</label><input type="number" id="lSalary" class="form-input" value="0"></div>
        <button class="btn btn-primary btn-block mt-4" onclick="saveNewLedger()">সংরক্ষণ করুন</button>
    `);
}

async function saveNewLedger() {
    const date = document.getElementById('lDate').value;
    const totalSales = document.getElementById('lSales').value;
    const marketExpense = document.getElementById('lMarket').value;
    const salaryPaid = document.getElementById('lSalary').value;

    await fetch('/api/ledger', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ date, totalSales, marketExpense, salaryPaid })
    });
    closeModal();
    fetchAllData();
}

function openAddStockModal() {
    openModal('নতুন স্টক যোগ', `
        <div class="form-group"><label class="form-label">আইটেমের নাম</label><input type="text" id="sName" class="form-input"></div>
        <div class="form-group"><label class="form-label">পরিমাণ</label><input type="number" id="sQty" class="form-input"></div>
        <div class="form-group"><label class="form-label">একক (কেজি/লিটার/পিস)</label><input type="text" id="sUnit" class="form-input" value="কেজি"></div>
        <div class="form-group"><label class="form-label">দর (৳)</label><input type="number" id="sPrice" class="form-input"></div>
        <button class="btn btn-primary btn-block mt-4" onclick="saveNewStock()">সংরক্ষণ করুন</button>
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
