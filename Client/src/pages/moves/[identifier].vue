<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import Loading from "../../components/Loading.vue";
import PageNotFound from '../../components/PageNotFound.vue';

const route = useRoute('/Moves/[identifier]');
const state = ref<number>(0);

</script>

<template>
  <Loading v-if="state===0"/>
  <div v-if="state===1">
    {{ state.identifier }} works!
  </div>
  <PageNotFound v-if="state===-1"/>
</template>

<style scoped>
</style>