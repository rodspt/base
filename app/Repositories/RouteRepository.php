<?php

namespace App\Repositories;

use App\Models\Route;
use App\DTO\PaginationDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class RouteRepository
{
    public function __construct(public Route $route)
    {}

    public function getPaginatePermissions(PaginationDTO $dto): LengthAwarePaginator
    {
        $filter = $dto->filter;
        return $this->route->where(function ($query) use ($filter) {
            if($filter !== "" && $filter !== '{filter}'){
                $query->where('name', 'LIKE', "%{$filter}%");
            }
        })->paginate($dto->totalPerPage, ['*'], 'page', $dto->page);
    }

}
