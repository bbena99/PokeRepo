<script setup lang="ts">
//@ts-ignore ts says there is not export at this location, but there is.
import { useRoute } from 'vue-router/auto';
import { ref } from 'vue';
import { getOne, getParse } from '../../service';
import { emptyPokemon, PokémonI, TypeI } from '../../models';
import { TYPES } from '../../constants';
import DamageMultiplier from '../../components/DamageMultiplier.vue';
import { RouterLink } from 'vue-router';

const route = useRoute('/Pokemon/[identifier]');

const curPokemon = ref<PokémonI>(emptyPokemon());
const baseStatTotal = ref<number>(0);
const typeData = ref<TypeI[]>([]);
const STAT_COLOR = [
  'bg-green-500', //hp
  'bg-red-600',//atk
  'bg-blue-600',//def
  'bg-yellow-400',  //sp atk
  'bg-purple-500',//sp def
  'bg-cyan-500',  //spd
]

getOne(route.params.identifier, (cb: PokémonI) => {
  curPokemon.value = cb
  baseStatTotal.value = Object.values(curPokemon.value.stats).reduce((acc, value) => acc + value, 0);
});
</script>

<template>
  <div class="flex justify-center items-center">
    <div class="grid grid-cols-5 gap-4 w-full h-full xl:w-2/3 overflow-y-scroll">
      <!--Pokemon name, types, and sprites-->
      <div class="w-full h-72 p-4 col-span-5 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
        <div class="w-2/3 flex flex-wrap">
          <h1 class="w-full text-header text-7xl">
            {{ curPokemon.name.charAt(0).toUpperCase() + curPokemon.name.slice(1) }}
          </h1>
          <img v-for="t in curPokemon.types" :src="t.src" :alt="t.name + '.png'" class="max-h-12 md:mx-4">
        </div>
        <div class="flex justify-center w-1/3 bg-bg2 rounded-full">
          <img class="w-1/2 aspect-square" :src="curPokemon.front_sprite" alt="front-sprite.png">
          <img class="w-1/2 aspect-square" :src="curPokemon.back_sprite" v-if="curPokemon.back_sprite"
            alt="back-default.png">
        </div>
      </div>
      <!--Pokemon Abilities-->
      <div class="w-full p-4 col-span-4 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
        <table>
          <thead>
            <tr class="w-full">
              <th colspan="3">
                <span class="text-header text-lg font-semibold w-full">
                  {{ curPokemon.name.charAt(0).toUpperCase() + curPokemon.name.slice(1) }}'s abilities:
                </span>
              </th>
            </tr>
            <tr>
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
              v-for="ability in curPokemon.abilities"
            >
              <td>
                <RouterLink :to="'ability/'+ability.name">
                  {{ ability.name }}
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
          {{ curPokemon.name.charAt(0).toUpperCase() + curPokemon.name.slice(1) }}'s stats:
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
</template>

<style scoped></style>
