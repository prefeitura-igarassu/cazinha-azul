<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait ListFromParent
{
    protected $parentModel = null;
    protected $parentField = null;
    
    protected function setParentModel( $modelClass , $field )
    {
        $this->parentModel = new $modelClass();
        $this->parentField = $field;
    }

    // ----- //
    // ----- //
    // ----- //

    public function list( Request $request , $id )
    {
        if( !$this->parentModel )
        {
            return abort( 404 );
        }

        $this->search_log( $request->input() , $id );
        
        $this->parentModel->findOrFail( $id );

        return $this->model
            ->where( $this->parentField , $id )
            ->when( $request->input( 'with' ) , function ( $query , $with ){
                $query->with( $with );
            } )
            ->when( $request->input( 'orderBy' ) , function ( $query , $orderBy ){
                $orderBy = explode( "," , $orderBy );

                for ( $i=0 ; $i < count( $orderBy ) ; $i+=2 )
                { 
                    $query = $query->orderBy( 
                        trim( $orderBy[ $i ] ) , 
                        trim( $orderBy[ $i + 1 ] )
                    );
                }
            } )
            ->get();
    }

}

?>