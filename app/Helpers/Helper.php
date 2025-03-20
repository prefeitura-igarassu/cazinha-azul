<?php

namespace App\Helpers;

class Helper
{

    public static function isEmpty( $value )
    {
        return $value === NULL || trim( $value ) === '';
    }

    public static function count( $input )
    {
        if( $input == null )
        {
            return 0;
        }
        if( is_object( $input ) 
            && method_exists(  $input , 'count' ) )
        {
            return $input->count();
        }
        else if( is_array( $input ) )
        {
            return count( $input );
        }
        else
        {
            return 0;
        }
    }

    public static function soNumero( $input )
    {
        return preg_replace( '/[^0-9]/' , '' , $input );
    }

    public static function getClassName( $obj )
    {
        $name = get_class( $obj );
        $name = explode( "\\" , $name );

        return $name[ count( $name ) - 1 ];
    }

}