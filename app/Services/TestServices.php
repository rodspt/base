<?php

namespace App\Services;

use App\Http\Requests\TestRequest as FormRequest;
use App\Http\Resources\TestResource as Resource;
use App\Models\Teste as Model;
use App\Repositories\TestRepository as Repository;
use App\Traits\ResponseTrait;

class TestServices
{
    use ResponseTrait;


    public function show($obj)
    {
        $find = Model::find($obj);
        if($find){
            $resource = new Resource($find);
            return $this->responseData($resource->colecao($find));
        }else{
            return $this->responseError(null, 'Teste não localizado');
        }
    }

    public function save(FormRequest $request, $obj = null)
    {
        try {
            $model = is_null($obj)? new Model() : Model::findOrFail($obj);
            $strLabel = is_null($obj)? "cadastrado" : "atualizado";
            $data = $request->validated();
            $model->fill($data);
            $model->save();
            return $this->responseSuccess([],"Teste {$strLabel} com sucesso");
        } catch (\Exception $e){
            return $this->responseError([], $e->getMessage());
        }
    }

    public function delete($obj){
        try {
            $model = Model::findOrFail($obj);
            $model->delete();
            return $this->responseSuccess([],"Teste excluído com sucesso");
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
