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
        Schema::create( 'fichas_andamentos' , function (Blueprint $table) {
            $table->id();
            $table->integer ( 'usuario_id'  );
            $table->integer ( 'ficha_id'    );
            $table->integer ( 'status'      )->default( 0 );
            $table->datetime( 'ocorrido_em' );
            $table->string  ( 'descricao' , 200 );
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
        Schema::dropIfExists( 'fichas_andamentos' );
    }
    
};