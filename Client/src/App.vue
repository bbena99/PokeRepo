<script setup lang="ts">
import { ref } from "vue";
import  NavBar  from "./components/NavBar.vue";

const theme = ref(localStorage.getItem('theme')??'light');
const secondary = ref(localStorage.getItem('Theme')??'');
</script>

<template>
  <div :class="[theme,secondary]">
    <NavBar />
    <RouterView/>
  </div>
</template>

<style scoped>
</style>
