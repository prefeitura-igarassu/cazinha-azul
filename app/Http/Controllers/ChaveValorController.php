<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChaveValor;
use App\Http\Controllers\Traits\Authorization;

class ChaveValorController extends BasicController
{
    use Authorization;

    public $modulo = "chave_valor";
    
    protected function boot()
    {
        $this->setModel( ChaveValor::class );

        $this->addAction([
            "insert"  => "Adicionar uma chave-valor.",
            "update"  => "Atualizar uma chave-valor.",
            "get"     => "Retornar uma chave-valor.",
            "destroy" => "Excluir uma chave-valor.",
            "search"  => "Pesquisar pelas chaves-valores.",
        ]);
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            "chave" => [ 'nullable' , 'string' , 'max:200' ] ,
            "valor" => [ 'nullable' , 'string' ] ,
            "usuario_id" => [ 'nullable' , 'numeric' ]
        ];
    }

}
