
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/migrate', function () {
    if(getenv('APP_ENV') === 'local') {
        Artisan::call('migrate:refresh', ['--seed' => true, '--force' => true]);
        return "Comando 'migrate:refresh --seed' executado com sucesso!";
    }
});

Route::get('/swagger', function () {
    if(getenv('APP_ENV') === 'local') {
        Artisan::call('l5-swagger:generate');
        return "Comando 'l5-swagger:generate executado com sucesso!";
    }
});

Route::get('/octane', function () {
    if(getenv('APP_ENV') === 'local') {
        Artisan::call('octane:reload');
        return "Comando 'octane:reload executado com sucesso!";
    }
});



Route::get('/cuidado', function(){
    App\Services\Example::$values[] = rand(4,2);
    dd(App\Services\Example::$values);
});


require __DIR__ . '/auth.php';


Route::middleware('auth:api')->group(function () {
    Route::apiResource('/teste', App\Http\Controllers\TestController::class);
});



/*
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [App\Http\Controllers\UserController::class, 'show'])
        ->name('user.show');
});

Route::middleware(['auth:api', 'verified'])->group(function () {
    Route::patch('/user', [UserController::class, 'update'])
        ->name('user.update');

    Route::patch('/user/change-password', [UserController::class, 'changePassword'])
        ->name('user.change-password');
});
*/


