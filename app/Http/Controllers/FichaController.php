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

use \App\Rules\CpfCnpjRule;
use \App\Helpers\Helper;

class FichaController extends BasicController
{
    public $modulo      = "Fichas";
    public $imagem_path = "fichas";
    
    protected function boot()
    {
        $this->setModel( Ficha::class );

        $this->addAction([
            "insert"   => "Adicionar uma ficha.",
            "update"   => "Atualizar uma ficha.",
            "get"      => "Retornar uma ficha.",
            "destroy"  => "Excluir uma ficha.",
            "search"   => "Pesquisar pelosa fichas.",
            "imprimir" => "Imprimir uma ficha.",
        ]);
    }

    public function imprimir( Request $request , $ficha )
    {
        return view( "ficha" , [
            "ficha" => $ficha
        ] );
    }

    public function getSearchWhere()
    {
        return [
            "global" => function ( $query , $key , $value ){
                $value = is_array( $value ) ? array_values( $value )[0] : $value;

                return $query->orWhere( 'nome'        , "like" , "%$value%" )
                             ->orWhere( 'pai_nome'    , "like" , "%$value%" )
                             ->orWhere( 'mae_nome'    , "like" , "%$value%" )
                             ->orWhere( 'nis'         , "like" , "%$value%" )
                             ->orWhere( 'sus'         , "like" , "%$value%" )
                             ->orWhere( 'responsavel' , "like" , "%$value%" )
                    ;
            },
        ];
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        $cpfUniqueRule = "";
        $nisUniqueRule = "";

        if( $request->filled( "cpf" ) )
        {
            $request->merge([ "cpf" => Helper::soNumero( $request->input( "cpf" ) ) ]);

            $cpfUniqueRule = $request->filled( 'id' )
                ? Rule::unique( Ficha::class )->whereNull('deleted_at')->ignore( $request->input( 'id' ) )
                : Rule::unique( Ficha::class )->whereNull('deleted_at');
        }

        /*
		$nisUniqueRule = $request->filled( 'nis' )
            ? Rule::unique( Ficha::class )->whereNull('deleted_at')->ignore( $request->input( 'id' , 0 ) )
            : '';
        */

        return [
            'unidade_id'    => [ 'required' , 'integer'  ],
            'cadastrado_em' => [ 'nullable' , 'date'     ],
            'nome'          => [ 'required' , 'string'   , "max:100" ],
            'cpf'           => [ 'nullable' , 'string'   , "max:20"  , $cpfUniqueRule , new CpfCnpjRule() ],
            'nascido_em'    => [ 'nullable' , 'date'     ],
            'sus'           => [ 'nullable' , 'string'   , "max:30"  ],
            'nis'           => [ 'nullable' , 'string'   , "max:30"  , $nisUniqueRule ],
            'mae_nome'      => [ 'nullable' , 'string'   , "max:100" ],
            'pai_nome'      => [ 'nullable' , 'string'   , "max:100" ],
            'responsavel'   => [ 'nullable' , 'string'   , "max:100" ],
            'escola'        => [ 'nullable' , 'string'   , "max:100" ],
            'endereco'      => [ 'nullable' , 'string'   , "max:100" ],
            'posto_saude'   => [ 'nullable' , 'string'   , "max:100" ],
            'telefone'      => [ 'nullable' , 'string'   , "max:20"  ],
            'email'         => [ 'nullable' , 'string'   , "max:50"  ],
            'cid'           => [ 'nullable' , 'array'    ],
            'observacao'    => [ 'nullable' , 'string'   ],
        ];
    }

}
