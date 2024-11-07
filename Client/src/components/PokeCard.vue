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
const STAT_COLOR = [
  'bg-green-500', //hp
  'bg-yellow-500',//atk
  'bg-orange-500',//def
  'bg-cyan-500',  //sp atk
  'bg-purple-500',//sp def
  'bg-pink-500',  //spd
]
</script>

<template>
  <div class="w-full h-full max-h-48 flex flex-wrap content-start justify-end bg-bg1 border-2 border-bg2 rounded overflow-hidden relative">
    <div class="w-32 h-32 -m-14 flex justify-end items-end bg-bg2 rounded-full relative">
      <img :src="pokemon.sprites.front_default" :alt="pokemon.name+'.png'" class="w-1/2 m-2"/>
    </div>
    <h1 style="width: calc(100% - 1rem);" class="h-8 text-header text-lg flex justify-end px-2">
      {{ pokemon.name.charAt(0).toUpperCase()+pokemon.name.slice(1) }}
    </h1>
    <div class="w-2/3 flex">
      <img 
        v-for="t in pokemon.types"
        :src="'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-viii/sword-shield/'+t.type.url.split('/')[6]+'.png'"
        :alt="t.type.name+'.png'"
        class="w-1/2 pr-1"
      >
    </div>
    <div class="w-full mt-6 px-2 flex flex-wrap">
      <div
        v-for="(stat,index) in pokemon.stats"
        :key="pokemon.name+stat.stat"
        class="w-1/2 h-8 bg-text rounded-full overflow-hidden"
      >
        <div
          :class="'h-full pl-0.5 flex items-center text-header rounded-full '+STAT_COLOR[index]"
          :style="{'width': (stat.base_stat/200*100+'\%')}"
        >
          {{ stat.base_stat }}
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>