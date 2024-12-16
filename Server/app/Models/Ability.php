<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
  private $id;
  private $name;
  private $effect_entry;
  /**
   * @param int $id_in is the ID of $this Ability.
   */
  public function setID($id_in):self{
    $this->id = $id_in;
    return $this;
  }
  /**
   * @param string $name_in is the Name of $this Ability.
   */
  public function setName($name_in):self{
    $this->name = $name_in;
    return $this;
  }
  /**
   * @param string $effect_entry_in is the effect of $this Ability.
   */
  public function setEffectEntry($effect_entry_in):self{
    $this->effect_entry = $effect_entry_in;
    return $this;
  }
  /**
   * @return int $id of $this Ability
   */
  public function getID():int{
    return $this->id;
  }
  /**
   * @return string $name of $this Ability
   */
  public function getName():string{
    return $this->name;
  }
  /**
   * @return string $effect_entry of $this Ability
   */
  public function getEffectEntry():string{
    return $this->effect_entry;
  }
  public function debugPrint():void{
    $out = new \Symfony\Component\Console\Output\ConsoleOutput();
    $out->writeln([
      ".................................................................................",
      "#".$this->id.":\t".$this->name,
      "Effect:\t".$this->effect_entry,
      ".................................................................................",
    ]);
  }
  public function minimalPrint():void{
    $out = new \Symfony\Component\Console\Output\ConsoleOutput();
    $out->writeln("#".$this->id.": ".$this->name);
  }
}
