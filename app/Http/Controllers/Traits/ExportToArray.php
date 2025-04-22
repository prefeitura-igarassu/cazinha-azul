<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait ExportToArray
{

    public function export_to_array( $resultado )
    {
        $resultado = $this->export_exec( $this->exportacao , 'process' , $resultado , [ $resultado ] );

        $export = [
            [ 'element' => 'tr' , 'class' => 'table-title' , 'value' => [] ]
        ];

        foreach( $this->exportacao[ 'columns' ] as $columnObj )
        {
            $export[ 0 ][ 'value' ][] = [
                'element' => 'th',
                'class'   => 'table-title border-top border-bottom',
                'value'   => $columnObj[ 'title' ]
            ];
        }

        $index = 0;

        foreach( $resultado as $data )
        {
            $row = [
                "element" => "tr",
                "class"   => $this->export_exec( $this->exportacao , 'getRowClass' , '' , [ $data , $index , $resultado ] ),
                "value"   => [],
            ];

            if( array_key_exists( "striped" , $this->exportacao ) && $index % 2 == 1 )
            {
                $row[ 'style' ] = $this->exportacao[ "striped" ];
            }

            foreach( $this->exportacao['columns'] as $columnName => $columnObj )
            {
                $row["value"][] = $this->export_to_data( $index , $data , $columnName , $columnObj );
            }

            $export[] = $row;
            $index++;
        }

        $lastRow = [ 'element' => 'tr' , 'class' => 'table-footer' , 'value' => [] ];
        $hasLastRow = false;

        foreach( $this->exportacao[ 'columns' ] as $columnObj )
        {
            $column = $this->export_exec( 
                $columnObj , 
                'footer' , 
                [ "element" => 'td', 'class' => 'table-footer', 'value' => null ] , 
                [ $resultado ] 
            );

            $column[ 'class' ] = "{$column['class']} border-top border-bottom";
            $lastRow[ 'value' ][] = $column;
            if( !$hasLastRow ) $hasLastRow = $column[ 'value' ] != null;
        }

        if( $hasLastRow ) $export[] = $lastRow;

        return [
            "element" => "table",
            "class"   => "table stripe w-full " . $this->export_exec( $this->exportacao , 'getTableClass' , '' , [ $resultado ] ),
            "value"   => $export
        ];
    }

    public function export_exec( $obj , $name , $default , $arguments )
    {
        if( !array_key_exists( $name , $obj ) )
        {
            return $default;
        }

        return is_callable( $obj[ $name] )
            ? $obj[ $name ](... $arguments)
            : $obj[ $name ];
    }

    public function export_to_data( $index , $data , $columnName , $columnObj )
    {
        $class = $this->export_exec( $columnObj , 'class' , '' , [ $data , $index ] );
        $style = $this->export_exec( $columnObj , 'style' , '' , [ $data , $index ] );

        if( array_key_exists( 'format' , $columnObj ) ) return [ 'element' => 'td', 'style' => $style, 'class' => $class              , 'value' => $columnObj['format']( $data ) ];
        
        $value = array_key_exists( 'value' , $columnObj ) 
            ? $columnObj[ 'value' ]( $data )
            : $data[ $columnName ];

        return $this->create_td_node( $columnObj['type'] , $value , $class , $style );
    }

    public function create_td_node( $type , $value , $class = "" , $style = "" )
    {
        if( is_null( $value ) )            return [ 'element' => 'td', 'style' => $style                     , 'class' => $class , 'value' => '' ];
        else if( $type === 'integer'  )    return [ 'element' => 'td', 'style' => "$style text-align: right;", 'class' => $class , 'value' => number_format( $value , 0 , ',' , '.' ) ];
        else if( $type === 'numeric'  )    return [ 'element' => 'td', 'style' => "$style text-align: right;", 'class' => $class , 'value' => number_format( $value , 2 , ',' , '.' ) ];
        else if( $type === 'money'    )    return [ 'element' => 'td', 'style' => "$style text-align: right;", 'class' => $class , 'value' => "R$ " . number_format( $value , 2 , ',' , '.' ) ];
        else if( $type === 'date'     )    return [ 'element' => 'td', 'style' => $style                     , 'class' => $class , 'value' => date( "d/m/Y"       , strtotime( $value ) ) ];
        else if( $type === 'datetime' )    return [ 'element' => 'td', 'style' => $style                     , 'class' => $class , 'value' => date( "d/m/Y H:i:s" , strtotime( $value ) ) ];
        else                               return [ 'element' => 'td', 'style' => $style                     , 'class' => $class , 'value' => $value ];
    }

    public function export_only_values( $resultado )
    {
        if( array_key_exists( 'value' , $resultado ) )
        {
            if( !is_array( $resultado['value'] ) )
            {
                return $resultado['value'];
            }
            else
            {
                $export = [];

                foreach( $resultado['value'] as $value ){
                    $export[] = $this->export_only_values( $value );
                }
        
                return $export;
            }
        }
        else
        {
            return $resultado;
        }
    }

    public function export_log( $tipo , $parametros , $id = null )
    {
        if( method_exists( $this , "log" ) )
        {
            $this->log( 
                $this->modulo , 
                "search" , 
                $id , 
                "Exportou para o $tipo" ,
                $parametros
            );
        }
    }

    public function export_node( $element , $value , $extra = [] )
    {
        return array_merge( $extra , [ "element" => $element , "value" => $value ] );
    }

}

?>