<script setup lang="ts">
import { useRoute } from 'vue-router';
import { getAll } from '../../service';
import { ref } from 'vue';

const route = useRoute();
const query = route.query;
const pokeList = ref<{name:string,url:string}[]>([]);
console.log(pokeList.value);

getAll({offset:+(query.offset??0),limit:+(query.limit??1302)},cb=>{
  console.log(cb)
  pokeList.value=cb.results;
});
function getPokéSrc(url:string):string{
  const index = url.match(/\d+/g)?.map(Number)[1];
  return `https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${index}.png`
}
</script>

<template>
  <div class="w-full h-full flex justify-center">
    <ul class="w-full xl:w-3/4 grid grid-cols-12 overflow-y-scroll overflow-x-hidden">
      <li v-for="poke in pokeList" :key="poke.name" onclick="">
        <RouterLink :to="'pokemon/'+poke.name" class="w-full h-full">
          <img
            class="w-24 h-24"
            :src=getPokéSrc(poke.url)
          />
          {{ poke.name }}
        </RouterLink>
      </li>
    </ul>
  </div>
</template>

<style scoped>
</style>
