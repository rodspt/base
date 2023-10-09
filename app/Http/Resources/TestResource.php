<?php

namespace App\Http\Resources;

use App\Models\Teste as Model;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestResource extends ResourceCollection
{
    public function toArray($request)
    {
        $this->collection->transform(function(Model $model) {
            return $this->colecao($model);
        });
        return [
            'data' => $this->collection,
            'total' => $this->resource->count()
        ];
    }

    public function colecao(Model $model){
        return [
            'id'        =>  $model->id,
            'nome'      =>  $model->name,
            'descricao' =>  $model->description,
        ];
    }
}
