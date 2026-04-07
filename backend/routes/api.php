<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;

// ── Public Routes (Throttled) ──────────────────────────────────
Route::middleware('throttle:60,1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->middleware('throttle:10,1');
        Route::post('login',    [AuthController::class, 'login'])->middleware('throttle:10,1');
    });

    // Listing data (Public)
    Route::get('packages',       [PackageController::class, 'index']);
    Route::get('packages/{id}',  [PackageController::class, 'show']);
    Route::get('cars',           [CarController::class, 'index']);
    Route::get('cars/{id}',      [CarController::class, 'show']);
    Route::get('blog',           [BlogController::class, 'index']);
    Route::get('blog/{id}',      [BlogController::class, 'show']);
    Route::get('cities',         [CityController::class, 'index']);

    // Integrated Feature: Public Reviews & Gallery
    Route::get('reviews',        [ReviewController::class, 'index']);
    Route::post('reviews',       [ReviewController::class, 'store'])->middleware('throttle:5,1');
    Route::get('galleries',      [GalleryController::class, 'index']);

    Route::post('bookings/guest', [BookingController::class, 'store'])->middleware('throttle:5,1');
});


// ── Authenticated Routes ────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('auth/logout',          [AuthController::class, 'logout']);
    Route::get('auth/me',               [AuthController::class, 'me']);
    Route::put('auth/profile',          [AuthController::class, 'updateProfile']);

    Route::get('bookings',              [BookingController::class, 'index']);
    Route::post('bookings',             [BookingController::class, 'store']);

    // ── Staff / Admin Routes ──────────────────────────────────
    Route::middleware('role:staff')->group(function () {
        Route::put('bookings/{id}',     [BookingController::class, 'update']);
        Route::delete('bookings/{id}',  [BookingController::class, 'destroy']);

        Route::post('packages',         [PackageController::class, 'store']);
        Route::put('packages/{id}',     [PackageController::class, 'update']);
        Route::delete('packages/{id}',  [PackageController::class, 'destroy']);

        Route::post('cars',             [CarController::class, 'store']);
        Route::put('cars/{id}',         [CarController::class, 'update']);
        Route::delete('cars/{id}',      [CarController::class, 'destroy']);

        Route::post('cities',           [CityController::class, 'store']);
        Route::put('cities/{id}',       [CityController::class, 'update']);
        Route::delete('cities/{id}',    [CityController::class, 'destroy']);

        Route::post('blog',             [BlogController::class, 'store']);
        Route::put('blog/{id}',         [BlogController::class, 'update']);
        Route::delete('blog/{id}',      [BlogController::class, 'destroy']);

        // Integrated Features (Admin Management)
        Route::get('admin/reviews',     [ReviewController::class, 'adminIndex']);
        Route::put('reviews/{id}/approve', [ReviewController::class, 'approve']);
        Route::delete('reviews/{id}',   [ReviewController::class, 'destroy']);

        Route::post('galleries',        [GalleryController::class, 'store']);
        Route::delete('galleries/{id}', [GalleryController::class, 'destroy']);

        Route::get('admin/stats',       [AdminController::class, 'stats']);
        Route::get('users',             [UserController::class, 'index']);
        Route::put('users/{id}',        [UserController::class, 'update']);
    });

    // ── Admin only ────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::delete('users/{id}',     [UserController::class, 'destroy']);
    });
});
