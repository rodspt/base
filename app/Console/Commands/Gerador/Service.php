<?php

namespace App\Console\Commands\Gerador;

class Service
{

    public static function make($arParams)
    {
        $texto = Service::namespace($arParams['nome']);
        $texto = Service::classe($arParams['nome'], $texto);
        $texto = Service::find($texto);
        $texto = Service::show($texto);
        $texto = Service::save($arParams, $texto);
        $texto = Service::delete($texto);
        $texto = Service::search($texto);
        Service::exportar($arParams['nome'],$texto);
    }

    public static function namespace($nome)
    {
        return str_replace("%s",$nome,'<?php

namespace App\Services;

use App\Models\%s as Model;
use App\Traits\ResponseTrait;
use App\Http\Requests\%sRequest as FormRequest;
use App\Http\Resources\%sResource as Resource;
use App\Repositories\%sRepository as Repository;

');
    }


    public static function classe($nome, $texto)
    {
        return $texto.'class '.$nome.'Service
{

    use ResponseTrait;

    public function __construct(public Model $model){}

';
    }


    public static function find($texto)
    {
       $notFound = "'Registro não encontrado'";
       return $texto.'
    public function notFind(){
       return $this->responseError(null, '.$notFound.');
    }

    public function find($id){
        if(!is_null($id)){
            $obj = $this->model->find($id);
            if(is_null($obj)){
                return false;
            }
            $this->model = $obj;
        }
    }';
    }


    public static function show($texto)
    {
      return $texto.'

    public function show($id)
    {
        $find = $this->find($id);
        if($find === false){
            return $this->notFind();
        }

        $resource = new Resource($this->model);
        return $this->responseData($resource->colecao($this->model));
    }';

    }

    public static function save($arParms, $texto)
    {
        $s = "            ";
        $l = "
";
        $texto .= '

    public function save(FormRequest $request, $id = null)
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
              $texto .= $s.'return $this->responseSuccess([],"Registro {$strLabel} com sucesso");;

        } catch (\Exception $e){
            return $this->responseError([], $e->getMessage());
        }
    }';
        return $texto;
    }

    public static function delete($texto)
    {
      return $texto.'

    public function delete($id)
    {
        try {
            if(!is_null($id) && $this->find($id) === false ){
                return $this->notFind();
            }

            $this->model->delete();
            return $this->responseSuccess([],"Registro excluído com sucesso");

        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage());
        }
    }';
    }

    public static function search($texto)
    {
       return $texto.'

    public function search($request)
    {
       $repository = new Repository();
       return new Resource($repository->search($request));
    }

}';
    }


    public static function exportar($nome,$texto)
    {

        $nameFile = "/app/Services/".$nome."Service.php";

        $arquivo = fopen(base_path($nameFile), 'w+');
        fwrite($arquivo, $texto);
        fclose($arquivo);
    }

}
