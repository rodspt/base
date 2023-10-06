<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class SendPasswordResetLinkController extends Controller
{
    /**
     * @OA\Post(
     * path="/forgot-password",
     * operationId="forgot-password",
     * tags={"forgot-password"},
     * tags={"Login"},
     * summary="Recuperar senha",
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
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json([
            'message' => __($status)
        ]);
    }
}
