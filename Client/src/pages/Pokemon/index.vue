<script setup lang="ts">
import { useRoute } from 'vue-router';
import { getAll } from '../../service';
import { ref } from 'vue';
import PokeCard from '../../components/PokeCard.vue';

const route = useRoute();
const query = route.query;
const pokeList = ref<{name:string,url:string}[]>([]);
console.log(pokeList.value);

getAll({offset:+(query.offset??0),limit:+(query.limit??60)},cb=>{
  console.log(cb)
  pokeList.value=cb.results;
});
</script>

<template>
  <div class="w-full h-full flex flex-wrap justify-center">
    <div id="search_bar" class="w-full h-16 flex justify-center items-center">
      <div class="w-3/4 h-full bg-bg2">
        
      </div>
    </div>
    <ul style="height: calc(100% - 4rem);" class="w-full xl:w-3/4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 overflow-y-scroll overflow-x-hidden">
      <li v-for="poke in pokeList" :key="poke.name" class="w-full min-h-48 p-2 col-span-1">
        <RouterLink :to="'pokemon/'+poke.name" class="w-full h-full drop-shadow-md">
          <PokeCard :poke/>
        </RouterLink>
      </li>
    </ul>
  </div>
</template>

<style scoped>
</style>
