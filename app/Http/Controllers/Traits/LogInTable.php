<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\UsuarioLog;

trait LogInTable
{
    
    public function log( $modulo , $acao , $id , $mensagem , $parametros = [] ){
        //from: https://stackoverflow.com/questions/33268683/how-to-get-client-ip-address-in-laravel-5
        UsuarioLog::create([
            "usuario_id" => auth()->user() ? auth()->user()->id : 0 ,
            "permissao_id" => 0,
            "ip" => request()->ip(),
            "externo_id" => $id,
            "modulo" => $modulo,
            "acao" => $acao,
            "descricao" => $mensagem,
            "parametros" => is_array( $parametros ) ? $parametros : (array) $parametros
        ]);
    }

}

?>