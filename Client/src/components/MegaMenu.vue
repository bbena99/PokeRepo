<script setup lang="ts">
import { ref } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faChevronDown, faChevronUp } from "@fortawesome/free-solid-svg-icons";
const props = defineProps(['btnTitle','btnClass','id','list','cols','menuClass','itemClass','states']);
const display = ref<string>("hidden ");
const bgRef = ref<string[]>([""].fill("",0,props.list.length-1));
function clickHandler(){
  if(display.value==="hidden ")display.value="grid "
  else display.value="hidden "
}
bgHandler();
function bgHandler(){
  //@ts-ignore
  props.list.forEach((obj,index) => {
    switch(obj.value){
      case 0:
        bgRef.value[index]="";
        break;
      case 1:
        bgRef.value[index]=" bg-green-700";
        break;
      case 2:
        bgRef.value[index]=" bg-red-700";
        break;
      default:
        bgRef.value[index]=" FAILURE";//This should never happen
    }
  });
}
</script>

<template>
<button 
  type="button"
  :class="'flex items-center justify-center p-3 text-header rounded-lg '+props.btnClass"
  @click="clickHandler()"
>
  <span class="mr-2">{{ props.btnTitle }}</span>
  <font-awesome-icon v-if="display==='grid '" :icon="faChevronUp"/>
  <font-awesome-icon v-else :icon="faChevronDown"/>
  <div :id=props.id :class="display+'grid-cols-'+props.cols+' absolute top-full z-20 w-auto h-auto border rounded-lg shadow-md bg-bg1 border-bg2 '+props.menuClass">
    <button 
      type="button"
      v-for="(item,index) in props.list"
      :key="item.name+'key'"
      :class="'p-2 m-2 rounded-2xl'+bgRef[index]+' '+props.itemClass"
      @click="item.value++;item.value%=props.states;bgHandler()"
    >
      <slot :src="item.src" :alt="item.name+'.png'">
      </slot>
    </button>
  </div>
</button>
<div v-if="display==='grid '" @click="clickHandler()" class="fixed w-screen h-screen z-10 top-0 left-0">

</div>
</template>

<style scoped>
</style>