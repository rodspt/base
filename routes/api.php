
<?php

use Illuminate\Support\Facades\Route;

//Dev
if(getenv('APP_ENV') === 'local') {
   require __DIR__ . '/local.php';
}

//Auth
require __DIR__ . '/auth.php';

//Publicas
Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/resource.php';
//Rotas privadas
Route::middleware('auth:api')->group(function () {

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


