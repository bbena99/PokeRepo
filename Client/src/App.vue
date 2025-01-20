<script setup lang="ts">
import { ref } from "vue";
import  NavBar  from "./components/NavBar.vue";

const theme = ref(localStorage.getItem('theme')??'light');
const secondary = ref(localStorage.getItem('Theme')??'');
const setTheme = (newTheme:string)=>{
  localStorage.setItem('theme',newTheme);
  theme.value=newTheme;
}
const setSecondary = (newTheme:string)=>{
  localStorage.setItem('secondary',newTheme);
  secondary.value=newTheme;
}
</script>

<template>
  <div :class="[theme,secondary]" class=" font-sans">
    <NavBar :theme :setTheme :secondary :setSecondary/>
    <div class="w-screen bg-gradient-to-b from-bg1 to-bg2 text-text overflow-y-scroll"style="height: calc(100vh - 5rem);">
      <RouterView/>
    </div>
  </div>
</template>

<style scoped>
</style>
