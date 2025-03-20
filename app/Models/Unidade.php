<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unidade extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table   = 'unidades';   // nome da tabela
    protected $guarded = [];           // permite 'mass assignment'
    
}
