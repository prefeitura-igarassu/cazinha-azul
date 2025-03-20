<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Terapeuta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

use \App\Rules\CpfCnpjRule;

class TerapeutaController extends BasicController
{
    public $modulo = "Terapeutas";
    
    protected function boot()
    {
        $this->setModel( Terapeuta::class );

        $this->addAction([
            "insert"   => "Adicionar um terapeuta.",
            "update"   => "Atualizar um terapeuta.",
            "get"      => "Retornar um terapeuta.",
            "destroy"  => "Excluir um terapeuta.",
            "search"   => "Pesquisar pelos terapeutas.",
        ]);
    }

    /*
    public function insert( Request $request )
    {
        // adicionar um usuário
    }
    */

    public function horarios( Request $request , $terapeuta )
    {
        $horarios = [];

        foreach( $terapeuta->horarios as $data )
        {
            $values = [];

            if( $data[ "horas" ][ 0 ] != "00:00" && $data[ "horas" ][ 1 ] != "00:00" ){
                $values = array_merge( 
                    $values , 
                    $this->horariosCriar( $terapeuta , $data[ "horas" ][ 0 ] , $data[ "horas" ][ 1 ] ) 
                );
            }

            if( $data[ "horas" ][ 2 ] != "00:00" && $data[ "horas" ][ 3 ] != "00:00" ){
                $values = array_merge( 
                    $values , 
                    $this->horariosCriar( $terapeuta , $data[ "horas" ][ 2 ] , $data[ "horas" ][ 3 ] ) 
                );
            }

            $horarios[ $data['dia'] ] = $values;
        }

        return $horarios;
    }

    private function horariosCriar( $terapeuta , $inicial , $final )
    {
        $inicial = explode( ":" , $inicial );
        $final   = explode( ":" , $final   );

        $horarios = [];

        for( $i = intval( $inicial[0] ) ; $i < $final[0] ; $i++ )
        {
            $zero = $i < 10 ? "0" : "";

            $horarios[] = "$zero$i:00";
            $horarios[] = "$zero$i:30";
        }
        
        if( $inicial[1] == "30" ) array_shift( $horarios );
        if( $final  [1] == "30" ) $horarios[] = "$final[0]:00";

        return $horarios;
    }

    public function getSearchWhere()
    {
        return [
            "global" => function ( $query , $key , $value ){
                $value = is_array( $value ) ? array_values( $value )[0] : $value;
                return $query->orWhere( 'nome' , "like" , "%$value%" );
            },
        ];
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            'unidade_id' => [ 'required' , 'integer' ],
            'usuario_id' => [ 'required' , 'integer' ],
            'servico_id' => [ 'required' , 'integer' ],
            'nome'       => [ 'required' , 'string'  , 'max:100' ],
            'horarios'   => [ 'nullable' , 'array'   ],
        ];
    }

}
