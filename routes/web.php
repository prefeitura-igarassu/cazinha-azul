<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

//from: https://github.com/barryvdh/laravel-dompdf
//use PDF;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$controllers = [];

Route::get('/', function () {
    return view( 'welcome' );
});

Route::get('/get-csrf-token', function () {
    return csrf_token();
});

Route::get('/login', function () {
    return redirect('/#/login');
})->name( 'login' );

Route::get('/password/resetar', function ( Request $request ) {
    $token = $request->input( "token" );
    $email = $request->input( "email" );

    return redirect( "/#/password/reset?token=$token&email=$email" );
})->name( 'password.reset' );



Route::middleware([ 'auth:sanctum', 'verified' ])->group(function () use ( $controllers ) {
    Route::get('/phpinfo', function () {
        phpinfo();
    });

    Route::get('/system/log', function () {
        return file_get_contents( storage_path( "logs/laravel.log" ) );
    });

    Route::get( "/cid/codigo/{cid}" , [ \App\Http\Controllers\CidController::class , 'cid'  ] );
    Route::get( "/cid/nome/{nome}"  , [ \App\Http\Controllers\CidController::class , 'nome' ] );

    // ----------------------- //
    // ----------------------- //
    // ----------------------- //

    $controllers[] = addRota( "unidades"          , "unidade"           , \App\Http\Controllers\UnidadeController::class        );
	$controllers[] = addRota( "fichas/andamentos" , "fichas_andamentos" , \App\Http\Controllers\FichaAndamentoController::class );
	
    Route::get( "/fichas/servicos/reposicionar"               , [ \App\Http\Controllers\FichaServicoController::class , 'reposicionar'      ] );
    Route::get( "/fichas/servicos/ultima"                     , [ \App\Http\Controllers\FichaServicoController::class , 'getUltimaPosicao'  ] );
    $controllers[] = addRota( "fichas/servicos"   , "fichas_servicos"   , \App\Http\Controllers\FichaServicoController::class   );
	
    Route::get( "/fichas/arquivos/{fichas_arquivos}/download" , [ \App\Http\Controllers\FichaArquivoController::class , 'download' ] );
    $controllers[] = addRota( "fichas/arquivos"   , "fichas_arquivos"   , \App\Http\Controllers\FichaArquivoController::class   );
    
    $controllers[] = addRota( "fichas"            , "ficha"             , \App\Http\Controllers\FichaController::class      );
	$controllers[] = addRota( "sessoes"           , "sessao"            , \App\Http\Controllers\SessaoController::class     );
	$controllers[] = addRota( "servicos"          , "servico"           , \App\Http\Controllers\ServicoController::class    );
	
    Route::get( "/terapeutas/{terapeuta}/horarios"                      , [ \App\Http\Controllers\TerapeutaController::class , 'horarios'  ] );
    $controllers[] = addRota( "terapeutas"        , "terapeuta"         , \App\Http\Controllers\TerapeutaController::class  );

	$controllers[] = addRota( "devolutivas"       , "devolutiva"        , \App\Http\Controllers\DevolutivaController::class );
	
    // ----------------------- //
    // ----------------------- //
    // ----------------------- //

    $controllers[] = addRota( "chaves"        , "chave"         , \App\Http\Controllers\ChaveValorController::class   );
	$controllers[] = addRota( "permissoes"    , "permissao"     , \App\Http\Controllers\UsuarioPermissaoController::class );
	$controllers[] = addRota( "logs"          , "log"           , \App\Http\Controllers\UsuarioLogController::class   );
	$controllers[] = addRota( "grupos"        , "grupo"         , \App\Http\Controllers\UsuarioGrupoController::class );

    Route::get( "/usuarios/options"      , [ \App\Http\Controllers\UsuarioController::class , 'getOptions' ] );
    Route::get( "/usuarios/{id}/imagem"  , [ \App\Http\Controllers\UsuarioController::class , 'getImagem' ] );
    Route::post( "/usuarios/{id}/imagem" , [ \App\Http\Controllers\UsuarioController::class , 'upload'    ] );
    Route::post( "/usuarios/{usuario}/alterar_senha" , [ \App\Http\Controllers\UsuarioController::class , 'alterarSenha' ] );
	$controllers[] = addRota( "usuarios" , "usuario" , \App\Http\Controllers\UsuarioController::class );

    Route::get( "/usuario-atual" , function (){
        if( !Auth::user() ) abort( 404 );
        return Auth::user();
    } );

    Route::get( "/actions" , function () use ( $controllers ){
        $actions = [];

        foreach ($controllers as $value) {
            $controller = new $value();

            $actions = array_merge( $actions , [
                $controller->modulo => $controller->getActions()
            ]);
        }

        return $actions;
    });
});







// ------------------------------------- //
// ------------------------------------- // funções auxiliares
// ------------------------------------- //

function addRota( $plural , $singular , $controller ){
    Route::get( "/{$plural}/regras" , [ $controller , 'getRegras' ] )
        ->name( "{$plural}.regras" );

    Route::get( "/{$plural}/dashboard/resume" , [ $controller , 'dash_resume' ] )
        ->name( "{$plural}.dashboard.resume" );

    Route::get( "/{$plural}/dashboard/group" , [ $controller , 'dash_group' ] )
        ->name( "{$plural}.dashboard.group" );

    // --- //

    Route::get( "/{$plural}" , [ $controller , 'search' ] )
        ->name( "{$plural}.search" );

    Route::post( "/{$plural}" , [ $controller , 'insert' ] )
        ->name( "{$plural}.insert" );

    Route::get( "/{$plural}/export/html" , [ $controller , 'export_to_html' ] )
        ->name( "$plural.export.html" );

    Route::get( "/{$plural}/export/pdf" , [ $controller , 'export_to_pdf' ] )
        ->name( "$plural.export.pdf" );

    Route::get( "/{$plural}/export/csv" , [ $controller , 'export_to_csv' ] )
        ->name( "$plural.export.csv" );

    Route::get( "/{$plural}/export/xls" , [ $controller , 'export_to_xls' ] )
        ->name( "$plural.export.xls" );

    Route::get( "/{$plural}/export/tabular" , [ $controller , 'export_to_tabular' ] )
        ->name( "$plural.export.tabular" );

    Route::get( "/{$plural}/options" , [ $controller , 'getOptions' ] )
        ->name( "$plural.options" );

    Route::delete( "/{$plural}/{{$singular}}" , [ $controller , 'destroy' ] )
        ->name( "{$plural}.destroy" );

    Route::get( "/{$plural}/{{$singular}}/imprimir" , [ $controller , 'imprimir' ] )
        ->name( "{$plural}.imprimir" );

    Route::get( "/{$plural}/{{$singular}}.png" , [ $controller , 'getImage' ] )
        ->name( "{$plural}.image" );

    Route::get( "/{$plural}/{{$singular}}" , [ $controller , 'get' ] )
        ->name( "{$plural}.get" );

    Route::put( "/{$plural}/{{$singular}}" , [ $controller , 'update' ] )
        ->name( "$plural.update" );

    return $controller;
}

function addRotaFromParent( $parentes , $plural , $controller )
{
    Route::get( "/{$parentes}/{id}/{$plural}" , [ $controller , 'list' ] )
        ->name( "$plural.list" );
}
