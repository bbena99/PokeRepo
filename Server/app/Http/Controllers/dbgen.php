<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pokemon;
use App\Models\PokeMove;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use PokePHP\PokeApi;

class dbgen
{
  protected $api;

  public function __construct(){
    $this->api = new PokeApi();
  }

  public function initDb($key){
    set_time_limit(5000000);
    if(env('INITKEY',NULL)!=$key) return response()->json(['error'=>'Unauthorized access'], Response::HTTP_UNAUTHORIZED);
    $pokemonNamesArray = json_decode($this->api->resourceList('pokemon',1,0));
    foreach($pokemonNamesArray->results as $pokemonStdPair){
      $pokemonJSON = json_decode(Http::get($pokemonStdPair->url));
      $pokemon = new Pokemon();
      $pokemon->setId($pokemonJSON->id)
              ->setName($pokemonJSON->name)
              ->setIsDefault($pokemonJSON->is_default)
              ->setOrder($pokemonJSON->order)
              ->setFrontSprite($pokemonJSON->sprites->front_default)
              ->setBackSprite($pokemonJSON->sprites->back_default);
      // DB::insert('insert into pokemon (id, name, is_default, order, front_sprite, back_sprite)',[
      //   $pokemon->id,
      //   $pokemon->name,
      //   $pokemon->is_default,
      //   $pokemon->order,
      //   $pokemon->sprites->front_default,
      //   $pokemon->sprites->back_default,
      // ]);
      foreach($pokemonJSON->abilities as $index => $abilityJSON){
        $id = substr_replace($abilityJSON->ability->url,'',-1);
        $id = substr($id,strrpos($id,'/'));
        $id = substr($id,1);
        $ability = [
          'ability_id' => $id,
          'name' => $abilityJSON->ability->name,
          'is_hidden' => $abilityJSON->is_hidden??0
        ];
        $pokemon->setSingleAbility($ability,$index);
      }
      foreach($pokemonJSON->types as $index => $typeJSON){
        $id = substr_replace($typeJSON->type->url,'',-1);
        $id = substr($id,strrpos($id,'/'));
        $id = substr($id,1);
        $type = [
          'type_id'=>$id,
          'name'=>$typeJSON->type->name
        ];
        $pokemon->setSingleType($type,$index);
      }
      foreach($pokemonJSON->moves as $move){
        //$this->out->writeln($move->move->name."\tlevel:".$move->version_group_details[0]->level_learned_at."\tMethod:".$move->version_group_details[0]->move_learn_method->name);
      }
      $base_stat_total=0;
      foreach($pokemonJSON->stats as $stat){
        $base_stat_total+=$stat->base_stat;
        $pokemon->setSingleStat($stat->base_stat,$stat->stat->name);
        // DB::insert('insert into relation_pokemon_stat (pokemon_id, stat_name, base_stat)',[
        //   $pokemon->id,
        //   $stat->stat->name,
        //   $stat->base_stat,
        // ]);
      }
      $pokemon->setSingleStat($base_stat_total,'total');
      $pokemon->minimalPrint();
    }
    /** Start of abilities */
    $moveNamesArray = json_decode($this->api->resourceList('move',1,0));
    foreach($moveNamesArray->results as $moveStdPair){
      $moveJSON = json_decode(Http::get($moveStdPair->url));
      $move = new PokeMove();
      $move ->setId($moveJSON->id)
            ->setName($moveJSON->name)
            ->setDamageType($this->parseIdentifier($moveJSON->damage_class->url))
            ->setAccuracy($moveJSON->accuracy)
            ->setPower($moveJSON->power)
            ->setPP($moveJSON->pp)
            ->setPriority($moveJSON->priority)
            ->setEffectChance($moveJSON->effect_chance)
            ->setEffectEntry($moveJSON->effect_entries[0]->effect)
            ->setMeta($moveJSON->meta);
      $move->debugPrint();
    }
    return response('Job Done');
    //return response()->json(['message'=>'initDb ok']);
  }
  private function parseIdentifier($url):string{
    $ret = substr_replace($url,'',-1);
    $ret = substr($ret,strrpos($ret,'/'));
    $ret = substr($ret,1);
    return $ret;
  }
}
