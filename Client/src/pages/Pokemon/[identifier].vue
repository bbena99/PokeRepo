<script setup lang="ts">
import { useRoute } from 'vue-router/auto';
import { getOne } from '../../service';
import { ref } from 'vue';
import { emptyPokemon, Pokémon } from '../../models'

const route = useRoute('/Pokemon/[identifier]');

const curPokemon = ref<Pokémon>(emptyPokemon());
getOne(route.params.identifier,(cb:Pokémon)=>{
  console.log(cb)
  curPokemon.value=cb;
});
</script>

<template>
  <h1>Pokemon/[identifier] page works</h1>
  <p>Your identifier is: {{ route.params.identifier }}</p>
  <p>you found {{ curPokemon.name }}</p>
  <div class="w-64 h-64 bg-red-600">
    box
  </div>
</template>

<style scoped>
</style>
