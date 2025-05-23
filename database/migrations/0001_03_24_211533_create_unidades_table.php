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
        Schema::create( 'unidades' , function (Blueprint $table) {
            $table->id();
            $table->string( 'nome' 	    , 100 )->nullable();
            $table->string( 'endereco'  , 100 )->nullable();
            $table->string( 'telefone'  ,  20 )->nullable();
            $table->string( 'observacao', 100 )->nullable();
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
        Schema::dropIfExists( 'unidades' );
    }
    
};