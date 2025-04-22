<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FichaArquivo extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table   = 'fichas_arquivos';     // nome da tabela
    protected $guarded = [];                    // permite 'mass assignment'

    public function ficha  (){ return $this->belongsTo( \App\Models\Ficha::class   , 'ficha_id'   ); }
    public function usuario(){ return $this->belongsTo( \App\Models\Usuario::class , 'usuario_id' ); }
}
