<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait Update
{
    
    public function update( Request $request , Model $model )
    {
        if( method_exists( $this , "autorizacao" ) ){
            $this->autorizacao( $this->modulo , "update" );
        }

        if( method_exists( $this , "beforeValidationEvent") ) 
            $this->beforeValidationEvent( $request );

        $validated = $request->validate( 
            $this->getRegras( $request , 'update' ) 
        );
	
        $model->update( $validated );
        $this->uploadImage( $request , $model->id );
        
        $this->update_log( $model , $model->getChanges() );

        return $model;
    }

    public function update_log( Model $model , $parametros )
    {
        if( method_exists( $this , "log" ) )
        {
            $this->log( 
                $this->modulo , 
                "update" , 
                $model->id , 
                "Modificou Nº {$model->id}" ,
                $parametros
            );
        }
    }

}

?>