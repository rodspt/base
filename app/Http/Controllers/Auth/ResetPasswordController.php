<?php

namespace App\Http\Controllers\Auth;

use App\DTO\Auth\AuthResetDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthResetRequest;
use App\Services\AuthService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ResetPasswordController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    public function __construct(private AuthService $authService)
    {}

    /**
     * @OA\Post(
     * path="/reset-password",
     * operationId="reset-password",
     * tags={"Autenticação"},
     * security={{"apiAuth":{}}},
     * summary="Resetar senha - É necessário o token enviado por e-mail",
     *  @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"cpf","token","email","password","password_confirmation"},
     *           @OA\Property(property="cpf", type="string", example="00000000191"),
     *           @OA\Property(property="token", type="string", example="token-email"),
     *           @OA\Property(property="email", type="string", example="teste@mail.com"),
     *           @OA\Property(property="password", type="string", example="teste123456"),
     *           @OA\Property(property="password_confirmation", type="string", example="teste123456"),
     *        )
     *    ),
     *  @OA\Response(response=201, description="Login recuperado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Login recuperado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function __invoke(AuthResetRequest $request): JsonResponse
    {
        $msgRetorno = $this->authService->reset(new AuthResetDTO(... $request->validated()));
        return response()->json(['message' => __($msgRetorno)]);
    }
}
