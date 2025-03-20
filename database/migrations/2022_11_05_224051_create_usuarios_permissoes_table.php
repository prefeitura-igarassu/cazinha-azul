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
        Schema::create( 'usuarios_permissoes' , function ( Blueprint $table ){
            $table->id();
            $table->integer( 'usuario_id' )->nullable();
            $table->integer( 'grupo_id'   )->nullable();
            $table->string( 'modulo' );
            $table->json( 'acoes' );
            $table->datetime( "expirar_em" )->nullable();
            $table->timestamps();
            $table->softDeletes();

            // add index
            $table->index( 'usuario_id' );
            $table->index( 'grupo_id' );
            $table->index( 'modulo' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'usuarios_permissoes' );
    }
};
