<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PokeController{
  protected $output;

  public function __construct(){
    $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();//Using the console to keep track of the progress of the insertions
  }
  public function getAll(Request $request){
    $name = $request->query('name');
    $type = $request->query('type');
    $notType = $request->query('notType');
    $limit = $request->query('limit');
    $offset = $request->query('offset');

    $dbQueryBuilder = DB::table('pokemon')->where('is_default','=','1');
    if($name)$dbQueryBuilder->where('name','like',"%".$name."%");
    if($type){
      $type = preg_split("/\,/",$type);
      $dbQueryBuilder->whereIn('id',DB::table('relation_pokemon_type')
        ->whereIn('type_id',$type)
        ->distinct()
        ->pluck('pokemon_id'));
    }
    if($notType){
      $notType = preg_split("/\,/",$notType);
      $dbQueryBuilder->whereNotIn('id',DB::table('relation_pokemon_type')
        ->whereIn('type_id',$notType)
        ->distinct()
        ->pluck('pokemon_id'));
    }
    if($limit)$dbQueryBuilder->limit($limit);
    if($offset)$dbQueryBuilder->offset($offset);
    $dbPokemon = $dbQueryBuilder->get();

    foreach($dbPokemon as $pokemon){
      $pokemon->types = DB::table('relation_pokemon_type')
        ->where('pokemon_id','=',$pokemon->id)
        ->join('types','relation_pokemon_type.type_id','=','types.id')
        ->select('types.*')
        ->get();

      $dbStats = DB::table('relation_pokemon_stat')->where('pokemon_id','=',$pokemon->id)->get();
      foreach($dbStats as $dbstat){
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

    $poke->types = DB::table('relation_pokemon_type')
      ->where('pokemon_id','=',$poke->id)
      ->join('types','relation_pokemon_type.type_id','=','types.id')
      ->select('types.*')
      ->get();
    foreach($poke->types as $type){
      $dbRelations = DB::table('relation_damage')
        ->where('receiver_id','=',$type->id)
        ->get();
      foreach($dbRelations as $relation){
        $type->relations[$relation->dealer_id] = $relation->damageable;
      }
    }
    $dbStats = DB::table('relation_pokemon_stat')->where('pokemon_id','=',$poke->id)->get();
    foreach($dbStats as $dbstat){
      $poke->stats[$dbstat->stat_name]=$dbstat->base_stat;
    }

    $poke->abilities = DB::table('relation_pokemon_abilities')
      ->where('pokemon_id','=',$poke->id)
      ->join('abilities','relation_pokemon_abilities.ability_id','=','abilities.id')
      ->select('abilities.*','relation_pokemon_abilities.hidden')
      ->get();

    $poke->moves = DB::table('relation_pokemon_moves')
      ->where('pokemon_id','=',$poke->id)
      ->join('moves','relation_pokemon_moves.move_id','=','moves.id')
      ->select('moves.name','moves.id','moves.damage_type','moves.accuracy','moves.power','moves.pp','relation_pokemon_moves.level','moves.effect_entry')
      ->orderBy('level','desc')
      ->get();

    return response()->json($poke)
      ->header('Access-Control-Allow-Origin', '*')
      ->header('Access-Control-Allow-Methods', 'GET');
  }
}
