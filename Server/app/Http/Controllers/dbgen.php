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
  protected $out;

  public function __construct(){
    $this->api = new PokeApi();
    $this->out = new \Symfony\Component\Console\Output\ConsoleOutput();//Using the console to keep track of the progress of the insertions
  }

  public function initDb($key){
    // return response()->json(['error'=>'initDb currently disabled'],500);
    set_time_limit(50000000);
    if(env('INITKEY',NULL)!=$key) return response()->json(['error'=>'Unauthorized access'], Response::HTTP_UNAUTHORIZED);
    $pokemonArray = $this->fetchPokemon();
    $abilityArray = $this->fetchAbilities();
    $moveArray = $this->fetchMoves();
    $typeArray = $this->fetchTypes();


    /** Start of DB insertions */
    $this->out->writeln(["---Start of Inserting into DataBase---","##Start of inserting Abilities"]);
    $this->DBAbilities($abilityArray);
    $this->DBMoves($moveArray);
    $this->DBPokemon($pokemonArray);

    $this->DBRelationPokemonAbility($pokemonArray);
    $this->DBRelationPokemonMoves($pokemonArray);
    $this->DBRelationPokemonType($pokemonArray);
    $this->DBRelationPokemonStat($pokemonArray);
    /** Inserting type */
    $this->out->writeln("##Start of inserting Types");
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
      $this->out->writeln("=[".$DBType->getId()."]=> ".$DBType->getName());
      /** Inserting relation_type_moves */
      $this->out->writeln($DBType->getName()." stats");
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
        //$this->out->writeln("=[".$DBTypeMoveID."]=> ".$DBTypeMoveName);
      }
      /** Inserting relation_damage */
      $this->out->writeln($DBType->getName()." double damage");
      foreach($DBType->getDoubleDamage() as $DBReceiverTypeID => $DBReceiverTypeName){
        $exists = DB::table('relation_damage')
          ->where([
              ['dealer_id','=',$DBType->getId()],
              ['receiver_id','=',$DBReceiverTypeID],
          ])->count();
        if(!$exists)DB::table('relation_damage')->upsert([
          'dealer_id'=>$DBType->getId(),
          'receiver_id'=>$DBReceiverTypeID,
          'damageable'=>2
        ],[
          'dealer_id','receiver_id'
        ],[
          'damageable'
        ]);
        //$this->out->writeln("=[".$DBType->getId()."]=> ".$DBReceiverTypeID." = 2");
      }
      $this->out->writeln($DBType->getName()." half damage");
      foreach($DBType->getHalfDamage() as $DBReceiverTypeID => $DBReceiverTypeName){
        $exists = DB::table('relation_damage')
          ->where([
              ['dealer_id','=',$DBType->getId()],
              ['receiver_id','=',$DBReceiverTypeID],
          ])->count();
        if(!$exists)DB::table('relation_damage')->upsert([
          'dealer_id'=>$DBType->getId(),
          'receiver_id'=>$DBReceiverTypeID,
          'damageable'=>1
        ],[
          'dealer_id','receiver_id'
        ],[
          'damageable'
        ]);
        //$this->out->writeln("=[".$DBType->getId()."]=> ".$DBReceiverTypeID." = 1/2");
      }
      $this->out->writeln($DBType->getName()." no damage");
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
        //$this->out->writeln("=[".$DBType->getId()."]=> ".$DBReceiverTypeID." = 0");
      }
    }

    return response()->json(['message'=>'initDb done']);
  }
  private function fetchPokemon():array{
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
      foreach($pokemonJSON->stats as $stat){
        $pokemon->setSingleStat($stat->stat->name,$stat->base_stat);
      }
      $pokemonArray[$pokemon->getId()]=$pokemon;
      $pokemon->minimalPrint();
    }
    return $pokemonArray;
  }
  private function fetchAbilities():array{
    /** Start of abilities */
    $abilityNamesArray = json_decode($this->api->resourceList('ability',1,0));
    $abilityArray = [];
    foreach($abilityNamesArray->results as $abilityStdPair){
      $abilityJSON = json_decode(Http::get($abilityStdPair->url));
      $ability = new Ability();
      foreach($abilityJSON->effect_entries as $effect){
        if($effect->language->name == 'en')$effectEntry = $effect->short_effect;
      }
      $ability->setID($abilityJSON->id)
              ->setName($abilityJSON->name)
              ->setEffectEntry($effectEntry);
      $ability->minimalPrint();
      $abilityArray[$ability->getID()]=$ability;
    }
    return $abilityArray;
  }
  private function fetchMoves():array{
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
    return $moveArray;
  }
  private function fetchTypes():array{
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
    return $typeArray;
  }
  private function DBAbilities($abilityArray):void{
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
      //$this->out->writeln("=[".$DBAbility->getID()."]=> ".$DBAbility->getName());
    }
  }
  private function DBMoves($moveArray):void{
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
      $this->out->writeln("=[".$DBMove->getID()."]=> ".$DBMove->getName());
    }
  }
  private function DBPokemon($pokemonArray):void{
    /** Inserting pokemon */
    $this->out->writeln("##Start of inserting Pokemon");
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
      $this->out->writeln("=[".$DBPokemon->getId()."]=> ".$DBPokemon->getName());
    }
  }
  private function DBRelationPokemonType($pokemonArray):void{
    foreach($pokemonArray as $DBPokemon){
      /** Inserting relation_pokemon_type */
      $this->out->writeln($DBPokemon->getName()." types:");
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
        //$this->out->writeln("=[".$DBPokemonType['type_id']."]=> ".$DBPokemonType['name']);
      }
    }
  }
  private function DBRelationPokemonAbility($pokemonArray):void{
    foreach($pokemonArray as $DBPokemon){
      /** Inserting relation_pokemon_ability */
      $this->out->writeln($DBPokemon->getName()." abilities:");
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
        $this->out->writeln("=[".$DBPokemonAbility['ability_id']."]=> ".$DBPokemonAbility['is_hidden']);
      }
    }
  }
  private function DBRelationPokemonMoves($pokemonArray):void{
    foreach($pokemonArray as $DBPokemon){
      /** Inserting relation_pokemon_moves */
      $this->out->writeln($DBPokemon->getName()." moves:");
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
        //$this->out->writeln("=[".$DBPokemonMoveID."]=> ".$DBPokemonMoveLevel);
      }
    }
  }
  private function DBRelationPokemonStat($pokemonArray):void{
    foreach($pokemonArray as $DBPokemon){
      /** Inserting relation_pokemon_stat */
      $this->out->writeln($DBPokemon->getName()." moves:");
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
        //$this->out->writeln("=[".$DBPokemonStatName."]=> ".$DBPokemonStatValue);
      }
    }
  }
  private function parseIdentifier($url):string{
    $ret = substr_replace($url,'',-1);
    $ret = substr($ret,strrpos($ret,'/'));
    $ret = substr($ret,1);
    return $ret;
  }
}
