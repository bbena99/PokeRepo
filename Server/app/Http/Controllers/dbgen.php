<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class dbgen
{
  function initDb($key){
    if(env('INITKEY',NULL)!=$key) return response()->json(['error'=>'Unauthorized access'], Response::HTTP_UNAUTHORIZED);
    return response()->json(['message'=>'initDb ok']);
  }
}
