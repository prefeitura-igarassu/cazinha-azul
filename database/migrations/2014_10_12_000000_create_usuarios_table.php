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
        Schema::create( 'usuarios' , function (Blueprint $table) {
            $table->id();
            $table->integer( 'grupo_id'   )->nullable();
            $table->integer( 'unidade_id' )->nullable();
            $table->string( 'nome' );
            $table->string( 'funcao' , 50 )->nullable();
            $table->string( 'email' )->unique();
            $table->timestamp( 'email_verified_at' )->nullable();
            $table->string( 'password' );
            $table->rememberToken();
            $table->dateTime( 'ultimo_acesso_em' )->nullable();
            $table->boolean( 'ativo'       )->default( true  );
            $table->timestamps();
            $table->softDeletes();
            
            $table->index( 'grupo_id' );
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'usuarios' );
        Schema::dropIfExists( 'password_reset_tokens' );
        Schema::dropIfExists( 'sessions' );
    }

};
