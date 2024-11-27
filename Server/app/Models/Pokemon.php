<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
  /**
   * This model holds the data for a pokemon.
   *
  */
  private int       $id;
  private string    $name;
  private bool      $is_default;
  private int       $order;
  private string    $front_sprite;
  private string    $back_sprite;
  private array     $stats;
  private array     $types;
  private array     $abilities;
  private array     $moves;

  public function setId(int $id_in):self{
    $this->id = $id_in;
    return $this;
  }
  public function setName(string $name_in):self{
    $this->name = $name_in;
    return $this;
  }
  public function setIsDefault(bool $is_default_in):self{
    $this->is_default = $is_default_in;
    return $this;
  }
  public function setOrder(int $order_in):self{
    $this->order = $order_in;
    return $this;
  }
  public function setFrontSprite(string $front_sprite_in):self{
    $this->front_sprite = $front_sprite_in;
    return $this;
  }
  public function setBackSprite(string $back_sprite_in):self{
    $this->back_sprite = $back_sprite_in;
    return $this;
  }
  public function setStats(array $stats_array_in):self{
    $this->stats = $stats_array_in;
    return $this;
  }
  public function setSingleStat(int $value, string $index):self{
    $this->stats[$index] = $value;
    return $this;
  }
  public function setTypes(array $types_in) : self {
    $this->types = $types_in;
    return $this;
  }
  public function setSingleType(array $type_in, int $index):self{
    $this->types[$index]=$type_in;
    return $this;
  }
  public function setAbilities(array $abilities_in):self{
    $this->abilities = $abilities_in;
    return $this;
  }
  public function setSingleAbility(array $ability, int $index):self{
    $this->abilities[$index]=$ability;
    return $this;
  }
  public function setMoves(array $moves_in):self{
    foreach($moves_in as $move_in){
      $id = $this->parseIdentifier($move_in->move->url);
      $data_in = end($move_in->version_group_details);
      $level = NULL;
      switch ($data_in->move_learn_method->name) {
        case 'level-up':
          $level = $data_in->level_learned_at;
          break;
        case 'egg':
          $level = 0;
          break;
        case 'machine':
        case 'tutor':
          $level = -1;
          break;
        default:
          break;
      }
      $this->moves[$id]=$level;
    }
    return $this;
  }
  public function getId():int{
    return $this->id;
  }
  public function getName():string{
    return $this->name;
  }
  public function getIsDefault():bool{
    return $this->is_default;
  }
  public function getOrder():int{
    return $this->order;
  }
  public function getFrontSprite():string{
    return $this->front_sprite;
  }
  public function getBackSprite():string{
    return $this->back_sprite;
  }
  public function getStats():array{
    return $this->stats;
  }
  public function getSingleStat($stat):int{
    return $this->stats[$stat];
  }
  public function getTypes():array{
    return $this->types;
  }
  public function getSingleType($index):string{
    return $this->types[$index];
  }
  public function getAbilities():array{
    return $this->abilities;
  }
  public function getSingleAbility($index):string{
    return $this->abilities[$index];
  }
  public function getMoves():array{
    return $this->moves;
  }
  public function debugPrint():void{
    $out = new \Symfony\Component\Console\Output\ConsoleOutput();
    $out->writeln([
      ".................................................................................",
      ">> #".$this->id."\t".$this->name,
      ($this->is_default)?("-is_default:\tTRUE"):("-is_default:\tFALSE"),
      "-order:\t".$this->order,
      "-front_sprite:\t".$this->front_sprite,
      "-back_sprite:\t".$this->back_sprite,
      "types:{"
    ]);
    foreach($this->types as $type){
      foreach($type as $field => $value)$out->writeln('-['.$field.']=>'.$value);
      $out->writeln('---');
    }
    $out->writeln([
      "}",
      "abilities:{"
    ]);
    foreach($this->abilities as $ability){
      foreach($ability as $field => $value)$out->writeln('-['.$field.']=>'.$value);
      $out->writeln('---');
    }
    $out->writeln([
      "}",
      "stats:{"
    ]);
    foreach($this->stats as $stat => $value){
      $out->writeln("-[".$stat."]=>".$value);
    }
    $out->writeln([
      "}",
      "moves:{"
    ]);
    foreach($this->moves as $id => $level){
      $out->writeln("-[".$id."]=>".$level);
    }
    $out->writeln([
      "}",
      ".................................................................................",
    ]);
  }
  public function minimalPrint():void{
    $out = new \Symfony\Component\Console\Output\ConsoleOutput();
    $out->writeln("#".$this->id.":\t".$this->name);
  }

  private function parseIdentifier($url):string{
    $ret = substr_replace($url,'',-1);
    $ret = substr($ret,strrpos($ret,'/'));
    $ret = substr($ret,1);
    return $ret;
  }
}
