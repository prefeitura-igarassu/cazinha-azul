<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ficha extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table   = 'fichas';     // nome da tabela
    protected $guarded = [];           // permite 'mass assignment'
    protected $casts   = [
        "cid" => "array"
    ];

    public function unidade(){ return $this->belongsTo( \App\Models\Unidade::class , 'unidade_id' ); }
    public function tecnico(){ return $this->belongsTo( \App\Models\Usuario::class , 'tecnico_id' ); }
}
