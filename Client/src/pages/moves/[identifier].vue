<script setup lang="ts">
import { ref } from 'vue';
//@ts-ignore ts says there is not export at this location, but there is.
import { useRoute } from 'vue-router/auto';
import Loading from "../../components/Loading.vue";
import PageNotFound from '../../components/PageNotFound.vue';
import { emptyMove, MovesI } from '../../models';
import { getOneMove } from '../../service';

const route = useRoute('/Moves/[identifier]');
const state = ref<number>(0);
const move = ref<MovesI>(emptyMove());
getOneMove(route.params.identifier,(m)=>{
  if(typeof(m)==='string'){
    state.value=-1;
  } else {
    move.value=m;
    state.value=1;
  }
})
</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1" class="flex justify-center items-center py-4">
    <div class="grid grid-cols-5 gap-4 w-full h-full xl:w-2/3">
      <!--Start of Ability name and description-->
      <div class="w-full h-60 p-4 col-span-5 rounded-lg border-2 border-bg2 mt-3 grid grid-cols-3 bg-bg1 shadow-xl">
        <h1 class="col-span-3 lg:col-span-2 w-full text-header text-7xl">
          {{ move.name.charAt(0).toUpperCase() + move.name.slice(1) }}
        </h1>
        <div class="col-span-3 lg:col-span-1 flex flex-wrap">
          <img :src="'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-ix/scarlet-violet/'+move.type_id+'.png'" alt="">
        </div>
        <span class="col-span-3 w-full text-text text-xl">
          {{ move.effect_entry }}
        </span>
      </div>
      <!--Start of pokemon that learn this move by leveling up-->
      <div v-if="move.pokemon.level.length>0" class="w-full p-4 col-span-5 rounded-lg border-2 border-bg2 mt-3 bg-bg1 shadow-xl">
        <span class="text-header text-2xl font-semibold w-full">
          Pokemon that learn this move through leveling up:
        </span>
        <div class="w-full grid grid-cols-6 gap-3">
          <div v-for="poke in move.pokemon.level" class="col-span-6 md:col-span-3 xl:col-span-2 grid grid-cols-8 border-2 border-bg2 rounded-full items-center [&>span]:px-2">
            <img :src="poke.front_sprite" :alt="poke.name+'_sprit'" class="rounded-full bg-bg2 col-span-2">
            <span class="flex items-center col-span-3">
              <RouterLink :to="'../Pokemon/'+poke.name" class="h-full underline hover:text-hover text-xl">
                {{ poke.name.charAt(0).toUpperCase() + poke.name.slice(1).replace('-',' ') }}
              </RouterLink>
            </span>
            <div class="col-span-2">
              <img v-for="type in poke.types" :src="'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-ix/scarlet-violet/'+type+'.png'" alt="TypeImg">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <PageNotFound v-if="state===-1" header="Move" :message="route.params.identifier+' was not found'" />
</template>

<style scoped>
</style>