<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest as FormRequest;
use App\Services\TestServices as Service;

class TestController extends Controller
{

    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     * path="/v1/teste?search={search}",
     * operationId="listarTest",
     * tags={"listarTest"},
     * tags={"Teste"},
     * summary="Listagem de Teste",
     * security={{"apiAuth":{}}},
     *    @OA\Parameter(description="Descrição do filtro", allowEmptyValue = true, in="path", name="search", required=false,
     *       @OA\Schema(type="string", example="")),
     *  @OA\Response(response=201, description="Listagem realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Listagem realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function index(FormRequest $request)
    {
        return $this->service->search($request);
    }



    /**
     * @OA\Get(
     * path="/v1/teste/{teste}",
     * operationId="buscarTeste",
     * tags={"buscarTeste"},
     * tags={"Teste"},
     * summary="Buscar Teste",
     * security={{"apiAuth":{}}},
     *    @OA\Parameter(description="Id do ator", in="path", name="ator",@OA\Schema(format="int64", type="integer", example="1")),
     *  @OA\Response(response=201, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function show($teste)
    {
        return $this->service->show($teste);
    }


    /**
     * @OA\Post(
     * path="/v1/teste",
     * operationId="registerTeste",
     * tags={"registerTeste"},
     * tags={"Teste"},
     * summary="Registrar Teste",
     * security={{"apiAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"nm_ator"},
     *              @OA\Property(property="nm_ator", type="string", example="Ator")
     *          )
     *      ),
     *  @OA\Response(response=201, description="Ator cadastrado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Ator cadastrado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function store(FormRequest $request)
    {
        return $this->service->save($request);
    }

    /**
     * @OA\Put(
     * path="/v1/teste/{teste}",
     * operationId="updateTeste",
     * tags={"updateTeste"},
     * tags={"Teste"},
     * summary="Atualizar Teste",
     * security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         description="ID do teste",
     *         in="path",
     *         name="name",
     *         required=true,
     *         @OA\Schema(
     *             format="int64",
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"nm_ator"},
     *              @OA\Property(property="nm_ator", type="string", example="Ator")
     *          )
     *      ),
     *  @OA\Response(response=201, description="Ator excluido com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Ator excluido com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function update(FormRequest $request, $teste)
    {
        return $this->service->save($request, $teste);
    }


    /**
     * @OA\Delete(
     * path="/v1/teste/{teste}",
     * operationId="excluirTeste",
     * tags={"excluirTeste"},
     * tags={"Teste"},
     * summary="Excluir Teste",
     * security={{"apiAuth":{}}},
     *     @OA\Parameter(
     *         description="ID do teste",
     *         in="path",
     *         name="ator",
     *         required=true,
     *         @OA\Schema(
     *             format="int64",
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *  @OA\Response(response=201, description="Ator excluido com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Ator excluido com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function destroy($ator)
    {
        return $this->service->delete($ator);
    }

}
