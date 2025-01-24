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
  private $machine;
  private $meta;

  /**
   * @param int $id_in sets the ID of $this move
   */
  public function setId(int $id_in):self{
    $this->id = $id_in;
    return $this;
  }
  /**
   * @param string $name_in sets the name of $this move
   */
  public function setName(string $name_in):self{
    $this->name = $name_in;
    return $this;
  }
  /**
   * @param int $damage_type_in sets the damage type of the move (1:Status 2:Physical 3:Special)
   */
  public function setDamageType(int $damage_type_in):self{
    $this->damage_type = $damage_type_in;
    return $this;
  }
  /**
   * @param int $accuracy_in sets the accuracy of $this move
   */
  public function setAccuracy(int $accuracy_in):self{
    $this->accuracy = $accuracy_in;
    return $this;
  }
  /**
   * @param int $power_in sets the power of $this move
   */
  public function setPower(int $power_in):self{
    $this->power = $power_in;
    return $this;
  }
  /**
   * @param int $pp_in sets the pp count of $this move
   */
  public function setPP(int $pp_in):self{
    $this->pp = $pp_in;
    return $this;
  }
  /**
   * @param int $priority_in sets the priority of $this move
   */
  public function setPriority(int $priority_in):self{
    $this->priority = $priority_in;
    return $this;
  }
  /**
   * @param int $effect_chance_in sets the effect chance of $this move
   */
  public function setEffectChance(int $effect_chance_in):self{
    $this->effect_chance = $effect_chance_in;
    return $this;
  }
  /**
   * @param string $effect_entry_in set the effect description for $this move
   */
  public function setEffectEntry(string $effect_entry_in):self{
    $this->effect_entry = $effect_entry_in;
    return $this;
  }
  /**
   * @param string $tm The latest technical machine for $this move
   */
  public function setMachine(string $tm):self{
    $this->machine = $tm;
    return $this;
  }
  /**
   * @param object $meta_in set the meta data of $this move
   */
  public function setMeta(object|null $meta_in):self{
    $this->meta = json_encode($meta_in);
    return $this;
  }
  /**
   * @return int returns ID of $this move
   */
  public function getId():int{
    return $this->id;
  }
  /**
   * @return string returns Name of $this move
   */
  public function getName():string{
    return $this->name;
  }
  /**
   * @return int returns the type of move $this is
   */
  public function getDamageType():int{
    return $this->damage_type;
  }
  /**
   * @return int returns the accuracy of $this move
   */
  public function getAccuracy():int{
    return $this->accuracy;
  }
  /**
   * @return int returns the power of $this move
   */
  public function getPower():int{
    return $this->power;
  }
  /**
   * @return int returns the pp count of $this move
   */
  public function getPP():int{
    return $this->pp;
  }
  /**
   * @return int returns the priority of $this move
   */
  public function getPriority():int{
    return $this->priority;
  }
  /**
   * @return int returns the effect chance of $this move
   */
  public function getEffectChance():int{
    return $this->effect_chance;
  }
  /**
   * @return string returns the description of $this move's effect
   */
  public function getEffectEntry():string{
    return $this->effect_entry;
  }
  /**
   * @return object returns the meta data for $this move
   */
  public function getMachine():string|NULL{
    return $this->machine;
  }
  /**
   * @return object returns the meta data for $this move
   */
  public function getMeta():string{
    return $this->meta??"";
  }

  /**
   * Print out to Symfony's console the full details of $this.
   */
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
      "effect: ".$this->effect_chance."% to ".$this->effect_entry,
      "meta data:".$this->meta
    ]);
    $out->writeln(".................................................................................");
  }
  /**
   * Print out to Symfony's console the id and name only from $this.
   */
  public function minimalPrint():void{
    $out = new \Symfony\Component\Console\Output\ConsoleOutput();
    $out->writeln("#".$this->id.":\t".$this->name);
  }
}
