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
        Schema::create( 'sessoes' , function (Blueprint $table) {
            $table->id();
            $table->integer ( 'usuario_id'    );
            $table->integer ( 'ficha_id'      );
            $table->integer ( 'servico_id'    );
            $table->integer ( 'terapeuta_id'  );
            $table->datetime( 'agendado_para' );
            $table->integer ( 'status'        )->default( 0 );
            $table->string  ( 'observacao' , 200 )->nullable();
            $table->text    ( 'evolucao'         )->nullable();
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
        Schema::dropIfExists( 'sessoes' );
    }
};