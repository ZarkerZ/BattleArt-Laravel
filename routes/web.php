<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\InterpretationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RatingPollingController;

// ================= PUBLIC ROUTES (Accessible by Guests) =================

// Home & Auth
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Password Reset
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Public Views (Artworks & Profiles)
Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
Route::get('/challenge/{id}', [ChallengeController::class, 'show'])->name('challenges.show') ->whereNumber('id');
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/interpretations', [InterpretationController::class, 'index'])->name('interpretations.index');
Route::get('/check-rating-update', [RatingPollingController::class, 'check']);


// ================= AUTHENTICATED USER ROUTES =================
Route::middleware('auth')->group(function () {

    // Actions (These require login)
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/like/challenge', [InteractionController::class, 'toggleChallengeLike']);
    Route::post('/like/interpretation', [InteractionController::class, 'toggleInterpretationLike']);
    Route::post('/rate/user', [InteractionController::class, 'rateUser']);

    // Personal Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile'); // Redirects to "My Profile"
    Route::get('/edit-profile', [EditProfileController::class, 'edit'])->name('edit-profile.edit');
    Route::post('/edit-profile', [EditProfileController::class, 'update'])->name('edit-profile.update');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
    Route::post('/settings/delete', [SettingsController::class, 'deleteAccount'])->name('settings.delete');
    Route::post('/settings/notifications', [SettingsController::class, 'toggleNotifications']);

    // Challenge Creation & Management
    Route::get('/challenge/create', [ChallengeController::class, 'create'])->name('challenges.create');
    Route::post('/challenge/create', [ChallengeController::class, 'store'])->name('challenges.store');
    Route::get('/challenge/edit/{id}', [ChallengeController::class, 'edit'])->name('challenges.edit');
    Route::post('/challenge/edit/{id}', [ChallengeController::class, 'update'])->name('challenges.update');
    Route::delete('/challenge/{id}', [ChallengeController::class, 'destroy'])->name('challenges.destroy');

    // Interpretation Creation
    Route::get('/interpretation/create', [InterpretationController::class, 'create'])->name('interpretations.create');
    Route::post('/interpretation/store', [InterpretationController::class, 'store'])->name('interpretations.store');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/read/{id?}', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
});


// ================= ADMIN ROUTES =================
// Initial Admin Creation (Protected by Controller Logic)
Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
Route::post('/admin/create', [AdminController::class, 'store'])->name('admin.store');

// Admin Dashboard & Management
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/admin/edit-profile', [AdminController::class, 'edit'])->name('admin.edit');
    Route::post('/admin/edit-profile', [AdminController::class, 'update'])->name('admin.update');

    // User Management
    Route::get('/admin/user/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::post('/admin/user/{id}/status', [AdminController::class, 'updateUserStatus'])->name('admin.users.status');

    // Art Management
    Route::get('/admin/artworks', [AdminController::class, 'manageArt'])->name('admin.art.index');
    Route::post('/admin/artworks/archive', [AdminController::class, 'archiveArt'])->name('admin.art.archive');
    Route::post('/admin/interpretations/archive', [AdminController::class, 'archiveInterpretation'])->name('admin.interpretations.archive');

    // Comment & Report Management
    Route::get('/admin/comments', [AdminController::class, 'manageComments'])->name('admin.comments.index');
    Route::post('/admin/comments/archive', [AdminController::class, 'archiveComment'])->name('admin.comments.archive');
    Route::get('/admin/reports', [AdminController::class, 'manageReports'])->name('admin.reports.index');
    Route::post('/admin/reports/update', [AdminController::class, 'updateReportStatus'])->name('admin.reports.update');
});
