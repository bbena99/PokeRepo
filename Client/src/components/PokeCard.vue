<script setup lang="ts">
const props = defineProps(['pokemon']);
const pokemon = props.pokemon;
const STAT_COLOR = [
  'bg-green-500', //hp
  'bg-red-600',//atk
  'bg-blue-600',//def
  'bg-yellow-400',  //sp atk
  'bg-purple-500',//sp def
  'bg-cyan-500',  //spd
]
</script>

<template>
  <div class="w-full h-full max-h-48 flex flex-wrap content-start justify-end bg-bg1 border-2 border-bg2 group-hover:border-hover rounded overflow-hidden">
    <div class="w-32 h-32 -m-14 flex justify-end items-end bg-bg2 group-hover:bg-hover rounded-full relative">
      <img :src="pokemon.sprites.front_default" :alt="pokemon.name+'.png'" class="w-1/2 m-2"/>
    </div>
    <h1 style="width: calc(100% - 1rem);" class="h-8 text-header text-lg flex justify-end px-2">
      {{ pokemon.name.charAt(0).toUpperCase()+pokemon.name.slice(1) }}
    </h1>
    <div class="w-1/2 h-10 flex flex-wrap justify-end">
      <img 
        v-for="t in pokemon.types"
        :src="'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-ix/scarlet-violet/'+t.type.url.split('/')[6]+'.png'"
        :alt="t.type.name+'.png'"
        class="w-full h-1/2 max-w-20 pb-1 pr-1"
      >
    </div>
    <div class="w-full flex flex-wrap">
      <div
        v-for="(stat,index) in pokemon.stats"
        :id="pokemon.name+'_'+stat.stat"
        :key="pokemon.name+stat.stat"
        class="max-w-full h-6 m-1 bg-text rounded-full overflow-hidden"
        style="width: calc(50% - 0.5rem);"
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