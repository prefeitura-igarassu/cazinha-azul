<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait DashboardGroup
{
    
    public function dash_group( Request $request ){
        $model = $this->model;
        $table = $model->getTable();

        if( !$request->filled( "groupBy" ) ){
            return null;
        }

        $this->dash_group_applySelect( $request , $model );
        $this->search_join           ( $request , $model , true   );
        $this->search_applyWith      ( $request , $model );
        $this->search_applyWhere     ( $request , $model , $table );
        $this->dash_group_applyGroup ( $request , $model );
        
        return $model->get();
    }

    public function dash_group_applySelect( Request $request , &$model ){
        $columns = $request->input( "columns" , null );
        $groupBy = $request->input( "groupBy" );

        $column    = $request->input( "column" );
        $operation = $request->input( "op" );

        if( !in_array( $operation , DASH_OPERATIONS ) ) $operation = "COUNT";
        if( !$column ) $column = "*";
        
        //FROM: https://stackoverflow.com/questions/55001129/show-null-values-in-group-by-query-as-zero
        $model = $columns 
            ? $model->selectRaw( "$groupBy as label, $columns, $operation( $column ) as total" )
            : $model->selectRaw( "$groupBy as label, $operation( $column ) as total" );
    }

    public function dash_group_applyGroup( Request $request , &$model ){
        //$groupBy = $request->input( "groupBy" );

        $model = $model->groupBy( "label" );
    }

}

?>