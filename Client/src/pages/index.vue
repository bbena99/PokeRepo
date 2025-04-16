<script setup lang="ts">
import { RouterLink } from 'vue-router';
import { faShare } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { DAMAGETYPES } from '../constants';
const moveArray = DAMAGETYPES.slice(1)
</script>

<template>
<div class="flex justify-center flex-wrap w-full h-full">
  <!-- Jumbotron -->
  <div class="flex justify-center flex-wrap w-full h-96 my-8 p-8 rounded-3xl bg-bg2">
    <h1 class="flex justify-center w-full text-header text-5xl font-bold">
      Welcome to Brock's Wiki
    </h1>
    <span class="text-text text-lg">
      The goal of this website is to provide pokemon fans with a wiki that looks nice and shows as much prevalent details about the pokemon they are looking for data on.
    </span>
  </div>
  <div class="grid grid-cols-6 gap-4 w-full md:w-5/6 lg:w-4/5 2xl:w-3/4 h-96">
    <RouterLink to="/pokemon" class="col-span-6 md:col-span-3 lg:col-span-2 flex flex-wrap justify-center justify-items-center w-full h-full bg-bg1 shadow-xl rounded-xl overflow-hidden md:hover:scale-105 hover:shadow-2xl hover:text-hover transition-all">
      <img
        src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png"
        alt="Pikachu.png"
        class="md:h-2/3"
        >
      <span class="flex justify-center items-center md:w-full h-full md:h-1/3 text-xl">
        Pokemon list &nbsp;
        <FontAwesomeIcon :icon="faShare"/>
      </span>
    </RouterLink>
    <RouterLink to="/move" class="col-span-6 md:col-span-3 lg:col-span-2 flex flex-wrap justify-center justify-items-center w-full h-full bg-bg1 shadow-xl rounded-xl overflow-hidden md:hover:scale-105 hover:shadow-2xl hover:text-hover transition-all">
      <div class="md:w-full h-2/3 md:p-12 md:pt-14 grid grid-cols-2 items-center justify-items-center">
        <img
          v-for="img in moveArray"
          :src="img.src"
          :alt="img.name"
          class="last:col-span-2 w-2/3 last:w-1/3"
        >
      </div>
      <span class="flex justify-center items-center md:w-full h-full md:h-1/3 text-xl">
        Move list &nbsp;
        <FontAwesomeIcon :icon="faShare"/>
      </span>
    </RouterLink>
  </div>
</div>
</template>

<style scoped>
</style>
