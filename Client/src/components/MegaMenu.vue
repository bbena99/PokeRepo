<script setup lang="ts">
import { ref } from "vue";
const props = defineProps(['btnTitle','id','list','cols']);
const display = ref<string>("hidden ");
const bgRef = ref<string[]>([""].fill("",0,props.list.length-1));
function clickHandler(){
  if(display.value==="hidden ")display.value="grid "
  else display.value="hidden "
}
function bgHandler(value:number,index:number){
  switch(value){
    case 0:
      bgRef.value[index]="";
      break;
    case 1:
      bgRef.value[index]=" bg-green-800";
      break;
    case 2:
      bgRef.value[index]=" bg-red-800";
      break;
    default:
      bgRef.value[index]=" FAILURE";//This should never happen
  }
}
</script>

<template>
<button 
  :data-collapse-toggle=props.id
  type="button"
  class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-header rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
  :aria-controls=props.id
  aria-expanded="false"
  @click="clickHandler()"
>
    <span class="sr-only text-header">{{ props.btnTitle }}</span>
</button>
<div :id=props.id :class="display+'absolute z-10 w-auto text-sm border rounded-lg shadow-md bg-bg1 border-bg2'">
  <button 
    type="button"
    v-for="(item,index) in props.list"
    :key="item.key"
    :class="'p-2 m-2 rounded-lg'+bgRef[index]"
    @click="item.value++;item.value%=3;bgHandler(item.value,index);"
  >
    {{ item.key }}
  </button>
</div>
</template>

<style scoped>
</style>