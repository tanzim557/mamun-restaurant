@extends('layouts.app')
@section('title', 'অর্ডার ট্র্যাক — মামুন হোটেল')

@section('content')
<section class="hero hero-short" style="margin-top:-64px;">
    <div class="hero-bg" style="background-image:url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1920&q=80');"></div>
    <div class="hero-content">
        <span class="text-secondary font-bold uppercase tracking-wide text-sm" style="display:inline-block;margin-bottom:0.75rem;background:rgba(245,158,11,0.15);padding:4px 14px;border-radius:9999px;border:1px solid rgba(245,158,11,0.3);">লাইভ ট্র্যাকিং</span>
        <h1>অর্ডার ট্র্যাক করুন</h1>
        <p style="color:rgba(255,255,255,0.7);margin-top:0.5rem;font-size:1.1rem;">আপনার অর্ডার আইডি অথবা মোবাইল নম্বর দিয়ে লাইভ স্ট্যাটাস দেখুন</p>
    </div>
</section>

<section class="section track-page-section">
    <!-- Ambient Glow Spheres -->
    <div class="ambient-glow glow-1" style="top:20%;left:5%;"></div>
    <div class="ambient-glow glow-2" style="bottom:15%;right:5%;"></div>

    <div class="container relative" style="max-width:680px;margin:0 auto;z-index:2;">
        <!-- Search Card -->
        <div class="track-search-card animate-fade-up">
            <form onsubmit="return handleTrackSubmit(event)">
                <label class="track-search-label">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:var(--secondary);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <span>অর্ডার আইডি অথবা মোবাইল নম্বর দিন</span>
                </label>
                <div class="track-input-group">
                    <input type="text" id="trackInput" class="track-input" placeholder="যেমন: MR-9446F9 অথবা 01988976269" required>
                    <button type="submit" class="btn-track-submit" id="trackBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <span>খুঁজুন</span>
                    </button>
                </div>
            </form>
            <p id="trackError" class="track-error-pill hidden"></p>
        </div>

        <!-- Loading State -->
        <div id="trackLoading" class="hidden text-center" style="padding:3rem 0;">
            <div class="spinner-icon" style="width:40px;height:40px;margin:0 auto 1rem;color:var(--primary);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10"/></svg>
            </div>
            <p style="color:#a1a1aa;">অর্ডার তথ্য লোড হচ্ছে...</p>
        </div>

        <!-- Result Card -->
        <div id="trackResult" class="hidden animate-fade-up">
            <div class="track-result-card">
                <!-- Result Header -->
                <div class="track-result-header">
                    <div>
                        <span style="font-size:0.75rem;color:#a1a1aa;text-transform:uppercase;font-weight:700;">অর্ডার নম্বর</span>
                        <div class="flex items-center gap-3 mt-1">
                            <h3 id="resOrderId" class="track-order-id"></h3>
                            <button type="button" onclick="copyTrackId()" class="btn-copy-id" id="trackCopyBtn">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                <span id="trackCopyText">কপি</span>
                            </button>
                        </div>
                    </div>
                    <div id="resStatusBadge" class="track-status-pill"></div>
                </div>

                <!-- Live Status Stepper -->
                <div class="track-stepper-box">
                    <h4 class="track-section-title">লাইভ ডেলিভারি স্ট্যাটাস</h4>
                    <div class="track-steps">
                        <div class="track-step" id="step1">
                            <div class="track-step-dot">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="track-step-text">
                                <p class="track-step-name">অর্ডার গৃহীত হয়েছে</p>
                                <span class="track-step-desc">অর্ডারটি সিস্টেমে নিশ্চিত হয়েছে</span>
                            </div>
                        </div>

                        <div class="track-step-line" id="line1"></div>

                        <div class="track-step" id="step2">
                            <div class="track-step-dot">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 13.87A4 4 0 0 1 7.41 6a5.11 5.11 0 0 1 1.05-1.54 5 5 0 0 1 7.08 0A5.11 5.11 0 0 1 16.59 6 4 4 0 0 1 18 13.87V21H6Z"/><line x1="6" y1="17" x2="18" y2="17"/></svg>
                            </div>
                            <div class="track-step-text">
                                <p class="track-step-name">রান্না ও প্রস্তুতি চলছে</p>
                                <span class="track-step-desc">আমাদের মাস্টার শেফ খাবার প্রস্তুত করছেন</span>
                            </div>
                        </div>

                        <div class="track-step-line" id="line2"></div>

                        <div class="track-step" id="step3">
                            <div class="track-step-dot">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            </div>
                            <div class="track-step-text">
                                <p class="track-step-name">ডেলিভারিতে বের হয়েছে</p>
                                <span class="track-step-desc">রাইডার আপনার ঠিকানার উদ্দেশ্যে রওনা দিয়েছেন</span>
                            </div>
                        </div>

                        <div class="track-step-line" id="line3"></div>

                        <div class="track-step" id="step4">
                            <div class="track-step-dot">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="track-step-text">
                                <p class="track-step-name">ডেলিভারি সম্পন্ন</p>
                                <span class="track-step-desc">খাবার সফলভাবে পৌঁছে দেওয়া হয়েছে</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Details & Items Grid -->
                <div class="track-info-grid">
                    <!-- Customer Details -->
                    <div class="track-info-card">
                        <h4 class="track-info-title">গ্রাহকের বিবরণ</h4>
                        <p class="track-info-row"><strong>নাম:</strong> <span id="resCustName"></span></p>
                        <p class="track-info-row"><strong>মোবাইল:</strong> <span id="resCustPhone"></span></p>
                        <p class="track-info-row"><strong>ঠিকানা:</strong> <span id="resCustAddr"></span></p>
                        <p class="track-info-row" id="resNoteRow" style="display:none;"><strong>নির্দেশনা:</strong> <span id="resCustNote"></span></p>
                        <p class="track-info-row"><strong>অর্ডারের সময়:</strong> <span id="resOrderTime"></span></p>
                    </div>

                    <!-- Items Summary -->
                    <div class="track-info-card">
                        <h4 class="track-info-title">অর্ডারকৃত খাবার</h4>
                        <div id="resItemsList" class="track-items-box"></div>
                        <div class="track-total-box">
                            <span>সর্বমোট পরিশোধ:</span>
                            <span class="text-primary font-bold text-lg" id="resTotalAmount"></span>
                        </div>
                    </div>
                </div>

                <!-- Direct Support Buttons -->
                <div class="track-actions-footer">
                    <a href="tel:01988976269" class="btn btn-outline-primary" style="flex:1;display:flex;align-items:center;justify-content:center;gap:8px;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>সরাসরি কল দিন</span>
                    </a>
                    <a href="https://wa.me/8801988976269" target="_blank" class="footer-wa-btn" style="flex:1;justify-content:center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>হোয়াটসঅ্যাপে হেল্পলাইন</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('id') || urlParams.get('orderId') || urlParams.get('phone');
    if (orderId) {
        document.getElementById('trackInput').value = orderId;
        searchOrder(orderId);
    }
});

