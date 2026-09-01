@extends('layouts.app')
@section('title', 'লাইভ ফুড অর্ডার ট্র্যাকিং — শ্যামনগর নজরুল হোটেল')

@section('content')
<div class="app-container">
    <div class="live-track-wrapper">
        <!-- Track Search Box -->
        <section class="app-search-section">
            <div class="app-section-header" style="margin-top:0;">
                <div class="app-section-title-wrap">
                    <span class="app-section-icon">🛵</span>
                    <h2 class="app-section-title">লাইভ ফুড অর্ডার ট্র্যাকিং</h2>
                </div>
            </div>
            
            <form onsubmit="return handleAppTrackSubmit(event)">
                <div class="app-search-box">
                    <span class="app-search-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </span>
                    <input type="text" id="trackQueryInput" class="app-search-input" placeholder="অর্ডার আইডি (যেমন: MR-9446F9) বা মোবাইল নম্বর দিন" required>
                    <button type="submit" class="promo-btn-mini" id="trackSubmitBtn" style="position:absolute;right:8px;top:50%;transform:translateY(-50%);margin-top:0;background:var(--fire-gradient);">খুঁজুন</button>
                </div>
            </form>
            <p id="trackErrorPill" class="text-primary text-xs font-bold mt-2 hidden"></p>
        </section>

        <!-- Loading -->
        <div id="trackLoadingState" class="hidden text-center" style="padding:3rem 0;">
            <div style="font-size:2rem;margin-bottom:0.5rem;" class="pulse-dot-green"></div>
            <p class="text-muted text-sm">অর্ডারের লাইভ তথ্য খোঁজা হচ্ছে...</p>
        </div>

        <!-- Result View -->
        <div id="trackResultState" class="hidden mt-4">
            <!-- Animated Delivery Map Simulation -->
            <div class="live-map-simulation">
                <span class="est-delivery-badge" id="trackEstBadge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span id="trackEstText">⏱️ আনুমানিক ২০-৩০ মিনিটের মধ্যে ডেলিভারি</span>
                </span>

                <div class="map-route-line">
                    <div class="map-route-progress" id="mapRouteProgress" style="width: 25%;"></div>
                </div>

                <div class="map-pin-restaurant" title="নজরুল হোটেল">🥘</div>
                <div class="map-rider-bike" id="mapRiderBike" style="left: 25%;">🛵</div>
                <div class="map-pin-customer" title="আপনার ঠিকানা">📍</div>
            </div>

            <!-- Order Live Card -->
            <div class="track-card-app">
                <div style="display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid rgba(255,255,255,0.08);padding-bottom:1rem;margin-bottom:1.25rem;">
                    <div>
                        <span style="font-size:0.72rem;color:var(--zinc-400);text-transform:uppercase;font-weight:700;">অর্ডার নম্বর</span>
                        <h3 id="trackOrderIdDisplay" style="font-family:'Outfit',monospace;font-size:1.25rem;font-weight:900;color:var(--secondary);"></h3>
                    </div>
                    <span id="trackStatusPill" class="loc-status-chip" style="font-size:0.8rem;padding:4px 12px;"></span>
                </div>

                <!-- 4-Stage Stepper -->
                <div class="track-app-stepper-box mb-4">
                    <div class="track-app-step" id="trackStep1">
                        <div class="track-app-dot">1</div>
                        <div class="track-app-text">
                            <h4>অর্ডার গৃহীত হয়েছে</h4>
                            <p>সিস্টেমে অর্ডার নিশ্চিত করা হয়েছে।</p>
                        </div>
                    </div>

                    <div class="track-app-step" id="trackStep2">
                        <div class="track-app-dot">2</div>
                        <div class="track-app-text">
                            <h4>রান্না ও প্রস্তুতি চলছে</h4>
                            <p>মাস্টার শেফ চুইঝাল দিয়ে খাবার প্রস্তুত করছেন।</p>
                        </div>
                    </div>

                    <div class="track-app-step" id="trackStep3">
                        <div class="track-app-dot">3</div>
                        <div class="track-app-text">
                            <h4>রাইডার ডেলিভারিতে বের হয়েছে</h4>
                            <p>খাবার আপনার ঠিকানার উদ্দেশ্যে রওনা দিয়েছে।</p>
                        </div>
                    </div>

                    <div class="track-app-step" id="trackStep4">
                        <div class="track-app-dot">4</div>
                        <div class="track-app-text">
                            <h4>ডেলিভারি সম্পন্ন</h4>
                            <p>খাবার সফলভাবে পৌঁছে দেওয়া হয়েছে।</p>
                        </div>
                    </div>
                </div>

                <!-- Customer Details & Order Items -->
                <div style="background:#14141b;border:1px solid rgba(255,255,255,0.08);border-radius:var(--radius);padding:1rem;margin-bottom:1rem;">
                    <h4 style="font-size:0.88rem;font-weight:800;color:#fff;margin-bottom:0.5rem;display:flex;align-items:center;gap:6px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>গ্রাহক ও ডেলিভারির তথ্য</span>
                    </h4>
                    <p style="font-size:0.8rem;color:var(--zinc-300);margin-bottom:3px;"><strong>নাম:</strong> <span id="trackCustName"></span></p>
                    <p style="font-size:0.8rem;color:var(--zinc-300);margin-bottom:3px;"><strong>মোবাইল:</strong> <span id="trackCustPhone"></span></p>
                    <p style="font-size:0.8rem;color:var(--zinc-300);margin-bottom:3px;"><strong>ঠিকানা:</strong> <span id="trackCustAddr"></span></p>
                    <p style="font-size:0.8rem;color:var(--zinc-300);" id="trackNoteRow"><strong>নির্দেশনা:</strong> <span id="trackCustNote"></span></p>
                </div>

                <!-- Itemized Receipt -->
                <div style="background:#14141b;border:1px solid rgba(255,255,255,0.08);border-radius:var(--radius);padding:1rem;margin-bottom:1.25rem;">
                    <h4 style="font-size:0.88rem;font-weight:800;color:#fff;margin-bottom:0.5rem;display:flex;align-items:center;gap:6px;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        <span>অর্ডারকৃত খাবারের পদ</span>
                    </h4>
                    <div id="trackItemsList" style="margin-bottom:0.75rem;"></div>
                    <div style="display:flex;justify-content:space-between;border-top:1px solid rgba(255,255,255,0.08);padding-top:8px;font-weight:900;font-size:1.05rem;">
                        <span>সর্বমোট প্রদেয়:</span>
                        <span style="color:var(--secondary);" id="trackTotalAmount">৳০</span>
                    </div>
                </div>

                <!-- Support Buttons -->
                <div class="quick-action-pills">
                    <a href="tel:01988976269" class="btn-app-action">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>রাইডারকে কল দিন</span>
                    </a>
                    <a href="https://wa.me/8801988976269" target="_blank" class="btn-app-action whatsapp">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <span>হোয়াটসঅ্যাপ হেল্পলাইন</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('id') || urlParams.get('phone') || urlParams.get('query');
    if (query) {
        document.getElementById('trackQueryInput').value = query;
        fetchTrackInfo(query);
    }
});

