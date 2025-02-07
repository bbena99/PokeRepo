<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbilityController{
  protected $output;
  protected $limit;
  protected $offset;

  public function __construct(){
    $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
  }

  public function getAll(Request $request){
    $this->limit = $request->query('limit','20');
    $this->offset = $request->query('offset','0');
    $dbAbility = DB::table('abilities')
        ->get();
    return response()->json($dbAbility)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET');
  }
  public function getOne($identifier){
    if(filter_var($identifier, FILTER_VALIDATE_INT))$dbAbility = DB::table('abilities')->where('id','=',$identifier)->get()[0];
    else $dbAbility = DB::table('abilities')->where('name','=',$identifier)->get()[0];
    $dbAbility->pokemon = [];
    $dbAbility->hiddenPokemon = [];

    foreach(DB::table('relation_pokemon_abilities')
              ->where('ability_id','=',$dbAbility->id)
              ->get()
            as $poke){
      $dbPoke = DB::table('pokemon')
          ->where('id','=',$poke->pokemon_id)
          ->get()[0];
      $dbPoke->types = [];
      foreach(DB::table('relation_pokemon_type')
          ->where('pokemon_id','=',$poke->pokemon_id)
          ->select('type_id')
          ->get() as $dbResult){
        array_push($dbPoke->types,$dbResult->type_id);
      }
      if($poke->hidden)array_push($dbAbility->hiddenPokemon,$dbPoke);
      else array_push($dbAbility->pokemon,$dbPoke);

    }

    return response()->json($dbAbility)
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET');
  }
}