function handleTrackSubmit(e) {
    e.preventDefault();
    const query = document.getElementById('trackInput').value.trim();
    if (query) searchOrder(query);
    return false;
}

async function searchOrder(query) {
    const loading = document.getElementById('trackLoading');
    const result = document.getElementById('trackResult');
    const errorEl = document.getElementById('trackError');
    const btn = document.getElementById('trackBtn');

    errorEl.classList.add('hidden');
    result.classList.add('hidden');
    loading.classList.remove('hidden');
    btn.disabled = true;

    try {
        const res = await fetch(`/api/orders/track?query=${encodeURIComponent(query)}`);
        const data = await res.json();

        loading.classList.add('hidden');
        btn.disabled = false;

        if (res.ok && data.success && data.order) {
            renderTrackDetails(data.order);
            result.classList.remove('hidden');
        } else {
            errorEl.innerText = data.error || 'কোনো অর্ডার পাওয়া যায়নি। সঠিক আইডি বা ফোন নম্বর দিন।';
            errorEl.classList.remove('hidden');
        }
    } catch(e) {
        loading.classList.add('hidden');
        btn.disabled = false;
        errorEl.innerText = 'তথ্য লোড করতে সমস্যা হয়েছে। ইন্টারনেট চেক করুন।';
        errorEl.classList.remove('hidden');
    }
}

