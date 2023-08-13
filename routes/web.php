<?php

use Illuminate\Support\Facades\Route;
use App\Services\Example;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/cuidado', function(){
    Example::$values[] = rand(4,2);
    dd(Example::$values);
});

Route::get('/', function () {
    return view('welcome');
});
