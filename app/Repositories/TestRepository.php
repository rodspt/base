<?php

namespace App\Repositories;

use App\Models\Teste as Model;
use App\Traits\SearchTrait;
use Illuminate\Support\Facades\Cache;

class TestRepository
{
    use SearchTrait;

    public function search($request)
    {
        $page = $request->get('page',  1);
        $perPage = $request->get('perPage', config('app.per_page'));
        $search = $this->filtroSearch($request->get('search'));
        $nameCache = $this->cacheSearch(Model::class, $perPage, $page, $search);

        return Cache::remember($nameCache, 60, function () use($perPage, $page, $search) {
           $data = Model::query();

           if(!is_null($search)){
               $data->where('name', 'ilike', "%" . trim($search) . "%");
           }

           return $data->paginate( $perPage );
        });
    }

}
