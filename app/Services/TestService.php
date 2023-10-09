<?php

namespace App\Services;

use App\Models\Teste as Model;
use App\Traits\ResponseTrait;
use App\Http\Requests\TestRequest as FormRequest;
use App\Http\Resources\TestResource as Resource;
use App\Repositories\TestRepository as Repository;

class TestService
{
    use ResponseTrait;

    public function __construct(
        public Model $model
    ){}

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

        $resource = new Resource($this->model);
        return $this->responseData($resource->colecao($this->model));
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
            return $this->responseSuccess([],"Registro {$strLabel} com sucesso");

        } catch (\Exception $e){
            return $this->responseError([], $e->getMessage());
        }
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
       $repository = new Repository();
       return new Resource($repository->search($request));
    }
}
