<?php

namespace App\Console\Commands\Gerador;

class Rota
{

    public static function make($arParams)
    {
       Rota::save($arParams);
    }

    public static function save($arParams)
    {
        $nameFile = $arParams['auth'] === true? "/routes/resource.php" : "/routes/public.php";
        $nameFile = base_path($nameFile);
        $texto = "\nRoute::apiResource('/{$arParams['rota']}', App\Http\Controllers\TestController::class);";

        $file = fopen($nameFile, "r+");
        while (($line = fgets($file)) !== false) {
            echo $line;
        }
        fwrite($file, $texto);
        fclose($file);
    }

}
