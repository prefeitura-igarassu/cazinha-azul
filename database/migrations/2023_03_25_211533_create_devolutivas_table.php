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
        Schema::create( 'devolutivas' , function (Blueprint $table) {
            $table->id();
            $table->integer ( 'usuario_id'   );
            $table->integer ( 'ficha_id'     );
            $table->integer ( 'servico_id'   );
            $table->integer ( 'terapeuta_id' );
            $table->datetime( 'feito_em'     );
            $table->text    ( 'pontuacao'    )->nullable();
            $table->text    ( 'evolucao'     )->nullable();
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
        Schema::dropIfExists( 'devolutivas' );
    }
};