
<?php

use Illuminate\Support\Facades\Route;


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

Route::get('/cache', function () {
    if(getenv('APP_ENV') === 'local') {
        Artisan::call('cache:clear');
        return "Comando 'cache:clear executado com sucesso!";
    }
});



Route::get('/cuidado', function(){
    App\Services\Example::$values[] = rand(4,2);
    dd(App\Services\Example::$values);
});
