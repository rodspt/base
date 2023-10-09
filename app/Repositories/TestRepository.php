<?php

namespace App\Repositories;

use App\Models\Teste as Model;

class TestRepository
{
    public function search($request)
    {
        $data = Model::query();

        if(!is_null($request->get('search'))){
            $data->where('name', 'ilike', "%" . trim($request->get('search')) . "%");
        }

        $perPage = $request->get('perPage',  config('perPage'));
        $data = $data->paginate( $perPage );

        return $data;
    }
}
