<script setup lang="ts">
const props = defineProps(['mult']);
const obj = {mult:(+props.mult),color:''};
switch(obj.mult){
  case 0:
    obj.color='bg-stone-900 text-gray-300';
    break;
  case 1:
    obj.color='bg-gray-300 text-stone-800';
    break;
  case 2:
    obj.color='bg-yellow-400 text-gray-900';
    break;
  case 4:
    obj.color='bg-red-600 text-gray-900';
}

</script>

<template>
  <div :class="'w-full h-full rounded-full '+obj.color">
    {{ obj.mult+'x' }}
  </div>
</template>

<style scoped>
</style>