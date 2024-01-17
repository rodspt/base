<?php

namespace App\Http\Controllers;

use App\DTO\User\CreateUserDTO;
use App\Http\Requests\User\{CreateUserRequest, GerenciarUserRequest, UpdateUserRequest};
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\{Auth};
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {}

    /**
     * Show the currently authenticated user.
     */
    public function show(): UserResource
    {
        return new UserResource(Auth::user());
    }


    /**
     * @OA\Post(
     * path="/solicitar",
     * operationId="solicitar",
     * tags={"solicitar"},
     * tags={"Usuário"},
     * summary="Solicitar acesso",
     *  @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"cpf","name","email","perfil_id","password","password_confirmation"},
     *           @OA\Property(property="cpf", type="string", example="00000000191"),
     *           @OA\Property(property="name", type="string", example="teste"),
     *           @OA\Property(property="email", type="string", example="teste@mail.com"),
     *           @OA\Property(property="perfil_id", type="integer", example=1),
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
    public function register(CreateUserRequest $request): UserResource
    {
        $user = $this->userService->createNew(new CreateUserDTO(... $request->validated()), false);
        if($user){
           event(new Registered($user));
        }
        return new UserResource($user);
    }

    /**
     * @OA\Post(
     * path="/users",
     * operationId="createUser",
     * tags={"Usuário"},
     * security={{"apiAuth":{}}},
     * summary="Criar usuário",
     *  @OA\RequestBody(
     *       required=true,
     *       @OA\JsonContent(
     *           required={"cpf","name","email","perfil_id","password","password_confirmation"},
     *           @OA\Property(property="cpf", type="string", example="00000000191"),
     *           @OA\Property(property="name", type="string", example="teste"),
     *           @OA\Property(property="email", type="string", example="teste@mail.com"),
     *           @OA\Property(property="perfil_id", type="integer", example=1),
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
    public function store(CreateUserRequest $request): UserResource
    {
        $user = $this->userService->createNew(new CreateUserDTO(... $request->validated()), true);
        if($user){
            event(new Registered($user));
        }
        return new UserResource($user);
    }


    /**
     * @OA\Get(
     * path="/approve/{cpf}",
     * operationId="aprovarUsuario",
     * tags={"Usuário"},
     * summary="Aprovar usuário",
     * security={{"apiAuth":{}}},
     *    @OA\Parameter(description="Cpf do usuário", in="path", name="cpf",@OA\Schema(format="int64", type="string", example="00000000191")),
     *  @OA\Response(response=201, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function approve(Request $request)
    {
        $gerenciarUser = new GerenciarUserRequest(['cpf'=> $request->cpf]);
        $gerenciarUser->validate(['cpf' => 'cpf']);

        if(!$user = $this->userService->findById($request->cpf)){
            return response()->json(['message' => 'route not found'],Response::HTTP_NOT_FOUND);
        }
        $user = $this->userService->gerenciarUser($user, 'A');
        return new UserResource($user);
    }

    /**
     * @OA\Get(
     * path="/block/{cpf}",
     * operationId="blockUsuario",
     * tags={"Usuário"},
     * summary="Bloquear usuário",
     * security={{"apiAuth":{}}},
     *    @OA\Parameter(description="Cpf do usuário", in="path", name="cpf",@OA\Schema(format="int64", type="string", example="00000000191")),
     *  @OA\Response(response=201, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=200, description="Busca realizada com sucesso", @OA\JsonContent()),
     *  @OA\Response(response=422, description="O servidor não entende o tipo de conteúdo da entidade de solicitação", @OA\JsonContent()),
     *  @OA\Response(response=400, description="Ocoreu um erro"),
     *  @OA\Response(response=404, description="Página não localizada"),
     * )
     */
    public function block(Request $request)
    {
        $gerenciarUser = new GerenciarUserRequest(['cpf'=> $request->cpf]);
        $gerenciarUser->validate(['cpf' => 'cpf']);

        if(!$user = $this->userService->findById($request->cpf)){
            return response()->json(['message' => 'route not found'],Response::HTTP_NOT_FOUND);
        }
        $user = $this->userService->gerenciarUser($user, 'B');
        return new UserResource($user);
    }

    /**
     * Update the currently authenticated user.
     */
    public function update(UpdateUserRequest $request): UserResource
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
            $request->user()->sendEmailVerificationNotification();
        }

        $request->user()->save();

        return new UserResource(Auth::user()->fresh());
    }


}
