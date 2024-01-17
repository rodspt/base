<?php

namespace App\Http\Controllers;

use App\DTO\PaginationDTO;
use App\Http\Requests\Perfil\StorePerfilRequest;
use App\Http\Resources\PermissionResource;
use App\DTO\Perfil\{ CreatePerfilDTO, EditPerfilDTO };
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\PerfilResource;
use App\Services\PerfilService;
use Illuminate\Http\Response;

class PerfilController extends Controller
{

    public function __construct(private PerfilService $perfilService)
    {}


    /**
     * @OA\Get(
     * path="/perfis?totalPerPage={totalPerPage}&page={page}&filter={filter}",
     * operationId="listPerfis",
     * tags={"Permissão"},
     * summary="Listagem de perfis",
     * security={{"apiAuth":{}}},
     *    @OA\Parameter(description="Total de items por página", allowEmptyValue=true, in="path", name="totalPerPage", required=false,
     *       @OA\Schema(type="integer", example=15)),
     *    @OA\Parameter(description="Número da página", allowEmptyValue=true, in="path", name="page", required=false,
     *        @OA\Schema(type="integer", example=1)),
     *    @OA\Parameter(description="Descrição do filtro", allowEmptyValue=true, in="path", name="filter", required=false,
     *         @OA\Schema(type="string", example="")),
     *  @OA\Response(response=201, description="Listagem realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Listagem realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function index(PaginationRequest $request)
    {
        $route = $this->perfilService->getPaginatePerfil(new PaginationDTO(...$request->validated()));
       return PerfilResource::collection($route);
    }

    /**
     * @OA\Get(
     * path="/perfis/{perfi}",
     * operationId="buscarPerfil",
     * tags={"Permissão"},
     * summary="Buscar Perfil",
     * security={{"apiAuth":{}}},
     *    @OA\Parameter(description="Id da rota", in="path", name="perfi",@OA\Schema(format="int64", type="integer", example="2")),
     *  @OA\Response(response=201, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function show(string $id)
    {
        if(!$perfil = $this->perfilService->findById($id)){
            return response()->json(['message' => 'perfil not found'],Response::HTTP_NOT_FOUND);
        }
        return new PerfilResource($perfil);
    }

    /**
     * @OA\Post(
     * path="/perfis",
     * operationId="storePerfil",
     * tags={"Permissão"},
     * summary="Criar perfil",
     * security={{"apiAuth":{}}},
     *  @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"name","description"},
     *           @OA\Property(property="name", type="string", example="Nome do Perfil"),
     *           @OA\Property(property="description", type="string", example="Descrição do Perfil")
     *        )
     *    ),
     *  @OA\Response(response=201, description="Perfil criado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Perfil criado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function store(StorePerfilRequest $request)
    {
        $route = $this->perfilService->createNew(new CreatePerfilDTO(... $request->validated()));
        return new PerfilResource($route);
    }

    /**
     * @OA\Put(
     * path="/perfis/{perfi}",
     * operationId="updatePerfil",
     * tags={"Permissão"},
     * summary="Atualizar Perfil",
     * security={{"apiAuth":{}}},
     *     @OA\Parameter(description="ID do perfil", in="path", name="perfi", required=true,
     *         @OA\Schema(format="int64", type="integer", example=2)
     *     ),
     *     @OA\RequestBody(required=true,
     *          @OA\JsonContent(
     *              required={"nome"},
     *              @OA\Property(property="nome", type="string", example="Acao"),
     *              @OA\Property(property="atividades",type="array",
     *                  @OA\Items(
     *                     @OA\Property(property="id", type="integer")
     *                ))
     *          )
     *      ),
     *  @OA\Response(response=201, description="Ação excluida com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Ação excluida com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function update(StorePerfilRequest $request, $perfi)
    {
        return $this->repository->update($request, $acao);
    }

}
