<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use PDF;

trait ExportToHTML
{

    public function export_to_html( Request $request )
    {
        $request->merge([ "perPage" => 0 ]);
        
        $resultado = $this->search( $request );
        $resultado = $this->export_to_array( $resultado );

        $this->export_log( "HTML" , $request->input() );

        // ---- //
        
        return view( 'exportacao' , [
            "header" => [
                "title"   => $this->modulo,
                "styles"  => [],
            ],
            "body"   => [
                "element" => "body",
                "class"   => "",
                "value"   => $resultado
            ]
        ]);
    }

    public function imprimir_html( $data )
    {
        return view( 'exportacao' , $data );
    }

}

?>