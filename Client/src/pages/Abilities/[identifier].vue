<script setup lang="ts">
//@ts-ignore ts says there is not export at this location, but there is.
import { useRoute } from 'vue-router/auto';
import { ref } from 'vue';
import Loading from "../../components/Loading.vue";
import PageNotFound from '../../components/PageNotFound.vue';
import { getOneAbility } from '../../service';
import { AbilityI, emptyAbility } from '../../models';
import { RouterLink } from 'vue-router';

const route = useRoute('/Abilities/[identifier]');
const state = ref<number>(0);
const ability = ref<AbilityI>(emptyAbility());
getOneAbility(route.params.identifier,(a)=>{
  if(typeof(a)==='string'){
    state.value=-1
  } else {
    ability.value=a;
    state.value=1;
  }
})

</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1" class="flex justify-center items-center py-4">
    <div class="grid grid-cols-5 gap-4 w-full h-full xl:w-2/3">
      <!--Start of Ability name and description-->
      <div class="w-full h-72 p-4 col-span-5 rounded-lg border-2 border-bg2 mt-3 grid grid-cols-3 bg-bg1 shadow-xl">
        <h1 class="col-span-3 w-full text-header text-7xl">
          {{ ability.name.charAt(0).toUpperCase() + ability.name.slice(1) }}
        </h1>
        <span class="col-span-3 w-full text-text text-xl">
          {{ ability.effect_entries }}
        </span>
      </div>
      <!--Start of pokemon with this ability that are NOT hidden-->
      <div v-if="ability.pokemon.length>0" class="w-full p-4 col-span-5 rounded-lg border-2 border-bg2 mt-3 bg-bg1 shadow-xl">
        <span class="text-header text-2xl font-semibold w-full">
          Pokemon that have this ability naturally:
        </span>
        <div class="w-full grid grid-cols-6 gap-2">
          <div v-for="poke in ability.pokemon" class="col-span-6 md:col-span-3 xl:col-span-2 grid grid-cols-8 border-2 border-bg2 rounded-full items-center [&>span]:px-2">
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
      <!--Start of pokemon with this ability that are hidden-->
      <div v-if="ability.hiddenPokemon.length>0" class="w-full p-4 col-span-5 rounded-lg border-2 border-bg2 mt-3 bg-bg1 shadow-xl">
        <span class="text-header text-2xl font-semibold w-full">
          Pokemon that have this ability hidden:
        </span>
        <div class="w-full grid grid-cols-6 gap-2">
          <div v-for="poke in ability.hiddenPokemon" class="col-span-6 md:col-span-3 xl:col-span-2 grid grid-cols-8 border-2 border-bg2 rounded-full items-center [&>span]:px-2">
            <img :src="poke.front_sprite" :alt="poke.name+'_sprit'" class="rounded-full bg-bg2 col-span-2">
            <span class="flex items-center col-span-3">
              <RouterLink :to="'../Pokemon/'+poke.name" class="h-full underline hover:text-hover text-xl">
                {{ poke.name.charAt(0).toUpperCase() + poke.name.slice(1) }}
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
  <PageNotFound v-if="state===-1" header="Ability" :message="'Ability: '+route.identifier+' was not found.'"/>
</template>

<style scoped>
</style>