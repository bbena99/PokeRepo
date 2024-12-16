<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ability;
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
    /** Start of pokemon */
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
      /** Start of pokemon => abilities */
      // return response()->json($pokemonJSON->abilities);
      foreach($pokemonJSON->abilities as $index => $abilityJSON){
        $id = $this->parseIdentifier($abilityJSON->ability->url);
        $ability = [
          'ability_id' => $id,
          'name' => $abilityJSON->ability->name,
          'is_hidden' => $abilityJSON->is_hidden??0
        ];
        $pokemon->setSingleAbility($index,$ability);
      }
      /** Start of pokemon => types */
      foreach($pokemonJSON->types as $index => $typeJSON){
        $id = $this->parseIdentifier($typeJSON->type->url);
        $type = [
          'type_id'=>$id,
          'name'=>$typeJSON->type->name
        ];
        $pokemon->setSingleType($index,$type);
      }
      /** Start of pokemon => moves */
      // return response()->json($pokemonJSON->moves);
      $pokemon->setMoves($pokemonJSON->moves);
      /** Start of pokemon => stats */
      $base_stat_total=0;
      foreach($pokemonJSON->stats as $stat){
        $base_stat_total+=$stat->base_stat;
        $pokemon->setSingleStat($stat->stat->name,$stat->base_stat);
      }
      $pokemon->setSingleStat('total',$base_stat_total);
      $pokemonArray[$pokemon->getId()]=$pokemon;
      $pokemon->minimalPrint();
    }
    /** Start of abilities */
    $abilityNamesArray = json_decode($this->api->resourceList('ability',1,0));
    $abilityArray = [];
    foreach($abilityNamesArray->results as $abilityStdPair){
      $abilityJSON = json_decode(Http::get($abilityStdPair->url));
      $abilityJSON->flavor_text_entries=[];
      $ability = new Ability();
      $effectEntry = end($abilityJSON->effect_entries);
      $ability->setID($abilityJSON->id)
              ->setName($abilityJSON->name)
              ->setEffectEntry($effectEntry->short_effect);
      $ability->minimalPrint();
      $abilityArray[$ability->getID()]=$ability;
    }
    /** Start of moves */
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
            ->setEffectChance($moveJSON->effect_chance??0)
            ->setEffectEntry($effectEntryJSON)
            ->setMeta($moveJSON->meta);
      $moveArray[$move->getId()] = $move;
      $move->debugPrint();
    }
    return response()->json("job done");

    /** Start of types */
    $typeNamesArray = json_decode($this->api->resourceList('type',1,0));
    $typeArray = [];
    foreach($typeNamesArray->results as $typeStdPair){
      $typeJSON = json_decode(Http::get($typeStdPair->url));
      $type = new PokeType();
      $type ->setId($typeJSON->id)
            ->setName($typeJSON->name)
            ->setSrc("https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-ix/scarlet-violet/".$typeJSON->id.".png")
            ->setMoves($typeJSON->moves)
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

    /** Inserting pokemon */
    foreach($pokemonArray as $DBPokemon){
      DB::insert('INSERT INTO pokemon ( id, name, is_default, order, front_sprite, back_sprite )',[
        $DBPokemon->getId(),
        $DBPokemon->getName(),
        $DBPokemon->getIsDefault(),
        $DBPokemon->getOrder(),
        $DBPokemon->getFrontSprite(),
        $DBPokemon->getBackSprite(),
      ]);
      /** Inserting relation_pokemon_type */
      foreach($DBPokemon->getTypes() as $DBPokemonTypeID => $DBPokemonTypeName){
        DB::insert('INSERT INTO relation_pokemon_type ( pokemon_id, type_id )',[
          $DBPokemon->getId(),
          $DBPokemonTypeID
        ]);
      }
      /** Inserting relation_pokemon_ability */
      foreach($DBPokemon->getAbilities() as $DBPokemonAbilityObj){
        DB::insert('INSERT INTO relation_pokemon_abilities ( pokemon_id, ability_id, is_hidden )',[
          $DBPokemon->getId(),
          $DBPokemonAbilityObj['ability_id'],
          $DBPokemonAbilityObj['is_hidden'],
        ]);
      }
      /** Inserting relation_pokemon_moves */
      foreach($DBPokemon->getMoves() as $DBPokemonMoveID => $DBPokemonMoveLevel){
        DB::insert('INSERT INTO relation_pokemon_moves ( pokemon_id, move_id, level)',[
          $DBPokemon->getId(),
          $DBPokemonMoveID,
          $DBPokemonMoveLevel,
        ]);
      }
    }
    /** Inserting type */
    foreach($typeArray as $DBType){
      DB::insert('INSERT INTO types (id, name, src)',[
        $DBType->getId(),
        $DBType->getName(),
        $DBType->getSrc(),
      ]);
      /** Inserting relation_type_moves */
      foreach($DBType->getMoves() as $DBTypeMoveID => $DBTypeMoveName){
        DB::insert('INSERT INTO relation_type_moves ( type_id, move_id )',[
          $DBType->getId(),
          $DBTypeMoveID
        ]);
      }
      /** Inserting relation_damage */
      foreach($DBType->getDoubleDamage() as $DBReceiverTypeID => $DBReceiverTypeName){
        DB::insert('INSERT INTO relation_damage (dealer_id, receiver_id, damageable)',[
          $DBType->getId(),
          $DBReceiverTypeID,
          true
        ]);
      }
      foreach($DBType->getHalfDamage() as $DBReceiverTypeID => $DBReceiverTypeName){
        DB::insert('INSERT INTO relation_damage (dealer_id, receiver_id, damageable)',[
          $DBReceiverTypeID,
          $DBType->getId(),
          true
        ]);
      }
      foreach($DBType->getHalfDamage() as $DBReceiverTypeID => $DBReceiverTypeName){
        DB::insert('INSERT INTO relation_damage (dealer_id, receiver_id, damageable)',[
          $DBReceiverTypeID,
          $DBType->getId(),
          true
        ]);
      }
    }

    return response()->json(['message'=>'initDb done']);
  }
  private function parseIdentifier($url):string{
    $ret = substr_replace($url,'',-1);
    $ret = substr($ret,strrpos($ret,'/'));
    $ret = substr($ret,1);
    return $ret;
  }
}
