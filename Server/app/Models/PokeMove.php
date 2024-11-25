<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PokeMove extends Model
{
  private $id;
  private $name;
  private $damage_type;
  private $accuracy;
  private $power;
  private $pp;
  private $priority;
  private $effect_chance;
  private $effect_entry;
  private $meta;

  public function setId($id_in):self{
    $this->id = $id_in;
    return $this;
  }
  public function setName($name_in):self{
    $this->name = $name_in;
    return $this;
  }
  public function setDamageType($damage_type_in):self{
    $this->damage_type = $damage_type_in;
    return $this;
  }
  public function setAccuracy($accuracy_in):self{
    $this->accuracy = $accuracy_in;
    return $this;
  }
  public function setPower($power_in):self{
    $this->power = $power_in;
    return $this;
  }
  public function setPP($pp_in):self{
    $this->pp = $pp_in;
    return $this;
  }
  public function setPriority($priority_in):self{
    $this->priority = $priority_in;
    return $this;
  }
  public function setEffectChance($effect_chance_in):self{
    $this->effect_chance = $effect_chance_in;
    return $this;
  }
  public function setEffectEntry($effect_entry_in):self{
    $this->effect_entry = $effect_entry_in;
    return $this;
  }
  public function setMeta($meta_in):self{
    $this->meta = $meta_in;
    return $this;
  }
  public function getId():int{
    return $this->id;
  }
  public function getName():string{
    return $this->name;
  }
  public function getDamageType():int{
    return $this->damage_type;
  }
  public function getAccuracy():int{
    return $this->accuracy;
  }
  public function getPower():int{
    return $this->power;
  }
  public function getPP():int{
    return $this->pp;
  }
  public function getPriority():int{
    return $this->priority;
  }
  public function getEffectChance():int{
    return $this->effect_chance;
  }
  public function getEffectEntry():int{
    return $this->effect_entry;
  }
  public function getMeta(){
    return $this->meta;
  }

  public function debugPrint(){
    $out = new \Symfony\Component\Console\Output\ConsoleOutput();
    $out->writeln([
      ".................................................................................",
      "#".$this->id.":\t".$this->name,
    ]);
    switch ($this->damage_type) {
      case 1:
        $out->writeln("damage_type: (1)Status");
        break;
      case 2:
        $out->writeln("damage_type: (2)Physical");
        break;
      case 3:
        $out->writeln("damage_type: (3)Special");
        break;
      default:
        $out->writeln("!!!invalid damage_type:".$this->damage_type);
        break;
    }
    $out->writeln([
      "power: ".$this->power."\t\tpp: ".$this->pp,
      "accuracy: ".$this->accuracy."\tpriority: ".$this->priority,
      "effect: ".$this->effect_chance."% to".$this->effect_entry,
      "meta data:"
    ]);
    foreach($this->meta as $key => $value){
      #$out->writeln(gettype($value));
      switch (gettype($value)) {
        case 'string':
        case 'integer':
        case 'NULL':
          $out->writeln("-[".$key."] => ".$value);
          break;
        default:
          $out->writeln("-[".$key."] =>");
          foreach($value as $subKey => $subValue){
            $out->writeln("---[".$subKey."] => ".$subValue);
          }
          break;
      }
    }
    $out->writeln(".................................................................................");
  }
  public function minimalPrint():void{
    $out = new \Symfony\Component\Console\Output\ConsoleOutput();
    $out->writeln("#".$this->id.":\t".$this->name);
  }
}
