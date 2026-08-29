// Gallery Page Logic
const albums = ['All', 'Food', 'Interior', 'Events'];
let activeAlbum = 'All';

const galleryImages = [
    { url: '/images/logo.jpg', caption: 'শ্যামনগর নজরুল হোটেল ব্যানার', album: 'Food' },
    { url: '/images/owner.jpg', caption: 'প্রোঃ আল-মামুন', album: 'Interior' },
    { url: 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1200&q=80', caption: 'স্পেশাল মাটন ও গরুর ঝাল মাংস', album: 'Food' },
    { url: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80', caption: 'রেস্তোরাঁর সুন্দর পরিবেশ', album: 'Interior' },
    { url: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80', caption: 'পারিবারিক খাবার আয়োজন', album: 'Events' },
    { url: 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80', caption: 'মুখরোচক বাংলা খাবার', album: 'Food' }
];

document.addEventListener('DOMContentLoaded', () => {
    renderAlbumTabs();
    renderGallery();
});

function renderAlbumTabs() {
    const container = document.getElementById('albumTabs');
    if (!container) return;

    container.innerHTML = albums.map(a => `
        <button class="filter-tab ${activeAlbum === a ? 'active' : ''}" onclick="setAlbum('${a}')">${a}</button>
    `).join('');
}

function setAlbum(a) {
    activeAlbum = a;
    renderAlbumTabs();
    renderGallery();
}

function renderGallery() {
    const grid = document.getElementById('galleryGrid');
    if (!grid) return;

    const filtered = activeAlbum === 'All' ? galleryImages : galleryImages.filter(img => img.album === activeAlbum);

    grid.innerHTML = filtered.map(img => `
        <div class="gallery-item animate-fade-up" onclick="openLightbox('${img.url}', '${img.caption.replace(/'/g, "\\'")}', '${img.album}')">
            <img src="${img.url}" alt="${img.caption}" loading="lazy">
            <div class="gallery-overlay">
                <div>
                    <p>${img.caption}</p>
                    <span>${img.album}</span>
                </div>
            </div>
        </div>
    `).join('');
}

function openLightbox(url, caption, album) {
    document.getElementById('lightboxImg').src = url;
    document.getElementById('lightboxCaption').innerText = caption;
    document.getElementById('lightboxAlbum').innerText = album;
    document.getElementById('lightbox').classList.add('open');
}

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
}
