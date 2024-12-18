<?php

namespace App\Services;

use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Cache;

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

    public function notFind()
    {
        return $this->responseError(null, 'Registro não encontrado');
    }

    public function find($id)
    {

        if(!is_null($id) && is_numeric($id))
        {
            $nameCache = class_basename($this->model)."_".$id;
            $obj =  Cache::remember($nameCache, 30, function () use($id) {
                return $this->model->find($id);
            });

            if(is_null($obj)){
                return false;
            }else{
                $this->model = $obj;
            }
        }else{
            return false;
        }
    }

    public function clearCache($classe, $id = null)
    {
        $classe = class_basename($classe);
       
        if(!is_null($id)){
            $nameCache = $classe ."_".$id;
            Cache::forget($nameCache);
        }else{
            $redis = Cache::connection();
            $cacheKeys = $redis->keys($classe.'_search*');
            if($cacheKeys){
              foreach ($cacheKeys as $key):
                  Cache::forget($key);
              endforeach;
           }
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
            $this->clearCache($this->model, $id);
            $this->clearCache($this->model, null);

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
