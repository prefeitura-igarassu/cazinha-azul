<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FailedJob extends Model
{
    use HasFactory;
    
    protected $table   = 'failed_jobs';    // nome da tabela
    protected $guarded = [];               // permite 'mass assignment'
}
