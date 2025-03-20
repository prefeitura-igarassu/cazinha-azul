<?php

namespace App\Helpers;

class CID 
{

    public static function cid( $cid )
    {
        $cid = str_replace( "." , "" , $cid );

        // <meta name="description" content="CID F800 Transtorno específico da articulação da fala">
        $content = CID::call( "https://cid10.com.br/%5Ebuscacode$?query=$cid" );
        if( !$content ) return null;

        $pattern = '/<meta name="description" content="([^"]*)">/';
        preg_match( $pattern , $content , $matches );

        if( !isset( $matches[1] ) ) return null;

        $matches[1] = str_replace( "CID " , "" , trim( $matches[1] ) );
        return $matches[1] == "CID" ? null : $matches[1];
    }
    
    public static function nome( $name )
    {
        $json = json_decode(
            CID::call( "https://cid10.com.br/ahead/?featureClass=P&style=full&maxRows=12&name_startsWith=$name&query=$name" )
        );

        if( !$json ) return [];

        $retorno = [];

        for( $i = 0 ; $i < count( $json ) ; $i++ )
        {
            $partes = explode(" " , $json[ $i ] , 2 );

            $codigo = str_replace( "." , "" , $partes[0] );
            $nome   = trim( $partes[1] );

            $retorno[ $codigo ] = $nome;
        }

        return $retorno;
    }

    private static function call( $url )
    {
        $conteudo = file_get_contents($url);

        return $conteudo === false 
            ? null 
            : $conteudo;
    }

}