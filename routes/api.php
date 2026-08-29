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
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/track', [OrderController::class, 'track']);

// Contact / Reservation
Route::post('/contact', [ReservationController::class, 'store']);

// Auth
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout']);

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

// Stock
Route::get('/stock', [StockController::class, 'index']);
Route::post('/stock', [StockController::class, 'store']);
Route::patch('/stock/{id}', [StockController::class, 'update']);
Route::delete('/stock/{id}', [StockController::class, 'destroy']);

// Upload
Route::post('/upload/image', [UploadController::class, 'image']);
Route::post('/admin/upload-image', [UploadController::class, 'image']);
