<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ficha;
use App\Models\FichaAndamento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class FichaAndamentoController extends BasicController
{
    public $modulo = "Andamentos da Ficha";
    
    protected function boot()
    {
        $this->setModel( FichaAndamento::class );
        
        $this->addAction([
            "insert"   => "Adicionar um andamento.",
            "update"   => "Atualizar um andamento.",
            "get"      => "Retornar um andamento.",
            "destroy"  => "Excluir um andamento.",
            "search"   => "Pesquisar pelos andamentos.",
        ]);
    }

    public function getSearchWhere()
    {
        return [
            "global" => function ( $query , $key , $value ){
                $value = is_array( $value ) ? array_values( $value )[0] : $value;
                return $query->orWhere( 'descricao' , "like" , "%$value%" );
            },
        ];
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            'usuario_id'  => [ 'required' , 'numeric' ],
            'ficha_id'    => [ 'required' , 'numeric' ],
            'status'      => [ 'required' , 'numeric' ],
            'ocorrido_em' => [ 'required' , 'date'    ],
            'descricao'   => [ 'required' , 'string'  ],
        ];
    }

}