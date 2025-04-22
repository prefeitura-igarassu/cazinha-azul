<?php

namespace App\Http\Controllers\Usuarios;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\UsuarioPermissao;
use App\Models\UsuarioLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait Imagem
{
    
    public function getImagem( $id )
    {
        if( !Storage::exists("usuarios/$id") )
             abort( 404 );

        return Storage::get( "usuarios/$id" );
    }

    public function upload( Request $request , $id )
    {
        if( method_exists( $this , "autorizacao" ) ){
            $this->autorizacao( $this->modulo , "update" );
        }

        if( (!$request->filled( 'imagem' ) ) ){
            return response( '{ "msg": "o arquivo enviado é inválido!" }' , 400 );
        }
        
        $base64Image  = explode( ";base64," , $request->input( 'imagem' ) );
        $explodeImage = explode( "image/"   , $base64Image[0] );
        $imageType = $explodeImage[1];
        $image = base64_decode( $base64Image[1] );

        Storage::makeDirectory( "usuarios/" );
        Storage::put( "usuarios/{$id}" , $image );

        $this->log( 
            $this->modulo , 
            "update" , 
            $id , 
            "Atualizou a foto de perfil do Usuário Nº {$id}" ,
            []
        );
    }

}