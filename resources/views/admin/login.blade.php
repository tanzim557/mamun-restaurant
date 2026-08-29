<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — মামুন হোটেল</title>
    <link rel="stylesheet" href="/css/style.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { background:#0f0f0f; }
        .login-wrapper { min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden; }
        .login-bg1 { position:absolute;top:-160px;right:-160px;width:384px;height:384px;background:rgba(220,38,38,0.2);border-radius:50%;filter:blur(80px); }
        .login-bg2 { position:absolute;bottom:-160px;left:-160px;width:384px;height:384px;background:rgba(245,158,11,0.1);border-radius:50%;filter:blur(80px); }
        .login-card { position:relative;z-index:1;width:100%;max-width:440px;margin:1rem;background:#18181b;border:1px solid #27272a;border-radius:1.5rem;box-shadow:0 25px 50px rgba(0,0,0,0.5);padding:2.5rem; }
        .login-logo { width:80px;height:80px;background:rgba(220,38,38,0.1);border:2px solid rgba(220,38,38,0.3);border-radius:1rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;font-size:2.25rem; }
        .login-input-wrap { position:relative;margin-bottom:1rem; }
        .login-input-icon { position:absolute;left:1rem;top:50%;transform:translateY(-50%);color:#52525b;font-size:1.1rem; }
        .login-input { width:100%;padding:0.875rem 1rem 0.875rem 2.75rem;background:#27272a;border:1px solid #3f3f46;border-radius:0.75rem;color:#fff;font-size:0.95rem;outline:none;transition:all 0.2s; }
        .login-input:focus { border-color:rgba(220,38,38,0.5);box-shadow:0 0 0 3px rgba(220,38,38,0.15); }
        .login-input::placeholder { color:#52525b; }
        .login-toggle { position:absolute;right:1rem;top:50%;transform:translateY(-50%);color:#52525b;font-size:1.1rem;cursor:pointer; }
        .login-toggle:hover { color:#a1a1aa; }
        .login-btn { width:100%;padding:1rem;background:#dc2626;color:#fff;border:none;border-radius:0.75rem;font-weight:700;font-size:1rem;cursor:pointer;transition:all 0.3s;display:flex;align-items:center;justify-content:center;gap:0.5rem; }
        .login-btn:hover { background:#b91c1c; }
        .login-btn:disabled { opacity:0.6;cursor:not-allowed; }
        .login-error { background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#f87171;font-size:0.875rem;padding:0.75rem 1rem;border-radius:0.5rem;margin-bottom:1rem;display:none; }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-bg1"></div>
        <div class="login-bg2"></div>
        <div class="login-card animate-fade-up">
            <div class="text-center" style="margin-bottom:2.5rem;">
                <div class="login-logo">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--primary);"><path d="M12 2a4 4 0 0 0-4 4v1H6a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2V6a4 4 0 0 0-4-4zm0 2a2 2 0 0 1 2 2v1h-4V6a2 2 0 0 1 2-2z"/></svg>
                </div>
                <h1 style="font-size:1.875rem;font-weight:800;color:#fff;">Admin Portal</h1>
                <p style="color:#a1a1aa;margin-top:0.5rem;font-size:0.875rem;">মামুন হোটেল অ্যাডমিন ম্যানেজমেন্ট</p>
            </div>
            <form id="loginForm" onsubmit="return handleLogin(event)">
                <div class="login-input-wrap">
                    <span class="login-input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </span>
                    <input type="email" class="login-input" id="loginEmail" required placeholder="admin@mamun.com">
                </div>
                <div class="login-input-wrap">
                    <span class="login-input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input type="password" class="login-input" id="loginPassword" required placeholder="••••••••" style="padding-right:3rem;">
                    <span class="login-toggle" onclick="togglePassword()">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </span>
                </div>
                <div class="login-error" id="loginError"></div>
                <button type="submit" class="login-btn" id="loginBtn">সাইন ইন করুন</button>
            </form>
            <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid #27272a;text-align:center;">
                <p style="color:#71717a;font-size:0.8rem;">ডিফল্ট লগইন: <span style="color:#f59e0b;font-weight:700;">admin@mamun.com</span> / পাসওয়ার্ড: <span style="color:#f59e0b;font-weight:700;">admin</span></p>
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            const inp = document.getElementById('loginPassword');
            inp.type = inp.type === 'password' ? 'text' : 'password';
        }
        async function handleLogin(e) {
            e.preventDefault();
            const btn = document.getElementById('loginBtn');
            const err = document.getElementById('loginError');
            btn.disabled = true; btn.innerHTML = '<span class="spinner-sm" style="border-color:#fff;border-top-color:transparent;"></span> Signing in...';
            err.style.display = 'none';
            try {
                const res = await fetch('/api/admin/login', {
                    method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                    body: JSON.stringify({ email: document.getElementById('loginEmail').value, password: document.getElementById('loginPassword').value })
                });
                const data = await res.json();
                if (res.ok && data.success) { window.location.href = '/admin/dashboard'; }
                else { err.textContent = data.error || 'Invalid credentials.'; err.style.display = 'block'; }
            } catch { err.textContent = 'Login failed. Please try again.'; err.style.display = 'block'; }
            finally { btn.disabled = false; btn.innerHTML = 'Sign In'; }
            return false;
        }
    </script>
</body>
</html>
