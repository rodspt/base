<?php

namespace App\Services;

use App\Models\Teste as Model;
use App\Http\Requests\TestRequest as FormRequest;
use App\Repositories\TestRepository as Repository;
use App\Http\Resources\TestResource as Resource;
use App\Traits\ResponseTrait;

class TestService extends ModelService
{
    use ResponseTrait;

    public $model;
    public $repository;
    public $resource;

    public function __construct(){
        $this->model = new Model();
        $this->repository = new Repository();
        $this->resource = new Resource(new Model());
    }


    public function save(FormRequest $request, $id = null)
    {

        try {
            if(!is_null($id) && $this->find($id) === false ){
                return $this->notFind();
            }

            $strLabel = is_null($id)? "cadastrado" : "atualizado";
            $this->model->fill($request->validated());
            $this->model->save();
            $this->clearCache($this->model, $id);

            return $this->responseSuccess([],"Registro {$strLabel} com sucesso");

        } catch (\Exception $e){
            return $this->responseError([], $e->getMessage());
        }
    }

}
