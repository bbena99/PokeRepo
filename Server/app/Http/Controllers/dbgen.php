<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pokemon;
use App\Models\PokeMove;
use App\Models\PokeType;
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
    $pokemonArray =[];
    foreach($pokemonNamesArray->results as $pokemonStdPair){
      $pokemonJSON = json_decode(Http::get($pokemonStdPair->url));
      $pokemon = new Pokemon();
      $pokemon->setId($pokemonJSON->id)
              ->setName($pokemonJSON->name)
              ->setIsDefault($pokemonJSON->is_default)
              ->setOrder($pokemonJSON->order)
              ->setFrontSprite($pokemonJSON->sprites->front_default)
              ->setBackSprite($pokemonJSON->sprites->back_default);
      foreach($pokemonJSON->abilities as $index => $abilityJSON){
        $id = $this->parseIdentifier($abilityJSON->ability->url);
        $ability = [
          'ability_id' => $id,
          'name' => $abilityJSON->ability->name,
          'is_hidden' => $abilityJSON->is_hidden??0
        ];
        $pokemon->setSingleAbility($ability,$index);
      }
      foreach($pokemonJSON->types as $index => $typeJSON){
        $id = $this->parseIdentifier($typeJSON->type->url);
        $type = [
          'type_id'=>$id,
          'name'=>$typeJSON->type->name
        ];
        $pokemon->setSingleType($type,$index);
      }
      $pokemon->setMoves($pokemonJSON->moves);
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
      $pokemonArray[$pokemon->getId()]=$pokemon;
      $pokemon->minimalPrint();
    }
    /** Start of abilities */
    $moveNamesArray = json_decode($this->api->resourceList('move',1,0));
    $moveArray = [];
    foreach($moveNamesArray->results as $moveStdPair){
      $moveJSON = json_decode(Http::get($moveStdPair->url));
      $move = new PokeMove();
      $effectEntryJSON = $moveJSON->effect_entries[0]->effect??NULL;
      $move ->setId($moveJSON->id)
            ->setName($moveJSON->name)
            ->setDamageType($this->parseIdentifier($moveJSON->damage_class->url))
            ->setAccuracy($moveJSON->accuracy)
            ->setPower($moveJSON->power)
            ->setPP($moveJSON->pp)
            ->setPriority($moveJSON->priority)
            ->setEffectChance($moveJSON->effect_chance)
            ->setEffectEntry($effectEntryJSON)
            ->setMeta($moveJSON->meta);
      $moveArray[$move->getId()] = $move;
      $move->minimalPrint();
    }

    /** Start of types */
    $typeNamesArray = json_decode($this->api->resourceList('type',1,0));
    $typeArray = [];
    foreach($typeNamesArray->results as $typeStdPair){
      $typeJSON = json_decode(Http::get($typeStdPair->url));
      $type = new PokeType();
      $type ->setId($typeJSON->id)
            ->setName($typeJSON->name)
            ->setSrc("https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-ix/scarlet-violet/".$typeJSON->id.".png")
            ->setDoubleDamage([])
            ->setHalfDamage([])
            ->setNoDamage([]);
      foreach($typeJSON->damage_relations->double_damage_to as $doubleDamageJSON){
        $type->setSingleDoubleDamage($this->parseIdentifier($doubleDamageJSON->url),$doubleDamageJSON->name);
      }
      foreach($typeJSON->damage_relations->half_damage_to as $halfDamageJSON){
        $type->setSingleHalfDamage($this->parseIdentifier($halfDamageJSON->url),$halfDamageJSON->name);
      }
      foreach($typeJSON->damage_relations->no_damage_to as $noDamageJSON){
        $type->setSingleNoDamage($this->parseIdentifier($noDamageJSON->url),$noDamageJSON->name);
      }
      $typeArray[$type->getId()]=$type;
      $type->minimalPrint();
    }
    /** Start of DB insertions */
    return response()->json(['message'=>'initDb done']);
  }
  private function parseIdentifier($url):string{
    $ret = substr_replace($url,'',-1);
    $ret = substr($ret,strrpos($ret,'/'));
    $ret = substr($ret,1);
    return $ret;
  }
}
