<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
  private $id;
  private $name;
  private $effect_entry;

  public function setID($id_in):self{
    $this->id = $id_in;
    return $this;
  }
  public function setName($name_in):self{
    $this->name = $name_in;
    return $this;
  }
  public function setEffectEntries($effect_entry_in):self{
    $this->effect_entry = $effect_entry_in;
    return $this;
  }
  public function getID():int{
    return $this->id;
  }
  public function getName():string{
    return $this->name;
  }
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
}
