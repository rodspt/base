<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    /**
     * @OA\Post(
     * path="/reset-password",
     * operationId="reset-password",
     * tags={"reset-password"},
     * tags={"Login"},
     * summary="Resetar senha",
     *  @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"token","email","password","password_confirmation"},
     *           @OA\Property(property="token", type="string"),
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
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'cpf' => ['required', 'cpf'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('cpf','email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json([
            'message' => __($status)
        ]);
    }
}
