<?php

namespace App\Console\Commands\Gerador;

class Resource
{
    public static function make($arParams)
    {
        $texto = Resource::classe($arParams['nome']);
        $texto = Resource::colecao($arParams, $texto);
        Resource::save($arParams['nome'],$texto);
    }

    public static function classe($nome)
    {
        $data = "'data'";
        $total = "'total'";
        return str_replace("%s",$nome,'<?php

namespace App\Http\Resources;

use App\Models\%s as Model;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestResource extends ResourceCollection
{
    public function toArray($request)
    {
        $this->collection->transform(function(Model $model) {
            return $this->colecao($model);
        });
        return [
            '.$data.' => $this->collection,
            '.$total.' => $this->resource->count()
        ];
    }

');
    }


    public static function colecao($arParams, $texto)
    {
        $s = "   ";
        $l = "
";
        $texto .= $s.'public function colecao(Model $model)
    {
        return ['.$l;

        foreach($arParams['campos'] as $campo):
            if($campo['visivel']) {
                $form = "'" . $campo['form'] . "'";
                $nome =  $campo['nome'];
                $texto .= "         ".$form . ' => $model->'. $nome . "," . $l;
            }
        endforeach;



        $texto .= $s."
        ];
    }".$l.$l;

$texto .= "}";

        return $texto;
    }


    public static function save($nome,$texto)
    {

        $nameFile = "/app/Http/Resources/".$nome."Resource.php";

        $arquivo = fopen(base_path($nameFile), 'w+');
        fwrite($arquivo, $texto);
        fclose($arquivo);
    }

}
