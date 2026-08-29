<?php

use Illuminate\Support\Facades\Route;

// All customer-facing pages and admin pages served as Blade views
Route::view('/', 'home')->name('home');
Route::view('/menu', 'menu')->name('menu');
Route::view('/order', 'order')->name('order');
Route::redirect('/gallery', '/menu');
Route::view('/about', 'about')->name('about');
Route::view('/contact', 'contact')->name('contact');
Route::view('/admin', 'admin.login')->name('admin.login');
Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
