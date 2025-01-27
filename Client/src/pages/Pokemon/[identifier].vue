<script setup lang="ts">
//@ts-ignore ts says there is not export at this location, but there is.
import { useRoute } from 'vue-router/auto';
import { ref } from 'vue';
import { getOne } from '../../service';
import { emptyPokemon, PokémonI } from '../../models';
import Loading from '../../components/Loading.vue';
import PageNotFound from '../../components/PageNotFound.vue'
import { TYPES } from '../../constants';
import DamageMultiplier from '../../components/DamageMultiplier.vue';
import { RouterLink } from 'vue-router';

const route = useRoute('/Pokemon/[identifier]');

const state = ref<number>(0);

const curPokemon = ref<PokémonI>(emptyPokemon());
const tempArray:number[] = [];
tempArray[19]=1;
tempArray.fill(1);
const typeRelations = ref<number[]>(tempArray);
const baseStatTotal = ref<number>(0);
const STAT_COLOR = [
  'bg-green-500',   //hp
  'bg-red-600',     //atk
  'bg-blue-600',    //def
  'bg-yellow-400',  //sp atk
  'bg-purple-500',  //sp def
  'bg-cyan-500',    //spd
]

getOne(route.params.identifier, (cb: PokémonI) => {
  if(typeof(cb)==='string'){
    state.value = -1;
  }else{
    curPokemon.value = cb
    baseStatTotal.value = Object.values(curPokemon.value.stats).reduce((acc, value) => acc + value, 0);
    state.value = 1;
    //Updating Type relations for the dmg taken section
    curPokemon.value.types.forEach(type=>{
      Object.keys(type.relations).forEach((key)=>{
        switch(type.relations[+key]){
          case 1:// 1/2 damage case.
            tempArray[+key-1]/=2;
            break;
          default:// no damage and double damage case.
            tempArray[+key-1]*=type.relations[+key];
        }
      })
    })
    typeRelations.value = tempArray;
    console.log(curPokemon.value)
  }
});
</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1" class="flex justify-center items-center py-4">
    <div class="grid grid-cols-5 gap-4 w-full h-full xl:w-2/3">
      <!--Pokemon name, types, and sprites-->
      <div class="w-full h-72 p-4 col-span-5 rounded-lg border-2 border-bg2 mt-3 grid grid-cols-3 bg-bg1 shadow-xl">
        <h1 class="col-span-2 w-full text-header text-7xl">
          {{ curPokemon.name.charAt(0).toUpperCase() + curPokemon.name.slice(1) }}
        </h1>
        <div class="flex row-span-2 justify-center w-full bg-bg2 rounded-full">
          <img class="w-1/2 h-full aspect-square" :src="curPokemon.front_sprite" alt="front-sprite.png">
          <img class="w-1/2 h-full aspect-square" :src="curPokemon.back_sprite" v-if="curPokemon.back_sprite" alt="back-default.png">
        </div>
        <div class="flex col-span-2">
          <img v-for="t in curPokemon.types" :src="t.src" :alt="t.name + '.png'" class="max-h-12 md:mx-4">
        </div>
      </div>
      <!--Pokemon Abilities-->
      <div class="w-full p-4 col-span-4 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
        <span class="text-header text-2xl font-semibold w-full">
          {{ curPokemon.name.charAt(0).toUpperCase() + curPokemon.name.slice(1) }}'s Abilities
        </span>
        <div class="grid grid-cols-12 items-center w-full rounded-xl border-2 border-text overflow-hidden [&>span]:text-header [&>span]:h-full [&>span]:p-2 [&>span]:flex [&>span]:items-center [&>span]:bg-bg2">
          <span class="border-r-2 border-header col-span-2">Name:</span>
          <span class="border-r-2 border-header col-span-2">Hidden:</span>
          <span class="col-span-8">Effect:</span>
          <div v-for="ability in curPokemon.abilities" class="grid grid-cols-12 items-center col-span-12 w-full h-full border-t-2 border-header *:p-2">
            <RouterLink :to="'../Abilities/'+ability.name" class="col-span-2 h-full flex items-center border-r-2 border-header underline hover:text-hover">
              {{ ability.name }}
            </RouterLink>
            <span class="flex items-center h-full border-r-2 border-header col-span-2">{{ ability.hidden?'true':'false' }}</span>
            <span class="col-span-8">{{ ability.effect_entries }}</span>
          </div>
        </div>
      </div>
      <!--Pokemon damage taken-->
      <div class="row-span-2 w-full p-4 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
        <span class="text-header text-2xl font-semibold w-full">
          Damage Taken:
        </span>
        <div class="grid grid-cols-1 items-center">
          <DamageMultiplier v-for="(ty, index) in TYPES" :type="ty" :mult="typeRelations[index]" />
        </div>
      </div>
      <!--Pokemon Stats-->
      <div class="w-full p-4 col-span-4 rounded-lg border-2 border-bg2 mt-3 flex flex-wrap bg-bg1 shadow-xl">
        <span class="text-header text-2xl font-semibold w-full">
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
      <!-- Start of moves section -->
      <div class="w-full p-4 col-span-5 rounded-lg border-2 border-bg2 mt-3 bg-bg1 shadow-xl">
        <span class="text-header text-2xl font-semibold w-full">
          {{ curPokemon.name.charAt(0).toUpperCase() + curPokemon.name.slice(1) }}'s Moves:
        </span>
        <div v-for="(method) in Object.keys(curPokemon.moves)" class="w-full p-2">
          <!--@vue-ignore  Sometimes pokemon don't have "other moves", so I added a conditional for that.-->
          <span v-if="curPokemon.moves[method].length>0" class="w-full text-xl text-header">
            {{  method==='level'?"Learned by leveling up:":
                method==='egg'?"Learned by egg inheritance:":
                method==='machine'?"Learned by technical machine":
                  "Learned by other means:" }}
          </span>
          <div class="w-full grid grid-cols-12 rounded-xl border-2 border-text overflow-hidden [&>span]:px-2 [&>span]:bg-bg2 [&>span]:text-header [&>span]:border-r-2 [&>span]:border-text">
            <!--@vue-ignore This attribute does exist-->
            <span class="col-span-2">Name</span>
            <!--@vue-ignore This attribute does exist-->
            <span class="col-span-1">Level</span>
            <span class="col-span-1">Damage Type</span>
            <span class="col-span-1">Accuracy</span>
            <span class="col-span-1">Power</span>
            <span class="col-span-6 !border-r-0">Effect Entry</span>
            <!--@vue-ignore  Move does exist on curPokemon.moves[method] since method is created from the keys of curPokemon.moves-->
            <div v-for="move in curPokemon.moves[method]" class="col-span-12 grid grid-cols-12 border-b-2 border-text [&>span]:px-2 [&>span]:border-r-2 [&>span]:border-text">
              <RouterLink :to="'../moves/'+move.name" class="col-span-2 px-2 border-r-2 border-text underline hover:text-hover">{{ move.name }}</RouterLink>
              <span class="col-span-1">{{ move.level }}</span>
              <span class="col-span-1">{{ move.damage_type }}</span>
              <span class="col-span-1">{{ move.accuracy }}</span>
              <span class="col-span-1">{{ move.power }}</span>
              <span class="col-span-6 !border-r-0">{{ move.effect_entry }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <PageNotFound v-if="state===-1" header="Pokemon" :message="`Couldn't find the data for ${route.params.identifier}`"/>
</template>

<style scoped></style>
