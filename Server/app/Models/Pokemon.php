<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
  /**
   * This model holds the data for a pokemon.
   *
  */
  private $id;
  private $name;
  private $is_default;
  private $order;
  private $front_sprite;
  private $back_sprite;
  private $stats;
  private $types;
  private $abilities;

  public function setId($id_in):self{
    $this->id = $id_in;
    return $this;
  }
  public function setName($name_in):self{
    $this->name = $name_in;
    return $this;
  }
  public function setIsDefault($is_default_in):self{
    $this->is_default = $is_default_in;
    return $this;
  }
  public function setOrder($order_in):self{
    $this->order = $order_in;
    return $this;
  }
  public function setFrontSprite($front_sprite_in):self{
    $this->front_sprite = $front_sprite_in;
    return $this;
  }
  public function setBackSprite($back_sprite_in):self{
    $this->back_sprite = $back_sprite_in;
    return $this;
  }
  public function setStats($stats_array_in):self{
    $this->stats = $stats_array_in;
    return $this;
  }
  public function setSingleStat($value, $index):self{
    $this->stats[$index] = $value;
    return $this;
  }
  public function setTypes($types_in) : self {
    $this->types = $types_in;
    return $this;
  }
  public function setSingleType($type_in, $index):self{
    $this->types[$index]=$type_in;
    return $this;
  }
  public function setAbilities($abilities_in):self{
    $this->abilities = $abilities_in;
    return $this;
  }
  public function setSingleAbility($ability,$index):self{
    $this->abilities[$index]=$ability;
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
      ".................................................................................",
    ]);
  }
}
