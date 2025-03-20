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
        Schema::create( 'usuarios_logs' , function (Blueprint $table) {
            $table->id();
            $table->integer( "cartorio_id"  )->nullable();
            $table->integer( "usuario_id"   )->nullable();
            $table->integer( "permissao_id" )->nullable();
            $table->longText( "externo_id"  )->nullable();
            $table->text( "ip" );
            $table->string( "modulo" );
            $table->string( "acao" );
            $table->longText( "descricao" );
            $table->json( "parametros" )->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index( 'usuario_id' );
            $table->index( 'permissao_id' );
            $table->index( 'created_at' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'usuarios_logs' );
    }

};
