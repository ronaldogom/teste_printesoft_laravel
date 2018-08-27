<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    protected $table = "permissoes";
    protected $fillable = ['route'];
    protected $hidden = ['created_at', 'updated_at'];
}
