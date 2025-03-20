<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait Printer
{
    //ler mais em: https://github.com/barryvdh/laravel-dompdf
    public function print_view( $view , $page = "A4" , $orientation = "portrait" )
    {
        return $this->print_html( $view->render() , $page , $orientation );
    }

    public function print_html( $html , $page = "A4" , $orientation = "portrait" )
    {
        return $this->print([
            "html" => $html,
            "page" => $page,
            "orientation" => $orientation,
        ]);
    }

    public function print( $params )
    {
        $curl = curl_init();

        curl_setopt_array( $curl , [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL  => 'https://admin.grupoconseng.com/convert/pdf',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $params
        ]);

        $response = curl_exec( $curl );
        
        if( curl_error( $curl ) ) return curl_error( $curl );

        curl_close( $curl );

        return response( $response , 200 )
            ->header( 'Content-Type' , 'application/pdf' );
    }

}

?>