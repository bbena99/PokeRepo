<script setup lang="ts">
import { useRoute } from 'vue-router';
import { getAll } from '../../service';
import { ref } from 'vue';

const route = useRoute();
const query = route.query;
const pokeList = ref<{name:string,url:string}[]>([]);
console.log(pokeList.value);

getAll({offset:+(query.offset??0),limit:+(query.limit??20)},cb=>{
  console.log(cb)
  pokeList.value=cb.results;
  console.log(pokeList.value)
});
</script>

<template>
  <h1>Pokemon page works</h1>
  <ul>
    <li v-for="poke in pokeList" :key="poke.name">{{ poke.name }}</li>
  </ul>
</template>

<style scoped>
</style>
