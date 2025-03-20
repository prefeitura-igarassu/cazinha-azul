<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\Usuarios\Imagem;
use App\Http\Controllers\Usuarios\Senha;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UsuarioCriadoMail;

class UsuarioController extends BasicController
{
    use Imagem, Senha;

    public $modulo = "usuarios";
    public $optionLabel = "nome";
    
    protected function boot()
    {
        $this->setModel( Usuario::class );

        $this->addAction([
            "insert"  => "Adicionar um usuário.",
            "update"  => "Atualizar um usuário.",
            "get"     => "Retornar um usuário.",
            "destroy" => "Excluir um usuário.",
            "search"  => "Pesquisar pelos usuários.",
        ]);
    }

    public function insert( Request $request )
    {
        $this->autorizacao( $this->modulo , "insert" );

        $senha   = Str::random( 6 );
        $validos = $request->validate( $this->getRegras( $request , 'insert' ) );
        
        $usuario  = $this->model::create( array_merge( $validos , [
            "password" => Hash::make( $validos[ 'password' ] )
        ] ) );

        return $usuario;
    }

    public function destroy( Request $request , Model $model )
    {
        $this->destroy_autorizacao();
        $this->isMeu( $request , $model );

        $model->update([
            "ativo" => false
        ]);
        
        $this->destroy_log( $model );
        $this->event( "destroy" , $model );
        
        return $model;
    }

    public function search_join( Request $request , &$model , $selectedWasDefined = false )
    {
        $model = $model->select( "usuarios.*" );
    }

    public function getSearchWhere()
    {
        return [
            "global" => function ( $query , $key , $value ){
                $value = is_array( $value ) ? array_values( $value )[0] : $value;

                return $query->orWhere( 'usuarios.nome'   , "like" , "%$value%" )
                             ->orWhere( 'usuarios.email'  , "like" , "%$value%" )
                             ->orWhere( 'usuarios.funcao' , "like" , "%$value%" )
                    ;
            },
        ];
    }
        
    public function getRegras( Request $request , $acao = 'insert' )
    {
        $rule = $request->filled( 'id' )
            ? Rule::unique( Usuario::class )->ignore( $request->input( 'id' ) )
            : Rule::unique( Usuario::class );

        $regras = [
            "grupo_id"    => [ 'nullable' , 'integer' ],
            "empresa_id"  => [ 'nullable' , 'integer' ],
            "nome"        => [ 'required' , 'string' , 'max:200' ],
            'email'       => [ 'required' , 'string' , 'email' , 'max:100' , $rule ],
            "endereco"    => [ 'nullable' , 'string' , 'max:200' ],
            "funcao"      => [ 'nullable' , 'string' , 'max:50'  ],
            "ativo"       => [ 'nullable' , 'boolean' ],
        ];

        if( $acao == 'insert' ){
            $regras = array_merge( $regras , [
                "password" => [ 'required' , 'string' , 'min:6' , 'max:200' ],
            ]);
        }

        return $regras;
    }

}
