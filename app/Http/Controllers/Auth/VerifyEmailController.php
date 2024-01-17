<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthVerifyEmailRequest;
use App\Services\AuthService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{

    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    public function __construct(private AuthService $authService)
    {}

    /**
     * @OA\Get(
     * path="/verify-email/{id}/{hash}",
     * operationId="verify-email",
     * tags={"Autenticação"},
     * summary="Confirmação de Verificação de E-mail",
     * security={{"apiAuth":{}}},
     *    @OA\Parameter(description="id",  in="path", name="id", required=true, @OA\Schema(type="integer", example="00000000191")),
     *    @OA\Parameter(description="hash",  in="path", name="hash", required=true, @OA\Schema(type="string", example="")),
     *  @OA\Response(response=201, description="Checagem de e-mail realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Checagem de e-mail realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function __invoke(AuthVerifyEmailRequest $request): JsonResponse
    {
        $msgRetorno = $this->authService->verifyEmail($request);
        return $this->responseData($msgRetorno);
    }
}
