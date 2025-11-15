<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\CarController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ReservationController;
use Illuminate\Support\Facades\Route;

// Rotas Públicas
Route::get('/', [HomeController::class, 'index'])->name('public.home');
Route::get('/carros', [CarController::class, 'index'])->name('public.cars.index');
Route::get('/carros/{car}', [CarController::class, 'show'])->name('public.cars.show');

// Rotas de Reserva (requerem autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/reservas/criar', [ReservationController::class, 'create'])->name('public.reservations.create');
    Route::post('/reservas', [ReservationController::class, 'store'])->name('public.reservations.store');
    Route::get('/reservas/{reservation}/sucesso', [ReservationController::class, 'success'])->name('public.reservations.success');
});

// Rotas Autenticadas
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Área do Cliente
Route::middleware(['auth', 'verified'])->prefix('cliente')->name('client.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/reservas', [\App\Http\Controllers\Client\ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservas/{reservation}', [\App\Http\Controllers\Client\ReservationController::class, 'show'])->name('reservations.show');
    Route::delete('/reservas/{reservation}/cancelar', [\App\Http\Controllers\Client\ReservationController::class, 'cancel'])->name('reservations.cancel');
    
    Route::get('/notificacoes', [\App\Http\Controllers\Client\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notificacoes/{notification}/marcar-lida', [\App\Http\Controllers\Client\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notificacoes/marcar-todas-lidas', [\App\Http\Controllers\Client\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notificacoes/{notification}', [\App\Http\Controllers\Client\NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    Route::get('/reservas/{reservation}/avaliar', [\App\Http\Controllers\Client\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reservas/{reservation}/avaliar', [\App\Http\Controllers\Client\ReviewController::class, 'store'])->name('reviews.store');
    
    Route::get('/recompensas', [\App\Http\Controllers\Client\RewardController::class, 'index'])->name('rewards.index');
});

require __DIR__.'/auth.php';
