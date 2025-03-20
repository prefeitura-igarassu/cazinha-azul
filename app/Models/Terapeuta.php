<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Terapeuta extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table   = 'terapeutas';     // nome da tabela
    protected $guarded = [];               // permite 'mass assignment'
    protected $casts = [
        "horarios" => "array"
    ];

    // retornar o nome do terapeuta

    public function usuario(){ return $this->belongsTo( \App\Models\Usuario::class , 'usuario_id' ); }    
    public function unidade(){ return $this->belongsTo( \App\Models\Unidade::class , 'unidade_id' ); }
    public function servico(){ return $this->belongsTo( \App\Models\Servico::class , 'servico_id' ); }
}
