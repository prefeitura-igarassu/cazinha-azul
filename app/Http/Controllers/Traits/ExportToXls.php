<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait ExportToXls
{

    public function export_to_xls( Request $request )
    {
        $request->merge([ "perPage" => 0 ]);
        
        $resultado = $this->search( $request );
        $resultado = $this->export_to_array( $resultado );
        $resultado = $this->export_only_values( $resultado );

        // ---- //

        http_response_code( 200 );
        header( "Content-Disposition: attachment; filename=\"{$this->modulo}.xls\"" );
        header( "Content-Type: application/excel; charset=UTF-8" );

        $out = fopen( 'php://output' , 'w' );

        fwrite( $out , "<table>"  );
        array_walk( $resultado , [ $this , 'export_table_tr' ] , $out );
        fwrite( $out , "</table>" );

        fclose( $out );

        // ---- //
        
        $this->export_log( "Excel" , $request->input() );
    }

    public function export_table_tr( $line , $key , $out )
    {
        fwrite( $out , "<tr>"  );
        array_walk( $line , array( $this , 'export_table_td' ) , $out );
        fwrite( $out , "</tr>" );
    }

    public function export_table_td( $str , $key , $out )
    {
        fwrite( $out , "<td>". htmlspecialchars( $str ) . "</td>" );
    }

}

?>