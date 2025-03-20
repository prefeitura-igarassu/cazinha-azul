<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\UsuarioPermissao;
use App\Models\UsuarioLog;

trait Authorization
{
    protected $actionsAllowed = [];
    protected $actions = [];

    public function addAction( $actions ){
        $this->actions = array_merge( $this->actions , $actions );
        return $this;
    }

    public function getActions(){
        return $this->actions;
    }

    // ------------------------ //
    // ------------------------ //
    // ------------------------ //
    
    public function autorizacao( $modulo , $acao )
    {
        $permissoes = $this->hasAutorizacao( $modulo , $acao );

        if( !$permissoes || count( $permissoes ) == 0 ){
            // ----------- Unauthorized
            abort( 401 , "" , [
                "modulo" => $modulo, 
                "acao" => $acao 
            ]);
        }
    }
    
    public function hasAutorizacao( $modulo , $acao ){
        if( property_exists( $this , "actionsAllowed" ) && in_array( $acao , $this->actionsAllowed ) ){
            return true;
        }

        if( auth()->user() == null ){
            return [];
        }

        return UsuarioPermissao::getPermissao( 
            auth()->user()->id , 
            auth()->user()->grupo_id , 
            $modulo , 
            $acao
        );
    }
}

?>