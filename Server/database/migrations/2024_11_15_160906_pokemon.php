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
      $table->json('stats');
    });
    Schema::create('abilities', function(Blueprint $table){
      $table->id();
      $table->string('name');
      $table->string('effect_entries');
    });
    Schema::create('moves', function(Blueprint $table){
      $table->id();
      $table->string('name');
      $table->tinyInteger('power');
      $table->tinyInteger('pp');
      $table->tinyInteger('priority');
      $table->tinyInteger('effect_chance');
      $table->string('effect_entry');
      $table->json('meta');
    });
    Schema::create('items', function(Blueprint $table){
      $table->id();
      $table->string('name');
    });
    Schema::create('types', function(Blueprint $table){
      $table->id();
      $table->string('name');
      $table->string('src');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    //
    Schema::drop('pokemon');
  }
};
