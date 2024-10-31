<script setup lang="ts">
  import { getAll } from '../service';
  getAll(cb=>{
    console.log(cb)
  });
</script>

<template>
  <h1>Pokemon page works</h1>
</template>

<style scoped>
</style>
