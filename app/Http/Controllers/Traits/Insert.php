<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Validator;

trait Insert
{

    public function insert( Request $request )
    {
        if( method_exists( $this , "autorizacao" ) ){
            $this->autorizacao( $this->modulo , "insert" );
        }

        if( method_exists( $this , "beforeValidationEvent") ) 
            $this->beforeValidationEvent( $request );
            
        $validos = $request->validate( $this->getRegras( $request , 'insert' ) );
        $model = $this->model::create( $validos );

        $this->uploadImage( $request , $model->id );

        $this->insert_log( $model , $request->input() );

        return $model;
    }

    public function insert_log( Model $model , $parametros )
    {
        if( method_exists( $this , "log" ) )
        {
            $this->log( 
                $this->modulo , 
                "insert" , 
                $model->id , 
                "Inseriu Nº {$model->id}" ,
                $parametros
            );
        }
    }

}

?>