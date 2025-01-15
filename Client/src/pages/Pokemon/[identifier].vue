<script setup lang="ts">
//@ts-ignore ts says there is not export at this location, but there is.
import { useRoute } from 'vue-router/auto';
import { ref } from 'vue';
import { getOne } from '../../service';
import { emptyPokemon, PokémonI, TypeI } from '../../models';
import Loading from '../../components/Loading.vue';
import PageNotFound from '../../components/PageNotFound.vue'
import { TYPES } from '../../constants';
import DamageMultiplier from '../../components/DamageMultiplier.vue';
import { RouterLink } from 'vue-router';

const route = useRoute('/Pokemon/[identifier]');

const state = ref<number>(0);
const curPokemon = ref<PokémonI>(emptyPokemon());
const baseStatTotal = ref<number>(0);
const STAT_COLOR = [
  'bg-green-500', //hp
  'bg-red-600',//atk
  'bg-blue-600',//def
  'bg-yellow-400',  //sp atk
  'bg-purple-500',//sp def
  'bg-cyan-500',  //spd
]

getOne(route.params.identifier, (cb: PokémonI) => {
  console.log(cb);
  if(typeof(cb)==='string'){
    state.value = -1;
  }else{
    curPokemon.value = cb
    baseStatTotal.value = Object.values(curPokemon.value.stats).reduce((acc, value) => acc + value, 0);
    state.value = 1;
  }
});
</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1" class="flex justify-center items-center overflow-hidden">
    <div class="grid grid-cols-5 gap-4 w-full h-full xl:w-2/3 overflow-y-scroll">
      <!--Pokemon name, types, and sprites-->
      <div class="w-full h-72 p-4 col-span-5 rounded-lg border-2 border-bg2 mt-3 grid grid-cols-3 bg-bg1 shadow-xl">
        <h1 class="col-span-2 w-full text-header text-7xl">
          {{ curPokemon.name.charAt(0).toUpperCase() + curPokemon.name.slice(1) }}
        </h1>
        <div class="flex row-span-2 justify-center w-full bg-bg2 rounded-full">
          <img class="w-1/2 h-full aspect-square" :src="curPokemon.front_sprite" alt="front-sprite.png">
          <img class="w-1/2 h-full aspect-square" :src="curPokemon.back_sprite" v-if="curPokemon.back_sprite"
          alt="back-default.png">
        </div>
        <div class="flex col-span-2">
          <img v-for="t in curPokemon.types" :src="t.src" :alt="t.name + '.png'" class="max-h-12 md:mx-4">
        </div>
      </div>
      <!--Pokemon Abilities-->
      <div class="w-full p-4 col-span-4 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
        <table>
          <thead>
            <tr class="w-full">
              <th colspan="3">
                <span class="text-header text-lg font-semibold w-full">
                  {{ curPokemon.name.charAt(0).toUpperCase() + curPokemon.name.slice(1) }}'s Abilities:
                </span>
              </th>
            </tr>
            <tr class="*:border-2 *:border-text *:text-header *:bg-bg2">
              <th>
                Name
              </th>
              <th>
                hidden
              </th>
              <th>
                Effect
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="*:border-2 *:border-text *:p-1"
              v-for="ability in curPokemon.abilities"
            >
              <td>
                <RouterLink :to="'../Abilities/'+ability.name" class="hover:text-hover underline">
                  {{ ability.name.charAt(0).toUpperCase() + ability.name.slice(1) }}
                </RouterLink>
              </td>
              <td>
                {{ ability.hidden?'true':'false' }}
              </td>
              <td>
                {{ ability.effect_entries }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--Pokemon damage taken-->
      <div class="row-span-2 w-full p-4 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
        <span class="text-header text-2xl">
          Damage Taken:
        </span>
        <div class="grid grid-cols-1 items-center">
          <DamageMultiplier v-for="(ty, index) in TYPES" :type="ty" :mult="index" />
        </div>
      </div>
      <!--Pokemon Stats-->
      <div class="w-full p-4 col-span-4 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
        <span class="text-header text-lg font-semibold w-full">
          {{ curPokemon.name.charAt(0).toUpperCase() + curPokemon.name.slice(1) }}'s Stats:
        </span>
        <div v-for="(stat, key, index) in curPokemon.stats" :id="curPokemon.name + '_' + key" :key="curPokemon.name + key"
          class="flex justify-between max-w-full h-6 m-1 pr-2 text-bg1 bg-text rounded-full overflow-hidden"
          style="width: calc(50% - 0.5rem);">
          <div :class="'h-full pl-2 flex items-center text-header rounded-full ' + STAT_COLOR[index]"
            :style="{ 'width': (stat! / 210 * 100 + '\%') }">
            {{ stat }}
          </div>
          {{
            //@ts-ignore This method does exist on strings, and works as intended.
            key.replaceAll('-', ' ')
          }}
        </div>
        <div class="flex justify-between w-full h-6 m-1 pr-2 text-bg1 bg-text rounded-full overflow-hidden">
          <div :style="{ 'width': ((baseStatTotal - 175) / 700 * 100 + '\%') }"
            class="h-full pl-2 flex items-center text-header rounded-e-full bg-bg2">
            {{ baseStatTotal }}
          </div>
          base stat total
        </div>
      </div>
    </div>
  </div>
  <PageNotFound v-if="state===-1" header="Pokemon" message="Sent from /Pokemon/[Identifier]"/>
</template>

<style scoped></style>
