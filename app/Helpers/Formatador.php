<?php

namespace App\Helpers;

use \Datetime;

class Formatador
{

    public static function dinheiro( $valor )
    {
        return "R$ " . number_format( $valor , 2 , ',' , '.' );
    }

    public static function data( $data )
    {
        if( $data == null ) return '';

        $data1 = new DateTime( $data );
        $data1->setTime( 0, 0, 0 );

        $data2 = new DateTime();
        $data2->setTime( 0, 0, 0 );

        $dias  = (integer) $data1->diff( $data2 )->format( "%R%a" );

        if($dias == -1) return 'Amanhã';
        else if($dias == 0) return 'Hoje';
        else if($dias == 1) return 'Ontem';
        else if($dias == 2) return 'Anteontem';
        else return date( "d/m/Y" , strtotime( $data ) );
    }

    public static function data_hora( $data )
    {
        if( $data == null ) return '';

        return Formatador::data( $data ) 
            . ' às ' . 
            date( "G:i" , strtotime( $data ) );
    }

    public static function data_diff_string( $data )
    {
        if( $data == null ) return '';

        $data1 = new DateTime( $data );
        $data1->setTime( 0, 0, 0 );

        $data2 = new DateTime();
        $data2->setTime( 0, 0, 0 );

        $dias  = (integer) $data1->diff( $data2 )->format( "%R%a" );

        if( $dias == 0 ) return "Hoje";

        // -----

        $mes  = (integer) ($dias / 30);
        $dias = ($dias % 30);
        $semana = (integer) ($dias / 7);
        $dias = ($dias % 7);

        $retorno = [];

        // -----

        if( $mes == 1 ) array_push( $retorno , '1 mês' );
        else if( $mes > 1 ) array_push( $retorno , "$mes meses" );

        if( $semana == 1 ) array_push( $retorno , '1 semana' );
        else if( $semana > 1 ) array_push( $retorno , "$semana semana" );

        if( $dias == 1 ) array_push( $retorno , '1 dia' );
        else if( $dias > 1 ) array_push( $retorno , "$dias dias" );
                
        return implode( ' e ' , $retorno );
    }

    public static function cpf_cnpj( $input )
    {
        $doc = preg_replace( "/[^0-9]/" , "" , $input );
        $qtd = strlen( $doc );

        if( $qtd === 11 ) return self::cpf( $doc );
        else if( $qtd === 14 ) return self::cnpj( $doc );
        else return $input;
    }

    public static function cpf( $doc )
    {
        return substr($doc, 0, 3) . '.' .
            substr($doc, 3, 3) . '.' .
            substr($doc, 6, 3) . '-' .
            substr($doc, 9, 2);
    }

    public static function cnpj( $doc )
    {
        return substr($doc, 0, 2) . '.' .
            substr($doc, 2, 3) . '.' .
            substr($doc, 5, 3) . '/' .
            substr($doc, 8, 4) . '-' .
            substr($doc, -2);
    }

}