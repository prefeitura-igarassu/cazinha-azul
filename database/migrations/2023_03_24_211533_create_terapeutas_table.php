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
        Schema::create( 'terapeutas' , function (Blueprint $table) {
            $table->id();
            $table->integer( 'unidade_id' );
            $table->integer( 'servico_id' );
            $table->integer( 'usuario_id' )->nullable();
            $table->string ( 'nome' , 100 )->nullable();
            $table->text   ( 'horarios'   )->nullable();
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
        Schema::dropIfExists( 'terapeutas' );
    }
    
};