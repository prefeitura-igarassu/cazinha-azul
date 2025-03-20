<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait Destroy
{

    public function destroy( Request $request , Model $model ){
        $this->destroy_autorizacao();
        $model->delete();
        $this->destroy_log( $model );
        
        return $model;
    }

    public function destroy_autorizacao(){
        if( method_exists( $this , "autorizacao" ) ){
            $this->autorizacao( $this->modulo , "destroy" );
        }
    }

    public function destroy_log( Model $model ){
        if( method_exists( $this , "log" ) ){
            $this->log( 
                $this->modulo , 
                "excluir" , 
                $model->id , 
                "Excluiu Nº {$model->id}" 
            );
        }
    }

}

?>