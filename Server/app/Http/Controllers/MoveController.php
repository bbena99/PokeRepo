<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MoveController{
  protected $output;

  public function __construct(){
    $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
  }

  public function getAll(Request $request){
    $name = $request->query('name');
    $type = $request->query('type');
    $notType = $request->query('notType');
    $limit = $request->query('limit')??50;
    $offset = $request->query('offset');
    $damageType = $request->query('damageType');
    $sort = $request->query('sort');

    $dbQueryBuilder = DB::table('moves')
      ->select('moves.*','relation_type_moves.type_id as type')
      ->join('relation_type_moves','moves.id','=','relation_type_moves.move_id');

    if($name)$dbQueryBuilder->where('name','like',"%".$name."%");
    if($type){
      $type = preg_split("/\,/",$type);
      $dbQueryBuilder->whereIn('type',$type);
    }
    if($notType){
      $notType = preg_split("/\,/",$notType);
      $dbQueryBuilder->whereNotIn('type',$notType);
    }
    if($damageType){
      $damageType = preg_split("/\,/",$damageType);
      $dbQueryBuilder->whereIn('damage_type',$damageType);
    }
    $count = count($dbQueryBuilder->get()->toArray());
    switch($sort){
      case 1: //By name
        $dbQueryBuilder->orderBy('name');
        break;
      case 2: //By Base Stat
        $dbQueryBuilder->orderBy('damage_type');
        break;
      case 3:
        $dbQueryBuilder->orderBy('type')->orderBy('id');
        break;
    }
    if($offset)$dbQueryBuilder->offset($offset);
    $dbQueryBuilder->limit($limit);
    $dbMove = $dbQueryBuilder->get();
    $results = [
      'maxMoves' => $count,
      'movesArray' => $dbMove,
    ];
    return response()->json($results)
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET');
  }
  public function getOne($identifier){
    if(filter_var($identifier, FILTER_VALIDATE_INT))$dbMove = DB::table('moves')->where('id','=',$identifier)->join('relation_type_moves','moves.id','=','relation_type_moves.move_id')->get()[0];
    else $dbMove = DB::table('moves')->where('name','=',$identifier)->join('relation_type_moves','moves.id','=','relation_type_moves.move_id')->get()[0];
    unset($dbMove->move_id);
    $dbMove->pokemon = [];
    foreach( DB::table('relation_pokemon_moves')
          ->where('move_id','=',$dbMove->id)
          ->join('pokemon','relation_pokemon_moves.pokemon_id','=','pokemon.id')
          ->get()
        as $dbPokemon
    ){
      $types = [];
      foreach( DB::table('relation_pokemon_type')->where('pokemon_id','=',$dbPokemon->id)->get() as $dbType ){
        array_push($types,$dbType->type_id);
      }
      $dbPokemon->types = $types;
      unset($dbPokemon->pokemon_id,$dbPokemon->move_id);
      array_push($dbMove->pokemon,$dbPokemon);
    }
    return response()->json($dbMove)
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET');
  }
}
