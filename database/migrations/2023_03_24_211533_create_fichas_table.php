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
        Schema::create( 'fichas' , function (Blueprint $table) {
            $table->id();
            $table->integer( 'unidade_id'     );
            $table->datetime( 'cadastrado_em' );
            $table->string ( 'cpf'         ,  20 )->nullable();
            $table->string ( 'nome'        , 100 )->nullable();
            $table->date   ( 'nascido_em'        )->nullable();
            $table->string ( 'sus'         , 30  )->nullable();
            $table->string ( 'nis' 	       , 30  )->nullable();
            $table->string ( 'mae_nome'    , 100 )->nullable();
            $table->string ( 'pai_nome'    , 100 )->nullable();
            $table->string ( 'responsavel' , 100 )->nullable();
            $table->string ( 'escola'      , 100 )->nullable();
            $table->string ( 'endereco'    , 100 )->nullable();
            $table->string ( 'posto_saude' , 100 )->nullable();
            $table->string ( 'telefone'    , 20  )->nullable();
            $table->string ( 'email' 	   , 50  )->nullable();
            $table->text   ( 'cid'               )->nullable();
            $table->text   ( 'observacao' )->nullable();
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
        Schema::dropIfExists( 'fichas' );
    }
};