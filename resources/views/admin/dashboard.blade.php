<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — মামুন হোটেল</title>
    <link rel="stylesheet" href="/css/style.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { background:#0f0f0f; color:#fff; font-family:'Inter',sans-serif; margin:0; }
        .admin-layout { display:flex; height:100vh; overflow:hidden; }
        .admin-sidebar { width:260px; background:#18181b; border-right:1px solid #27272a; display:flex; flex-direction:column; flex-shrink:0; transition:transform 0.3s ease; }
        .admin-brand { padding:1.25rem; border-bottom:1px solid #27272a; display:flex; align-items:center; gap:0.75rem; }
        .admin-brand-icon { width:40px; height:40px; background:rgba(220,38,38,0.2); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; color:#ef4444; }
        .admin-nav { flex:1; padding:0.75rem; overflow-y:auto; }
        .admin-nav-item { width:100%; display:flex; align-items:center; gap:0.75rem; padding:0.75rem 1rem; border-radius:0.75rem; font-size:0.875rem; font-weight:500; color:#a1a1aa; background:none; border:none; text-align:left; cursor:pointer; transition:all 0.2s; margin-bottom:0.25rem; }
        .admin-nav-item:hover { background:#27272a; color:#fff; }
        .admin-nav-item.active { background:#dc2626; color:#fff; box-shadow:0 4px 14px rgba(220,38,38,0.3); }
        .admin-nav-badge { margin-left:auto; background:#eab308; color:#000; font-size:0.75rem; font-weight:700; width:20px; height:20px; border-radius:50%; display:flex; align-items:center; justify-content:center; }
        .admin-main { flex:1; display:flex; flex-direction:column; overflow:hidden; }
        .admin-header { background:rgba(24,24,27,0.8); backdrop-filter:blur(10px); border-bottom:1px solid #27272a; padding:1rem 1.5rem; display:flex; align-items:center; justify-content:space-between; }
        .admin-content { flex:1; overflow-y:auto; padding:1.5rem; }
        .stat-box { background:#18181b; border:1px solid #27272a; border-radius:1rem; padding:1.25rem; }
        .stat-icon-wrap { width:44px; height:44px; border-radius:0.75rem; display:flex; align-items:center; justify-content:center; font-size:1.25rem; margin-bottom:0.75rem; }
        .table-wrap { background:#18181b; border:1px solid #27272a; border-radius:1rem; overflow:hidden; }
        table { width:100%; border-collapse:collapse; text-align:left; font-size:0.875rem; }
        th { background:#27272a; color:#a1a1aa; padding:0.75rem 1rem; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; }
        td { padding:0.875rem 1rem; border-top:1px solid #27272a; }
        tr:hover td { background:rgba(255,255,255,0.02); }
        .badge { padding:0.25rem 0.625rem; border-radius:9999px; font-size:0.75rem; font-weight:700; }
        .badge-pending { background:rgba(234,179,8,0.15); color:#facc15; border:1px solid rgba(234,179,8,0.3); }
        .badge-confirmed { background:rgba(59,130,246,0.15); color:#60a5fa; border:1px solid rgba(59,130,246,0.3); }
        .badge-delivered { background:rgba(34,197,94,0.15); color:#4ade80; border:1px solid rgba(34,197,94,0.3); }
        .badge-cancelled { background:rgba(239,68,68,0.15); color:#f87171; border:1px solid rgba(239,68,68,0.3); }
        .modal-overlay { display:none; position:fixed; inset:0; z-index:100; background:rgba(0,0,0,0.7); backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1rem; }
        .modal-overlay.open { display:flex; }
        .modal-card { background:#18181b; border:1px solid #3f3f46; border-radius:1rem; width:100%; max-width:520px; box-shadow:0 25px 50px rgba(0,0,0,0.6); max-height:90vh; overflow-y:auto; }
        .modal-header { display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #27272a; }
        .modal-body { padding:1.5rem; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-brand">
                <div class="admin-brand-icon" style="background:var(--primary);color:#fff;border-radius:10px;padding:6px;display:flex;align-items:center;justify-content:center;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                </div>
                <div><h3 style="font-size:1rem;font-weight:800;letter-spacing:0.05em;">MAMUN</h3><p style="font-size:0.75rem;color:#71717a;">Admin Panel</p></div>
            </div>
            <nav class="admin-nav">
                <button class="admin-nav-item active" onclick="switchTab('overview', this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    <span>Overview</span>
                </button>
                <button class="admin-nav-item" onclick="switchTab('menu', this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
                    <span>Menu Items</span>
                </button>
                <button class="admin-nav-item" onclick="switchTab('orders', this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    <span>Online Orders</span>
                    <span class="admin-nav-badge" id="pendingBadge" style="display:none;">0</span>
                </button>
                <button class="admin-nav-item" onclick="switchTab('employees', this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>Employees</span>
                </button>
                <button class="admin-nav-item" onclick="switchTab('dues', this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    <span>Customer Dues</span>
                </button>
                <button class="admin-nav-item" onclick="switchTab('ledger', this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    <span>ব্যক্তিগত আয়-ব্যয়</span>
                </button>
                <button class="admin-nav-item" onclick="switchTab('stock', this)">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
                    <span>Stock Items</span>
                </button>
            </nav>
            <div style="padding:0.75rem;border-top:1px solid #27272a;">
                <button onclick="handleLogout()" class="admin-nav-item" style="color:#71717a;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#ef4444;"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span>Sign Out</span>
                </button>
            </div>
        </aside>

        <!-- Main Area -->
        <div class="admin-main">
            <header class="admin-header">
                <div>
                    <h1 id="tabTitle" style="font-size:1.25rem;font-weight:800;color:#fff;">Overview</h1>
                    <p style="font-size:0.75rem;color:#71717a;">Mamun Restaurant Management</p>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button onclick="fetchAllData()" class="btn btn-sm btn-outline" style="border-color:#3f3f46;color:#a1a1aa;">🔄 Refresh</button>
                    <a href="/" target="_blank" class="btn btn-sm btn-outline-primary">🌐 View Site</a>
                </div>
            </header>
            <main class="admin-content" id="tabContent">
                <!-- Tab views will render dynamically via JS -->
            </main>
        </div>
    </div>

    <!-- Modals Container -->
    <div class="modal-overlay" id="adminModal">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modalTitle" style="font-size:1.1rem;font-weight:700;">Edit</h3>
                <button onclick="closeModal()" style="color:#a1a1aa;font-size:1.5rem;">&times;</button>
            </div>
            <div class="modal-body" id="modalBody"></div>
        </div>
    </div>

    <script src="/js/admin.js"></script>
</body>
</html>
