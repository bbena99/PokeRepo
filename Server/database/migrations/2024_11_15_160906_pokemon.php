<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    //
    Schema::create('pokemon', function(Blueprint $table){
      $table->id();
      $table->string('name');
      $table->boolean('is_default');
      $table->integer('order');
      $table->string('front_sprite');
      $table->string('back_sprite');
    });
    Schema::create('abilities', function(Blueprint $table){
      $table->id();
      $table->string('name');
      $table->string('effect_entries');
    });
    Schema::create('moves', function(Blueprint $table){
      $table->id();
      $table->string('name');
      $table->tinyInteger('damage_type');
      $table->tinyInteger('accuracy');
      $table->tinyInteger('power');
      $table->tinyInteger('pp');
      $table->tinyInteger('priority');
      $table->tinyInteger('effect_chance');
      $table->string('effect_entry');
      $table->json('meta');
    });
    Schema::create('types', function(Blueprint $table){
      $table->id();
      $table->string('name');
      $table->string('src');
    });

    /** Start of relation tables */
    Schema::create('relation_pokemon_abilities', function(Blueprint $table){
      $table->foreignId('pokemon_id');
      $table->foreignId('ability_id');
      $table->boolean('hidden');
      $table->boolean('past');
    });
    Schema::create('relation_pokemon_type', function(Blueprint $table){
      $table->foreignId('pokemon_id');
      $table->foreignId('type_id');
      $table->boolean('past');
    });
    Schema::create('relation_pokemon_moves', function(Blueprint $table){
      $table->foreignId('pokemon_id');
      $table->foreignID('move_id');
      $table->tinyInteger('level');//-1=TMmachine | 0=egg | >0=level up
    });
    Schema::create('relation_type_moves', function(Blueprint $table){
      $table->foreignId('type_id');
      $table->foreignId('move_id');
    });
    Schema::create('relation_pokemon_stat', function(Blueprint $table){
      $table->foreignId('pokemon_id');
      $table->string('stat_name');
      $table->tinyInteger('base_stat');
    });
    Schema::create('relation_damage', function(Blueprint $table){
      $table->foreignId('dealer_id');
      $table->foreignId('receiver_id');
      $table->boolean('damageable');//If false, deals no damage. If true, deals double damage.
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    //
    Schema::drop('pokemon');
    Schema::drop('abilities');
    Schema::drop('moves');
    Schema::drop('types');
    Schema::drop('relation_pokemon_abilities');
    Schema::drop('relation_pokemon_type');
    Schema::drop('relation_pokemon_moves');
    Schema::drop('relation_type_moves');
    Schema::drop('relation_damage');
  }
};
