<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servico extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table   = 'servicos';       // nome da tabela
    protected $guarded = [];               // permite 'mass assignment'
    protected $casts = [
        "propriedades" => "array"
    ];
}
