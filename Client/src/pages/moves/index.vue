<script setup lang="ts">
import { ref } from 'vue';
import { RouterLink, useRoute } from 'vue-router';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowDownWideShort, faMagnifyingGlass, faPencil, faRotate } from '@fortawesome/free-solid-svg-icons';
import { getAllMoves } from '../../service';
import Loading from '../../components/Loading.vue';
import PageNotFound from '../../components/PageNotFound.vue';
import MegaMenu from '../../components/MegaMenu.vue';
import { DAMAGETYPES, TYPES } from '../../constants';
import { MovesI } from '../../models';

interface moveQueryI{
  name?:string;
  offset?:number;
  limit:number;
  type?:string;
  notType?:string;
  damageType?:string;
  sort?:number;
}

const state = ref<number>(0);
const route = useRoute();
const query = ref<moveQueryI>({
  offset:+(route.query.offset??0),
  limit:+(route.query.limit??50),
  name:route.query.name?.toString(),
  type:route.query.type?.toString(),
  notType:route.query.notType?.toString(),
  damageType:route.query.damageType?.toString(),
  sort:+(route.query.sort??0),
});
let typeArray:number[] = query.value.type?query.value.type.split(',').map(v=>{return +v;}):[];
let notTypeArray:number[] = query.value.notType?query.value.notType.toString().split(',').map(v=>{return +v;}):[];
const list = ref(TYPES.map((T,i)=>{
  let value = 0;
  if(typeArray.includes(i+1))value=1;
  else if(notTypeArray.includes(i+1))value=2;
  const item = {
    name:T.name,
    value:value,
    src:'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-ix/scarlet-violet/'+(i+1)+'.png',
  }
  return item;
}))
let damageTypeArray:number[] = query.value.damageType?query.value.damageType.split(',').map(v=>{return +v;}):[];
const damageTypeList = ref<{name:string;value:number;src:string}[]>([]);
for(let i=1; i<4; i++){
  damageTypeList.value.push({
    name:DAMAGETYPES[i].name??"fail",
    value:damageTypeArray.includes(i)?1:0,
    src:DAMAGETYPES[i].src??"fail"
  })
}
const sortArray = [
  'Number',
  'Name',
  'Damage Type',
  'Type',
]
const moveArray = ref<MovesI[]>([]);
getAllMoves({
  offset:+(query.value.offset??0),
  limit:+(query.value.limit??50),
  name:query.value.name??'',
  type:typeArray,
  notType:notTypeArray,
  damageType:damageTypeArray,
  sort:+(query.value.sort??0)
},(a)=>{
  moveArray.value=[...a];
  console.log(moveArray.value)
  state.value=1;
})
function queryBuilder(){
  state.value=0;
  moveArray.value=[];
  let retQuery:string = '?';

  if(query.value.limit!==50)retQuery+='limit='+query.value.limit+'&';
  if(query.value.offset)retQuery+='offset='+query.value.offset+'&';
  if(query.value.name)retQuery+='name='+query.value.name+'&';
  list.value.forEach((obj,index)=>{
    const id=index+1;
    switch(obj.value){
      case 1:
        if(!typeArray.includes(id))typeArray.push(id);
        notTypeArray = notTypeArray.filter((value)=>{if(value!==id)return true; else return false;})
        // console.log('typeArray:',typeArray);
        break;
      case 2:
        if(!notTypeArray.includes(id))notTypeArray.push(id);
        typeArray = typeArray.filter((value)=>{if(value!==id)return true; else return false;})
        // console.log('notTypeArray',notTypeArray)
        break;
      default:
        typeArray = typeArray.filter((value)=>{if(value!==id)return true; else return false;})
        notTypeArray = notTypeArray.filter((value)=>{if(value!==id)return true; else return false;})
        // console.log('neither',typeArray,notTypeArray)
    }
  })
  if(typeArray.length>0)retQuery+='type='+typeArray.join(',')+'&';
  if(notTypeArray.length>0)retQuery+='notType='+notTypeArray.join(',')+'&';
  damageTypeList.value.forEach((obj,index)=>{
    const id = index+1;
    switch(obj.value){
      case 1:
        if(!damageTypeArray.includes(id))damageTypeArray.push(id);
        break;
      default:
      damageTypeArray = damageTypeArray.filter((value)=>{if(value!==id)return true; else return false;})
    }
  })
  if(damageTypeArray.length>0)retQuery+='gen='+damageTypeArray.join(',')+'&'
  if(query.value.sort)retQuery+='sort='+query.value.sort+'&';
  window.location.href=retQuery;
}
</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1" class="w-full h-full flex flex-wrap content-start items-start justify-center">
    <div id="search_bar" class="w-full mt-4 flex justify-center items-center text-header">
      <div class="flex items-center justify-evenly w-4/5 xl:w-3/4 bg-bg2 rounded-3xl xl:rounded-full">
        <form class="grid grid-cols-12 gap-2 items-center relative w-11/12">
          <!--Search input  -->
          <div class="h-3/4 max-h-14 flex items-center justify-center col-span-12 xl:col-span-5">
            <div class="inset-y-0 start-0 flex items-center ps-3 z-10 -mr-7">
              <FontAwesomeIcon :icon="faPencil"/>
            </div>
            <input 
              type="search"
              id="name-search"
              class="block w-full h-full p-3 ps-10 text-sm text-text border-none shadow-sm rounded-lg bg-bg1 focus:ring-hover"
              placeholder="Name Search"
              v-model="query.name"
            />
          </div>
          <!--Results per page count-->
          <div class="h-3/4 col-span-4 sm:col-span-2 xl:col-span-1 w-full items-center rounded-lg bg-bg1 text-text">
            <select
              id="page-count"
              class="w-full h-full py-0 border-none text-sm rounded-lg block bg-transparent hover:cursor-pointer"
              v-model="query.limit"
              title="page count"
            >
              <option selected value=100>100</option>
              <option value=50> 50</option>
              <option value=100>100</option>
              <option value=200>200</option>
              <option value=300>300</option>
            </select>
          </div>
          <!--Type Menu-->
          <MegaMenu
            id="type_filter"
            btnTitle='Type Filter'
            btnClass='first:pr-1 bg-bg1 w-full h-3/4 col-span-4 sm:col-span-2 xl:col-span-1'
            cols=3
            :list=list
            states=3
          >
            <template v-slot:default="{src,alt}">
              <img :src="src" :alt="alt"/>
            </template>
          </MegaMenu>
          <!--Gen Menu-->
          <MegaMenu
            id="dmg_filter"
            btnTitle='Damage Filter'
            btnClass='first:pr-1 bg-bg1 w-full h-3/4 col-span-4 sm:col-span-2 xl:col-span-1'
            cols=1
            :list="damageTypeList"
            states=3
          >
            <template v-slot:default="{src,alt}">
              <img :src="src" :alt="alt"/>
            </template>
          </MegaMenu>
          <!--Search Button-->
          <button
            type="submit"
            @click="queryBuilder()"
            class="flex items-center col-span-4 sm:col-span-2 xl:col-span-1 justify-center h-3/4 text-header bg-hover hover:bg-bg2 hover:ring-2 hover:ring-hover focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-4 py-2"
          >
            <FontAwesomeIcon :icon="faMagnifyingGlass" class="pr-1"/>
            Search
          </button>
          <!--Reset Filters Button-->
          <button
            type="button"
            id="reset-button"
            title="Single click to reset; Double to refresh page"
            @click="query.limit=50;query.offset=undefined;query.name=undefined;list.forEach((item)=>{item.value=0});damageTypeList.forEach((item)=>{item.value=0});query.sort=0;"
            @dblclick="query.limit=50;query.offset=undefined;query.name=undefined;list.forEach((item)=>{item.value=0});damageTypeList.forEach((item)=>{item.value=0});query.sort=0;queryBuilder();"
            class="flex items-center justify-center col-span-4 sm:col-span-2 xl:col-span-1 h-3/4 text-header bg-hover hover:bg-bg2 hover:ring-2 hover:ring-hover focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-4 py-2"
          >
            <FontAwesomeIcon :icon="faRotate" class="pr-1"/>
            Reset Filters
          </button>
          <!--Sort selector-->
          <div class="h-3/4 flex items-center col-span-4 sm:col-span-2 rounded-lg bg-bg1 text-text pl-2">
            <label for="sort_selection"><FontAwesomeIcon :icon="faArrowDownWideShort"></FontAwesomeIcon></label>
            <select
              id="sort_selection"
              title="sorting"
              class="w-full bg-transparent h-full py-0 border-none text-sm rounded-lg block hover:cursor-pointer"
              v-model="query.sort"
            >
              <option v-for="(val,index) in sortArray" :value="index">{{ val }}</option>
            </select>
          </div>
        </form>
      </div>
    </div>
    <ul style="max-height: calc(100vh - 10rem);" class="grid grid-cols-12 gap-3 xl:w-2/3 p-4 overflow-y-scroll">
      <li v-for="move in moveArray" class="flex col-span-12 sm:col-span-6 md:col-span-4 2xl:col-span-3">
        <RouterLink :to="'Moves/'+move.name" class="w-full flex justify-between rounded-xl shadow border-2 border-bg2 bg-bg1 p-2 pl-3 text-lg underline hover:text-hover hover:border-hover hover:shadow-2xl hover:scale-105">
          <span>
            {{ move.name.charAt(0).toUpperCase() + move.name.slice(1).replace('-',' ') }}
          </span>
          <div class="flex w-28 h-7">
            <img 
            :src="DAMAGETYPES[move.damage_type].src"
            :alt="DAMAGETYPES[move.damage_type].src"
            class="w-1/3 h-full"
            >
            <img
            :src="'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/types/generation-ix/scarlet-violet/'+move.type+'.png'"
            :alt="'type'+move.type"
            class="w-2/3 h-full"
            >
          </div>
        </RouterLink>
      </li>
    </ul>
  </div>
  <PageNotFound v-if="state===-1"/>
</template>

<style scoped>
</style>