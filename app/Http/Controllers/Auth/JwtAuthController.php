<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class JwtAuthController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

     /**
     * @OA\Post(
     * path="/register",
     * operationId="register",
     * tags={"register"},
     * tags={"Login"},
     * summary="Criar usuário",
     *  @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"cpf","name","email","password","password_confirmation"},
	 *           @OA\Property(property="cpf", type="string", example="00000000019"),
	 *           @OA\Property(property="name", type="string", example="teste"),
     *           @OA\Property(property="email", type="string", example="teste@mail.com"),
     *           @OA\Property(property="password", type="string", example="teste123456"),
     *           @OA\Property(property="password_confirmation", type="string", example="teste123456")
     *        )
     *    ),
     *  @OA\Response(response=201, description="Login criado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Login criado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function register(CreateUserRequest $request): JsonResponse
    {

        try {
            $user = new User();
            $data = $request->validated();
            $data['passwrod'] = Hash::make($request->password);
            $user->fill($request->validated());
            $user->save();
            event(new Registered($user));

            return $this->responseSuccess([],'Usuário criado com sucesso');
        } catch (\Exception $e){
            return $this->responseError([], $e->getMessage(), JsonResponse::HTTP_BAD_REQUEST);
        }

    }

     /**
     * @OA\Post(
     * path="/login",
     * operationId="login",
     * tags={"login"},
     * tags={"Login"},
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
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'cpf' => ['required', 'string'],
            'password' => ['required', 'string']
        ]);

        $credentials = $request->only(['cpf', 'password']);

        $token = Auth::attempt($credentials);


        if (!$token) {
            return response()->json([
                'message' => 'O usuário não existe ou a credencial é inválida'
            ], 401);
        }

        $data = User::find($request->cpf);
        return response()->json([
            'user' => new UserResource($data),
            'access_token' => $token,
        ]);
    }

     /**
     * @OA\Post(
     * path="/logout",
     * operationId="logout",
     * tags={"logout"},
     * tags={"Login"},
     * summary="Realiza Logout",
     *  @OA\Response(response=201, description="Logout realizado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Logout realizado com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function logout(): Response
    {
        Auth::logout();

        return response()->noContent();
    }

    /**
     * @OA\Post(
     * path="/refresh",
     * operationId="refresh",
     * tags={"refresh"},
     * tags={"Login"},
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
        $token = Auth::refresh();

        return response()->json([
            'access_token' => $token,
        ]);
    }
}
