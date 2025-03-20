<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\CID;

class CidController extends Controller
{
    
    public function cid( Request $request , $cids )
    {
        $retorno = [];

        foreach( explode( "," , $cids ) as $cid )
        {
            $cid = trim( $cid );
            $cid = $cid ? CID::cid( $cid ) : null;

            if( $cid ) $retorno[] = $cid;
        }

        return $retorno;
    }
    
    public function nome( Request $request , $nome )
    {
        return CID::nome( $nome );
    }
    
}
