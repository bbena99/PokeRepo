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
    // return response()->json(['error'=>'initDb currently disabled'],500);
    set_time_limit(50000000);
    if(env('INITKEY',NULL)!=$key) return response()->json(['error'=>'Unauthorized access'], Response::HTTP_UNAUTHORIZED);
    /** Start of pokemon */
    $pokemonNamesArray = json_decode($this->api->resourceList('pokemon',3000,0));
    $pokemonArray =[];
    foreach($pokemonNamesArray->results as $pokemonStdPair){
      $pokemonJSON = json_decode(Http::get($pokemonStdPair->url));
      $pokemon = new Pokemon();
      $pokemon->setId($pokemonJSON->id)
              ->setName($pokemonJSON->name)
              ->setIsDefault($pokemonJSON->is_default)
              ->setOrder($pokemonJSON->order)
              ->setFrontSprite($pokemonJSON->sprites->front_default??"")
              ->setBackSprite($pokemonJSON->sprites->back_default??"");
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
    $abilityNamesArray = json_decode($this->api->resourceList('ability',3000,0));
    $abilityArray = [];
    foreach($abilityNamesArray->results as $abilityStdPair){
      $abilityJSON = json_decode(Http::get($abilityStdPair->url));
      $ability = new Ability();
      $effectEntry = end($abilityJSON->effect_entries);
      $ability->setID($abilityJSON->id)
              ->setName($abilityJSON->name)
              ->setEffectEntry($effectEntry->short_effect??"");
      $ability->minimalPrint();
      $abilityArray[$ability->getID()]=$ability;
    }
    /** Start of moves */
    $moveNamesArray = json_decode($this->api->resourceList('move',3000,0));
    $moveArray = [];
    foreach($moveNamesArray->results as $moveStdPair){
      $moveJSON = json_decode(Http::get($moveStdPair->url));
      $move = new PokeMove();
      $effectEntryJSON = $moveJSON->effect_entries[0]->effect??NULL;
      $move ->setId($moveJSON->id)
            ->setName($moveJSON->name)
            ->setDamageType($this->parseIdentifier($moveJSON->damage_class->url))
            ->setAccuracy($moveJSON->accuracy??0)
            ->setPower($moveJSON->power??0)
            ->setPP($moveJSON->pp??0)
            ->setPriority($moveJSON->priority??0)
            ->setEffectChance($moveJSON->effect_chance??0)
            ->setEffectEntry($effectEntryJSON??"")
            ->setMeta($moveJSON->meta);
      $moveArray[$move->getId()] = $move;
      $move->minimalPrint();
    }

    /** Start of types */
    $typeNamesArray = json_decode($this->api->resourceList('type',100,0));
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
    $out = new \Symfony\Component\Console\Output\ConsoleOutput();//Using the console to keep track of the progress of the insertions
    $out->writeln(["---Start of Inserting into DataBase---","##Start of inserting Abilities"]);
    /** Inserting ability */
    foreach($abilityArray as $DBAbility){
      DB::table('abilities')->upsert([
        "id"  =>$DBAbility->getID(),
        "name" =>$DBAbility->getName(),
        "effect_entries"  =>$DBAbility->getEffectEntry()
      ],[
        'id'
      ],[
        'name','effect_entries'
      ]);
      //$out->writeln("=[".$DBAbility->getID()."]=> ".$DBAbility->getName());
    }
    /** Inserting moves */
    foreach($moveArray as $DBMove){
      DB::table('moves')->upsert([
        "id"  =>$DBMove->getID(),
        "name" =>$DBMove->getName(),
        "damage_type"=>$DBMove->getDamageType(),
        "accuracy"=>$DBMove->getAccuracy(),
        "power"=>$DBMove->getPower(),
        "pp"=>$DBMove->getPP(),
        "priority"=>$DBMove->getPriority(),
        "effect_chance"=>$DBMove->getEffectChance(),
        "effect_entry"  =>$DBMove->getEffectEntry(),
        "meta"=>$DBMove->getMeta(),
      ],[
        'id'
      ],[
        'name','damage_type','accuracy','power','pp','effect_chance','effect_entry','meta'
      ]);
      $out->writeln("=[".$DBMove->getID()."]=> ".$DBMove->getName());
    }
    /** Inserting pokemon */
    $out->writeln("##Start of inserting Pokemon");
    foreach($pokemonArray as $DBPokemon){
      DB::table('pokemon')->upsert([
        'id'=>$DBPokemon->getId(),
        'name'=>$DBPokemon->getName(),
        'is_default'=>$DBPokemon->getIsDefault(),
        'order'=>$DBPokemon->getOrder(),
        'front_sprite'=>$DBPokemon->getFrontSprite(),
        'back_sprite'=>$DBPokemon->getBackSprite(),
      ],[
        'id'
      ],[
        'name','is_default','order','front_sprite','back_sprite'
      ]);
      $out->writeln("=[".$DBPokemon->getId()."]=> ".$DBPokemon->getName());
      /** Inserting relation_pokemon_type */
      $out->writeln($DBPokemon->getName()." types:");
      foreach($DBPokemon->getTypes() as $DBPokemonType){
        $exists = DB::table('relation_pokemon_type')
          ->where([
            ['pokemon_id','=',$DBPokemon->getId()],
            ['type_id','=',$DBPokemonType['type_id']]
          ])->count();
        if(!$exists)DB::table('relation_pokemon_type')->upsert([
          'pokemon_id'=>$DBPokemon->getId(),
          'type_id'=>$DBPokemonType['type_id']
        ],[
          'pokemon_id','type_id'
        ]);
        //$out->writeln("=[".$DBPokemonType['type_id']."]=> ".$DBPokemonType['name']);
      }
      /** Inserting relation_pokemon_ability */
      $out->writeln($DBPokemon->getName()." abilities:");
      foreach($DBPokemon->getAbilities() as $DBPokemonAbility){
        $exists = DB::table('relation_pokemon_abilities')
          ->where([
            ['pokemon_id','=',$DBPokemon->getId()],
            ['ability_id','=',$DBPokemonAbility]
          ])->count();
        if(!$exists)DB::table('relation_pokemon_abilities')->upsert([
          'pokemon_id'=>$DBPokemon->getId(),
          'ability_id'=>$DBPokemonAbility['ability_id'],
          'hidden'=>$DBPokemonAbility['is_hidden'],
        ],[
          'pokemon_id','ability_id',
        ],[
          'hidden'
        ]);
        //$out->writeln("=[".$DBPokemonAbility['ability_id']."]=> ".$DBPokemonAbility['is_hidden']);
      }
      /** Inserting relation_pokemon_moves */
      $out->writeln($DBPokemon->getName()." moves:");
      foreach($DBPokemon->getMoves() as $DBPokemonMoveID => $DBPokemonMoveLevel){
        $exists = DB::table('relation_pokemon_moves')
          ->where([
            ['pokemon_id','=',$DBPokemon->getId()],
            ['move_id','=',$DBPokemonMoveID]
          ])->count();
        if(!$exists)DB::table('relation_pokemon_moves')->upsert([
          'pokemon_id'=>$DBPokemon->getId(),
          'move_id'=>$DBPokemonMoveID,
          'level'=>$DBPokemonMoveLevel,
        ],[
          'pokemon_id','move_id',
        ],[
          'level'
        ]);
        //$out->writeln("=[".$DBPokemonMoveID."]=> ".$DBPokemonMoveLevel);
      }
      /** Inserting relation_pokemon_stat */
      $out->writeln($DBPokemon->getName()." moves:");
      foreach($DBPokemon->getStats() as $DBPokemonStatName => $DBPokemonStatValue){
        $exists = DB::table('relation_pokemon_stat')
          ->where([
            ['pokemon_id','=',$DBPokemon->getId()],
            ['stat_name','=',$DBPokemonStatName],
            ['base_stat','=',$DBPokemonStatValue]
          ])->count();
        if(!$exists)DB::table('relation_pokemon_stat')->upsert([
          'pokemon_id'=>$DBPokemon->getId(),
          'stat_name'=>$DBPokemonStatName,
          'base_stat'=>$DBPokemonStatValue,
        ],[
          'pokemon_id','stat_name',
        ],[
          'base_stat'
        ]);
        //$out->writeln("=[".$DBPokemonStatName."]=> ".$DBPokemonStatValue);
      }
    }
    /** Inserting type */
    $out->writeln("##Start of inserting Types");
    foreach($typeArray as $DBType){
      DB::table('types')->upsert([
        'id'=>$DBType->getId(),
        'name'=>$DBType->getName(),
        'src'=>$DBType->getSrc(),
      ],[
        'id',
      ],[
        'name','src'
      ]);
      $out->writeln("=[".$DBType->getId()."]=> ".$DBType->getName());
      /** Inserting relation_type_moves */
      $out->writeln($DBType->getName()." moves");
      foreach($DBType->getMoves() as $DBTypeMoveID => $DBTypeMoveName){
        $exists = DB::table('relation_type_moves')
          ->where([
            ['type_id','=',$DBType->getId()],
            ['move_id','=',$DBTypeMoveID]
          ])->count();
        if(!$exists)DB::table('relation_type_moves')->upsert([
          'type_id'=>$DBType->getId(),
          'move_id'=>$DBTypeMoveID,
        ],[
          'type_id','move_id'
        ],[]);
        //$out->writeln("=[".$DBTypeMoveID."]=> ".$DBTypeMoveName);
      }
      /** Inserting relation_damage */
      $out->writeln($DBType->getName()." double damage");
      foreach($DBType->getDoubleDamage() as $DBReceiverTypeID => $DBReceiverTypeName){
        $exists = DB::table('relation_damage')
          ->where([
            ['dealer_id','=',$DBType->getId()],
            ['receiver_id','=',$DBReceiverTypeID]
          ])->count();
        if(!$exists)DB::table('relation_damage')->upsert([
          'dealer_id'=>$DBType->getId(),
          'receiver_id'=>$DBReceiverTypeID,
          'damageable'=>true
        ],[
          'dealer_id','receiver_id'
        ],[
          'damageable'
        ]);
        //$out->writeln("=[".$DBType->getId()."]=> ".$DBReceiverTypeID." = 2");
      }
      $out->writeln($DBType->getName()." half damage");
      foreach($DBType->getHalfDamage() as $DBReceiverTypeID => $DBReceiverTypeName){
        $exists = DB::table('relation_damage')
          ->where([
              ['dealer_id','=',$DBReceiverTypeID],
              ['receiver_id','=',$DBType->getId()],
          ])->count();
        if(!$exists)DB::table('relation_damage')->upsert([
          'dealer_id'=>$DBReceiverTypeID,
          'receiver_id'=>$DBType->getId(),
          'damageable'=>true
        ],[
          'dealer_id','receiver_id'
        ],[
          'damageable'
        ]);
        //$out->writeln("=[".$DBType->getId()."]=> ".$DBReceiverTypeID." = 1/2");
      }
      $out->writeln($DBType->getName()." no damage");
      foreach($DBType->getNoDamage() as $DBReceiverTypeID => $DBReceiverTypeName){
        $exists = DB::table('relation_damage')
          ->where([
            ['dealer_id','=',$DBType->getId()],
            ['receiver_id','=',$DBReceiverTypeID]
          ])->count();
        if(!$exists)DB::table('relation_damage')->upsert([
          'dealer_id'=>$DBType->getId(),
          'receiver_id'=>$DBReceiverTypeID,
          'damageable'=>false
        ],[
          'dealer_id','receiver_id'
        ],[
          'damageable'
        ]);
        //$out->writeln("=[".$DBType->getId()."]=> ".$DBReceiverTypeID." = 0");
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
