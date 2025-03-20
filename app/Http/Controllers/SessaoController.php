<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sessao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SessaoController extends BasicController
{
    public $modulo = "Serviços";
    
    protected function boot()
    {
        $this->setModel( Sessao::class );

        $this->addAction([
            "insert"   => "Adicionar uma sessão.",
            "update"   => "Atualizar uma sessão.",
            "get"      => "Retornar uma sessão.",
            "destroy"  => "Excluir uma sessão.",
            "search"   => "Pesquisar pelas sessões.",
        ]);
    }

    public function getSearchWhere()
    {
        return [
            "global" => function ( $query , $key , $value ){
                $value = is_array( $value ) ? array_values( $value )[0] : $value;

                return $query->orWhere( 'nome'    , "like" , "%$value%" );
            },
        ];
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            'usuario_id'    => [ 'required' , 'integer'  ],                 // quem agendou
            'ficha_id'      => [ 'required' , 'integer'  ],
            'servico_id'    => [ 'required' , 'integer'  ],
            'terapeuta_id'  => [ 'required' , 'integer'  ],
            'agendado_para' => [ 'required' , 'date'     ],
            'status'        => [ 'required' , 'integer'  ],
            'evolucao'      => [ 'nullable' , 'string'   ],
            'observacao'    => [ 'nullable' , 'string'   , 'max:200' ],
        ];
    }

}
