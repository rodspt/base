<?php

use App\Http\Controllers\Auth\{
            JwtAuthController,
            ResetPasswordController,
            SendPasswordResetLinkController,
            VerifyEmailController
};
use Illuminate\Support\Facades\Route;


Route::post('/login', [JwtAuthController::class, 'login'])
    ->name('login');


Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');


Route::post('/refresh', [JwtAuthController::class, 'refresh'])
    ->name('refresh');

Route::post('/forgot-password', SendPasswordResetLinkController::class)
    ->name('password.email');

Route::post('/reset-password', ResetPasswordController::class)
    ->name('password.update');



Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [JwtAuthController::class, 'logout'])
        ->name('logout');
});
