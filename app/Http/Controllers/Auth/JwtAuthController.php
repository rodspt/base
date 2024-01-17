<?php

namespace App\Http\Controllers\Auth;

use App\DTO\Auth\AuthDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Services\AuthService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class JwtAuthController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    public function __construct(private AuthService $authService)
    {}

     /**
     * @OA\Post(
     * path="/login",
     * operationId="login",
     * tags={"Autenticação"},
     * summary="Realizar autenticação",
     *  @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"cpf","password"},
     *           @OA\Property(property="cpf", type="string", example="00000000191"),
     *           @OA\Property(property="password", type="string", example="teste123456")
     *        )
     *    ),
     *  @OA\Response(response=201, description="Login realizado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Login realizado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function login(AuthRequest $request): JsonResponse
    {
       $retorno = $this->authService->auth(new AuthDTO(... $request->validated()));
       return $this->responseData($retorno['message']?? $retorno, $retorno['code'] ?? 200);
    }

     /**
     * @OA\Post(
     * path="/logout",
     * operationId="logout",
     * tags={"Autenticação"},
     * security={{"apiAuth":{}}},
     * summary="Realiza Logout",
     *  @OA\Response(response=201, description="Logout realizado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Logout realizado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function logout(): JsonResponse
    {
        if(auth()->user()){
            auth()->logout();
            return response()->json(['message' => 'Logout realizado'],200);
        }
        return response()->json(['message' => 'Você não está logado'],401);
    }

    /**
     * @OA\Post(
     * path="/refresh",
     * operationId="refresh",
     * tags={"Autenticação"},
     * summary="Realiza RefreshToken",
     * security={{"apiAuth":{}}},
     *  @OA\Response(response=201, description="RefreshToken realizado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="RefreshToken realizado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function refresh(): JsonResponse
    {
        $token = auth()->refresh();

        return response()->json([
            'access_token' => $token,
        ]);
    }
}
