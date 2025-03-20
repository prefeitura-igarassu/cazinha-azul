<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

trait Search
{
    
    public function search( Request $request )
    {
        if( method_exists( $this , "autorizacao" ) ){
            $this->autorizacao( $this->modulo , "search" );
        }

        $perPage = $request->input( "perPage"  , "0" );
        $model = $this->model;
        $table = $model->getTable();

		$this->search_applySelect ( $request , $model );
        $this->search_join		  ( $request , $model );
        $this->search_applyWith   ( $request , $model );
        $this->search_applyWhere  ( $request , $model , $table );
        $this->search_applyOrderBy( $request , $model );
        $this->search_applyGroupBy( $request , $model );
        $this->search_log( $request->input() );

        //Log::info( "search SQL: " . $model->toSql() );

        return $perPage > 0 
            ? $model->paginate( $perPage )
            : $model->get();
    }
    
    public function search_applySelect( Request $request , &$model )
    {
        if( !$request->filled( "select" ) )
        {
            return ;
        }
        
        $model = $model->select( DB::raw( 
            join( ", " , $request->input( "select" ) ) 
        ) );
    }

    public function search_join( Request $request , &$model , $selectedWasDefined = false )
    {
        
    }

    public function search_applyWith( Request $request , &$model )
    {
        if( $request->filled( "with" ) )
        {
            $model = $model->with( $request->input( "with" ) );
        }

        // from: https://laravel.com/docs/9.x/eloquent#querying-soft-deleted-models
        if( $request->input( "withTrashed" , false ) )
        {
            $model = $model->withTrashed();
        }
    }

    public function search_applyOrderBy( Request $request , &$model )
    {
        if( !$request->filled( "orderBy" ) ){
            return ;
        }

        $orderBy = explode( "," , $request->get( "orderBy" ) );

        for ( $i=0 ; $i < count( $orderBy ) ; $i+=2 )
        { 
            $model = $model->orderBy( 
                trim( $orderBy[ $i ] ) , 
                trim( $orderBy[ $i + 1 ] )
            );
        }
    }

    public function search_applyWhere( Request $request , &$model , $table )
    {
        $columns = array_keys( $this->getRegras( $request , 'search' ) );
        $wheres  = $this->getSearchWhere();

        foreach( $request->input() as $key => $value )
        {
            if( array_key_exists( $key , $wheres ) ){
                $model = $model->where( fn( $query ) => $query = $wheres[ $key ]( $query , $key , $value ) );
            }
            else if( in_array( $key , $columns ) ){
                $this->search_applyWhereValue( $model , $key , $value , $table );
            }
        }
    }

    public function search_applyWhereValue( &$model , $key , $value , $table )
    {
        $key =  "$table.$key";

        if( !is_array( $value ) ){                          // ex., 10, 'teste', true
            $model = $model->where( $key , $value );
            return ;
        }

        if( $value == array_values( $value ) ){
            $model = $model->whereIn( $key , $value );
        }
        else{
            if( array_key_exists( 'lt' , $value ) && $value['lt'] ) 
                $model = $model->where( $key , "<"  , $value['lt'] );

            if( array_key_exists( 'le' , $value ) && $value['le'] ) 
                $model = $model->where( $key , "<=" , $value['le'] );

            if( array_key_exists( 'ge' , $value ) && $value['ge'] ) 
                $model = $model->where( $key , ">=" , $value['ge'] );

            if( array_key_exists( 'gt' , $value ) && $value['gt'] ) 
                $model = $model->where( $key , ">"  , $value['gt'] );

            if( array_key_exists( 'eq' , $value ) ) 
                $model = $value['eq'] 
                    ? $model->where( $key , "="  , $value['eq'] ) 
                    : $model->whereNull( $key );

            if( array_key_exists( 'ne' , $value ) ) 
                $model = $value['ne'] 
                    ? $model->where( $key , "!=" , $value['ne'] )
                    : $model->whereNotNull( $key );

            if( array_key_exists( '**' , $value ) ) $model = $model->where( $key , "like"  , "%" . $value['**'] . "%" );
            if( array_key_exists( '*.' , $value ) ) $model = $model->where( $key , "like"  , "%" . $value['*.'] );
            if( array_key_exists( '.*' , $value ) ) $model = $model->where( $key , "like"  , $value['.*'] . "%" );
        }
    }

    public function search_applyGroupBy( Request $request , &$model )
    {
        if( !$request->filled( "groupBy" ) ) return ;

        $model = $model->groupBy( $request->get( "groupBy" ) );
    }

    public function getSearchWhere(){
        return [];
    }

    public function search_log( $parametros , $id = null )
    {
        if( method_exists( $this , "log" ) )
        {
            $this->log( 
                $this->modulo , 
                "search" , 
                $id , 
                "Pesquisou" ,
                $parametros
            );
        }
    }

}

?>
