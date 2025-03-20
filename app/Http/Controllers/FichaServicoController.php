<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FichaServico;
use App\Models\Sessao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Helpers\DataUtils;

use \Datetime;

class FichaServicoController extends BasicController
{
    public $modulo = "Serviços da Ficha";
    
    protected function boot()
    {
        $this->setModel( FichaServico::class );
        
        $this->addAction([
            "insert"   => "Associar um serviço a uma ficha.",
            "update"   => "Atualizar um serviço a uma ficha.",
            "get"      => "Retornar um serviço a uma ficha.",
            "destroy"  => "Excluir um serviço a uma ficha.",
            "search"   => "Pesquisar pelos serviços das fichas.",
        ]);
    }

    public function insert( Request $request )
    {
        $model = parent::insert( $request );
        $this->custom( $request , $model , $model );

        return $model;
    }

    public function update( Request $request , $antes )
    {
        $depois = parent::update( $request , $antes );
        $this->custom( $request , $antes , $depois );
        
        return $depois;
    }

    public function custom( Request $request , $antes , $depois )
    {
        if( $antes->status == FichaServico::SOLICITADO && $depois->status != FichaServico::SOLICITADO )
        {
            $depois->posicao = null;
            $depois->save();

            DB::update( 
                "UPDATE fichas_servicos SET posicao = posicao - 1 WHERE status = 0 AND servico_id = ? AND unidade_id = ? AND posicao >= ? AND deleted_at IS NULL;" ,
                [ $antes->unidade_id , $antes->servico_id , $antes->posicao ]
            );
        }
        else if( $depois->status == FichaServico::SOLICITADO && !$depois->posicao )
        {
            $depois->posicao = FichaServico::select( "posicao" )
                ->where( "unidade_id" , $depois->unidade_id )
                ->where( "servico_id" , $depois->servico_id )
                ->orderBy( "posicao" , "desc" )
                ->first()?->posicao + 1;
            
            // deixar o agendamento em branco
            $depois->terapeuta_id   = null;
            $depois->dia            = null;
            $depois->horario        = null;
            $depois->periodo_inicial = null;
            $depois->periodo_final  = null;
            $depois->save();
        }
        else if( $depois->status == FichaServico::ALTA )
        {
            $depois->posicao = 0;
            
            // deixar o agendamento em branco
            $depois->terapeuta_id   = null;
            $depois->dia            = null;
            $depois->horario        = null;
            $depois->periodo_inicial = null;
            $depois->periodo_final  = null;
            $depois->save();
        }
        else if( $depois->status == FichaServico::AGENDADO )
        {
            $atual = new Datetime( $depois->periodo_inicial );
            $total = DataUtils::diff( $depois->periodo_inicial , $depois->periodo_final );

            for( $i = 0 ; $i < $total ; $i++ )
            {
                if( $atual->format( 'w' ) == $depois->dia )
                {
                    Sessao::create([
                        'usuario_id'    => auth()->user()->id,
                        'ficha_id'      => $depois->ficha_id,
                        'servico_id'    => $depois->servico_id,
                        'terapeuta_id'  => $depois->terapeuta_id,
                        'agendado_para' => $atual->format( 'Y-m-d' ) . " {$depois->horario}:00",
                        'status'        => Sessao::AGENDADO,
                    ]);
                }

                $atual = DataUtils::add( $atual , "1 day" );
            }
        }
    }

    public function recalcularPosicao( Request $request )
    {
        $validated = $request->validate([
            "unidade_id" => [ "required" , "integer" ],
            "servico_id" => [ "required" , "integer" ],
        ]);


        DB::select( "SET @row_number = 0;" );
        DB::update( "UPDATE fichas_servicos SET posicao = (@row_number := @row_number + 1) 
            WHERE status = 0 AND servico_id = ? AND unidade_id = ? AND deleted_at IS NULL;" , 
            [ $validated['unidade_id'] , $validated['servico_id'] ]
        );
    }

    public function getUltimaPosicao( Request $request )
    {
        $validated = $request->validate([
            "unidade_id" => [ "required" , "integer" ],
            "servico_id" => [ "required" , "integer" ],
        ]);

        return FichaServico::select( "posicao" )
            ->where( "unidade_id" , $validated[ 'unidade_id' ] )
            ->where( "servico_id" , $validated[ 'servico_id' ] )
            ->orderBy( "posicao" , "desc" )
            ->first()?->posicao;
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            'unidade_id'      => [ 'required' , 'integer' ],
            'usuario_id'      => [ 'required' , 'integer' ],
            'ficha_id'        => [ 'required' , 'integer' ],
            'servico_id'      => [ 'required' , 'integer' ],
            'solicitado_em'   => [ 'required' , 'date'    ],
            'status'          => [ 'required' , 'integer' ],  // solicitado (0), fila (1), agendado (2), alta (3)

            'terapeuta_id'    => [ 'nullable' , 'integer' ],
            'dia'             => [ 'nullable' , 'integer' ],
            'horario'         => [ 'nullable' , 'string'  , 'max:5' ],
            'periodo_inicial' => [ 'nullable' , 'date'    ],
            'periodo_final'   => [ 'nullable' , 'date'    ],
        ];
    }

}