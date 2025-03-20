<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Console\Commands\CheckTables\Describe;
use App\Console\Commands\CheckTables\Compare;

class CheckTables extends Command
{
    use Describe, Compare;

    protected $signature   = "app:check-tables";
    protected $description = "verifica todos os arquivos PHP dentro de database/migrations";

    const TYPES = ["string","text","tinyText","mediumText","longText","integer","numeric","boolean","date","datetime","json","jsonb","set","enum"];
    const STATUS_EQUAL    = 0;
    const STATUS_NEW      = 1;
    const STATUS_MODIFIED = 2;
    const STATUS_RENAMED  = 3;

    public function handle()
    {
        $path = glob( database_path( "migrations/" ) . "*.json" );

        foreach( $path as $file )
        {
            $this->info( "lendo o arquivo a {$file}..." );
            $json = json_decode( file_get_contents( $file ) );

            if( $json->type == "table" )
            {
                $this->check( $json );
            }            
        }
    }

    private function check( $json )
    {
        $tableExists = Schema::hasTable( $json->name );
        $this->info( " verificando a {$json->name} (a tabela existe: {$tableExists})..." );

        if( $tableExists )
        {
            $this->compare( $json , $this->describe( $json ) );
        }

        if( $tableExists ) Schema::table ( $json->name , fn( Blueprint $table ) => $this->checkTable( $table , $json ,  true ) );
        else               Schema::create( $json->name , fn( Blueprint $table ) => $this->checkTable( $table , $json , false ) );
    }

    private function checkTable( Blueprint $table , $json , $tableExists ) {
        if( property_exists( $json , 'columns' ) ){
            $options = [
                "before" => null,
                "name"   => null,
                "values" => [],
                "tableExists"  => $tableExists,
                "columnExists" => null,
            ];

            foreach( $json->columns as $columnName => $columnValues ){
                $options[ "name"   ] = $columnName;
                $options[ "values" ] = $columnValues;
                $options[ "columnExists" ] = $options[ "tableExists" ] 
                    ? Schema::hasColumn( $json->name , $columnName )
                    : false;
                
                $this->createColumn( $table , $options );
                $options[ "before" ] = $columnName;
            }
        }

        if( property_exists( $json , "indexes" ) ) $table->index  ( $json->indexes );
        if( property_exists( $json , "comment" ) ) $table->comment( $json->comment );

        //$table->dropIndex('geo_state_index');
        //$table->dropUnique('users_email_unique');
        //$table->dropPrimary('users_id_primary');

        $this->info( "   salvando..." );
    }

    private function createColumn( Blueprint $table , array $options )
    {
        $this->info( "   verificando a coluna {$options['name']}..." );

        if( $options[ "name" ] == "id" )
        {
            $table->id();
        }
        else if( $options[ "name" ] == "created_at" )
        {
            $table->timestamps();
        }
        else if( $options[ "name" ] == "updated_at" )
        {
            // faz nada, porque já foi adicionado
        }
        else if( $options[ "name" ] == "deleted_at" )
        {
            $table->softDeletes();
        }
        else
        {
            $definition = $this->getColumnDefinition( $options );
            $column     = null;

            switch( $definition[ "type" ] )
            {
                case "string"    : $column = $table->string    ( $definition[ "name" ] , $definition[ "max" ] ); break;
                case "text"      : $column = $table->text      ( $definition[ "name" ] ); break;
                case "tinyText"  : $column = $table->tinyText  ( $definition[ "name" ] ); break;
                case "mediumText": $column = $table->mediumText( $definition[ "name" ] ); break;
                case "longText"  : $column = $table->longText  ( $definition[ "name" ] ); break;
                case "integer"   : $column = $table->integer ( $definition[ "name" ] ); break;
                case "numeric"   : $column = $table->double  ( $definition[ "name" ] ); break;
                case "boolean"   : $column = $table->boolean ( $definition[ "name" ] ); break;
                case "date"      : $column = $table->date    ( $definition[ "name" ] ); break;
                case "datetime"  : $column = $table->dateTime( $definition[ "name" ] ); break;
                case "json"      : $column = $table->json    ( $definition[ "name" ] ); break;
                case "jsonb"     : $column = $table->jsonb   ( $definition[ "name" ] ); break;
                case "set"       : $column = $table->set     ( $definition[ "name" ] , $definition[ "values" ] ); break;
                case "enum"      : $column = $table->enum    ( $definition[ "name" ] , $definition[ "values" ] ); break;
                default          : $column = $table->string  ( $definition[ "name" ] , $definition[ "max"    ] ); break;
            }

            if( $options[ "tableExists" ] && $options[ "before" ] )
                $column->after( $options[ "before" ] );

            if( $definition[ "default"  ] ) $column->default( $definition[ "default" ] );
            if( $definition[ "unsigned" ] ) $column->unsigned();
            if( $definition[ "comment"  ] ) $column->comment( $definition[ "comment" ] );
            if( $definition[ "nullable" ] ) $column->nullable();
            if( $definition[ "unique"   ] ) $column->unique();

            if( $options[ "columnExists" ] ) $column->change();

            // $table->renameColumn('from', 'to');      renomear uma columna
            // $table->dropColumn([ 'votes' ]);         remover uma coluna
        }
    }

    private function getColumnDefinition( array $options )
    {
        $definition = [
            "name"     => $options[ "name" ],
            "type"     => "string",
            "nullable" => false,
            "default"  => null,
            "max"      => null,
            "min"      => null,
            "unique"   => null,
            "comment"  => null,
            "values"   => null,
            "unsigned" => null,
        ];

        foreach( $options["values"] as $value )
        {
                 if( in_array( $value , CheckTables::TYPES ) ) $definition[ "type"     ] = $value;
            else if( $value == "nullable"                    ) $definition[ "nullable" ] = true;
            else if( $value == "unsigned"                    ) $definition[ "unsigned" ] = true;
            else if( $value == "unique"                      ) $definition[ "unique"   ] = true;

            else if( str_starts_with( $value , "max"     ) ) $definition[ "max"     ] = $this->getValue( $value );
            else if( str_starts_with( $value , "min"     ) ) $definition[ "min"     ] = $this->getValue( $value );
            else if( str_starts_with( $value , "comment" ) ) $definition[ "comment" ] = $this->getValue( $value );
            else if( str_starts_with( $value , "default" ) ) $definition[ "default" ] = $this->getValue( $value );
            else if( str_starts_with( $value , "values"  ) ) $definition[ "values"  ] = explode( "," , $this->getValue( $value ) );
        }

        return $definition;
    }

    private function getValue( $value )
    {
        return substr( $value , strpos( $value , ":" ) + 1 );
    }

    

}
