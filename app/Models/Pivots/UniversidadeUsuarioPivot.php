<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Model;

class UniversidadeUsuarioPivot extends Model
{
    protected $table = "universidades_usuarios_pivots";
    protected $fillable = ['usuario_id', 'universidade_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
