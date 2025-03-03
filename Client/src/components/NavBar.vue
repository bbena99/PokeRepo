<script setup lang="ts">
import { faBars, faMoon, faSun } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref } from 'vue';

const props = defineProps(['theme','setTheme','secondary','setSecondary'])
const display = ref<string>("hidden ");
function displayChanger(){
  display.value = display.value==="hidden "?"inline-block ":"hidden ";
}
function themeChanger(){
  const newTheme = props.theme==='light'?('dark'):('light');
  props.setTheme(newTheme);
}
</script>

<template>
  <nav class="bg-bg2 text-header">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
      <RouterLink to="/" class="flex items-center space-x-3 rtl:space-x-reverse">
          <img src="../assets/images/Brock.png" class="h-12" alt="Flowbite Logo" />
          <span class="self-center text-2xl font-semibold whitespace-nowrap">
            Brock Wiki
          </span>
      </RouterLink>
      <button type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm rounded-lg md:hidden" @click="displayChanger()">
          <span class="sr-only">Open main menu</span>
          <FontAwesomeIcon class="text-2xl" :icon="faBars"/>
      </button>
      <div :class="display+'w-full h-auto md:block md:w-auto'" id="navbar-default">
        <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 [&>li]:flex">
          <li>
            <RouterLink to="/Abilities" class="block py-2 px-3 rounded hover:text-hover md:bg-transparent" aria-current="page">
              Abilities
            </RouterLink>
          </li>
          <li>
            <RouterLink to="/Moves" class="block py-2 px-3 rounded hover:text-hover md:bg-transparent" aria-current="page">
              Moves
            </RouterLink>
          </li>
          <li>
            <RouterLink to="/Pokemon" class="block py-2 px-3 rounded hover:text-hover md:bg-transparent" aria-current="page">
              Pokemon
            </RouterLink>
          </li>
          <li>
            <button type="button" @click="themeChanger()">
              <FontAwesomeIcon class="text-2xl" v-if="props.theme==='light'" :icon="faSun"/>
              <FontAwesomeIcon class="text-2xl" v-else :icon="faMoon"/>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<style scoped>
</style>
