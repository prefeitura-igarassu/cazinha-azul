<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Traits\Search;
use App\Http\Controllers\Traits\Insert;
use App\Http\Controllers\Traits\Update;
use App\Http\Controllers\Traits\Destroy;
use App\Http\Controllers\Traits\Get;
use App\Http\Controllers\Traits\Authorization;
use App\Http\Controllers\Traits\LogInTable;
use App\Http\Controllers\Traits\Image;

use App\Http\Controllers\Traits\DashboardResume;
use App\Http\Controllers\Traits\DashboardGroup;
use App\Http\Controllers\Traits\GetOptions;
use App\Http\Controllers\Traits\Printer;

class BasicController extends Controller
{
    use Authorization, Search, Insert, Update, Destroy, Get, LogInTable;
    use DashboardResume, DashboardGroup, GetOptions, Printer, Image;

    protected $regras = [];
    protected $model  = null;

    public function __construct()
    {
        $this->boot();
    }

    protected function boot()
    {
        // faz nada
    }

    protected function setModel( $modelClass )
    {
        $this->model = new $modelClass();
        return $this;
    }
    
    protected function getRegras( Request $request , $acao )
    {
        return [];
    }
    
}
