<script setup lang="ts">
const props = defineProps(['header','message'])
</script>

<template>
  <span>
    {{props.header}} Not found!
    Message sent: {{ props.message }}
  </span>
</template>

<style scoped>
</style>