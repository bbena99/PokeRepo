<script setup lang="ts">
  import { getOne } from '../../service';
  getOne('',cb=>{
    console.log(cb)
  });
</script>

<template>
  <h1>Pokemon page works</h1>
</template>

<style scoped>
</style>
