<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PokePHP\PokeApi;
use Illuminate\Support\Facades\DB;

class PokeController{
  protected $output;
  protected $limit;
  protected $offset;

  public function __construct(){
    $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();//Using the console to keep track of the progress of the insertions
  }
  public function getAll(Request $request){
    $this->limit = $request->query('limit','20');
    $this->offset = $request->query('offset','0');
    $dbPokemon = DB::table('pokemon')->get();
    foreach($dbPokemon as $pokemon){
      $dbtypes = DB::table('relation_pokemon_type')
      ->where('pokemon_id','=',$pokemon->id)
      ->join('types','relation_pokemon_type.type_id','=','types.id')
      ->get();
    foreach($dbtypes as $type){
      unset($type->pokemon_id);
      unset($type->type_id);
    }
    $pokemon->types = $dbtypes;

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
    if(filter_var($identifier, FILTER_VALIDATE_INT))$poke = DB::table('pokemon')->where('id','=',$identifier)->get()[0];
    else $poke = DB::table('pokemon')->where('name','=',$identifier)->get()[0];

    $dbtypes = DB::table('relation_pokemon_type')
      ->where('pokemon_id','=',$poke->id)
      ->join('types','relation_pokemon_type.type_id','=','types.id')
      ->get();
    foreach($dbtypes as $type){
      unset($type->pokemon_id);
      unset($type->type_id);
    }
    $poke->types = $dbtypes;

    $dbstats = DB::table('relation_pokemon_stat')->where('pokemon_id','=',$poke->id)->get();
    foreach($dbstats as $dbstat){
      $poke->stats[$dbstat->stat_name]=$dbstat->base_stat;
    }

    $dbabilities = DB::table('relation_pokemon_abilities')
      ->where('pokemon_id','=',$poke->id)
      ->join('abilities','relation_pokemon_abilities.ability_id','=','abilities.id')
      ->get();
    foreach($dbabilities as $ability){
      unset($ability->pokemon_id);
      unset($ability->ability_id);
    }
    $poke->abilities = $dbabilities;

    return response()->json($poke)
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET');
  }
}
