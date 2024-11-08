<script setup lang="ts">
//@ts-ignore ts says there is not export at this location, but there is.
import { useRoute } from 'vue-router/auto';
import { ref } from 'vue';
import { getOne, getParse } from '../../service';
import { emptyPokemon, PokémonI } from '../../models';

const route = useRoute('/Pokemon/[identifier]');

const curPokemon = ref<PokémonI>(emptyPokemon());
const typeData = ref([]);
getOne(route.params.identifier,(cb:PokémonI)=>{
  console.log(cb)
  curPokemon.value=cb;
  curPokemon.value.types.forEach(t=>{
    getParse('type',t.type.name,(type)=>{
      console.log(type);
    });
  })
});
</script>

<template>
  <div class="w-full flex justify-center items-center">
    <div class="w-full h-full xl:w-2/3 overflow-y-scroll">
      <div class="w-full p-4 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-lg">
        <div class="w-2/3 flex flex-wrap">
          <h1 class="w-full text-header text-7xl">
            {{ curPokemon.name.charAt(0).toUpperCase()+curPokemon.name.slice(1) }}
          </h1>
          <img 
            v-for="t in curPokemon.types"
            :src="'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-viii/sword-shield/'+t.type.url.split('/')[6]+'.png'"
            :alt="t.type.name+'.png'"
            class="max-h-11 md:m-4"
          >
        </div>
        <img class="w-1/6 bg-bg2 rounded-s-full" :src="curPokemon.sprites.front_default" alt="front-sprite.png">
        <img class="w-1/6 bg-bg2 rounded-e-full" :src="curPokemon.sprites.back_default" alt="back-default.png">
        <div class="w-full">
          <div></div>
        </div>
      </div>
      <div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
