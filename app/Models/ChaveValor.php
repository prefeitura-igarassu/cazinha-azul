<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChaveValor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "chaves_valores";
    protected $guarded = [];
}
