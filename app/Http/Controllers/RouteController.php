<?php

namespace App\Http\Controllers;

use App\DTO\PaginationDTO;
use App\DTO\Route\CreatePerfilDTO;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\Route\StorePermissionRequest;
use App\Http\Requests\Route\StorePerfilRequest;
use App\Http\Resources\PerfilResource;
use App\Http\Resources\RouteResource;
use App\Services\RouteService;
use Illuminate\Http\Response;

class RouteController extends Controller
{

    public function __construct(private RouteService $routeService)
    {}


    /**
     * @OA\Get(
     * path="/routes?totalPerPage={totalPerPage}&page={page}&filter={filter}",
     * operationId="listRotas",
     * tags={"Permissão"},
     * summary="Listagem de rotas",
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
        $route = $this->routeService->getPaginatePermissions(new PaginationDTO(...$request->validated()));
       return RouteResource::collection($route);
    }

    /**
     * @OA\Get(
     * path="/routes/{route}",
     * operationId="buscarRota",
     * tags={"Permissão"},
     * summary="Buscar Rota",
     * security={{"apiAuth":{}}},
     *    @OA\Parameter(description="Id da rota", in="path", name="route",@OA\Schema(format="int64", type="integer", example="1")),
     *  @OA\Response(response=201, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function show(string $id)
    {

        if(!$route = $this->routeService->findById($id)){
            return response()->json(['message' => 'route not found'],Response::HTTP_NOT_FOUND);
        }
        return new RouteResource($route);
    }

    /**
     * @OA\Post(
     * path="/routes",
     * operationId="storeRoute",
     * tags={"Permissão"},
     * summary="Criar rota",
     * security={{"apiAuth":{}}},
     *  @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"name","description"},
     *           @OA\Property(property="name", type="string", example="test.store"),
     *           @OA\Property(property="description", type="string", example="Registrar rota")
     *        )
     *    ),
     *  @OA\Response(response=201, description="Rota criado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Rota criado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function store(StorePerfilRequest $request)
    {
        $route = $this->routeService->createNew(new CreatePerfilDTO(... $request->validated()));
        return new RouteResource($route);
    }


    /**
     * @OA\Post(
     * path="/permissions/{perfil}",
     * operationId="storePermission",
     * tags={"Permissão"},
     * summary="Atribuir perfis a rota",
     * security={{"apiAuth":{}}},
     *  @OA\Parameter(
     *    description="ID da rota", in="path", name="perfil", required=true,
     *      @OA\Schema( format="int64", type="integer", example=1)
     *  ),
     *  @OA\RequestBody(
     *    @OA\JsonContent(required=true, required={"routes"},
     *       @OA\Property(property="routes",type="array",@OA\Items(type="integer"))
     *      )
     *   ),
     *  @OA\Response(response=201, description="Rota criado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Rota criado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function permissions(StorePermissionRequest $request, int $perfil)
    {
        $response = $this->routeService->syncPermissions($perfil, $request->routes);
        if(!$response){
            return response()->json(['message' => 'route not found'], Response::HTTP_NOT_FOUND);
        }
        return PerfilResource::collection($response);
    }
}
