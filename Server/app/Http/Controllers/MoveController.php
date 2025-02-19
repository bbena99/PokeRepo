<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoveController{
  protected $output;
  protected $limit;
  protected $offset;

  public function __construct(){
    $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
  }

  public function getAll(Request $request){
    $this->limit = $request->query('limit','20');
    $this->offset = $request->query('offset','0');
    $dbMove = DB::table('moves')->get();
    return response()->json($dbMove)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET');
  }
  public function getOne($identifier){
    if(filter_var($identifier, FILTER_VALIDATE_INT))$dbMove = DB::table('moves')->where('id','=',$identifier)->get()[0];
    else $dbMove = DB::table('moves')->where('name','=',$identifier)->get()[0];
    $dbMove->pokemon = [];
    foreach( DB::table('relation_pokemon_moves')
          ->where('move_id','=',$dbMove->id)
          ->join('pokemon','relation_pokemon_moves.pokemon_id','=','pokemon.id')
          ->get()
        as $dbPokemon
    ){
      $types = [];
      foreach( DB::table('relation_pokemon_type')->where('pokemon_id','=',$dbPokemon->id)->get() as $dbType ){

      }
    }
    return response()->json($dbMove)
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET');
  }
}
