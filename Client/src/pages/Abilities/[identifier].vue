<script setup lang="ts">
//@ts-ignore ts says there is not export at this location, but there is.
import { useRoute } from 'vue-router/auto';
import { ref } from 'vue';
import Loading from "../../components/Loading.vue";
import PageNotFound from '../../components/PageNotFound.vue';
import { getOneAbility } from '../../service';
import { AbilityI } from '../../models';

const route = useRoute('/Abilities/[identifier]');
const state = ref<number>(0);
const ability = ref<AbilityI>();
getOneAbility(route.params.identifier,(a)=>{
  if(typeof(a)==='string'){
    state.value=-1
  } else {
    ability.value=a;
    state.value=1;
  }
})

</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1">
    {{ state.identifier }} works!
  </div>
  <PageNotFound v-if="state===-1" header="Ability" :message="'Ability: '+route.identifier+' was not found.'"/>
</template>

<style scoped>
</style>