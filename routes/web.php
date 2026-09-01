<?php

use Illuminate\Support\Facades\Route;

// All customer-facing pages and admin pages served as Blade views
Route::get('/', function () {
    try {
        $menuItems = \App\Models\MenuItem::with('category')->get();
        $categories = \App\Models\Category::all();
    } catch (\Throwable $e) {
        $menuItems = collect([]);
        $categories = collect([]);
    }
    return view('home', compact('menuItems', 'categories'));
})->name('home');
Route::view('/menu', 'menu')->name('menu');
Route::view('/order', 'order')->name('order');
Route::view('/track', 'track')->name('track');
Route::redirect('/gallery', '/menu');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/admin', 'admin.login')->name('admin.login');
Route::view('/admin/login', 'admin.login');
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
