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
    $dbPokemon = DB::table('pokemon')->get();
    foreach($dbPokemon as $pokemon){
      $types = DB::table('relation_pokemon_type')->where('pokemon_id','=',$pokemon->id)->get();
      foreach($types as $type){
        $dbtype = DB::table('types')->where('id','=',$type->type_id)->get();
        unset($dbtype["id"]);
        $pokemon->types[(int)$type->type_id]=$dbtype;
      }
      $dbstats = DB::table('relation_pokemon_stat')->where('pokemon_id','=',$pokemon->id)->get();
      foreach($dbstats as $dbstat){
        $pokemon->stats[$dbstat->stat_name]=$dbstat->base_stat;
      }
    }
    return response()->json($dbPokemon)
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET');
  }
  public function getOne($identifier){
    $poke = DB::table('pokemon')->where('id','=',$identifier)->get()[0];
    $types = DB::table('relation_pokemon_type')->where('pokemon_id','=',$poke->id)->get();
    foreach($types as $type){
      $dbtype = DB::table('types')->where('id','=',$type->type_id)->get();
      unset($dbtype["id"]);
      $poke->types[(int)$type->type_id]=$dbtype;
    }
    $dbstats = DB::table('relation_pokemon_stat')->where('pokemon_id','=',$poke->id)->get();
    foreach($dbstats as $dbstat){
      $poke->stats[$dbstat->stat_name]=$dbstat->base_stat;
    }
    return response()->json($poke)
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
