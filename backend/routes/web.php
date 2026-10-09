<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadFollowUpController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware('auth')->name('api.')->group(function (): void {
    Route::apiResource('leads', LeadController::class)->only(['index', 'store', 'show', 'update']);
    Route::get('leads/{lead}/follow-ups', [LeadFollowUpController::class, 'index'])
        ->name('leads.follow-ups.index');
    Route::post('leads/{lead}/follow-ups', [LeadFollowUpController::class, 'store'])
        ->name('leads.follow-ups.store');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'create'])
        ->name('password.confirm');
    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware('throttle:password-confirm')
        ->name('password.confirm.store');
});

Route::middleware('admin-system')->group(function (): void {
    Route::view('/admin-sistem', 'admin.system')->name('admin.system.index');
});

Route::get('/', function () {
    return view('welcome');
});
