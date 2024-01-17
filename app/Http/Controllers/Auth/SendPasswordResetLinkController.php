<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthResetEmailRequest;
use App\Services\AuthService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class SendPasswordResetLinkController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    public function __construct(private AuthService $authService)
    {}

    /**
     * @OA\Post(
     * path="/forgot-password",
     * operationId="forgot-password",
     * tags={"Autenticação"},
     * summary="Envia o e-mail de recuperação com o token de recuperação",
     *  @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"email"},
     *           @OA\Property(property="email", type="string", example="teste@mail.com")
     *        )
     *    ),
     *  @OA\Response(response=201, description="Login recuperado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Login recuperado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function __invoke(AuthResetEmailRequest $request): JsonResponse
    {
        $msgRetorno = $this->authService->sendResetEmail($request->validated('email'));
        return response()->json(['message' => __($msgRetorno)]);
    }
}
