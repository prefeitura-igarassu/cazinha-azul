<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class UsuarioPermissao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "usuarios_permissoes";
    protected $guarded = [];

    protected $casts = [
        'acoes' => 'array'
    ];

    public static function getPermissao( $usuarioId , $grupoId , $modulo , $acao ){
        return UsuarioPermissao::where( function ( $query ) use ( $usuarioId , $grupoId ){
            $query->orWhere( "usuario_id" , $usuarioId );
            $query->orWhere( "grupo_id"   , $grupoId   );
        })
        ->where( "modulo"       , $modulo )
        ->where( "acoes->$acao" , true    )
        ->where( function ( $query ){
            $query->whereNull( "expirar_em" );
            $query->orWhere( "expirar_em" , "<=" , date('Y-m-d H:i:s') );
        })->get();
    }

}
