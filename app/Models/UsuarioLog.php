<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsuarioLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "usuarios_logs";
    protected $guarded = [];

    protected $casts = [
        'parametros' => 'array'
    ];

    public function usuario(){
        return $this->belongsTo( \App\Models\Usuario::class , 'usuario_id' );
    }
}
