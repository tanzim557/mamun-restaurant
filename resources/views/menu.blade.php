@extends('layouts.app')
@section('title', 'Menu — মামুন হোটেল')

@section('content')
<section class="hero hero-short" style="margin-top:-64px;">
    <div class="hero-bg" style="background-image:url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1920&q=80');"></div>
    <div class="hero-content">
        <span class="text-secondary font-bold uppercase tracking-wide text-sm" style="display:block;margin-bottom:0.75rem;">Curated with Passion</span>
        <h1>Our Menu</h1>
    </div>
</section>

<div class="filter-bar">
    <div class="container flex items-center justify-between gap-4" style="flex-wrap:wrap;">
        <div class="search-box" style="display:flex;align-items:center;position:relative;">
            <span class="search-icon" style="position:absolute;left:14px;color:var(--zinc-400);display:flex;align-items:center;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input type="text" id="menuSearch" placeholder="Search dishes..." style="padding-left:2.5rem;">
        </div>
        <div class="filter-tabs" id="categoryTabs"></div>
    </div>
</div>

<section class="section" style="padding-top:3rem;">
    <div class="container">
        <div class="grid grid-3" id="menuGrid"></div>
        <div id="menuEmpty" class="hidden text-center" style="padding:4rem 0;color:var(--zinc-400);">
            <div style="margin-bottom:1rem;opacity:0.3;display:flex;justify-content:center;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/></svg>
            </div>
            <p style="font-size:1.2rem;">No dishes found. Try a different filter.</p>
        </div>
        <div id="menuLoading"><div class="grid grid-3"><div class="skeleton" style="height:380px;"></div><div class="skeleton" style="height:380px;"></div><div class="skeleton" style="height:380px;"></div></div></div>
    </div>
</section>
@endsection

@section('scripts')
<script src="/js/menu.js"></script>
@endsection
