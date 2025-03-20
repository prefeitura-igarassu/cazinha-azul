<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FichaServico extends Model
{
    const SOLICITADO = 0;
    const AGENDADO   = 1;
    const ALTA       = 2;

    // ----- //

    use HasFactory, SoftDeletes;

    protected $table   = 'fichas_servicos';   // nome da tabela
    protected $guarded = [];                  // permite 'mass assignment'



    /*
    protected function posicao(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value->status == 0 ? DB::select( 
                    "SELECT COUNT( * ) as posicao FROM fichas_servicos WHERE status = 0 AND servico_id = ? AND solicitado_em <= ? ORDER BY solicitado_em",
                    [ $value->servico_id , $value->solicitado_em ]
                ) : 0,
        );
    }
    */



    public function ficha    (){ return $this->belongsTo( \App\Models\Ficha::class     , 'ficha_id'     ); }
    public function servico  (){ return $this->belongsTo( \App\Models\Servico::class   , 'servico_id'   ); }
    public function terapeuta(){ return $this->belongsTo( \App\Models\Terapeuta::class , 'terapeuta_id' ); }
    public function usuario  (){ return $this->belongsTo( \App\Models\Usuario::class   , 'usuario_id'   ); }
}
