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
    $limit = $request->query('limit')??50;
    $offset = $request->query('offset');
    $gen = $request->query('gen');
    $sort = $request->query('sort');

    $dbQueryBuilder = DB::table('pokemon')
      ->join('relation_pokemon_stat', 'pokemon.id', '=', 'relation_pokemon_stat.pokemon_id')
      ->leftJoin(
        DB::raw('(SELECT pokemon_id, MIN(type_id) as type_id FROM relation_pokemon_type GROUP BY pokemon_id) as first_type'),
        'pokemon.id',
        '=',
        'first_type.pokemon_id'
      )
      ->select(
        'pokemon.*',
        'first_type.type_id as type',
        DB::raw('SUM(relation_pokemon_stat.base_stat) as total_stats')
      )
      ->where('is_default','=','1')
      ->groupBy('pokemon.id', 'pokemon.name', 'pokemon.is_default', 'pokemon.order', 'pokemon.front_sprite', 'pokemon.back_sprite','type');

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
    if($gen){
      $gen = preg_split("/\,/",$gen);
      $firstQuery = true;
      foreach($gen as $genVal){
        switch(+$genVal){
          case 1:
            if($firstQuery){
              $dbQueryBuilder->whereBetween('id',[1,151]);
              $firstQuery=false;
            }
            else $dbQueryBuilder->orWhereBetween('id',[1,151]);
            break;
          case 2:
            if($firstQuery){
              $dbQueryBuilder->whereBetween('id',[152,251]);
              $firstQuery=false;
            }
            else $dbQueryBuilder->orWhereBetween('id',[152,251]);
            break;
          case 3:
            if($firstQuery){
              $dbQueryBuilder->whereBetween('id',[252,386]);
              $firstQuery=false;
            }
            else $dbQueryBuilder->orWhereBetween('id',[252,386]);
            break;
          case 4:
            if($firstQuery){
              $dbQueryBuilder->whereBetween('id',[387,493]);
              $firstQuery=false;
            }
            else $dbQueryBuilder->orWhereBetween('id',[387,493]);
            break;
          case 5:
            if($firstQuery){
              $dbQueryBuilder->whereBetween('id',[494,649]);
              $firstQuery=false;
            }
            else $dbQueryBuilder->orWhereBetween('id',[494,649]);
            break;
          case 6:
            if($firstQuery){
              $dbQueryBuilder->whereBetween('id',[650,721]);
              $firstQuery=false;
            }
            else $dbQueryBuilder->orWhereBetween('id',[650,721]);
            break;
          case 7:
            if($firstQuery){
              $dbQueryBuilder->whereBetween('id',[722,809]);
              $firstQuery=false;
            }
            else $dbQueryBuilder->orWhereBetween('id',[722,809]);
            break;
          case 2:
            if($firstQuery){
              $dbQueryBuilder->whereBetween('id',[810,905]);
              $firstQuery=false;
            }
            else $dbQueryBuilder->orWhereBetween('id',[810,905]);
            break;
          case 2:
            if($firstQuery){
              $dbQueryBuilder->whereBetween('id',[906,1025]);
              $firstQuery=false;
            }
            else $dbQueryBuilder->orWhereBetween('id',[906,1025]);
            break;
          default:
            return response('error in gen switch statement',500)
              ->header('Access-Control-Allow-Origin', '*')
              ->header('Access-Control-Allow-Methods', 'GET');
        }
      }
    }
    switch($sort){
      case 1: //By name
        $dbQueryBuilder->orderBy('name');
        break;
      case 2: //By Base Stat
        $dbQueryBuilder->orderBy('total_stats');
        break;
      case 3:
        $dbQueryBuilder->orderBy('type')->orderBy('id');
        break;
    }
    $count = count($dbQueryBuilder->get()->toArray());
    if($offset&&$offset<$count)$dbQueryBuilder->offset($offset);
    $dbQueryBuilder->limit($limit);
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
    $results = [
        'maxPokemon' => $count,
        'pokemonArray' => $dbPokemon,
    ];
    return response()->json($results)
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
