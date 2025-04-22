<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuarioLog;

class UsuarioLogController extends BasicController
{
    public $modulo = "usuarios_logs";
    
    protected function boot()
    {
        $this->setModel( UsuarioLog::class );

        $this->addAction([
            "search"  => "Pesquisar pelos registros do sistema (Log).",
        ]);
    }

    public function getSearchWhere()
    {
        return [
            "global" => function ( $query , $key , $value ){
                $value = is_array( $value ) ? array_values( $value )[0] : $value;

                return $query->orWhere( 'modulo'     , "like" , "%$value%" )
                             ->orWhere( 'descricao'  , "like" , "%$value%" )
                             ->orWhere( 'externo_id' , "like" , "%$value%" )
                             ->orWhere( 'acao'       , "like" , "%$value%" )
                             ->orWhere( 'ip'         , "like" , "%$value%" )
                             ->orWhere( 'parametros' , "like" , "%$value%" )
                    ;
            },

            "created_at" => function ( $query , $key , $value ){
                return $this->search_applyWhereValue( $query , $key , $value , "usuarios_logs" );
            },
        ];
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            "usuario_id"   => [ 'required' , 'integer' ] ,
            "cartorio_id"  => [ 'required' , 'integer' ] ,
            "permissao_id" => [ 'nullable' , 'integer' ] ,
            "externo_id"   => [ 'nullable' , 'integer' ] ,
            "ip"           => [ 'required' , 'string' , 'max:200' ] ,
            "modulo"       => [ 'required' , 'string' , 'max:200' ] ,
            "acao"         => [ 'required' , 'string' , 'max:200' ] ,
            "descricao"    => [ 'required' , 'string' ] ,
            "parametros"   => [ 'nullable' , 'json'   ] ,
        ];
    }

}
