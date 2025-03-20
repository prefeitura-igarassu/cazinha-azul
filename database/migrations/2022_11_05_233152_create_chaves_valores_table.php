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
        Schema::create( 'chaves_valores' , function (Blueprint $table) {
            $table->id();
            $table->integer( 'usuario_id' )->nullable();
            $table->string( "chave" );
            $table->longText( "valor" )->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index( 'usuario_id' );
            $table->index( 'chave' );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'chaves_valores' );
    }

};
