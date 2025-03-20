<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory;
    
    protected $table   = 'jobs';    // nome da tabela
    protected $guarded = [];        // permite 'mass assignment'
}
