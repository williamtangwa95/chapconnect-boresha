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

use App\Http\Controllers\InteractionController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/category/{category}', [HomeController::class, 'category'])->name('category');
Route::get('/profile/{id}', [HomeController::class, 'profile'])->name('profile');
Route::get('/profile/{id}/photos', [HomeController::class, 'photos'])->name('profile.photos');
Route::get('/profile/{id}/videos', [HomeController::class, 'videos'])->name('profile.videos');
Route::get('/download/app', [HomeController::class, 'downloadApp'])->name('app.download');
// Public contact request — throttled to 5/hour per IP (additional app-level check inside controller)
Route::post('/profile/{id}/connect', [\App\Http\Controllers\ContactRequestController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('profile.connect');

// Public Talent Interaction Routes (Likes, Followers, Comments)
Route::post('/talent/{id}/like', [InteractionController::class, 'toggleLike'])->name('talent.like');
Route::post('/talent/{id}/follow', [InteractionController::class, 'toggleFollow'])->name('talent.follow');
Route::post('/talent/{id}/comment', [InteractionController::class, 'storeComment'])->name('talent.comment');
Route::delete('/comment/{id}', [InteractionController::class, 'deleteComment'])->name('talent.comment.delete');
Route::get('/interactions/status', [InteractionController::class, 'getStatuses'])->name('talent.interactions.status');

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes (Security Question & Answer)
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('password.email');
Route::get('/forgot-password/verify', [AuthController::class, 'showSecurityQuestionRecovery'])->name('password.verify-question');
Route::post('/forgot-password/verify', [AuthController::class, 'verifySecurityQuestionAndReset'])->name('password.verify-submit');

// User Dashboard Panel Routes (Authenticated)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/update', [DashboardController::class, 'update'])->name('dashboard.update');
    
    // Photos uploads management
    Route::get('/dashboard/photos', [DashboardController::class, 'photos'])->name('dashboard.photos');
    Route::post('/dashboard/photos', [DashboardController::class, 'storePhoto'])->name('dashboard.photos.store');
    Route::post('/dashboard/photos/{id}/update', [DashboardController::class, 'updatePhoto'])->name('dashboard.photos.update');
    Route::delete('/dashboard/photos/{id}', [DashboardController::class, 'deletePhoto'])->name('dashboard.photos.delete');
    
    // Videos uploads management
    Route::get('/dashboard/videos', [DashboardController::class, 'videos'])->name('dashboard.videos');
    Route::post('/dashboard/videos', [DashboardController::class, 'storeVideo'])->name('dashboard.videos.store');
    Route::post('/dashboard/videos/{id}/update', [DashboardController::class, 'updateVideo'])->name('dashboard.videos.update');
    Route::delete('/dashboard/videos/{id}', [DashboardController::class, 'deleteVideo'])->name('dashboard.videos.delete');

    // News updates management
    Route::get('/dashboard/news', [DashboardController::class, 'news'])->name('dashboard.news');
    Route::post('/dashboard/news', [DashboardController::class, 'storeNews'])->name('dashboard.news.store');
    Route::post('/dashboard/news/{id}/update', [DashboardController::class, 'updateNews'])->name('dashboard.news.update');
    Route::delete('/dashboard/news/{id}', [DashboardController::class, 'deleteNews'])->name('dashboard.news.delete');

    // Comments management
    Route::get('/dashboard/comments', [DashboardController::class, 'comments'])->name('dashboard.comments');

    // Publish / Unpublish profile
    Route::post('/dashboard/publish', [DashboardController::class, 'publish'])->name('dashboard.publish');
    Route::post('/dashboard/unpublish', [DashboardController::class, 'unpublish'])->name('dashboard.unpublish');
Route::get('/dashboard/support/submit', function () {
    return redirect()->route('login');
});
    // Submit support issue / ticket
    Route::post('/dashboard/support/submit', [\App\Http\Controllers\CustomerCareController::class, 'userSubmit'])->name('dashboard.support.submit');

    // Notifications API
    Route::get('/notifications/unread', [\App\Http\Controllers\NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('/notifications/{id}/mark-read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    // Assigned staff ticket action route
    Route::post('/admin/tickets/{id}/staff-action', [\App\Http\Controllers\CustomerCareController::class, 'staffAction'])->name('admin.tickets.staff-action');

    // Invoice printing view route
    Route::get('/dashboard/invoice/{id}', [DashboardController::class, 'printInvoice'])->name('dashboard.invoice.print');

    // Target user dashboard action route
    Route::post('/dashboard/contact-requests/{id}/action', [\App\Http\Controllers\ContactRequestController::class, 'userAction'])->name('dashboard.contact-requests.action');

    // Administrative action route (Admin / Customer Care)
    Route::post('/admin/contact-requests/{id}/action', [\App\Http\Controllers\ContactRequestController::class, 'adminAction'])->name('admin.contact-requests.action');

    // Talent Payment Request submission
    Route::post('/dashboard/request-payment', [DashboardController::class, 'requestPayment'])->name('dashboard.request-payment');
});

// Customer Care Dashboard & Support Ticket Management (Protected by auth and customer_care middleware)
Route::middleware(['auth', 'customer_care'])->group(function () {
    Route::get('/customer-care', [\App\Http\Controllers\CustomerCareController::class, 'index'])->name('customer-care.dashboard');
    Route::post('/customer-care/tickets', [\App\Http\Controllers\CustomerCareController::class, 'store'])->name('customer-care.tickets.store');
    Route::post('/customer-care/tickets/{id}/update', [\App\Http\Controllers\CustomerCareController::class, 'update'])->name('customer-care.tickets.update');
    Route::delete('/customer-care/tickets/{id}', [\App\Http\Controllers\CustomerCareController::class, 'destroy'])->name('customer-care.tickets.delete');
    Route::post('/customer-care/unblock/{id}', [\App\Http\Controllers\CustomerCareController::class, 'unblockAccount'])->name('customer-care.unblock');
});

// Super Admin Panel Routes (Protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/analytics/export-excel', [AdminController::class, 'exportAnalyticsExcel'])->name('admin.analytics.export-excel');
    Route::get('/admin/analytics/download-pdf', [AdminController::class, 'downloadAnalyticsPdf'])->name('admin.analytics.download-pdf');
    Route::get('/admin/activity-logs/export-excel', [AdminController::class, 'exportActivityExcel'])->name('admin.activity-logs.export-excel');
    Route::get('/admin/activity-logs/download-pdf', [AdminController::class, 'downloadActivityPdf'])->name('admin.activity-logs.download-pdf');
    Route::post('/admin/user/store', [AdminController::class, 'storeUser'])->name('admin.user.store');
    Route::post('/admin/staff/store', [AdminController::class, 'storeStaff'])->name('admin.staff.store');
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
    Route::post('/admin/settings/notification-sound', [AdminController::class, 'uploadNotificationSound'])->name('admin.settings.notification-sound');
    Route::post('/admin/settings/update', [AdminController::class, 'updateSystemSettings'])->name('admin.settings.update');
    Route::post('/admin/settings/reset-welcome-sound', [AdminController::class, 'resetWelcomeSound'])->name('admin.settings.reset-welcome-sound');
    Route::post('/admin/settings/clear-cache', [AdminController::class, 'clearCache'])->name('admin.settings.clear-cache');

    // Packages & Invoices CRUD/Payment Routes
    Route::post('/admin/packages', [AdminController::class, 'storePackage']);
    Route::post('/admin/packages/{id}/update', [AdminController::class, 'updatePackage']);
    Route::delete('/admin/packages/{id}', [AdminController::class, 'deletePackage'])->name('admin.packages.delete');
    Route::post('/admin/user/{id}/assign-package', [AdminController::class, 'assignPackage']);
    Route::post('/admin/invoices/{id}/pay', [AdminController::class, 'recordInvoicePayment']);

    // Talent Payment Request Administration Routes
    Route::post('/admin/settings/payment-criteria', [AdminController::class, 'updatePaymentCriteria'])->name('admin.settings.payment-criteria');
    Route::post('/admin/payment-requests/{id}/pay', [AdminController::class, 'payRequest'])->name('admin.payment-requests.pay');
    Route::post('/admin/payment-requests/{id}/reject', [AdminController::class, 'rejectRequest'])->name('admin.payment-requests.reject');
});
