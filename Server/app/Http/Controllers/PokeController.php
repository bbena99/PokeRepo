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
    $nameArray = json_decode($this->api->resourceList('pokemon',$this->limit,$this->offset),true);
    $pokeArray=[];
    foreach( $nameArray['results'] as $stdPair) {
      $poke = json_decode($this->api->pokemon($stdPair['name']),true);
      $pokeArray[$poke['id']] = json_encode($poke);
    }
    return response()->json($pokeArray,200,[],JSON_PRETTY_PRINT)
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET');
  }
  public function getOne($identifier){
    $poke = json_decode($this->api->pokemon($identifier),true);
    return response($poke,200,[]);
      // ->header('Access-Control-Allow-Origin', '*')
      // ->header('Access-Control-Allow-Methods', 'GET');
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
