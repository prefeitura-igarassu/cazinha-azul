<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

const DASH_OPERATIONS = [ "MAX" , "MIN" , "SUM" , "AVG" , "COUNT" ];

trait DashboardResume
{
    
    public function dash_resume( Request $request ){
        $model = $this->model;
        $table = $model->getTable();

        $this->dash_resume_applySelect( $request , $model );
        $this->search_join      ( $request , $model , true );
        $this->search_applyWith ( $request , $model );
        $this->search_applyWhere( $request , $model , $table );

        // ------ //
        
        $resultSQL = $model->get();
        
             if( count( $resultSQL ) == 0 ) return "";
        else if( count( $resultSQL ) == 1 ) return $resultSQL[0][ 'result' ];

        $result = [];
        foreach( $resultSQL as $r ) $result[] = $r[ 'result' ];
        
        return $result;
    }

    public function dash_resume_applySelect( Request $request , &$model ){
        $column    = $request->input( "column" , $model->getTable() . ".id" );
        $operation = $request->input( "op" );

        if( $operation ){
            $operation = strtoupper( $operation );
            if( !in_array( $operation , DASH_OPERATIONS ) ) $operation = "SUM";

            $model = $model->selectRaw( "$operation($column) as result" );
            //$model = $model->groupBy( $column );
        }
        else{
            $model = $model->selectRaw( "$column as result" );
            //$model = $model->groupBy( $column );
        }
    }

}

?>