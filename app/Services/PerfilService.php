<?php

namespace App\Services;

use App\DTO\PaginationDTO;
use App\Enum\PerfilEnum;
use App\DTO\Perfil\{CreatePerfilDTO, EditPerfilDTO};
use App\Models\Perfil;
use App\Repositories\PerfilRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class PerfilService
{
    public function __construct(protected PerfilRepository $perfilRepository)
    {}

    public function getPaginatePerfil(PaginationDTO $dto) :LengthAwarePaginator
    {
        return $this->perfilRepository->getPaginatePerfil($dto);
    }
    public function findById(int $id): ?Perfil
    {
        if($id === PerfilEnum::DEV()){
            return null;
        }
        return $this->perfilRepository->perfil->find($id);
    }



    public function createNew(CreatePerfilDTO $dto): Perfil
    {
        $data = (array) $dto;
        return $this->perfilRepository->perfil->create($data);
    }


    public function update(EditPerfilDTO $dto): bool
    {
        if(!$perfil = $this->findById($dto->id)){
            return false;
        }

        return $perfil->update((array) $dto);
    }


    public function delete(int $id): bool
    {
        if(!$perfil = $this->findById($id)){
            return false;
        }
        return $perfil->delete();
    }

}
