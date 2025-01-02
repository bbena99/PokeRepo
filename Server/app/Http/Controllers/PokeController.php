<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PokePHP\PokeApi;
use Illuminate\Support\Facades\DB;

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
    $dbPokemon = DB::table('pokemon')->where('id','=',4000)->count();
    if($dbPokemon){
      $respon = true;
    }else {
      $respon = false;
    }
    return response()->json($respon);

    foreach($dbPokemon as $pokemon){
      $types = DB::table('relation_pokemon_type')->where('pokemon_id','=',$pokemon->id)->get();
      foreach($types as $type){
        $pokemon->types[$type->type_id] = DB::table('types')->where('id','=',$type->type_id)->get();
      }
      $dbstats = DB::table('relation_pokemon_stat')->where('pokemon_id','=',$pokemon->id)->get();

    }
    return response()->json($dbPokemon);
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
