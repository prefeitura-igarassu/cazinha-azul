<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devolutiva extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table   = 'devolutivas';    // nome da tabela
    protected $guarded = [];               // permite 'mass assignment'
    
    public function unidade  (){ return $this->belongsTo( \App\Models\Unidade::class   , 'unidade_id' 	); }
    public function servico  (){ return $this->belongsTo( \App\Models\Servico::class   , 'servico_id' 	); }
    public function terapeuta(){ return $this->belongsTo( \App\Models\Terapeuta::class , 'terapeuta_id' ); }
    public function ficha    (){ return $this->belongsTo( \App\Models\Ficha::class     , 'ficha_id'	    ); }
    public function usuario  (){ return $this->belongsTo( \App\Models\Usuario::class   , 'usuario_id'	); }
}
