<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\NotificationController;

Route::prefix('v1')->group(function () {
    // Cars
    Route::get('cars', [CarController::class, 'index']);
    Route::get('cars/{car}', [CarController::class, 'show']);
    Route::post('cars', [CarController::class, 'store']);
    Route::put('cars/{car}', [CarController::class, 'update']);
    Route::delete('cars/{car}', [CarController::class, 'destroy']);

    // Reservations
    Route::get('reservations', [ReservationController::class, 'index']);
    Route::get('reservations/{reservation}', [ReservationController::class, 'show']);
    Route::post('reservations', [ReservationController::class, 'store']);
    Route::put('reservations/{reservation}', [ReservationController::class, 'update']);
    Route::delete('reservations/{reservation}', [ReservationController::class, 'destroy']);
    Route::post('reservations/{reservation}/status', [ReservationController::class, 'updateStatus']);
    Route::post('reservations/{reservation}/pay', [ReservationController::class, 'pay']);

    // Drivers
    Route::get('drivers', [DriverController::class, 'index']);
    Route::get('drivers/{driver}', [DriverController::class, 'show']);
    Route::post('drivers', [DriverController::class, 'store']);
    Route::put('drivers/{driver}', [DriverController::class, 'update']);
    Route::delete('drivers/{driver}', [DriverController::class, 'destroy']);

    // Locations
    Route::get('locations', [LocationController::class, 'index']);
    Route::get('locations/{location}', [LocationController::class, 'show']);
    Route::post('locations', [LocationController::class, 'store']);
    Route::put('locations/{location}', [LocationController::class, 'update']);
    Route::delete('locations/{location}', [LocationController::class, 'destroy']);

    // Users
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    // Notifications
    Route::get('users/{user}/notifications', [NotificationController::class, 'index']);
    Route::post('users/{user}/notifications', [NotificationController::class, 'store']);
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy']);
});


