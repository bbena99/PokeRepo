<script setup lang="ts">
  import { useRoute } from 'vue-router';
  import { getAll } from '../../service';

  const route = useRoute();
  const query = route.query;
  console.log(query)
  getAll({offset:+(query.offset??0),limit:+(query.limit??20)},cb=>{
    console.log(cb)
  });
</script>

<template>
  <h1>Pokemon page works</h1>
</template>

<style scoped>
</style>
