<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PokePHP\PokeApi;

class PokeController{
  protected $api;

  public function __construct(){
    $this->api = new PokeApi();
  }
  public function test(){
  return response()->json($this->api->pokemon('10'))
    ->header('Access-Control-Allow-Origin', '*')
    ->header('Access-Control-Allow-Methods', 'GET');
  }
}

// <?php

// namespace App\Http\PokeControllers;

// use Illuminate\Routing\Controller;
// use PokePHP\PokeApi;

// abstract class PokeController extends Controller{
//   protected $api;

//   public function __construct(){
//     $this->api = new PokeApi();
//   }
//   public function test(){
//     return response()->json($this->api->pokemon('10'))
//       ->header('Access-Control-Allow-Origin', '*')
//       ->header('Access-Control-Allow-Methods', 'GET');
//   }
// }
