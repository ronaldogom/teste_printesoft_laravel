<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = "usuarios";
    protected $fillable = ['nome', 'email', 'senha', 'telefone', 'status'];
    //protected $hidden = ['created_at', 'updated_at'];
}
