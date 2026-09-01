<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>স্বত্বাধিকারী প্রবেশদ্বার — শ্যামনগর নজরুল হোটেল</title>
    <link rel="stylesheet" href="/css/admin-app.css">
    <meta name="theme-color" content="#09090d">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background: #09090d;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .login-glow-1 {
            position: absolute;
            top: -150px;
            right: -150px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(234, 179, 8, 0.18) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(50px);
            pointer-events: none;
        }
        .login-glow-2 {
            position: absolute;
            bottom: -150px;
            left: -150px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(239, 68, 68, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            filter: blur(50px);
            pointer-events: none;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            background: rgba(18, 18, 25, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1.5px solid rgba(234, 179, 8, 0.35);
            border-radius: 1.75rem;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8), 0 0 30px rgba(234, 179, 8, 0.1);
            padding: 2.2rem 1.8rem;
            position: relative;
            z-index: 10;
        }
        .crown-badge {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(234, 179, 8, 0.25), rgba(239, 68, 68, 0.2));
            border: 2px solid rgba(234, 179, 8, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            margin: 0 auto 1.25rem;
            box-shadow: 0 0 25px rgba(234, 179, 8, 0.3);
            animation: badgeBeat 2s infinite;
        }
    </style>
</head>
<body>
    <div class="login-glow-1"></div>
    <div class="login-glow-2"></div>

    <div class="login-card">
        <div style="text-align:center;margin-bottom:2rem;">
            <div class="crown-badge">👑</div>
            <h1 style="font-size:1.6rem;font-weight:900;color:#fff;letter-spacing:-0.02em;margin-bottom:4px;">নজরুল হোটেল</h1>
            <p style="font-size:0.85rem;color:#eab308;font-weight:700;">স্বত্বাধিকারী প্রবেশদ্বার • VIP Owner Hub</p>
        </div>

        <form id="loginForm" onsubmit="return handleLogin(event)">
            <div style="margin-bottom:1rem;">
                <label style="display:block;font-size:0.78rem;font-weight:700;color:#94a3b8;margin-bottom:6px;">অ্যাডমিন ইমেইল</label>
                <input type="email" class="owner-input" id="loginEmail" required value="admin@mamun.com" placeholder="admin@mamun.com" style="margin-bottom:0;">
            </div>

            <div style="margin-bottom:1.25rem;position:relative;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                    <label style="font-size:0.78rem;font-weight:700;color:#94a3b8;">গোপন পাসওয়ার্ড</label>
                    <span onclick="togglePassword()" style="font-size:0.75rem;color:#eab308;cursor:pointer;font-weight:700;">👁️ দেখুন</span>
                </div>
                <input type="password" class="owner-input" id="loginPassword" required value="admin" placeholder="••••••••" style="margin-bottom:0;">
            </div>

            <div id="loginError" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.35);color:#f87171;font-size:0.82rem;padding:0.75rem;border-radius:0.75rem;margin-bottom:1rem;display:none;"></div>

            <button type="submit" class="owner-btn-submit" id="loginBtn">
                👑 স্বত্বাধিকারী প্রবেশ করুন
            </button>
        </form>

        <div style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid rgba(255,255,255,0.08);text-align:center;">
            <p style="color:#64748b;font-size:0.78rem;">শ্যামনগর নজরুল হোটেল অ্যান্ড রেস্টুরেন্ট ম্যানেজমেন্ট</p>
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
            btn.disabled = true; 
            btn.innerHTML = '⏳ লগইন যাচাই হচ্ছে...';
            err.style.display = 'none';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                const res = await fetch('/api/admin/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ 
                        email: document.getElementById('loginEmail').value, 
                        password: document.getElementById('loginPassword').value 
                    })
                });
                const data = await res.json();
                if (res.ok && data.success) { 
                    window.location.href = '/admin/dashboard'; 
                } else { 
                    err.textContent = data.error || 'ভুল ইমেইল বা পাসওয়ার্ড দেওয়া হয়েছে।'; 
                    err.style.display = 'block'; 
                }
            } catch(ex) { 
                err.textContent = 'লগইন করতে সমস্যা হয়েছে। সার্ভার চালু আছে কি না পরীক্ষা করুন।'; 
                err.style.display = 'block'; 
            } finally { 
                btn.disabled = false; 
                btn.innerHTML = '👑 স্বত্বাধিকারী প্রবেশ করুন'; 
            }
            return false;
        }
    </script>
</body>
</html>
