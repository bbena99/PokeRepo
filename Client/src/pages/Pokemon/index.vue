<script setup lang="ts">
import { useRoute } from 'vue-router';
import { getAll, getOne } from '../../service';
import { ref } from 'vue';
import PokeCard from '../../components/PokeCard.vue';
import { PokémonI, standardPair } from '../../models';

const route = useRoute();
const query = route.query;
const pokeList = ref<Map<number,PokémonI>>(new Map());
console.log(pokeList.value);

getAll({offset:+(query.offset??0),limit:+(query.limit??50)},cb=>{
  cb.results.forEach((poke:standardPair)=>{
    getOne(poke.name,(a:PokémonI)=>{
      pokeList.value.set(a.id,a);
    })
  })
});
</script>

<template>
  <div class="w-full h-full flex flex-wrap justify-center">
    <div id="search_bar" class="w-full h-16 flex justify-center items-center">
      <div class="w-3/4 h-full bg-bg2">
        
      </div>
    </div>
    <ul style="height: calc(100% - 4rem);" class="w-full xl:w-3/4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 overflow-y-scroll overflow-x-visible">
      <li v-for="[_,value] in pokeList" :key="value.name" class="group w-full min-h-48 p-2 col-span-1 z-0 hover:scale-[1.3] hover:z-10">
        <RouterLink :to="'pokemon/'+value.name" class="w-full h-full drop-shadow-md">
          <PokeCard :pokemon="value"/>
        </RouterLink>
      </li>
    </ul>
  </div>
</template>

<style scoped>
</style>
