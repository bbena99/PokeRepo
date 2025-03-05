<script setup lang="ts">
import { PokémonI } from '../models';

const props = defineProps(['pokemon']);
const pokemon:PokémonI = props.pokemon;
const STAT_COLOR = [
  'bg-green-500', //hp
  'bg-red-600',//atk
  'bg-blue-600',//def
  'bg-yellow-400',  //sp atk
  'bg-purple-500',//sp def
  'bg-cyan-500',  //spd
]
let baseStatTotal = Object.values(pokemon.stats).reduce((acc, value)=>acc+value,0);
</script>

<template>
  <div class="w-full h-full max-h-58 flex flex-wrap content-start justify-end bg-bg1 border-2 border-bg2 group-hover:border-hover rounded overflow-hidden">
    <div class="w-32 h-32 -m-14 flex justify-end items-end bg-bg2 group-hover:bg-hover rounded-full relative">
      <img :src="pokemon.front_sprite" :alt="pokemon.name+'.png'" class="w-1/2 m-2"/>
    </div>
    <h1 style="width: calc(100% - 1rem);" class="h-8 text-header text-lg flex justify-end px-2">
      {{ pokemon.name.charAt(0).toUpperCase()+pokemon.name.slice(1) }}
    </h1>
    <div class="w-1/2 h-10 flex flex-wrap justify-end">
      <img 
        v-for="t in pokemon.types"
        :src=t.src
        :alt="t.name+'.png'"
        class="w-full h-1/2 max-w-20 pb-1 pr-1"
      >
    </div>
    <div class="w-full my-1 flex flex-wrap">
      <div
        v-for="(stat,key,index) in pokemon.stats"
        :id="pokemon.name+'_'+key"
        :key="pokemon.name+key"
        class="max-w-full h-6 m-1 bg-text rounded-full overflow-hidden"
        style="width: calc(50% - 0.5rem);"
      >
        <div
          :class="'h-full pl-0.5 flex items-center text-header rounded-full '+STAT_COLOR[index]"
          :style="{'width': (stat!/210*85+15+'\%')}"
        >
          {{ stat }}
        </div>
      </div>
      <div class="w-full h-6 m-1 bg-text rounded-full overflow-hidden">
        <div :style="{'width': ((baseStatTotal-175)/700*100+'\%')}" class="h-full pl-1 flex items-center text-header rounded-e-full bg-bg2">
          {{ baseStatTotal }}
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>