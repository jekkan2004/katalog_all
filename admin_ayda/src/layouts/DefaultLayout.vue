<template>
    <div class="flex min-h-screen">
      <!-- Sidebar -->
      <Sidebar />
  
      <!-- Konten + Footer -->
      <div class="flex flex-col flex-1">
        <main class="flex-1 p-6 bg-gray-50">
          <slot />
        </main>
        <Footer />
      </div>
    </div>
  </template>
  
<script setup>
    import Sidebar from "../components/sidebar_menu.vue";
    import Footer from "../components/Footer.vue";
</script>
  