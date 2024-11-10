<script setup lang="ts">
import { standardPair } from '../models';

const props = defineProps(['mult','type']);
const obj = {mult:(+props.mult),color:''};
const type:standardPair = props.type;
console.log(type)
switch(obj.mult){
  case 0:
    obj.color='bg-stone-900 text-gray-300';
    break;
  case 2:
    obj.color='bg-yellow-400 text-gray-900';
    break;
  case 4:
    obj.color='bg-red-600 text-gray-900';
    break;
  default:
    obj.color='bg-gray-300 text-stone-800';
    break;
}

</script>

<template>
  <div :class="'w-24 h-3/6 rounded-full pr-2 flex justify-between items-center '+obj.color">
    <img 
      :src="'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-ix/scarlet-violet/'+type.url.split('/')[6]+'.png'"
      :alt="type.name+'.png'"
      class="w-20 h-full rounded-full"
    >
    {{ obj.mult+'x' }}
  </div>
</template>

<style scoped>
</style>