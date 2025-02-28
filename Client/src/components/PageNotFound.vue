<script setup lang="ts">
const props = defineProps(['header','message'])
</script>

<template>
<section class="w-full flex justify-center">
  <div className="bg-bg1 shadow-2xl rounded-3xl mt-16 w-full sm:w-4/5 md:w-3/4 lg:w-2/3 xl:w-3/5 min-w-36">
    <div className="py-8 px-4 mx-auto max-w-screen-xl text-center lg:py-16">
      <h1 className="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
        {{ props.header }} not found!
      </h1>
      <p className="mb-8 text-lg font-normal text-gray-600 lg:text-xl sm:px-16 lg:px-48 dark:text-gray-400">
        I'm sorry, but the page you are looking for doesn't exist.<br/>
        message from page: {{ props.message }}
      </p>
    </div>
  </div>
</section>
</template>

<style scoped>
</style>