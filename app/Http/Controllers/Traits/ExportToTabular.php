<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait ExportToTabular
{

    public function export_to_tabular( Request $request )
    {
        $request->merge([ "perPage" => 0 ]);
        
        $resultado = $this->search( $request );
        $resultado = $this->export_to_array( $resultado );
        $resultado = $this->export_only_values( $resultado );
        
        http_response_code( 200 );
        //header( "Content-Disposition: attachment; filename=\"{$this->modulo}.xls\"" );
        header( "Content-Type: application/vnd.ms-excel; charset=UTF-8" );

        // ------ //

        $out = fopen( 'php://output' , 'w' );
        
        foreach( $resultado as $line ){
            array_walk( $line , array( $this , 'tablular_filterCustomerData' ) );
            fwrite( $out , implode( "\t" , $line ) . "\r\n" );
        }

        fclose( $out );
        
        $this->export_log( "Excel" , $request->input() );
    }

    // Filter Customer Data
    public function tablular_filterCustomerData( $str ) {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';

        return $str;
    }

}

?>