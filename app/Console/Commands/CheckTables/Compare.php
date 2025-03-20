<?php

namespace App\Console\Commands\CheckTables;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Console\Commands\CheckTables;

trait Compare
{
    
    public function diff( $json , $describe )
    {
        // faz nada
    }

    public function compare( $json , $describe )
    {
        $result = [];
        $before = null;

        foreach( $json->columns as $name => $values )
        {
            $found = array_filter( $describe[ "columns" ] , fn ( $column ) => $name == $column["name"] );
            
            if( count( $found ) == 0 ) 
            {
                $result[] = [ "name" => $name , "status" => CheckTables::STATUS_NEW ];
            }
            else
            {
                $result[] = $this->compareColumn( $this->getColumnDefinition([
                    "before" => $before,
                    "name"   => $name,
                    "values" => $values,
                    "tableExists"  => true,
                    "columnExists" => true,
                ]) , array_values( $found )[0] );
            }

            $before = $name;
        }

        print_r( $result );

        return $result;
    }

    public function compareColumn( $columnFromFile , $columnFromDB )
    {
        $result = [ 
            "name"   => $columnFromFile[ "name" ] , 
            "status" =>  CheckTables::STATUS_EQUAL
        ];

        if( $columnFromFile[ "name"     ] != $columnFromDB[ "name"     ] ) $result[ "name"     ] = [ "file" => $columnFromFile[ "name"     ] , "db" => $columnFromDB[ "name"     ] ];
        if( $columnFromFile[ "type"     ] != $columnFromDB[ "type"     ] ) $result[ "type"     ] = [ "file" => $columnFromFile[ "type"     ] , "db" => $columnFromDB[ "type"     ] ];
        if( $columnFromFile[ "nullable" ] != $columnFromDB[ "nullable" ] ) $result[ "nullable" ] = [ "file" => $columnFromFile[ "nullable" ] , "db" => $columnFromDB[ "nullable" ] ];
        if( $columnFromFile[ "default"  ] != $columnFromDB[ "default"  ] ) $result[ "default"  ] = [ "file" => $columnFromFile[ "default"  ] , "db" => $columnFromDB[ "default"  ] ];
        if( $columnFromFile[ "max"      ] != $columnFromDB[ "max"      ] ) $result[ "max"      ] = [ "file" => $columnFromFile[ "max"      ] , "db" => $columnFromDB[ "max"      ] ];
        if( $columnFromFile[ "unique"   ] != $columnFromDB[ "unique"   ] ) $result[ "unique"   ] = [ "file" => $columnFromFile[ "unique"   ] , "db" => $columnFromDB[ "unique"   ] ];
        if( $columnFromFile[ "comment"  ] != $columnFromDB[ "comment"  ] ) $result[ "comment"  ] = [ "file" => $columnFromFile[ "comment"  ] , "db" => $columnFromDB[ "comment"  ] ];
        if( $columnFromFile[ "values"   ] != $columnFromDB[ "values"   ] ) $result[ "values"   ] = [ "file" => $columnFromFile[ "values"   ] , "db" => $columnFromDB[ "values"   ] ];
        if( $columnFromFile[ "unsigned" ] != $columnFromDB[ "unsigned" ] ) $result[ "unsigned" ] = [ "file" => $columnFromFile[ "unsigned" ] , "db" => $columnFromDB[ "unsigned" ] ];

        $result[ "status" ] = count( $result ) > 2 
            ? CheckTables::STATUS_MODIFIED 
            : CheckTables::STATUS_EQUAL;

        return $result;
    }

}