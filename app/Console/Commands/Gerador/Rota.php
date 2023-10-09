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
        $name = $arParams['nome'];
        $nameFile = $arParams['auth'] === true? "/routes/resource.php" : "/routes/public.php";
        $nameFile = base_path($nameFile);
        $texto = str_replace('%s', $name,"\nRoute::apiResource('/{$arParams['rota']}', App\Http\Controllers\%sController::class);");

        $file = fopen($nameFile, "r+");
        while (($line = fgets($file)) !== false) {
            echo $line;
        }
        fwrite($file, $texto);
        fclose($file);
    }

}
