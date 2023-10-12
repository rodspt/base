<?php

namespace App\Repositories;

use App\Models\Teste as Model;
use Illuminate\Support\Facades\Cache;

class TestRepository
{
    public function search($request)
    {
        $page = $request->get('page',  1);
        $perPage = $request->get('perPage', config('app.per_page'));
        $search = $request->get('search');
        $search2 = $search ? "_".strip_tags($search) : "";
        $name = 'redis_test_search_'.$page.'_'.$perPage.$search2;

        return Cache::remember($name, 60, function () use($perPage, $page, $search) {
           $data = Model::query();

           if(!is_null($search)){
               $data->where('name', 'ilike', "%" . trim($search) . "%");
           }

           return $data->paginate( $perPage );
        });
    }

}
