<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sessao extends Model
{
    use HasFactory, SoftDeletes;
    
    const AGENDADO  = 0;
    const ATENTIDO  = 1;
    const FALTOU    = 2;
    const CANCELADO = 3;

    protected $table   = 'sessoes';       // nome da tabela
    protected $guarded = [];               // permite 'mass assignment'

    public function ficha    (){ return $this->belongsTo( \App\Models\Ficha::class     , 'ficha_id'     ); }    
    public function terapeuta(){ return $this->belongsTo( \App\Models\Terapeuta::class , 'terapeuta_id' ); }
    public function unidade  (){ return $this->belongsTo( \App\Models\Unidade::class   , 'unidade_id'   ); }
    public function servico  (){ return $this->belongsTo( \App\Models\Servico::class   , 'servico_id'   ); }
}
