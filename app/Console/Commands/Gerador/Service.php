<?php

namespace App\Console\Commands\Gerador;

class Service
{

    public static function make($arParams)
    {
        $texto = Service::namespace($arParams['nome']);
        $texto = Service::classe($arParams['nome'], $texto);
        $texto = Service::save($arParams, $texto);
        Service::exportar($arParams['nome'],$texto);
    }

    public static function namespace($nome)
    {
        return str_replace("%s",$nome,'<?php

namespace App\Services;

use App\Models\%s;
use App\Http\Requests\%s\%sRequest;
use App\Http\Resources\%s\%sResource;
use App\Repositories\%sRepository;
use App\Traits\ResponseTrait;

');
    }


    public static function classe($nome, $texto)
    {
        return $texto.'class '.$nome.'Service extends ModelService
{

    use ResponseTrait;

    public $model;
    public $repository;
    public $resource;

    public function __construct(){
        $this->model = new '.$nome.'();
        $this->repository = new '.$nome.'Repository();
        $this->resource = new '.$nome.'Resource(new '.$nome.'());
    }

';
    }



    public static function save($arParms, $texto)
    {
        $s = "            ";
        $nome = $arParms['nome'];
        $l = "
";
        $texto .= '

    public function save('.$nome.'Request $request, $id = null)
    {

        try {
            if(!is_null($id) && $this->find($id) === false ){
                return $this->notFind();
            }

            $strLabel = is_null($id)? "cadastrado" : "atualizado";
            $modelo = $this->model;
            $arDados = $request->validated();
            '.$l;
        foreach($arParms['campos'] as $campo):
            if($campo['visivel']) {
                $form = "'" . $campo['form'] . "'";
                $texto .= $s . '$modelo->' . $campo['nome'] . ' = $arDados[' . $form . '];' . $l;
            }
        endforeach;

        $texto .= $s.'$modelo->save();'.$l.$l;
        $texto .= $s.'$this->clearCache($this->model, $id);'.$l.$l;

        $texto .= $s.'return $this->responseSuccess([],"Registro {$strLabel} com sucesso");;

        } catch (\Exception $e){
            return $this->responseError([], $e->getMessage());
        }

     }


}
';
        return $texto;
    }


    public static function exportar($nome,$texto)
    {

        $nameFile = "/app/Services/".$nome."Service.php";

        $arquivo = fopen(base_path($nameFile), 'w+');
        fwrite($arquivo, $texto);
        fclose($arquivo);
    }

}
