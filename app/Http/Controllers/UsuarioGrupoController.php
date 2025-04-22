<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuarioGrupo;

class UsuarioGrupoController extends BasicController
{
    public $modulo = "usuarios_grupos";
    
    protected function boot()
    {
        $this->setModel( UsuarioGrupo::class );

        $this->addAction([
            "insert"  => "Adicionar um grupo.",
            "update"  => "Atualizar um grupo.",
            "get"     => "Retornar um grupo.",
            "destroy" => "Excluir um grupo.",
            "search"  => "Pesquisar pelos grupos.",
        ]);
    }

    public function getSearchWhere()
    {
        return [
            "global" => function ( $query , $key , $value ){
                $value = is_array( $value ) ? array_values( $value )[0] : $value;

                return $query->orWhere( 'nome'      , "like" , "%$value%" )
                             ->orWhere( 'descricao' , "like" , "%$value%" )
                    ;
            },
        ];
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            "nome"      => [ 'required' , 'string'  , 'max:200' ] ,
            "descricao" => [ 'nullable' , 'string'  , 'max:200' ] ,
        ];
    }
}
