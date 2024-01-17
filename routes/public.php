<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {  return view('welcome'); });

Route::post('/solicitar', [UserController::class, 'solicitar'])
    ->name('solicitar');


