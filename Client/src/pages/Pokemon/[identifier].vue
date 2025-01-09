<script setup lang="ts">
//@ts-ignore ts says there is not export at this location, but there is.
import { useRoute } from 'vue-router/auto';
import { ref } from 'vue';
import { getOne, getParse } from '../../service';
import { emptyPokemon, PokémonI, TypeI } from '../../models';
import { TYPES } from '../../constants';
import DamageMultiplier from '../../components/DamageMultiplier.vue';

const route = useRoute('/Pokemon/[identifier]');

const curPokemon = ref<PokémonI>(emptyPokemon());
const typeData = ref<TypeI[]>([]);

getOne(route.params.identifier,(cb:PokémonI)=>{
  curPokemon.value=cb;
});
</script>

<template>
  <div class="flex justify-center items-center">
    <div class="grid grid-cols-5 w-full h-full xl:w-2/3 overflow-y-scroll">
      <!--Pokemon name, types, and sprites-->
      <div class="w-full h-72 p-4 col-span-5 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
        <div class="w-2/3 flex flex-wrap">
          <h1 class="w-full text-header text-7xl">
            {{ curPokemon.name.charAt(0).toUpperCase()+curPokemon.name.slice(1) }}
          </h1>
          <img 
            v-for="t in curPokemon.types"
            :src="t.src"
            :alt="t.name+'.png'"
            class="max-h-12 md:mx-4"
          >
        </div>
        <div class="flex justify-center w-1/3 bg-bg2 rounded-full">
          <img class="w-1/2 aspect-square" :src="curPokemon.front_sprite" alt="front-sprite.png">
          <img class="w-1/2 aspect-square" :src="curPokemon.back_sprite" v-if="curPokemon.back_sprite" alt="back-default.png">
        </div>
      </div>
      <!--Pokemon Stats-->
      <div class="w-full p-4 col-span-4 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
      </div>
      <!--Pokemon damage taken-->
      <div class="w-full p-4 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
        <span class="text-header text-2xl">
          Damage Taken:
        </span>
        <div class="grid grid-cols-1 items-center">
          <DamageMultiplier
            v-for="(ty,index) in TYPES"
            :type="ty"
            :mult="index"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
