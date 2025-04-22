<?php

namespace App\Http\Controllers\Usuarios;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\UsuarioPermissao;
use App\Models\UsuarioLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


trait Senha
{
    
    public function alterarSenha( Request $request , $model ){
        if( method_exists( $this , "autorizacao" ) ){
            $this->autorizacao( $this->modulo , "update" );
        }

        Validator::make( $request->input() , [
            'password' => [ 'required' , 'string' , 'min:6' , 'max:200' , 'confirmed' ],
        ]);

        $model->forceFill([
            'password' => Hash::make( $request->input( 'password' ) ),
        ])->save();

        $this->log( 
            $this->modulo , 
            "update" , 
            $model->id , 
            "Atualizou a senha do Usuário Nº {$model->id}" ,
            []
        );
    }

}