<?php

namespace App\Helpers;

class Link 
{

    public static function whatsapp( $input , $texto = 'Olá' )
    {
        $numero = Helper::soNumero( $input );
        return "https://web.whatsapp.com/send?phone=55$numero&text=$texto";
    }

    public static function map( $endereco )
    {
        return "https://maps.google.com/?q=$endereco";
    }

}