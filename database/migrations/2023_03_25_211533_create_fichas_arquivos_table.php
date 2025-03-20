<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( "fichas_arquivos" , function (Blueprint $table) {
            $table->id();
            $table->integer ( 'usuario_id' );
            $table->integer ( 'ficha_id'   );
            $table->datetime( 'anexado_em' );
            $table->string  ( 'titulo'    , 100 );
            $table->string  ( 'descricao' , 200 );

            // dados do arquivo
            $table->string  ( 'nome'      , 100 );
            $table->string  ( 'tipo'      , 100 );
            $table->integer ( 'tamanho'    );
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( "fichas_arquivos" );
    }
    
};