<template>
  <router-view />
  <MenuHeader />
  <MenuFooter />
</template>

<script setup>
import MenuHeader from './components/MenuHeader.vue'
import MenuFooter from './components/MenuFooter.vue'
</script>