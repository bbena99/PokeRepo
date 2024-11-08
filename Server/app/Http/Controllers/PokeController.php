<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PokePHP\PokeApi;

class PokeController{
  protected $api;
  protected $limit;
  protected $offset;

  public function __construct(){
    $this->api = new PokeApi();
  }
  public function getResource($parse, $identifier){
    $endpoint = $parse."/".$identifier;
    return response()->json($this->api->resourceList($endpoint,null,null))
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET');

  }
  public function getAll(Request $request){
    $this->limit = $request->query('limit','20');
    $this->offset = $request->query('offset','0');
    return response()->json($this->api->resourceList('pokemon',$this->limit,$this->offset))
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET');
  }
  public function getOne($identifier){
    return response()->json($this->api->pokemon($identifier))
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
