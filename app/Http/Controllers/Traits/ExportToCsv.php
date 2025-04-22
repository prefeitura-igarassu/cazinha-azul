<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait ExportToCsv
{

    public function export_to_csv( Request $request )
    {
        $request->merge([ "perPage" => 0 ]);
        
        $resultado = $this->search( $request );
        $resultado = $this->export_to_array( $resultado );
        $resultado = $this->export_only_values( $resultado );

        http_response_code( 200 );
        //header( "Content-Disposition: attachment; filename=\"{$this->modulo}.csv\"" );
        header( "Content-Type: text/csv; charset=UTF-8" );

        // ---- //

        $out = fopen( 'php://output' , 'w' );
        foreach( $resultado as $line ) fputcsv( $out , $line );
        fclose( $out );
        
        $this->export_log( "CSV" , $request->input() );
    }

}

?>