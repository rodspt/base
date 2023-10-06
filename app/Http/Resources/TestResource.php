<?php

namespace App\Http\Resources;

use App\Models\Teste;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TestResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $this->collection->transform(function(Teste $teste) {
            return [
                'id'      =>  $teste->id,
                'name'      =>  $teste->name,
                'description' =>  $teste->description,
            ];
        });
        return [
            'data' => $this->collection,
            'total' => $this->resource->count()
        ];
    }
}
