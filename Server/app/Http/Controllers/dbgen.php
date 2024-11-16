<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
    $this->out = new \Symfony\Component\Console\Output\ConsoleOutput();
  }

  public function initDb($key){
    set_time_limit(5000000);
    if(env('INITKEY',NULL)!=$key) return response()->json(['error'=>'Unauthorized access'], Response::HTTP_UNAUTHORIZED);
    $pokemonNamesArray = json_decode($this->api->resourceList('pokemon',4,0));
    foreach($pokemonNamesArray->results as $pokemonStdPair){
      $pokemon = json_decode(Http::get($pokemonStdPair->url));
      $this->out->writeln('#'.$pokemon->id."\t".$pokemon->name);
      // DB::insert('insert into pokemon (id, name, is_default, order, front_sprite, back_sprite)',[
      //   $pokemon->id,
      //   $pokemon->name,
      //   $pokemon->is_default,
      //   $pokemon->order,
      //   $pokemon->sprites->front_default,
      //   $pokemon->sprites->back_default,
      // ]);
      $this->out->writeln(">>Abilities:");
      foreach($pokemon->abilities as $ability){
        $this->out->writeln($ability->is_hidden?($ability->ability->name."\tHidden?TRUE"):($ability->ability->name."\tHidden?FALSE"));
      }
      $this->out->writeln(">>Types:");
      foreach($pokemon->types as $type){
        $this->out->writeln($type->type->name."\t".$type->type->url);
      }
      $this->out->writeln(">>Moves:");
      foreach($pokemon->moves as $move){
        $this->out->writeln($move->move->name."\tlevel:".$move->version_group_details[0]->level_learned_at."\tMethod:".$move->version_group_details[0]->move_learn_method->name);
      }
      $this->out->writeln(">>Stats:");
      foreach($pokemon->stats as $stat){
        $this->out->writeln($stat->stat->name."\t".$stat->base_stat);
        // DB::insert('insert into relation_pokemon_stat (pokemon_id, stat_name, base_stat)',[
        //   $pokemon->id,
        //   $stat->stat->name,
        //   $stat->base_stat,
        // ]);
      }
    }
    return response('Job Done');
    //return response()->json(['message'=>'initDb ok']);
  }
}
