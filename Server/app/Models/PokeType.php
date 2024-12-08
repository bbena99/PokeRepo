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

  /**
   * @param int $id_in is the ID of $this Type.
   */
  public function setId(int $id_in):self{
    $this->id = $id_in;
    return $this;
  }
  /**
   * @param string $name_in is the Name of $this Type.
   */
  public function setName(string $name_in):self{
    $this->name = $name_in;
    return $this;
  }
  /**
   * @param string $src_in is the src for <img>'s related to $this Type.
   */
  public function setSrc(string $src_in):self{
    $this->src = $src_in;
    return $this;
  }
  /**
   * @param array<int,object> $moves
   */
  public function setMoves(array $moves):self{
    $this->moves = [];
    foreach($moves as $moveStdPair){
        $id = $this->parseIdentifier($moveStdPair->url);
        $this->moves[$id]=$moveStdPair->name;
    }
    return $this;
  }
  /**
   * @param int     $id   ID of the move
   * @param string  $name Name of the move
   */
  public function setSingleMove(int $id, string $name):self{
    $this->moves[$id] = $name;
    return $this;
  }
  /**
   * @param array<int,string> $double_damage_in
   */
  public function setDoubleDamage($double_damage_in):self{
    $this->double_damage = $double_damage_in;
    return $this;
  }
  /**
   * @param int     $id   ID of the receiver type
   * @param string  $name Name of the receiver type
   */
  public function setSingleDoubleDamage($id,$name):self{
    $this->double_damage[$id] = $name;
    return $this;
  }
  /**
   * @param array<int,string> $half_damage_in
   */
  public function setHalfDamage($half_damage_in):self{
    $this->half_damage = $half_damage_in;
    return $this;
  }
  /**
   * @param int     $id   ID of the receiver type
   * @param string  $name Name of the receiver type
   */
  public function setSingleHalfDamage($id,$name):self{
    $this->half_damage[$id] = $name;
    return $this;
  }
  /**
   * @param array<int,string> $no_damage_in
   */
  public function setNoDamage(array $no_damage_in):self{
    $this->no_damage = $no_damage_in;
    return $this;
  }
  /**
   * @param int     $id   ID of the receiver type
   * @param string  $name Name of the receiver type
   */
  public function setSingleNoDamage($id,$name):self{
    $this->no_damage[$id] = $name;
    return $this;
  }
  /**
   * get the ID of $this Type.
   * @return int ID of $this Type
   */
  public function getId():int{
    return $this->id;
  }
  /**
   * get the Name of $this Type.
   * @return string Name of $this Type.
   */
  public function getName():string{
    return $this->name;
  }
  /**
   * get the <img>'s associated src of $this Type.
   * @return string Src for the img of $this Type.
   */
  public function getSrc():string{
    return $this->src;
  }
  /**
   * get a list of all the moves this Type has.
   * @return array<int,string> (move_id => move_name)
   */
  public function getMoves():array{
    return $this->moves;
  }
  /**
   * get a list of all double damage relations to this type
   * @return array<int,string> (receiver_id => receiver_name)
   */
  public function getDoubleDamage():array{
    return $this->double_damage;
  }
  /**
   * check if $this deals double damage to $id
   * @param int $id is the ID of the type you want to compare $this to.
   */
  public function isDoubleDamage(int $id):bool{
    return $this->double_damage[$id]?true:false;
  }
  /**
   * get a list of all half damage relations to this type
   *
   * @return array<int,string> (receiver_id => receiver_name)
   */
  public function getHalfDamage():array{
    return $this->half_damage;
  }
  /**
   * check if $this deals half damage to $id
   * @param int $id is the ID of the type you want to compare $this to.
   */
  public function isHalfDamage(int $id):bool{
    return $this->half_damage[$id]?true:false;
  }
  /**
   * get a list of all no damage relations to this type
   * @return array<int,string> (receiver_id => receiver_name)
   */
  public function getNoDamage():array{
    return $this->no_damage;
  }
  /**
   * check if $this deals no damage to $id
   * @param int $id is the ID of the type you want to compare $this to.
   */
  public function isNoDamage(int $id):bool{
    return $this->no_damage[$id]?true:false;
  }

  /**
   * check if $this type has $id move
   * @param int $id is the ID of the move you want to check $this has.
   */
  public function hasMove(int $id):bool{
    return $this->moves[$id]?true:false;
  }

  /**
   * Print out to Symfony's console the full details of $this.
   */
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
  /**
   * Print out to Symfony's console the id and name only from $this.
   */
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
