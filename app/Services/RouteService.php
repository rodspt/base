<?php

namespace App\Services;

use App\DTO\PaginationDTO;
use App\DTO\Route\CreateRouteDTO;
use App\DTO\Route\EditRouteDTO;
use App\Enum\PerfilEnum;
use App\Enum\TempoEnum;
use App\Models\Perfil;
use App\Models\Route;
use App\Models\User;
use App\Repositories\RouteRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RouteService
{
    public function __construct(protected RouteRepository $routeRepository)
    {}

    public function getPaginatePermissions(PaginationDTO $dto) :LengthAwarePaginator
    {
        $dto->setDefault();
        return $this->routeRepository->getPaginatePermissions($dto);
    }
    public function findById(string $id): ?Route
    {
        return $this->routeRepository->route->find($id);
    }

    public function createNew(CreateRouteDTO $dto): Route
    {
        $data = (array) $dto;
        return $this->routeRepository->route->create($data);
    }

    public function syncPermissions(int $id, array $array): ?Collection
    {
        $perfil = new Perfil();
        if(!$perfil = $perfil->find($id)){
            return null;
        }
        $perfil->routes()->sync($array);
        return $perfil->routes()->get();
    }


    public function update(EditRouteDTO $dto): bool
    {
        if(!$route = $this->findById($dto->id)){
            return false;
        }

        return $route->update((array) $dto);
    }


    public function delete(string $id): bool
    {
        if(!$route = $this->findById($id)){
            return false;
        }
        return $route->delete();
    }


    public function hasPermissions(User $user, string $routeName = ''): bool
    {
        if($user->perfil_id === PerfilEnum::DEV() || $routeName === ''){
            return true;
        }

        $route = new Route();
        $exists = false;

        $arRoutes = Cache::remember('permission_route_'.$routeName, TempoEnum::MINUTO_DEZ,
            function() use ($route, $routeName){
                $routes = $route->load('perfis')->where('name', $routeName)->get()->first();
                return $routes ? $routes->toArray() : [];
        });

        if(isset($arRoutes['perfis']))
        {
          foreach($arRoutes['perfis'] as $perfis):
            if($perfis['id'] === $user->perfil_id){
                $exists = true;
            }
          endforeach;
          return $exists;
        }

        return true;
    }

}
