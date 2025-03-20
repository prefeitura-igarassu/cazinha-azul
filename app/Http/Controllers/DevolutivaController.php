<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devolutiva;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DevolutivaController extends BasicController
{
    public $modulo = "Devolutivas";
    
    protected function boot()
    {
        $this->setModel( Devolutiva::class );

        $this->addAction([
            "insert"   => "Adicionar uma devolutiva.",
            "update"   => "Atualizar uma devolutiva.",
            "get"      => "Retornar uma devolutiva.",
            "destroy"  => "Excluir uma devolutiva.",
            "search"   => "Pesquisar pelas devolutivas.",
        ]);
    }

    public function getRegras( Request $request , $acao = 'insert' )
    {
        return [
            'usuario_id'    => [ 'required' , 'integer' ],
            'ficha_id'      => [ 'required' , 'integer' ],
            'servico_id'    => [ 'required' , 'integer' ],
            'terapeuta_id'  => [ 'required' , 'integer' ],
            'feito_em'      => [ 'required' , 'date'    ],
            'pontuacao'     => [ 'nullable' , 'array'   ],
            'evolucao'      => [ 'nullable' , 'string'  ],
        ];
    }

}
