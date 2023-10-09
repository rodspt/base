<?php

namespace App\Console\Commands\Gerador;

class Request
{

    public static function make($arParams)
    {
        $texto = Request::namespace($arParams['nome']);
        $texto = Request::classe($arParams['nome'], $texto);
        $texto = Request::authorize($texto);
        $texto = Request::attributes($arParams, $texto);
        $texto = Request::rules($arParams, $texto);
        Request::save($arParams['nome'],$texto);
    }

    public static function namespace($nome)
    {
        return sprintf("<?php

namespace App\Http\Requests;

use App\Models\%s as Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

",$nome);
    }


    public static function classe($nome, $texto)
    {
        return $texto."class {$nome}Request extends FormRequest
{

";
    }

    public static function authorize($texto)
    {
       return $texto."  public function authorize(): bool
  {
     return true;
  }

";
    }


    public static function attributes($arParams, $texto)
    {
        $s = "   ";
        $l = "
";
       $texto .= $s."public function attributes()
    {
        return [".$l;

        foreach($arParams['campos'] as $campo):
            if($campo['visivel']) {
                $form = "'" . $campo['form'] . "'";
                $label = "'" . $campo['label'] . "'";
                $texto .= "         ".$form . " => " . $label . "," . $l;
            }
        endforeach;



       $texto .= $s."
        ];
    }".$l.$l;

       return $texto;
    }


    public static function rules($arParams, $texto)
    {
        $s = "   ";
        $l = "
";
        $texto .= $s."public function rules(): array".$l;
        $texto .= $s."{".$l.$l;

        $texto .= $s." return [".$l;
        foreach($arParams['campos'] as $campo):
            if($campo['visivel']) {
                $type = "'".$campo['typeForm']."'";
                $required = $campo['required']? "'required'" : "'nullable'";
                $form = "'" . $campo['form'] . "'";
                $texto .= "         ".$form . " => [".$type.','.$required;

                if(isset($campo['atributos']['min'])){
                    $texto .=",'min:".$campo['atributos']['min']."'";
                }
                if(isset($campo['atributos']['max'])){
                    $texto .=",'max:".$campo['atributos']['max']."'";
                }
                if(isset($campo['atributos']['uniqueform'])){
                    $route = $arParams['rota'];
                    $texto .=",". 'Rule::unique(Model::class)->ignore($this?->'.$route.')';
                }

                $texto .= "],".$l;
            }
        endforeach;

        $texto .= $s.$s."];".$l.$l;
        $texto .= $s."}".$l.$l;
        $texto .= "}";
        return $texto;
    }

    public static function save($nome,$texto)
    {

        $nameFile = "/app/Http/Requests/".$nome."Request.php";

        $arquivo = fopen(base_path($nameFile), 'w+');
        fwrite($arquivo, $texto);
        fclose($arquivo);
    }

}
