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
        Schema::create( 'servicos' , function (Blueprint $table) {
            $table->id();
            $table->string ( 'nome'      , 100 )->nullable();
            $table->string ( 'descricao' , 200 )->nullable();
            $table->text   ( 'propriedades'    )->nullable();
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
        Schema::dropIfExists( 'servicos' );
    }
};