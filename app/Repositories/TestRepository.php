<?php

namespace App\Repositories;

use App\Models\Teste;

class TestRepository
{
    public function search($request)
    {
        $data = Teste::query();

        if(!is_null($request->get('search'))){
            $data->where('name', 'ilike', "%" . trim($request->get('search')) . "%");
        }

        $perPage = $request->get('perPage',  5);

        $data->orderBy('name');
        $data = $data->paginate( $perPage );

        return $data;
    }
}
