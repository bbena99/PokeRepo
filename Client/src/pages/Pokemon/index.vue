<script setup lang="ts">
import { useRoute } from 'vue-router';
import { ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faMagnifyingGlass, faPen, faRotate } from '@fortawesome/free-solid-svg-icons';
import { getAll } from '../../service';
import PokeCard from '../../components/PokeCard.vue';
import Loading from '../../components/Loading.vue';
import { PokémonI } from '../../models';
import MegaMenu from '../../components/MegaMenu.vue';
import { TYPES } from '../../constants';

interface pokeQueryI{
  offset?:number;
  limit:number;
  name?:string;
  type?:string;
  notType?:string;
}

const route = useRoute();
const query = ref<pokeQueryI>({
  offset:undefined,
  limit:50,
  name:undefined,
  type:undefined,
  notType:undefined,
  ...route.query
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
const genList = ref([{}]);
genList.value.pop();
for(let i=1; i<10; i++){
  genList.value.push({
    name:"Gen-"+i,
    value:0,
    src:"Gen "+i
  })
}
const pageNumber = Math.floor(query.value.offset??0/query.value.limit);
const state = ref<number>(0);
const pokeList = ref<Map<number,PokémonI>>(new Map());

getAll({offset:+(query.value.offset??0),limit:+(query.value.limit??50),name:query.value.name??'',type:typeArray,notType:notTypeArray},(cb:PokémonI)=>{
  pokeList.value.set(cb.id,cb);
  state.value=1
});
function queryBuilder(){
  state.value=0;
  pokeList.value.clear();
  let retQuery:string = '';

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
  getAll({offset:+(query.value.offset??0),limit:+(query.value.limit??50),name:query.value.name??'',type:typeArray,notType:notTypeArray},(cb:PokémonI)=>{
    pokeList.value.set(cb.id,cb);
    state.value=1;
  });
}
</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1" class="w-full h-full flex flex-wrap content-start items-start justify-center">
    <div id="search_bar" class="w-full h-16 mt-4 flex justify-center items-center text-header">
      <div class="flex items-center justify-evenly w-3/4 h-16 bg-bg2 rounded-full">
        <form class="grid grid-cols-12 gap-2 items-center relative w-11/12">
          <div class="h-3/4 flex items-center justify-center col-span-6">
            <div class="inset-y-0 start-0 flex items-center ps-3 z-10 -mr-7">
              <FontAwesomeIcon :icon="faPen"/>
            </div>
            <input 
              type="search"
              id="name-search"
              class="block w-full h-full p-3 ps-10 text-sm text-text border-none shadow-sm rounded-lg bg-bg1 focus:ring-hover"
              placeholder="Name Search"
              v-model="query.name"
            />
          </div>
          <div class="h-3/4 col-span-2 grid grid-cols-2 items-center rounded-lg bg-bg1 text-text">
            <label for="page-count" class="w-20 block mx-2 text-sm font-medium">Results per page</label>
            <select id="page-count" class="h-full border-none text-sm rounded-lg block w-full" v-model="query.limit">
              <option selected value=50>50</option>
              <option value=10> 10</option>
              <option value=25> 25</option>
              <option value=100>100</option>
              <option value=200>200</option>
            </select>
          </div>
          <MegaMenu
            id="type_filter"
            btnTitle='Type Filter'
            btnClass='first:pr-1 bg-bg1 w-full h-3/4'
            cols=3
            :list=list
            states=3
          >
            <template v-slot:default="{src,alt}">
              <img :src="src" :alt="alt"/>
            </template>
          </MegaMenu>
          <MegaMenu
            id="gen_filter"
            btnTitle='Gen Filter'
            btnClass='first:pr-1 bg-bg1 w-full h-3/4'
            cols=1
            :list="genList"
            states=2
          >
            <template v-slot:default="{src,alt}">
              <span>
                {{ src }}
              </span>
            </template>
          </MegaMenu>
          <button
            type="button"
            @click="queryBuilder()"
            class="flex items-center justify-center h-3/4 text-header bg-hover hover:bg-bg2 hover:ring-2 hover:ring-hover focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-4 py-2"
          >
            <FontAwesomeIcon :icon="faMagnifyingGlass" class="pr-1"/>
            Search
          </button>
          <button
            type="button"
            id="reset-button"
            @click="query.limit=50;query.name=undefined;query.notType=undefined;query.type=undefined;"
            @dblclick="query.limit=50;query.name=undefined;query.notType=undefined;query.type=undefined;queryBuilder();"
            class="flex items-center justify-center h-3/4 text-header bg-hover hover:bg-bg2 hover:ring-2 hover:ring-hover focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-4 py-2"
          >
            <FontAwesomeIcon :icon="faRotate" class="pr-1"/>
            Reset Filters
          </button>
          <div id="tooltip-reset-button" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip">
              Tooltip content
              <div class="tooltip-arrow" data-popper-arrow></div>
          </div>
        </form>
      </div>
    </div>
    <ul style="max-height: calc(100% - 8.5rem);" class="w-full xl:w-3/4 p-4 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 overflow-y-scroll overflow-x-hidden">
      <li v-for="[_,value] in pokeList" :key="value.name" class="group w-full h-58 col-span-1 z-0 transition-all ease-in-out sm:hover:scale-110 hover:z-10">
        <RouterLink :to="'Pokemon/'+value.name" class="w-full drop-shadow-md">
          <PokeCard :pokemon="value"/>
        </RouterLink>
      </li>
    </ul>
    <div class="flex items-center justify-center absolute bottom-0 w-full h-14 p-2">
      <div class="grid grid-cols-7 w-1/2 bg-bg1 h-full">
        
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
