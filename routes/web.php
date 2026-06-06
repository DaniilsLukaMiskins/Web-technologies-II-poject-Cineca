<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

//  main page
Route::get('/', [MovieController::class, 'home'])->name('home');

Route::get('/lang/{locale}', function ($locale) {
    $supported = ['en', 'lv'];
    if (in_array($locale, $supported)) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');

// only for GUESTS
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// only for AUTHORIZED (including visitor - only logout)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// only for user, moderator, admin (NOT visitor)
Route::middleware(['auth', 'role:user,moderator,admin'])->group(function () {

    // Movies
    Route::get('/movies', [MovieController::class, 'index'])->name('movies.index');
    Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
    Route::get('/movies/{tmdbId}', [MovieController::class, 'show'])->name('movies.show');

    // Reviews
    Route::get('/movies/{tmdbId}/review/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Watchlist
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist', [WatchlistController::class, 'store'])->name('watchlist.store');
    Route::put('/watchlist/{watchlist}', [WatchlistController::class, 'update'])->name('watchlist.update');
    Route::delete('/watchlist/{watchlist}', [WatchlistController::class, 'destroy'])->name('watchlist.destroy');

    // Friends
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::post('/friends', [FriendController::class, 'store'])->name('friends.store');
    Route::put('/friends/{friend}', [FriendController::class, 'update'])->name('friends.update');
    Route::delete('/friends/{friend}', [FriendController::class, 'destroy'])->name('friends.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::get('/profile/reviews', [ProfileController::class, 'reviews'])->name('profile.reviews');

    // Admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
        Route::post('/admin/users/{user}/role', [AdminController::class, 'changeRole'])->name('admin.changeRole');
        Route::get('/admin/log', [AdminController::class, 'log'])->name('admin.log');
    });
});