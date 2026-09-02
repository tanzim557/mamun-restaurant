<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CustomerDueController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UploadController;

// Public menu
Route::get('/menu', [MenuController::class, 'index']);
Route::get('/menu/items', [MenuController::class, 'items']);
Route::get('/menu/categories', [MenuController::class, 'categories']);

// Public orders & tracking
Route::get('/orders', [OrderController::class, 'index']);
Route::middleware('throttle:15,1')->post('/orders', [OrderController::class, 'store']);
Route::get('/orders/track', [OrderController::class, 'track']);
Route::get('/orders/{id}', [OrderController::class, 'show']);

// Restaurant Status (Open/Closed)
Route::get('/restaurant/status', [AdminController::class, 'getRestaurantStatus']);
Route::post('/admin/restaurant/status', [AdminController::class, 'updateRestaurantStatus']);
Route::post('/restaurant/status', [AdminController::class, 'updateRestaurantStatus']);

// Contact / Reservation (Throttled to prevent spam)
Route::middleware('throttle:6,1')->post('/contact', [ReservationController::class, 'store']);

// Auth (Strictly throttled to 5 attempts/minute against brute force)
Route::middleware('throttle:5,1')->post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout']);

// Customer Auth & Profile
Route::middleware('throttle:10,1')->post('/customer/register', [AuthController::class, 'customerRegister']);
Route::middleware('throttle:10,1')->post('/customer/login', [AuthController::class, 'customerLogin']);
Route::post('/customer/update-profile', [AuthController::class, 'customerUpdateProfile']);

// Admin CRUD
Route::match(['patch', 'delete'], '/admin/orders/{id}', [AdminController::class, 'manageOrder']);
Route::get('/admin/reservations', [AdminController::class, 'reservations']);
Route::match(['patch', 'delete'], '/admin/reservations/{id}', [AdminController::class, 'manageReservation']);

Route::post('/admin/menu', [AdminController::class, 'createMenuItem']);
Route::match(['patch', 'delete'], '/admin/menu/{id}', [AdminController::class, 'manageMenuItem']);

Route::get('/admin/employees', [EmployeeController::class, 'index']);
Route::post('/admin/employees', [EmployeeController::class, 'store']);
Route::patch('/admin/employees/{id}', [EmployeeController::class, 'update']);
Route::delete('/admin/employees/{id}', [EmployeeController::class, 'destroy']);

Route::get('/admin/customer-dues', [CustomerDueController::class, 'index']);
Route::post('/admin/customer-dues', [CustomerDueController::class, 'store']);
Route::patch('/admin/customer-dues/{id}', [CustomerDueController::class, 'update']);
Route::delete('/admin/customer-dues/{id}', [CustomerDueController::class, 'destroy']);

// Ledger
Route::get('/ledger', [LedgerController::class, 'index']);
Route::post('/ledger', [LedgerController::class, 'store']);
Route::get('/ledger/summary', [LedgerController::class, 'summary']);
Route::delete('/ledger/{id}', [LedgerController::class, 'destroy']);

// Stock / Raw Material Inventory
Route::get('/stock', [StockController::class, 'index']);
Route::get('/admin/stock', [StockController::class, 'index']);
Route::get('/admin/stock/alerts', [StockController::class, 'alerts']);
Route::post('/stock', [StockController::class, 'store']);
Route::post('/admin/stock', [StockController::class, 'store']);
Route::patch('/stock/{id}', [StockController::class, 'update']);
Route::patch('/admin/stock/{id}', [StockController::class, 'update']);
Route::delete('/stock/{id}', [StockController::class, 'destroy']);
Route::delete('/admin/stock/{id}', [StockController::class, 'destroy']);

// Upload
Route::post('/upload/image', [UploadController::class, 'image']);
Route::post('/admin/upload-image', [UploadController::class, 'image']);
