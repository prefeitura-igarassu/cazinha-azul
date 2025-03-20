<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Models\UsuarioPermissao;
use App\Models\UsuarioLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;

trait Image
{
    
    public function getImage( $model )
    {
        $path = Helper::getClassName( $this->model );
        //if( true ) return "{$path}/{$model->id}.png";

        if( !Storage::exists( "{$path}/{$model->id}.png" ) )
             abort( 404 );

        return Storage::get( "$path/{$model->id}.png" );
    }

    public function uploadImage( Request $request , $id )
    {
        if( !$request->filled( "imagem" ) ) return ;

        $base64Image  = explode( ";base64," , $request->input( "imagem" )[ "conteudo" ] );
        $explodeImage = explode( "image/"   , $base64Image[0] );
        $imageType    = $explodeImage[1];
        $image        = base64_decode( $base64Image[1] );

        $path = Helper::getClassName( $this->model );
        Storage::makeDirectory( "{$path}/" );
        Storage::put( "{$path}/{$id}.png" , $image );

        $this->log( 
            $this->modulo , 
            "update" , 
            $id , 
            "Atualizou a foto de perfil Nº {$id}" ,
            []
        );
    }

}