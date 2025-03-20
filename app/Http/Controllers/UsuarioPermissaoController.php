<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsuarioPermissao;
use App\Http\Controllers\Traits\Authorization;
use Illuminate\Support\Facades\Log;

class UsuarioPermissaoController extends BasicController
{
    use Authorization;

    public $modulo = "usuarios_permissoes";
    
    protected function boot()
    {
        $this->setModel( UsuarioPermissao::class );
        
        $this->addAction([
            "insert"  => "Adicionar uma permissão.",
            "update"  => "Atualizar uma permissão.",
            "get"     => "Retornar uma permissão.",
            "destroy" => "Excluir uma permissão.",
            "search"  => "Pesquisar pelas permissões.",
        ]);
    }

    public function has( Request $request , $modulo , $acao )
    {
        if( auth()->user() == null ){
            return [];
        }

        return $this->autorizacao( $modulo , $acao );
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            "grupo_id"   => [ 'nullable' , 'numeric' , 'min:1' ],
            "usuario_id" => [ 'nullable' , 'numeric' , 'min:1' ],
            "modulo"     => [ 'required' , 'string' , 'max:200' ],
            "acoes"      => [ 'nullable' , 'array' ],
            "expirar_em" => [ 'nullable' , 'date'  ],
        ];
    }
}
