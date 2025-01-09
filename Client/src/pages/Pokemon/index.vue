<script setup lang="ts">
import { useRoute } from 'vue-router';
import { getAll } from '../../service';
import { ref } from 'vue';
import PokeCard from '../../components/PokeCard.vue';
import { PokémonI } from '../../models';

const route = useRoute();
const query = route.query;
const pokeList = ref<Map<number,PokémonI>>(new Map());

getAll({offset:+(query.offset??0),limit:+(query.limit??50)},(cb:PokémonI)=>{
  pokeList.value.set(cb.id,cb);
});
</script>

<template>
  <div class="w-full h-full flex flex-wrap justify-center">
    <div id="search_bar" class="w-full h-16 flex justify-center items-center">
      <div class="w-3/4 h-full bg-bg2">
        
      </div>
    </div>
    <ul style="height: calc(100% - 4rem);" class="w-full xl:w-3/4 p-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 overflow-y-scroll overflow-x-hidden">
      <li v-for="[_,value] in pokeList" :key="value.name" class="group w-full h-58 col-span-1 z-0 transition-all ease-in-out sm:hover:scale-110 hover:z-10">
        <RouterLink :to="'pokemon/'+value.name" class="w-full h-full drop-shadow-md">
          <PokeCard :pokemon="value"/>
        </RouterLink>
      </li>
    </ul>
  </div>
</template>

<style scoped>
</style>
