<script setup lang="ts">
import { ref } from 'vue';
import { getAllMoves } from '../../service';
import Loading from '../../components/Loading.vue';
import PageNotFound from '../../components/PageNotFound.vue';
import { MovesI } from '../../models';
import { RouterLink } from 'vue-router';


const state = ref<number>(0);
const moveArray = ref<MovesI[]>([]);
getAllMoves({
  limit: 0,
  offset: 0
},(a)=>{
  moveArray.value=[...a];
  state.value=1;
})
</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1" class="flex justify-center items-center py-4">
    <div class="grid grid-cols-12 gap-3 xl:w-2/3">
      <RouterLink v-for="move in moveArray" :to="'move/'+move.name" class="col-span-6 md:col-span-4 xl:col-span-3 2xl:col-span-2 rounded-xl shadow-lg border-2 border-bg2 bg-bg1 p-2 text-lg underline hover:text-hover hover:border-hover hover:shadow-2xl hover:scale-105">
        {{ move.name.charAt(0).toUpperCase() + move.name.slice(1).replace('-',' ') }}
      </RouterLink>
    </div>
  </div>
  <PageNotFound v-if="state===-1"/>
</template>

<style scoped>
</style>