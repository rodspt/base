<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'routes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
    ];

    public $with = ['perfis'];

    public function perfis() : BelongsToMany
    {
        return $this->belongsToMany(Perfil::class);
    }

}
