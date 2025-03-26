<script setup lang="ts">
import { ref } from 'vue';
import { getAllMoves } from '../../service';
import Loading from '../../components/Loading.vue';
import PageNotFound from '../../components/PageNotFound.vue';
import { DAMAGETYPES } from '../../constants';
import { MovesI } from '../../models';
import { RouterLink, useRoute } from 'vue-router';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPencil } from '@fortawesome/free-solid-svg-icons';

interface moveQueryI{
  name?:string;
  offset?:number;
  limit:number;
  type?:string;
  notType?:string;
  sort?:number;
}

const state = ref<number>(0);
const route = useRoute();
const query = ref<moveQueryI>({
  offset:+(route.query.offset??0),
  limit:+(route.query.limit??50),
  name:route.query.name?.toString(),
  type:route.query.type?.toString(),
  notType:route.query.notType?.toString(),
  sort:+(route.query.sort??0),
});
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
  <div v-if="state===1" class="flex justify-center items-center flex-wrap h-100%">
    <div class="flex items-center justify-evenly w-4/5 xl:w-3/4 h-16 m-4 bg-bg2 rounded-3xl xl:rounded-full">
      <form class="grid grid-cols-12 gap-2 items-center relative w-11/12 p-2">
        <!-- Search Input -->
        <div class="col-span-5 h-12 flex items-center">
          <FontAwesomeIcon :icon="faPencil" class="z-10 h-6 -mr-8"/>
          <input
            type="text"
            class="block w-full h-full p-3 ps-10 text-sm text-text border-none shadow-sm rounded-lg bg-bg1 focus:ring-hover"
            placeholder="Name Search"
          >
        </div>
      </form>
    </div>
    <ul style="height: calc(100% - 6rem);" class="grid grid-cols-12 gap-3 xl:w-2/3 overflow-y-scroll">
      <li v-for="move in moveArray" class="flex col-span-12 sm:col-span-6 md:col-span-4 2xl:col-span-3">
        <RouterLink :to="'Moves/'+move.name" class="w-full flex justify-between rounded-xl shadow border-2 border-bg2 bg-bg1 p-2 pl-3 text-lg underline hover:text-hover hover:border-hover hover:shadow-2xl hover:scale-105">
          <span>
            {{ move.name.charAt(0).toUpperCase() + move.name.slice(1).replace('-',' ') }}
          </span>
          <div class="flex w-28 h-7">
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
      </li>
    </ul>
  </div>
  <PageNotFound v-if="state===-1"/>
</template>

<style scoped>
</style>