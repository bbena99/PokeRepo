<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PokeType extends Model
{
  private $id;
  private $name;
  private $src;
  private $moves;
  private $double_damage;
  private $half_damage;
  private $no_damage;


  public function setId(int $id_in):self{
    $this->id = $id_in;
    return $this;
  }
  public function setName(string $name_in):self{
    $this->name = $name_in;
    return $this;
  }
  public function setSrc(string $src_in):self{
    $this->src = $src_in;
    return $this;
  }
  public function setMoves(array $moves):self{
    $this->moves = [];
    foreach($moves as $moveStdPair){
        $id = $this->parseIdentifier($moveStdPair->url);
        $this->moves[$id]=$moveStdPair->name;
    }
    return $this;
  }
  public function setSingleMove(int $id, string $name):self{
    $this->moves[$id] = $name;
    return $this;
  }
  public function setDoubleDamage($double_damage_in):self{
    $this->double_damage = $double_damage_in;
    return $this;
  }
  public function setSingleDoubleDamage($id,$name):self{
    $this->double_damage[$id] = $name;
    return $this;
  }
  public function setHalfDamage($half_damage_in):self{
    $this->half_damage = $half_damage_in;
    return $this;
  }
  public function setSingleHalfDamage($id,$name):self{
    $this->half_damage[$id] = $name;
    return $this;
  }
  public function setNoDamage($no_damage_in):self{
    $this->no_damage = $no_damage_in;
    return $this;
  }
  public function setSingleNoDamage($id,$name):self{
    $this->no_damage[$id] = $name;
    return $this;
  }

  public function getId():int{
    return $this->id;
  }
  public function getName():string{
    return $this->name;
  }
  public function getSrc():string{
    return $this->src;
  }
  public function getMoves():array{
    return $this->moves;
  }
  public function getDoubleDamage():array{
    return $this->double_damage;
  }
  public function isDoubleDamage($id):bool{
    return $this->double_damage[$id]?true:false;
  }
  public function getHalfDamage():array{
    return $this->half_damage;
  }
  public function isHalfDamage($id):bool{
    return $this->half_damage[$id]?true:false;
  }
  public function getNoDamage():array{
    return $this->no_damage;
  }
  public function isNoDamage($id):bool{
    return $this->no_damage[$id]?true:false;
  }

  public function hasMove(int $id):bool{
    return $this->moves[$id]?true:false;
  }

  public function debugPrint():void{
    $out = new \Symfony\Component\Console\Output\ConsoleOutput();
    $out->writeln([
      ".................................................................................",
      "#".$this->id.":\t".$this->name,
      "src:\t".$this->src,
      "Moves:"
    ]);
    foreach($this->moves as $id => $name){
      $out->writeln("-[".$id."] => ".$name);
    }
    $out->writeln("Double Damage to:");
    foreach($this->double_damage as $id => $name){
      $out->writeln("-[".$id."] => ".$name);
    }
    $out->writeln("Half Damage to:");
    foreach($this->half_damage as $id => $name){
      $out->writeln("-[".$id."] => ".$name);
    }
    $out->writeln("No Damage to:");
    foreach($this->no_damage as $id => $name){
      $out->writeln("-[".$id."] => ".$name);
    }
    $out->writeln(".................................................................................");
  }
  public function minimalPrint(){
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
