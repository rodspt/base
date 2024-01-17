<?php

namespace App\Services;

use App\DTO\Auth\AuthDTO;
use App\DTO\Auth\AuthResetDTO;
use App\Http\Requests\Auth\AuthVerifyEmailRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(protected User $user)
    {}

    public function findById(string $id): ?User
    {
        return $this->user->find($id);
    }

    public function checkStatusUser(User|null $user): array
    {
        if(!$user){
            return array('message' => 'Usuário e/ou senha inválido','code' => 401);
        }

        if (!$user->hasVerifiedEmail()) {
            return array('message' => 'Até o momento não houve confirmação de e-mail, verifique sua caixa de entrada','code' => 401);
        }

        if (!empty($user->cpf_bloqueio)) {
            return array( 'message' => 'Usuário bloqueado pelo administrador','code' => 401);
        }

        if (empty($user->cpf_aprovacao)) {
            return array( 'message' => 'Aguarde a aprovação do administrador','code' => 401);
        }

        return array();
    }

    public function auth(AuthDTO $dto): array
    {

        $user = $this->findById($dto->cpf);
        $errValidacoes = $this->checkStatusUser($user);
        if(!isset($errValidacoes['message']))
        {
            $token = Auth::attempt((array) $dto);
            if (!$token) {
                return array('message' => 'O usuário não existe ou a credencial é inválida','code' => 401);
            }
            return array('user' => new UserResource($user),'access_token' => $token);
        }
        return $errValidacoes;
    }

    public function verifyEmail(AuthVerifyEmailRequest $request): array
    {
        $user = $this->findById($request->id);

        if ($user->hasVerifiedEmail()) {
            return array('message' => 'E-mail ja verificado');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return array('message' => 'E-mail verificado com sucesso');
    }

    public function sendResetEmail(string $email): string
    {
        $status = Password::sendResetLink(['email'=>$email]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
        return $status;
    }

    public function reset(AuthResetDTO $dto): string
    {
        $arDados = (array) $dto;
        $status = Password::reset($arDados,  function ($user) use ($dto) {
                $user->forceFill([
                    'password' => Hash::make($dto->password),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }
        return $status;
    }
}
