<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait GetOptions
{

    public function getOptions( Request $request )
    {
        $options = [];

        if( $request->input( "addNullValue" , true ) )
        {
            $options[] = [
                "label" => $request->input( "setNullLabel" , "Nenhum" ),
                "value" => null
            ];
        }
        
        if( !property_exists( $this , "optionLabel" ) )
        {
            $this->optionLabel = "nome";
        }

        $model = $this->model;
        $table = $model->getTable();

        $this->search_applyWhere  ( $request , $model , $table );
        $this->search_applyOrderBy( $request , $model );
        $this->search_applyGroupBy( $request , $model );

        foreach( $model->get() as $option )
        {
            $options[] = [
                "label" => $option->{ $this->optionLabel } ,
                "value" => $option->id
            ];
        }
        
        return $options;
    }
    
}

?>