function handleAppTrackSubmit(e) {
    e.preventDefault();
    const query = document.getElementById('trackQueryInput').value.trim();
    if (query) fetchTrackInfo(query);
    return false;
}

let activeTrackQuery = null;
let trackPollTimer = null;

async function silentRefreshTrack(query) {
    try {
        const res = await fetch(`/api/orders/track?query=${encodeURIComponent(query)}`);
        const data = await res.json();
        if (data.success && data.order) {
            renderTrackDetails(data.order);
        }
    } catch(e) {}
}

async function fetchTrackInfo(query) {
    const loading = document.getElementById('trackLoadingState');
    const result = document.getElementById('trackResultState');
    const errPill = document.getElementById('trackErrorPill');
    const btn = document.getElementById('trackSubmitBtn');

    if (errPill) errPill.classList.add('hidden');
    if (loading && !activeTrackQuery) loading.classList.remove('hidden');
    if (btn) btn.disabled = true;

    try {
        const res = await fetch(`/api/orders/track?query=${encodeURIComponent(query)}`);
        const data = await res.json();

        if (data.success && data.order) {
            renderTrackDetails(data.order);
            if (result) result.classList.remove('hidden');
            activeTrackQuery = query;
            if (!trackPollTimer) {
                trackPollTimer = setInterval(() => {
                    if (activeTrackQuery) silentRefreshTrack(activeTrackQuery);
                }, 2500);
            }
        } else {
            throw new Error(data.error || 'কোনো অর্ডার পাওয়া যায়নি। সঠিক অর্ডার আইডি বা মোবাইল নম্বর দিন।');
        }
    } catch(e) {
        if (errPill) {
            errPill.textContent = e.message;
            errPill.classList.remove('hidden');
        }
        showToast(e.message, 'error');
    } finally {
        if (loading) loading.classList.add('hidden');
        if (btn) btn.disabled = false;
    }
}

