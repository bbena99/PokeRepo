<script setup lang="ts">
import { ref } from 'vue';
import { AbilityI } from '../../models';
import Loading from "../../components/Loading.vue";
import PageNotFound from '../../components/PageNotFound.vue';
import { getAllAbilities } from '../../service';


const state = ref<number>(0);
const abilityArray = ref<AbilityI[]>([]);
getAllAbilities({
  limit: 0,
  offset: 0
},(a)=>{
  abilityArray.value=[...a];
  state.value=1;
})
</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1" class="flex justify-center items-center py-4">
    <div class="grid grid-cols-12 gap-3 xl:w-2/3">
      <RouterLink v-for="ability in abilityArray" :to="'abilities/'+ability.name" class="col-span-6 md:col-span-4 xl:col-span-3 rounded-lg border-2 border-bg2 bg-bg1 p-2 text-lg underline hover:text-hover hover:border-hover hover:scale-105">
        {{ ability.name.charAt(0).toUpperCase() + ability.name.slice(1).replace('-',' ') }}
      </RouterLink>
    </div>
  </div>
  <PageNotFound v-if="state<0" header="Abilities" message="List of abilities not found."/>
</template>

<style scoped>
</style>