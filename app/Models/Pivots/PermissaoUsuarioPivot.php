<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Model;

class PermissaoUsuarioPivot extends Model
{
    protected $table = "permissoes_usuarios_pivots";
    protected $fillable = ['usuario_id', 'permissao_id'];
    protected $hidden = ['created_at', 'updated_at'];
}
