<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ficha;
use App\Models\FichaArquivo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class FichaArquivoController extends BasicController
{
    public $modulo = "Arquivos da Ficha";
    
    protected function boot()
    {
        $this->setModel( FichaArquivo::class );
        
        $this->addAction([
            "insert"   => "Adicionar um arquivo.",
            "update"   => "Atualizar um arquivo.",
            "get"      => "Retornar um arquivo.",
            "destroy"  => "Excluir um arquivo.",
            "search"   => "Pesquisar pelos arquivos.",

            "download" => "Download dos arquivos.",
        ]);
    }

    public function insert( Request $request )
    {
        if( !$request->input( 'arquivo' ) ) abort( 404 );

        $model = parent::insert( $request );

        $base64  = explode( ";base64," , $request->input( "arquivo" ) );
        $content = base64_decode( $base64[1] );

        Storage::makeDirectory( "arquivos/" );
        Storage::put( "arquivos/{$model->nome}" , $content );

        return $model;
    }

    public function download( Request $request , $model )
    {
        if( !Storage::exists( "arquivos/{$model->nome}" ) )
            abort( 404 );
        
        return Storage::download( "arquivos/{$model->nome}" );
    }

    public function getSearchWhere()
    {
        return [
            "global" => function ( $query , $key , $value ){
                $value = is_array( $value ) ? array_values( $value )[0] : $value;

                return $query->orWhere( 'titulo'    , "like" , "%$value%" )
                             ->orWhere( 'descricao' , "like" , "%$value%" )
                             ->orWhere( 'nome'      , "like" , "%$value%" );
            },
        ];
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            'usuario_id' => [ 'required' , 'numeric' ],
            'ficha_id'   => [ 'required' , 'numeric' ],
            'anexado_em' => [ 'required' , 'date'    ],
            'titulo'     => [ 'required' , 'string'  , 'max:100' ],
            'descricao'  => [ 'required' , 'string'  , 'max:200' ],
            'nome'       => [ 'required' , 'string'  , 'max:100' ],
            'tipo'       => [ 'required' , 'string'  , 'max:100' ],
            'tamanho'    => [ 'required' , 'numeric' ],
        ];
    }

}