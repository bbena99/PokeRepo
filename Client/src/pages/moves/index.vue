<script setup lang="ts">
import { ref } from 'vue';
import { getAllMoves } from '../../service';
import Loading from '../../components/Loading.vue';
import PageNotFound from '../../components/PageNotFound.vue';
import { DAMAGETYPES } from '../../constants';
import { MovesI } from '../../models';
import { RouterLink } from 'vue-router';


const state = ref<number>(0);
const moveArray = ref<MovesI[]>([]);
getAllMoves({
  limit: 0,
  offset: 0
},(a)=>{
  moveArray.value=[...a];
  state.value=1;
})
</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1" class="flex justify-center items-center py-4">
    <div class="grid grid-cols-12 gap-3 xl:w-2/3">
      <RouterLink v-for="move in moveArray" :to="'Moves/'+move.name" class="flex justify-between col-span-6 md:col-span-4 xl:col-span-3 rounded-xl shadow border-2 border-bg2 bg-bg1 p-2 text-lg underline hover:text-hover hover:border-hover hover:shadow-2xl hover:scale-105">
        {{ move.name.charAt(0).toUpperCase() + move.name.slice(1).replace('-',' ') }}
        <div class="flex w-28 h-8">
          <img 
            :src="DAMAGETYPES[move.damage_type].src"
            :alt="DAMAGETYPES[move.damage_type].src"
            class="w-1/3 h-full"
          >
          <img
            :src="'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-ix/scarlet-violet/'+move.type_id+'.png'"
            :alt="'type'+move.type_id"
            class="w-2/3 h-full"
          >
        </div>
      </RouterLink>
    </div>
  </div>
  <PageNotFound v-if="state===-1"/>
</template>

<style scoped>
</style>