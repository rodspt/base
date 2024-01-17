<?php

namespace App\Repositories;

use App\Models\Perfil;
use App\DTO\PaginationDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class PerfilRepository
{
    public function __construct(public Perfil $perfil)
    {}

    public function getPaginatePerfil(PaginationDTO $dto): LengthAwarePaginator
    {
        $filter = $dto->filter;
        return $this->perfil->where(function ($query) use ($filter) {
            $query->where('id', '!=', 1);
            if($filter !== "" && $filter !== '{filter}'){
                $query->where('name', 'LIKE', "%{$filter}%");
            }
        })->paginate($dto->totalPerPage, ['*'], 'page', $dto->page);
    }

}
