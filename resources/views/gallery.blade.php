@extends('layouts.app')
@section('title', 'Gallery — মামুন হোটেল')

@section('content')
<section class="hero hero-short" style="margin-top:-64px;">
    <div class="hero-bg" style="background-image:url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1920&q=80');"></div>
    <div class="hero-content"><span class="text-secondary font-bold uppercase tracking-wide text-sm" style="display:block;margin-bottom:0.75rem;">A Feast for the Eyes</span><h1>Gallery</h1></div>
</section>
<section style="padding:2rem 0;border-bottom:1px solid var(--zinc-200);">
    <div class="container flex justify-center gap-3" style="flex-wrap:wrap;" id="albumTabs"></div>
</section>
<section class="section" style="padding-top:3rem;">
    <div class="container"><div class="gallery-grid" id="galleryGrid"></div></div>
</section>
<div class="lightbox" id="lightbox">
    <div class="lightbox-inner">
        <img id="lightboxImg" src="" alt="">
        <div class="lightbox-caption"><p id="lightboxCaption" class="text-white font-bold text-lg"></p><span id="lightboxAlbum" class="text-secondary text-sm"></span></div>
        <button class="lightbox-close" onclick="closeLightbox()">&times;</button>
    </div>
</div>
@endsection

@section('scripts')
<script src="/js/gallery.js"></script>
@endsection
