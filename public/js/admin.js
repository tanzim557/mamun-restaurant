// Admin Dashboard Logic: Robust, pure Vanilla JS for all 7 tabs & CRUD operations
let currentTab = 'overview';
let dataStore = {
    menu: [],
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
        const [menuRes, orderRes, empRes, dueRes, ledgRes, stockRes] = await Promise.all([
            fetch('/api/menu/items').then(r => r.json()).catch(() => []),
            fetch('/api/orders').then(r => r.json()).catch(() => []),
            fetch('/api/admin/employees').then(r => r.json()).catch(() => []),
            fetch('/api/admin/customer-dues').then(r => r.json()).catch(() => []),
            fetch('/api/ledger').then(r => r.json()).catch(() => []),
            fetch('/api/stock').then(r => r.json()).catch(() => [])
        ]);

        dataStore.menu = Array.isArray(menuRes) ? menuRes : [];
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

    container.innerHTML = `
        <div class="grid grid-4 mb-6">
            <div class="stat-box" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;padding:1.25rem;">
                <div class="stat-icon-wrap" style="width:44px;height:44px;border-radius:0.75rem;display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin-bottom:0.75rem;background:rgba(59,130,246,0.15);color:#60a5fa;">🍽️</div>
                <h2 style="font-size:1.75rem;font-weight:800;color:#fff;">${dataStore.menu.length}</h2>
                <p style="color:#a1a1aa;font-size:0.75rem;margin-top:0.25rem;">Menu Items</p>
            </div>
            <div class="stat-box" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;padding:1.25rem;">
                <div class="stat-icon-wrap" style="width:44px;height:44px;border-radius:0.75rem;display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin-bottom:0.75rem;background:rgba(234,179,8,0.15);color:#facc15;">⏳</div>
                <h2 style="font-size:1.75rem;font-weight:800;color:#fff;">${pending}</h2>
                <p style="color:#a1a1aa;font-size:0.75rem;margin-top:0.25rem;">Pending Orders</p>
            </div>
            <div class="stat-box" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;padding:1.25rem;">
                <div class="stat-icon-wrap" style="width:44px;height:44px;border-radius:0.75rem;display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin-bottom:0.75rem;background:rgba(249,115,22,0.15);color:#fb923c;">💵</div>
                <h2 style="font-size:1.75rem;font-weight:800;color:#fff;">৳${Math.round(totalSalaryDue).toLocaleString()}</h2>
                <p style="color:#a1a1aa;font-size:0.75rem;margin-top:0.25rem;">Salary Due (Total)</p>
            </div>
            <div class="stat-box" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;padding:1.25rem;">
                <div class="stat-icon-wrap" style="width:44px;height:44px;border-radius:0.75rem;display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin-bottom:0.75rem;background:rgba(239,68,68,0.15);color:#f87171;">💳</div>
                <h2 style="font-size:1.75rem;font-weight:800;color:#fff;">৳${Math.round(totalCustomerDue).toLocaleString()}</h2>
                <p style="color:#a1a1aa;font-size:0.75rem;margin-top:0.25rem;">Customer Due (Total)</p>
            </div>
        </div>

        <div class="stat-box mb-6" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;padding:1.5rem;">
            <h3 style="font-size:1.15rem;font-weight:700;margin-bottom:1rem;color:#fff;">🛒 Recent Orders</h3>
            <div class="table-wrap" style="border:1px solid #27272a;border-radius:0.75rem;overflow:hidden;">
                <table style="width:100%;border-collapse:collapse;text-align:left;font-size:0.875rem;">
                    <thead>
                        <tr style="background:#27272a;">
                            <th style="padding:0.75rem 1rem;color:#a1a1aa;">Customer</th>
                            <th style="padding:0.75rem 1rem;color:#a1a1aa;">Phone & Address</th>
                            <th style="padding:0.75rem 1rem;color:#a1a1aa;">Total</th>
                            <th style="padding:0.75rem 1rem;color:#a1a1aa;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${dataStore.orders.slice(0, 6).map(o => `
                            <tr style="border-top:1px solid #27272a;">
                                <td style="padding:0.85rem 1rem;"><strong style="color:#fff;">${o.customerName || 'N/A'}</strong></td>
                                <td style="padding:0.85rem 1rem;color:#a1a1aa;font-size:0.8rem;">${o.phoneNumber || ''}<br><span style="color:#71717a;">${(o.address || '').split('\n').join(', ')}</span></td>
                                <td style="padding:0.85rem 1rem;color:#ef4444;font-weight:700;">৳${o.totalAmount || 0}</td>
                                <td style="padding:0.85rem 1rem;"><span style="padding:0.25rem 0.6rem;border-radius:9999px;font-size:0.75rem;font-weight:700;background:rgba(234,179,8,0.15);color:#facc15;border:1px solid rgba(234,179,8,0.3);">${o.status || 'PENDING'}</span></td>
                            </tr>
                        `).join('')}
                        ${dataStore.orders.length === 0 ? '<tr><td colspan="4" style="padding:2rem;text-align:center;color:#71717a;">কোনো অর্ডার নেই।</td></tr>' : ''}
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

// ── 2. Menu Tab ──
function renderMenuTab(container) {
    container.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <p style="color:#a1a1aa;font-size:0.875rem;">${dataStore.menu.length} টি মেনু আইটেম</p>
            <button class="btn btn-sm btn-primary" onclick="openAddMenuModal()">➕ নতুন আইটেম যোগ</button>
        </div>
        <div class="table-wrap" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;text-align:left;font-size:0.875rem;">
                <thead style="background:#27272a;">
                    <tr>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">ছবি</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">নাম</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">ক্যাটাগরি</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">মূল্য</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Available</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${dataStore.menu.map(item => `
                        <tr style="border-top:1px solid #27272a;">
                            <td style="padding:0.75rem 1rem;">${item.image ? `<img src="${item.image}" style="width:40px;height:40px;border-radius:8px;object-fit:cover;">` : '🍲'}</td>
                            <td style="padding:0.75rem 1rem;"><strong style="color:#fff;">${item.name}</strong> ${item.isFeatured ? '<span style="color:#facc15;font-size:0.75rem;">⭐ Featured</span>' : ''}</td>
                            <td style="padding:0.75rem 1rem;"><span style="background:#27272a;padding:0.2rem 0.5rem;border-radius:6px;font-size:0.75rem;color:#a1a1aa;">${item.category ? item.category.name : 'N/A'}</span></td>
                            <td style="padding:0.75rem 1rem;color:#ef4444;font-weight:700;">৳${item.price}</td>
                            <td style="padding:0.75rem 1rem;"><button onclick="toggleMenuAvailability('${item.id}', ${item.isAvailable})" style="background:${item.isAvailable ? '#22c55e' : '#52525b'};color:#fff;padding:0.25rem 0.6rem;border-radius:9999px;font-size:0.75rem;">${item.isAvailable ? 'Active' : 'Off'}</button></td>
                            <td style="padding:0.75rem 1rem;">
                                <button onclick="deleteMenuItem('${item.id}')" style="color:#ef4444;padding:0.25rem 0.5rem;">🗑️</button>
                            </td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
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
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Customer</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Phone & Address</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Items</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Total</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Status</th>
                        <th style="padding:0.75rem 1rem;color:#a1a1aa;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    ${dataStore.orders.map(o => {
                        let addrHtml = (o.address || '').split('\n').join(', ');
                        const mapMatch = addrHtml.match(/https:\/\/maps\.google\.com\/\?q=[^\]\s]+/);
                        if (mapMatch) {
                            const mapUrl = mapMatch[0];
                            addrHtml = addrHtml.replace(`[ম্যাপ লিংক: ${mapUrl}]`, `<br><a href="${mapUrl}" target="_blank" style="display:inline-block;margin-top:4px;padding:2px 8px;background:rgba(59,130,246,0.2);color:#60a5fa;border-radius:4px;font-weight:600;font-size:0.75rem;">📍 ম্যাপে লোকেশন দেখুন</a>`);
                        }
                        return `
                            <tr style="border-top:1px solid #27272a;">
                                <td style="padding:0.85rem 1rem;"><strong style="color:#fff;">${o.customerName}</strong>${o.note ? `<p style="font-size:0.75rem;color:#71717a;">${o.note}</p>` : ''}</td>
                                <td style="padding:0.85rem 1rem;font-size:0.8rem;color:#a1a1aa;">${o.phoneNumber}<br>${addrHtml}</td>
                                <td style="padding:0.85rem 1rem;font-size:0.8rem;color:#e4e4e7;">${(o.items || []).map(i => `• ${i.name || i.menu_item_name} × ${i.quantity || i.qty}`).join('<br>')}</td>
                                <td style="padding:0.85rem 1rem;color:#ef4444;font-weight:700;">৳${o.totalAmount}</td>
                                <td style="padding:0.85rem 1rem;">
                                    <select onchange="updateOrderStatus('${o.id}', this.value)" style="background:#27272a;color:#fff;border:1px solid #3f3f46;padding:0.25rem 0.5rem;border-radius:6px;font-size:0.8rem;">
                                        <option value="PENDING" ${o.status === 'PENDING' ? 'selected' : ''}>PENDING</option>
                                        <option value="CONFIRMED" ${o.status === 'CONFIRMED' ? 'selected' : ''}>CONFIRMED</option>
                                        <option value="DELIVERED" ${o.status === 'DELIVERED' ? 'selected' : ''}>DELIVERED</option>
                                        <option value="CANCELLED" ${o.status === 'CANCELLED' ? 'selected' : ''}>CANCELLED</option>
                                    </select>
                                </td>
                                <td style="padding:0.85rem 1rem;"><button onclick="deleteOrder('${o.id}')" style="color:#ef4444;">🗑️</button></td>
                            </tr>
                        `;
                    }).join('')}
                    ${dataStore.orders.length === 0 ? '<tr><td colspan="6" style="padding:2rem;text-align:center;color:#71717a;">কোনো অর্ডার নেই।</td></tr>' : ''}
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
            <button class="btn btn-sm btn-primary" onclick="openAddEmployeeModal()">➕ নতুন কর্মী যোগ</button>
        </div>
        <div class="grid grid-3">
            ${dataStore.employees.map(e => `
                <div class="stat-box" style="background:#18181b;border:1px solid #27272a;border-radius:1rem;padding:1.25rem;">
                    <div class="flex justify-between items-center mb-2">
                        <h4 style="font-weight:700;font-size:1.1rem;color:#fff;">${e.name}</h4>
                        <button onclick="deleteEmployee('${e.id}')" style="color:#ef4444;font-size:0.85rem;">🗑️</button>
                    </div>
                    <p style="color:#a1a1aa;font-size:0.75rem;margin-bottom:0.75rem;">👔 ${e.position} • 📞 ${e.phone || 'N/A'}</p>
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
            <button class="btn btn-sm btn-primary" onclick="openAddDueModal()">➕ নতুন ডিউ যোগ</button>
        </div>
        <div class="grid grid-3">
            ${dataStore.dues.map(d => {
                const rem = (parseFloat(d.totalDue) || 0) - (parseFloat(d.paidAmount) || 0);
                return `
                    <div class="stat-box" style="background:#18181b;border:1px solid ${rem > 0 ? 'rgba(239,68,68,0.3)' : 'rgba(34,197,94,0.3)'};border-radius:1rem;padding:1.25rem;">
                        <div class="flex justify-between items-center mb-2">
                            <h4 style="font-weight:700;color:#fff;">${d.name}</h4>
                            <button onclick="deleteDue('${d.id}')" style="color:#ef4444;">🗑️</button>
                        </div>
                        <p style="color:#a1a1aa;font-size:0.75rem;margin-bottom:0.75rem;">📍 ${(d.address || d.phone || 'N/A').split('\n').join(', ')}</p>
                        <div class="flex justify-between" style="font-size:0.75rem;color:#a1a1aa;margin-bottom:0.5rem;">
                            <span>মোট: ৳${Math.round(d.totalDue)}</span>
                            <span>পরিশোধ: ৳${Math.round(d.paidAmount)}</span>
                        </div>
                        <p style="font-weight:700;font-size:0.875rem;color:${rem > 0 ? '#f87171' : '#4ade80'};text-align:right;">
                            ${rem > 0 ? `বাকি: ৳${Math.round(rem)}` : '✅ পরিশোধ সম্পন্ন'}
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
            <button class="btn btn-sm btn-primary" onclick="openAddLedgerModal()">➕ নতুন দৈনিক হিসাব</button>
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
                            <td style="padding:0.75rem 1rem;"><button onclick="deleteLedger('${l.id}')" style="color:#ef4444;">🗑️</button></td>
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
            <button class="btn btn-sm btn-primary" onclick="openAddStockModal()">➕ নতুন স্টক যোগ</button>
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
                            <td style="padding:0.75rem 1rem;"><strong style="color:#fff;">${s.name}</strong> ${s.isLowStock ? '<span style="color:#f87171;font-size:0.75rem;">⚠️ Low</span>' : ''}</td>
                            <td style="padding:0.75rem 1rem;font-weight:700;color:${s.isLowStock ? '#f87171' : '#4ade80'};">${s.quantity} ${s.unit}</td>
                            <td style="padding:0.75rem 1rem;color:#71717a;">${s.minQuantity} ${s.unit}</td>
                            <td style="padding:0.75rem 1rem;color:#ef4444;font-weight:700;">৳${s.lastPrice}</td>
                            <td style="padding:0.75rem 1rem;"><button onclick="deleteStock('${s.id}')" style="color:#ef4444;">🗑️</button></td>
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

function openAddMenuModal() {
    openModal('নতুন মেনু আইটেম যোগ', `
        <div class="form-group"><label class="form-label">নাম</label><input type="text" id="mName" class="form-input"></div>
        <div class="form-group"><label class="form-label">মূল্য (৳)</label><input type="number" id="mPrice" class="form-input"></div>
        <div class="form-group"><label class="form-label">বিবরণ</label><input type="text" id="mDesc" class="form-input"></div>
        <button class="btn btn-primary btn-block mt-4" onclick="saveNewMenuItem()">সংরক্ষণ করুন</button>
    `);
}

async function saveNewMenuItem() {
    const name = document.getElementById('mName').value;
    const price = document.getElementById('mPrice').value;
    const desc = document.getElementById('mDesc').value;
    if (!name || !price) return alert('নাম ও মূল্য আবশ্যক');

    await fetch('/api/admin/menu', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ name, price, description: desc })
    });
    closeModal();
    fetchAllData();
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
