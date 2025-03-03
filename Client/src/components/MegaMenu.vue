<script setup lang="ts">
import { ref } from "vue";
const props = defineProps(['btnTitle','id','list','cols']);
const display = ref<string>("hidden ");
function clickHandler(){
  if(display.value==="hidden ")display.value="grid "
  else display.value="hidden "
}
</script>

<template>
<button 
  :data-collapse-toggle=props.id
  type="button"
  class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-header rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
  :aria-controls=props.id
  aria-expanded="false"
  v-on:click="clickHandler()"
>
    <span class="sr-only text-header">{{ props.btnTitle }}</span>
</button>
<div :id=props.id :class="display+'absolute z-10 w-auto text-sm border rounded-lg shadow-md bg-bg1 border-bg2'">
  <button type="button" v-for="item in props.list" :key="item.key">
    {{ item.key }}
  </button>
</div>
</template>

<style scoped>
</style>