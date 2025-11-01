<template>
  <RouterView v-slot="{ Component }">
    <DefaultLayout>
      <component :is="Component" />
    </DefaultLayout>
  </RouterView>
</template>

<script setup>
import DefaultLayout from "./layouts/DefaultLayout.vue";
</script>
