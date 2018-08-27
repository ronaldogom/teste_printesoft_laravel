<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Universidade extends Model
{
    protected $table = "universidades";
    protected $fillable = ['nome', 'endereco', 'cidade', 'bairro', 'estado'];
    protected $hidden = ['created_at', 'updated_at'];
}
