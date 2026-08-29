// Shared App JS: Navbar Toggle & Scroll Effects
document.addEventListener('DOMContentLoaded', () => {
    const navToggle = document.getElementById('navToggle');
    const mobileNav = document.getElementById('mobileNav');
    const mobileNavClose = document.getElementById('mobileNavClose');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    const navbar = document.getElementById('navbar');

    if (navToggle && mobileNav) {
        navToggle.addEventListener('click', () => mobileNav.classList.add('open'));
    }
    if (mobileNavClose) {
        mobileNavClose.addEventListener('click', () => mobileNav.classList.remove('open'));
    }
    if (mobileNavOverlay) {
        mobileNavOverlay.addEventListener('click', () => mobileNav.classList.remove('open'));
    }

    window.addEventListener('scroll', () => {
        if (navbar) {
            if (window.scrollY > 20) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        }
    });
});
