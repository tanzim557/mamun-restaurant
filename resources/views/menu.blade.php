@extends('layouts.app')
@section('title', 'খাবারের সম্পূর্ণ মেনু — শ্যামনগর নজরুল হোটেল')

@section('content')
<div class="app-container">
    <!-- In-App Search Bar -->
    <section class="app-search-section">
        <div class="app-search-box">
            <span class="app-search-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </span>
            <input type="text" id="menuSearchInput" class="app-search-input" placeholder="মেনুর খাবার খুঁজুন... (গরু, হাঁস, মাছ, মুরগী)" oninput="filterMenuSearch(this.value)">
            <button type="button" id="menuSearchClear" class="app-search-clear hidden" onclick="clearMenuSearch()">✕</button>
        </div>
    </section>

    <!-- Sticky Category Pills -->
    <div class="app-cat-sticky-bar">
        <div class="app-cat-pills" id="menuCategoryPills">
            <button class="cat-pill active" onclick="selectMenuCat('all', this)">🔥 সকল পদ</button>
        </div>
    </div>

    <!-- Menu Grid -->
    <section class="mt-4 mb-4">
        <div class="app-section-header" style="margin-top:0;">
            <div class="app-section-title-wrap">
                <span class="app-section-icon">🍲</span>
                <h2 class="app-section-title" id="menuSectionTitle">সকল খাবারের মেনু</h2>
            </div>
            <span class="text-muted text-xs" id="menuItemCount">লোড হচ্ছে...</span>
        </div>

        <div id="menuLoading" class="food-app-grid">
            <div class="skeleton" style="height:240px;"></div>
            <div class="skeleton" style="height:240px;"></div>
            <div class="skeleton" style="height:240px;"></div>
            <div class="skeleton" style="height:240px;"></div>
        </div>

        <div class="food-app-grid" id="menuGrid"></div>

        <div id="menuEmpty" class="hidden text-center" style="padding:4rem 1rem;">
            <div style="font-size:3rem;margin-bottom:0.5rem;">🔍</div>
            <h4 style="font-size:1.15rem;font-weight:800;color:#fff;">কোনো খাবার খুঁজে পাওয়া যায়নি</h4>
            <p class="text-muted text-sm mt-2">অন্য কোনো ক্যাটাগরি বেছে নিন অথবা সার্চ পরিবর্তন করুন।</p>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script src="/js/menu.js"></script>
@endsection
