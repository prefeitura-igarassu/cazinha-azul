<?php

namespace App\Actions\Fortify;

use App\Models\Usuario;
use App\Models\UsuarioGrupo;
use App\Models\UsuarioPermissao;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): Usuario
    {
        Validator::make($input, [
            'nome' => ['required', 'string', 'max:255'],
            'email' => [ 'required', 'string', 'email', 'max:255', Rule::unique(Usuario::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $usuario = Usuario::create([
            'nome'     => $input['nome'],
            'email'    => $input['email'],
            'grupo_id' => $this->createGrupo()->id,
            'password' => Hash::make( $input['password'] ),
            'telefone' => $input['telefone'] ?? '',
            'endereco' => $input['endereco'] ?? '',
            'funcao'   => $input['funcao']   ?? '',
        ]);

        if( array_key_exists( 'imagem' , $input ) ){
            $base64Image  = explode( ";base64," , $input[ 'imagem' ] );
            $explodeImage = explode( "image/"   , $base64Image[0] );
            $imageType = $explodeImage[1];
            $image = base64_decode( $base64Image[1] );

            Storage::makeDirectory( "usuarios/" );
            Storage::put( "usuarios/{$usuario->id}" , $image );
        }

        return $usuario;
    }

    public function createGrupo(){
        $grupo = UsuarioGrupo::create([
            'nome' => "Administradores",
            'descricao' => "Grupo dos administradores do sistema",
        ]);

        $modulos = [ 
            "usuarios" , 
            "usuarios_permissoes" , 
            "usuarios_grupos" 
        ];

        foreach( $modulos as $modulo ){
            UsuarioPermissao::create([
                "expirar_em" => null,
                "usuario_id" => null,
                "grupo_id" => $grupo->id,
                "modulo" => $modulo,
                "acoes" => [
                    "insert"  => true,
                    "update"  => true,
                    "destroy" => true,
                    "search"  => true,
                    "get"     => true,
                ]
            ]);
        }

        return $grupo;
    }
}
