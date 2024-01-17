<?php

namespace App\Repositories;

use App\Models\User;
use App\DTO\PaginationDTO;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function __construct(public User $user)
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
