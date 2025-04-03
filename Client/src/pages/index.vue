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
    <RouterLink to="/pokemon" class="col-span-6 md:col-span-3 lg:col-span-2 grid grid-cols-3 justify-center justify-items-center w-full h-full bg-bg1 shadow-xl rounded-xl overflow-hidden hover:scale-105 hover:shadow-2xl hover:text-hover transition-all">
      <img
        src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png"
        alt="Pikachu.png"
        class="md:col-span-3 w-full"
        >
      <span class="col-span-2 md:col-span-3 text-xl">
        Pokemon list
        <FontAwesomeIcon :icon="faShare"/>
      </span>
    </RouterLink>
    <RouterLink to="/move" class="col-span-6 md:col-span-3 lg:col-span-2 grid grid-cols-3 justify-center justify-items-center w-full h-full bg-bg1 shadow-xl rounded-xl overflow-hidden hover:scale-105 hover:shadow-2xl hover:text-hover transition-all">
      <div class="md:col-span-3 w-full grid grid-cols-2 justify-items-center">
        <img
          v-for="img in moveArray"
          :src="img.src"
          :alt="img.name"
          class="last:col-span-2"
        >
      </div>
    </RouterLink>
  </div>
</div>
</template>

<style scoped>
</style>
