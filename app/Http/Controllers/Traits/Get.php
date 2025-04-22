<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait Get
{

    public function get( Request $request , Model $model )
    {
        if( method_exists( $this , "autorizacao" ) ){
            $this->autorizacao( $this->modulo , "get" );
        }

        $this->get_log( $model );

        if( $request->input( 'with' ) ){
            return $model
                ->with( $request->input( 'with' ) )
                ->find( $model->id );
        }

        return $model;
    }

    public function get_log( Model $model )
    {
        if( method_exists( $this , "log" ) )
        {
            $this->log( 
                $this->modulo , 
                "get" , 
                $model->id , 
                "Retorno Nº {$model->id}" 
            );
        }
    }
    
}

?>