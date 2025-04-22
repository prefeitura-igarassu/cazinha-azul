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

    public function search_applySelect( Request $request , &$model )
    {
        if( $request->filled( "select" ) )
        {
            return parent::search_applySelect( $request , $model );
        }
        
        return $model = $model->select( DB::raw( 
            "sessoes.*, terapeuta.nome as terapeuta_nome, ficha.nome as ficha_nome, servico.nome as servico_nome"
        ) );
    }

    public function search_join( Request $request , &$model , $selectedWasDefined = false )
    {
        $model = $model->leftJoin( "terapeutas as terapeuta" , "terapeuta.id" , "sessoes.terapeuta_id" )
                       ->leftJoin( "servicos as servico"     , "servico.id"   , "sessoes.servico_id"   )
                       ->leftJoin( "fichas as ficha"         , "ficha.id"     , "sessoes.ficha_id"     );
    }

    public function getSearchWhere()
    {
        return [
            "terapeuta_usuario_id" => function ( $query , $key , $value ){
                return $query->where( 'terapeuta.usuario_id' , $value );
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
