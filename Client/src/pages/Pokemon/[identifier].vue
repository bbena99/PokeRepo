<script setup lang="ts">
  import { useRoute } from 'vue-router/auto';
  import { getOne } from '../../service';

  const route = useRoute('/Pokemon/[identifier]');
  
  console.log()
  let curPokemon;
  getOne(route.params.identifier,cb=>{
    console.log(cb)
    curPokemon=cb;
  });
</script>

<template>
  <h1>Pokemon/[identifier] page works</h1>
  <p>Your identifier is: {{ route.params.identifier }}</p>
  <p>you found {{ curPokemon?.name }}</p>
</template>

<style scoped>
</style>