function renderTrackDetails(order) {
    const shortId = order.shortId || ('MR-' + order.id.substring(0, 6).toUpperCase());
    document.getElementById('trackOrderIdDisplay').textContent = shortId;
    document.getElementById('trackCustName').textContent = order.customer_name || '—';
    document.getElementById('trackCustPhone').textContent = order.phone_number || '—';
    document.getElementById('trackCustAddr').textContent = order.address || '—';

    const noteEl = document.getElementById('trackCustNote');
    const noteRow = document.getElementById('trackNoteRow');
    if (order.note && order.note.trim()) {
        noteEl.textContent = order.note;
        noteRow.style.display = 'block';
    } else {
        noteRow.style.display = 'none';
    }

    // Items list
    const itemsList = document.getElementById('trackItemsList');
    const items = order.order_items || order.orderItems || [];
    let calcTotal = 0;

    itemsList.innerHTML = items.map(item => {
        const name = item.menu_item_name || item.name || 'খাবার পদ';
        const qty = item.quantity || item.qty || 1;
        const price = parseFloat(item.price) || 0;
        const itemTotal = price * qty;
        calcTotal += itemTotal;

        return `
            <div style="display:flex;justify-content:space-between;font-size:0.82rem;color:var(--zinc-300);padding:3px 0;">
                <span>${name} × ${qty}</span>
                <span class="font-bold text-white">৳${itemTotal}</span>
            </div>
        `;
    }).join('');

    const finalTotal = order.total_amount > 0 ? order.total_amount : calcTotal;
    document.getElementById('trackTotalAmount').textContent = `৳${finalTotal}`;

    // Update Stepper & Rider Map Animation
    updateStepperAndMap(order.status || 'PENDING');
}

function updateStepperAndMap(status) {
    const statusUpper = (status || '').toUpperCase();
    const pill = document.getElementById('trackStatusPill');
    const progress = document.getElementById('mapRouteProgress');
    const bike = document.getElementById('mapRiderBike');
    const estText = document.getElementById('trackEstText');

    const step1 = document.getElementById('trackStep1');
    const step2 = document.getElementById('trackStep2');
    const step3 = document.getElementById('trackStep3');
    const step4 = document.getElementById('trackStep4');

    [step1, step2, step3, step4].forEach(s => {
        s.className = 'track-app-step';
    });

    if (statusUpper === 'PENDING') {
        pill.textContent = 'অর্ডার গৃহীত';
        pill.style.color = '#fbbf24';
        step1.classList.add('active');
        progress.style.width = '20%';
        bike.style.left = '20%';
        estText.textContent = '⏱️ কিচেনে খাবার তৈরির প্রস্তুতি চলছে (২৫-৩৫ মিনিট)';
    } else if (statusUpper === 'PREPARING') {
        pill.textContent = 'রান্না হচ্ছে';
        pill.style.color = '#f97316';
        step1.classList.add('completed');
        step2.classList.add('active');
        progress.style.width = '45%';
        bike.style.left = '45%';
        estText.textContent = '🔥 মাস্টার শেফ গরম চুইঝাল দিয়ে রান্না করছেন (১৫-২০ মিনিট)';
    } else if (statusUpper === 'DELIVERING' || statusUpper === 'OUT_FOR_DELIVERY') {
        pill.textContent = 'ডেলিভারিতে বের হয়েছে';
        pill.style.color = '#38bdf8';
        step1.classList.add('completed');
        step2.classList.add('completed');
        step3.classList.add('active');
        progress.style.width = '75%';
        bike.style.left = '75%';
        estText.textContent = '🛵 রাইডার আপনার ঠিকানার উদ্দেশ্যে রওনা দিয়েছেন (১০-১৫ মিনিট)';
    } else if (statusUpper === 'DELIVERED' || statusUpper === 'COMPLETED') {
        pill.textContent = 'ডেলিভারি সম্পন্ন';
        pill.style.color = '#4ade80';
        step1.classList.add('completed');
        step2.classList.add('completed');
        step3.classList.add('completed');
        step4.classList.add('completed');
        progress.style.width = '100%';
        bike.style.left = '88%';
        estText.textContent = '✓ খাবার সফলভাবে পৌঁছে দেওয়া হয়েছে! উপভোগ করুন।';
    } else if (statusUpper === 'CANCELLED') {
        pill.textContent = 'বাতিল হয়েছে';
        pill.style.color = '#ef4444';
        estText.textContent = '❌ এই অর্ডারটি বাতিল করা হয়েছে।';
    } else {
        pill.textContent = 'প্রক্রিয়াধীন';
        pill.style.color = '#fbbf24';
        step1.classList.add('active');
        progress.style.width = '20%';
        bike.style.left = '20%';
    }
}
</script>
@endsection
