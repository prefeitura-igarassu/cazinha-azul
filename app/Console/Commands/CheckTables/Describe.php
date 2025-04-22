<?php

namespace App\Console\Commands\CheckTables;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait Describe
{

    public function describe( $json )
    {
        $table = [
            "name" => $json->name,
            "columns" => [],
            "indexes" => [],
            "comment" => null,
        ];

        $result = DB::select( "SHOW FULL COLUMNS FROM {$json->name};" );
        
        foreach( $result as $column )
        {
            /*
            [Field] => id
            [Type] => bigint(20) unsigned
            [Null] => NO
            [Key] => PRI
            [Default] => 
            [Extra] => auto_increment
            */
            
            $typeArr = explode( " " , $column->Type );
            $keyArr  = explode( " " , $column->Key  );

            $typeIndexOf = strpos( $typeArr[0] , "(" );

            $table[ "columns" ][] = [
                "name"     => $column->Field,
                "nullable" => $column->Null == "YES",
                "default"  => $column->Default,
                "extra"    => $column->Extra,
                "unsigned" => in_array( "unsigned" , $typeArr ),
                "type"     => $this->getDescribeType   ( $typeArr[0] , $typeIndexOf ),
                "max"      => $this->getDescribeMax    ( $typeArr[0] , $typeIndexOf ),
                "values"   => $this->getDescribeOptions( $typeArr[0] , $typeIndexOf ),
                "min"      => null,
                "primary"  => in_array( "PRI" , $keyArr ),
                "unique"   => in_array( "UNI" , $keyArr ),
                "comment"  => $column->Comment,
            ];
        }

        // ----- get indexes
        $indexes = DB::select( "SHOW INDEXES FROM {$json->name};" );
        foreach( $indexes as $index ) $table["indexes"][] = $index->Column_name;
        $table["indexes"] = array_unique( $table["indexes"] );

        $comments = DB::select( 
            "SELECT table_comment FROM INFORMATION_SCHEMA.TABLES WHERE table_schema = ? AND table_name = ?" , 
            [ $json->schema , $json->name ] 
        );

        $table[ "comment" ] = count( $comments ) > 0 ? $comments[0]->table_comment : null;

        return $table;
    }

    private function getDescribeType( $type , $indexOf )
    {
        if( $type == "tinyint(1)" ) return "boolean";

        $type = $indexOf ? substr( $type , 0 , $indexOf ) : $type;

        if( $type == "varchar" ) return "string";
        if( $type == "int"     ) return "integer";
        if( $type == "double"  ) return "numeric";
        else                     return $type;
    }

    private function getDescribeMax( $type , $indexOf )
    {
        if( !str_starts_with( $type , "varchar" ) ) return null;

        return substr( $type , $indexOf + 1 , strlen( $type ) - $indexOf - 2 );
    }

    private function getDescribeOptions( $type , $indexOf )
    {
        if( !str_starts_with( $type , "set" ) && !str_starts_with( $type , "enum" ) ) return null;

        return array_map( 
            fn ( $el ) => trim( str_replace( "'" , " " , $el ) ) , 
            explode( "','" , substr( $type , $indexOf + 1 , strlen( $type ) - $indexOf - 2 ) ) 
        );
    }

}