<script setup lang="ts">
import { ref } from "vue";
import  NavBar  from "./components/NavBar.vue";

const theme = ref(localStorage.getItem('theme')??'light');
const secondary = ref(localStorage.getItem('Theme')??'');
</script>

<template>
  <div :class="[theme,secondary]">
    <NavBar />
    <div class="w-screen overflow-scroll"style="height: calc(100vh - 5rem);">
      <RouterView/>
    </div>
  </div>
</template>

<style scoped>
</style>
