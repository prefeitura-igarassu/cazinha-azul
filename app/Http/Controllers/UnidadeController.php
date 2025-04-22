<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unidade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use \App\Rules\CpfCnpjRule;
use App\Helpers\Helper;

class UnidadeController extends BasicController
{
    public $modulo = "Unidades";
    
    protected function boot()
    {
        $this->setModel( Unidade::class );

        $this->addAction([
            "insert"   => "Adicionar uma unidade.",
            "update"   => "Atualizar uma unidade.",
            "get"      => "Retornar uma unidade.",
            "destroy"  => "Excluir uma unidade.",
            "search"   => "Pesquisar pelas unidades.",
            "imprimir" => "Imprimir uma unidade.",
        ]);
    }
    
    public function getSearchWhere()
    {
        return [
            "global" => function ( $query , $key , $value ){
                $value = is_array( $value ) ? array_values( $value )[0] : $value;

                return $query->orWhere( 'nome'       , "like" , "%$value%" )
                             ->orWhere( 'endereco'   , "like" , "%$value%" )
                             ->orWhere( 'telefone'   , "like" , "%$value%" )
                             ->orWhere( 'observacao' , "like" , "%$value%" )
                    ;
            },
        ];
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            'nome'       => [ 'required' , 'string' , 'max:100' ],
            'endereco'   => [ 'nullable' , 'string' , 'max:100' ],
            'telefone'   => [ 'nullable' , 'string' , 'max:20'  ],
            'observacao' => [ 'nullable' , 'string' , 'max:100' ],
        ];
    }

}
