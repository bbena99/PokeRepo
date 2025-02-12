<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import Loading from "../../components/Loading.vue";
import PageNotFound from '../../components/PageNotFound.vue';
import { emptyMove, MovesI } from '../../models/Move';
import { getOneMove } from '../../service';

const route = useRoute('/Moves/[identifier]');
const state = ref<number>(0);
const move = ref<MovesI>(emptyMove());

getOneMove(route.params.identifier,(m)=>{
  if(typeof(m)==='string'){
    state.value=-1;
  } else {
    move.value=m;
    state.value=1;
  }
})
</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1">
    {{ route.params.identifier }} works!
  </div>
  <PageNotFound v-if="state===-1" />
</template>

<style scoped>
</style>