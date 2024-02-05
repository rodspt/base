<?php

namespace App\Models;

use App\Enum\PerfilEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Por padrao a chave primaria e incremental de inteiro
     */
    protected $primaryKey = 'cpf';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cpf',
        'nome',
        'email',
        'password',
        'perfil_id',
        'cpf_aprovacao',
        'cpf_bloqueio',
        'aprovacao_at'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'aprovacao_at' => 'datetime',
        'password' => 'hashed',
    ];


    /**
     * Get the JWT identifier.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Get the JWT custom claims.
     */
    public function getJWTCustomClaims(): array
    {
        return ['cpf' => $this->cpf, 'perfil' => $this->perfil_id];
    }

    public function perfil() : BelongsTo
    {
        return $this->belongsTo(Perfil::class);
    }

    public function isSuperAdmin(): bool
    {
        return in_array($this->email,config('acl.super_admins'));
    }

    public function hasPermissions(User $user, string $permissionName): bool
    {
        if($user->perfil_id === PerfilEnum::DEV()){
            return true;
        }
        return $user->perfil()->permissions()->where('name', $permissionName)->exists();
    }
}
