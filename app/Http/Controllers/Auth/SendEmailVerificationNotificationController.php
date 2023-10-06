<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SendEmailVerificationNotificationController extends Controller
{
    /**
     * @OA\Post (
     * path="/email/verification-notification",
     * operationId="verification-notification",
     * tags={"verification-notification"},
     * tags={"Login"},
     * summary="Reenviar e-mail de verificação",
     * security={{"apiAuth":{}}},
     *  @OA\Response(response=201, description="Reenviado email de notificação com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Reenviado email de notificação com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function __invoke(): JsonResponse
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'email-already-verified',
            ]);
        }

        Auth::user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'verification-link-sent',
        ]);
    }
}
