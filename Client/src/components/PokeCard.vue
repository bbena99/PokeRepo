<script setup lang="ts">
import { ref } from 'vue';
import { emptyPokemon, Pokémon, standardPair } from '../models';
import { getOne } from '../service';

const props = defineProps(['poke']);
const poke:standardPair = props.poke;
const pokemon= ref<Pokémon>(emptyPokemon());
getOne(poke.name,(cb:Pokémon)=>{
  pokemon.value=cb;
});

</script>

<template>
  <div class="w-full h-full flex flex-wrap bg-bg1 border-2 border-bg2 rounded overflow-hidden relative">
    <div class="w-32 h-32 -m-14 flex justify-end items-end bg-bg2 rounded-full relative">
      <img :src="pokemon.sprites.front_default" :alt="pokemon.name+'.png'" class="w-1/2 m-2"/>
    </div>
    <h1 style="width: calc(100% - 1rem);" class="h-8 text-header text-lg flex justify-end px-2">
      {{ pokemon.name }}
    </h1>
    <div class="w-full flex">
      <img v-for="t in pokemon.types" src="" alt="">
    </div>
  </div>
</template>

<style scoped>
</style>