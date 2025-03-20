<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::model( 'unidade'           , \App\Models\Unidade::class        );
        Route::model( 'servico'           , \App\Models\Servico::class        );
        Route::model( 'terapeuta'         , \App\Models\Terapeuta::class      );
        Route::model( 'sessao'            , \App\Models\Sessao::class         );
        Route::model( 'devolutiva'        , \App\Models\Devolutiva::class     );
        Route::model( 'ficha'             , \App\Models\Ficha::class          );
        Route::model( 'fichas_andamentos' , \App\Models\FichaAndamento::class );
        Route::model( 'fichas_servicos'   , \App\Models\FichaServico::class   );
        Route::model( 'fichas_arquivos'   , \App\Models\FichaArquivo::class   );
        
        Route::model( 'chave'      , \App\Models\ChaveValor::class       );
        Route::model( 'usuario'    , \App\Models\Usuario::class          );
        Route::model( 'log'        , \App\Models\UsuarioLog::class       );
        Route::model( 'grupo'      , \App\Models\UsuarioGrupo::class     );
        Route::model( 'permissao'  , \App\Models\UsuarioPermissao::class );
    }

}