function renderTrackDetails(order) {
    const shortId = order.shortId || ('MR-' + order.id.substring(0, 6).toUpperCase());
    document.getElementById('resOrderId').innerText = shortId;
    document.getElementById('resOrderId').dataset.id = shortId;

    document.getElementById('resCustName').innerText = order.customerName || 'N/A';
    document.getElementById('resCustPhone').innerText = order.phoneNumber || 'N/A';
    document.getElementById('resCustAddr').innerText = order.address || 'N/A';

    if (order.note && order.note.trim() !== '') {
        document.getElementById('resCustNote').innerText = order.note;
        document.getElementById('resNoteRow').style.display = 'block';
    } else {
        document.getElementById('resNoteRow').style.display = 'none';
    }

    if (order.createdAt) {
        const d = new Date(order.createdAt);
        document.getElementById('resOrderTime').innerText = d.toLocaleString('bn-BD', { dateStyle: 'medium', timeStyle: 'short' });
    }

    // Items
    const itemsContainer = document.getElementById('resItemsList');
    if (order.items && order.items.length > 0) {
        itemsContainer.innerHTML = order.items.map(i => `
            <div class="flex justify-between text-sm py-1" style="border-bottom:1px dashed rgba(255,255,255,0.06);">
                <span style="color:#d4d4d8;">${i.menu_item_name || i.name} × ${i.quantity || i.qty}</span>
                <span class="font-bold text-white">৳${i.price * (i.quantity || i.qty)}</span>
            </div>
        `).join('');
    } else {
        itemsContainer.innerHTML = '<p class="text-muted text-xs">আইটেমের বিবরণ পাওয়া যায়নি</p>';
    }

    document.getElementById('resTotalAmount').innerText = '৳' + (order.totalAmount || 0);

    // Status Stepper & Badge
    updateStatusStepper(order.status);
}

function updateStatusStepper(status) {
    const s = (status || 'pending').toLowerCase();
    const badge = document.getElementById('resStatusBadge');

    // Reset all steps
    ['step1', 'step2', 'step3', 'step4'].forEach(id => {
        document.getElementById(id).className = 'track-step';
    });
    ['line1', 'line2', 'line3'].forEach(id => {
        document.getElementById(id).className = 'track-step-line';
    });

    let currentStep = 1;
    let badgeText = 'অর্ডার গৃহীত হয়েছে';
    let badgeClass = 'status-pending';

    if (s === 'pending' || s === 'confirmed') {
        currentStep = 1;
        badgeText = 'অর্ডার নিশ্চিত হয়েছে';
        badgeClass = 'status-pending';
    } else if (s === 'preparing' || s === 'cooking') {
        currentStep = 2;
        badgeText = 'রান্না হচ্ছে';
        badgeClass = 'status-preparing';
    } else if (s === 'out_for_delivery' || s === 'on_the_way') {
        currentStep = 3;
        badgeText = 'ডেলিভারিতে বের হয়েছে';
        badgeClass = 'status-shipping';
    } else if (s === 'delivered' || s === 'completed') {
        currentStep = 4;
        badgeText = 'ডেলিভারি সম্পন্ন';
        badgeClass = 'status-delivered';
    } else if (s === 'cancelled') {
        badge.innerHTML = `<span class="track-badge-cancelled">অর্ডার বাতিল হয়েছে</span>`;
        return;
    }

    badge.innerHTML = `<span class="${badgeClass}">${badgeText}</span>`;

    for (let i = 1; i <= currentStep; i++) {
        const stepEl = document.getElementById(`step${i}`);
        if (i === currentStep && currentStep < 4) {
            stepEl.classList.add('current');
        } else {
            stepEl.classList.add('completed');
        }

        if (i < currentStep) {
            const lineEl = document.getElementById(`line${i}`);
            if (lineEl) lineEl.classList.add('active');
        }
    }
}

function copyTrackId() {
    const idEl = document.getElementById('resOrderId');
    if (!idEl) return;
    const textToCopy = idEl.innerText.trim();

    navigator.clipboard.writeText(textToCopy).then(() => {
        const btnText = document.getElementById('trackCopyText');
        if (btnText) {
            btnText.innerText = 'কপি হয়েছে!';
            setTimeout(() => {
                btnText.innerText = 'কপি';
            }, 2500);
        }
    }).catch(() => {
        alert('আইডি: ' + textToCopy);
    });
}
</script>
@endsection
