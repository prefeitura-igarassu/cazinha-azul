<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use PDF;

trait ExportToPDF
{

    public function export_to_pdf( Request $request )
    {
        $request->merge([ "perPage" => 0 ]);
        
        $resultado = $this->search( $request );
        $resultado = $this->export_to_array( $resultado );

        $this->export_log( "PDF" , $request->input() );

        return $this->imprimir_pdf([
            "header" => [
                "title"   => $this->modulo,
                "styles"  => [],
            ],
            "body"   => [
                "element" => "body",
                "class"   => "font-10",
                "value"   => $resultado
            ]
        ]);
    }

    public function imprimir_pdf( $data )
    {
        http_response_code( 200 );
        header( "Content-Type: application/pdf" );

        $pdf = PDF::loadView( 'exportacao' , $data );
        $pdf->setPaper( 'A4' , 'landscape' );
        return $pdf->stream();
    }

}

?>