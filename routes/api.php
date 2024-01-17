
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{RouteController, UserController, PerfilController};

//Dev
if(getenv('APP_ENV') === 'local') {
   require __DIR__ . '/local.php';
}

//Auth
require __DIR__ . '/auth.php';

//Publicas
require __DIR__ . '/public.php';


//Rotas privadas
Route::middleware(['auth:api','acl'])->group(function () {
    Route::apiResource('/perfis', PerfilController::class);
    Route::apiResource('/routes', RouteController::class);
    Route::post('/users', [UserController::class,'store'])->name('users.store');
    Route::post('/permissions/{route}', [RouteController::class,'permissions'])->name('permissions');
    Route::get('/approve/{cpf}', [UserController::class,'approve'])->name('approve');
    Route::get('/block/{cpf}', [UserController::class,'block'])->name('block');
    //require __DIR__ . '/resource.php';
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


