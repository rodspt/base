<?php

namespace App\Services;

use App\Traits\ResponseTrait;

class ModelService
{
    use ResponseTrait;

    public $model;
    public $repository;
    public $resource;

    public function __construct($model, $repository,$resource){
        $this->model = $model;
        $this->repository = $repository;
        $this->resource = $resource;
    }

    public function notFind(){
        return $this->responseError(null, 'Registro não encontrado');
    }

    public function find($id){
        if(!is_null($id)){
            $obj = $this->model->find($id);
            if(is_null($obj)){
                return false;
            }
            $this->model = $obj;
        }
    }

    public function show($id)
    {
        $find = $this->find($id);
        if($find === false){
            return $this->notFind();
        }

        $resource = new $this->resource($this->model);
        return $this->responseData($resource->colecao($this->model));
    }

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
    }

    /**
     * Search users with params request
     *
     * @param Request
     * @return Collection
     */
    public function search($request)
    {

        $data = $this->repository->search($request);
        return new $this->resource($data);
    }
}
