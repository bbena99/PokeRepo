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
    return $this->effect_chance;
  }
  public function getMeta(){
    return $this->meta;
  }
}
