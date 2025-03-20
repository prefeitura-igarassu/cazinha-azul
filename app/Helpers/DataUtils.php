<?php

namespace App\Helpers;

use \Datetime;

class DataUtils
{
    public static function add( $data , $texto )
    {
        if( $data instanceof DateTime )
        {
            return date_add( 
                $data , 
                date_interval_create_from_date_string( $texto ) 
            );
        }
        else 
        {
            return date_add( 
                getDate( $data ) , 
                date_interval_create_from_date_string( $texto ) 
            );
        }
    }

    public static function dia( $data )
    {
        $dias = array(
            'Domingo' , 
            'Segunda-feira', 
            'Terça-feira', 
            'Quarta-feira', 
            'Quinta-feira', 
            'Sexta-feira', 
            'Sabado'
        );

        return $dias[
            date( 'w' , strtotime( $data ) )
        ];
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

        return DataUtils::data( $data ) 
            . ' às ' . 
            date( "G:i" , strtotime( $data ) );
    }

    public static function hora( $data )
    {
        if( $data == null ) return '';

        return date( "G:i" , strtotime( $data ) );
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

    public static function diff( $data1 , $data2 )
    {
        if( !$data1 || !$data2 ) return '';

        $data1 = new DateTime( $data1 );
        $data1->setTime( 0, 0, 0 );

        $data2 = new DateTime( $data2 );
        $data2->setTime( 0, 0, 0 );

        return (integer) $data1->diff( $data2 )->format( "%R%a" );
    }

    public static function day_of_week( $date )
    {
        return date( 'w' , strtotime( $date ) );
    }

}