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
        Schema::create( 'fichas_servicos' , function (Blueprint $table) {
            $table->id();
            $table->integer ( 'usuario_id' );
            $table->integer ( 'ficha_id'   );
            $table->integer ( 'servico_id' );
            $table->datetime( 'solicitado_em' );
            $table->integer ( 'status'  )->default( 0 );
            $table->integer ( 'posicao' )->nullable();
            
            $table->integer ( 'terapeuta_id'    )->nullable();
            $table->integer ( 'dia'             )->nullable();
            $table->string  ( 'horario'     , 5 )->nullable();  // 00:00
            $table->date    ( 'periodo_inicial' )->nullable();
            $table->date    ( 'periodo_final'   )->nullable();
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
        Schema::dropIfExists( 'fichas_servicos' );
    }
    
};