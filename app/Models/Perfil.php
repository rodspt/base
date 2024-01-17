<?php

namespace App\Models;

use DB;
use App\GeneralRegister;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perfil extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'perfis';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'hide',
    ];


    public function routes(): BelongsToMany
    {
        return $this->belongsToMany(Route::class);
    }
}
