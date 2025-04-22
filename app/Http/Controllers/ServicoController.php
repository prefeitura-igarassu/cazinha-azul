<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servico;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ServicoController extends BasicController
{
    public $modulo = "Serviços";
    
    protected function boot()
    {
        $this->setModel( Servico::class );

        $this->addAction([
            "insert"   => "Adicionar um serviço.",
            "update"   => "Atualizar um serviço.",
            "get"      => "Retornar um serviço.",
            "destroy"  => "Excluir um serviço.",
            "search"   => "Pesquisar pelos serviços.",
        ]);
    }

    public function getSearchWhere()
    {
        return [
            "global" => function ( $query , $key , $value ){
                $value = is_array( $value ) ? array_values( $value )[0] : $value;

                return $query->orWhere( "nome"      , "like" , "%$value%" )
                             ->orWhere( "descricao" , "like" , "%$value%" );
            },
        ];
    }

    public function search_applyOrderBy( Request $request , &$model )
    {
        if( $request->filled( "orderBy" ) )
        {
            parent::search_applyOrderBy( $request , $model );
        }
        else
        {
            $model = $model->orderBy( "nome" , "ASC" );
        }
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            'nome'      => [ 'required' , 'string' , 'max:100' ],
            'descricao' => [ 'nullable' , 'string' , 'max:200' ],
        ];
    }

}
