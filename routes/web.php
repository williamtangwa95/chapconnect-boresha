<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{category}', [HomeController::class, 'category'])->name('category');
Route::get('/profile/{id}', [HomeController::class, 'profile'])->name('profile');
Route::get('/profile/{id}/photos', [HomeController::class, 'photos'])->name('profile.photos');
Route::get('/profile/{id}/videos', [HomeController::class, 'videos'])->name('profile.videos');

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// User Dashboard Panel Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/update', [DashboardController::class, 'update'])->name('dashboard.update');
    
    // Photos uploads management
    Route::get('/dashboard/photos', [DashboardController::class, 'photos'])->name('dashboard.photos');
    Route::post('/dashboard/photos', [DashboardController::class, 'storePhoto'])->name('dashboard.photos.store');
    Route::delete('/dashboard/photos/{id}', [DashboardController::class, 'deletePhoto'])->name('dashboard.photos.delete');
    
    // Videos uploads management
    Route::get('/dashboard/videos', [DashboardController::class, 'videos'])->name('dashboard.videos');
    Route::post('/dashboard/videos', [DashboardController::class, 'storeVideo'])->name('dashboard.videos.store');
    Route::delete('/dashboard/videos/{id}', [DashboardController::class, 'deleteVideo'])->name('dashboard.videos.delete');

    // Publish / Unpublish profile
    Route::post('/dashboard/publish', [DashboardController::class, 'publish'])->name('dashboard.publish');
    Route::post('/dashboard/unpublish', [DashboardController::class, 'unpublish'])->name('dashboard.unpublish');
});

// Super Admin Panel Routes (Protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');
    Route::delete('/admin/media/{id}', [AdminController::class, 'deleteMedia'])->name('admin.media.delete');
    Route::post('/admin/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    Route::delete('/admin/categories/{id}', [AdminController::class, 'deleteCategory'])->name('admin.categories.delete');
    Route::post('/admin/categories/{id}/update', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::post('/admin/user/{id}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.user.reset-password');
    Route::post('/admin/user/{id}/update', [AdminController::class, 'updateUser'])->name('admin.user.update');
    Route::delete('/admin/users/bulk-delete', [AdminController::class, 'bulkDelete'])->name('admin.users.bulk-delete');
    Route::post('/admin/user/{id}/toggle-publish', [AdminController::class, 'togglePublish'])->name('admin.user.toggle-publish');
    Route::post('/admin/users/bulk-publish', [AdminController::class, 'bulkPublish'])->name('admin.users.bulk-publish');
    Route::post('/admin/users/bulk-unpublish', [AdminController::class, 'bulkUnpublish'])->name('admin.users.bulk-unpublish');
});
