<template>
    <aside
        :class="[
            'bg-white shadow-lg flex flex-col justify-beetween transition-all duration-300 z-50',
            isOpen ? 'w-64' : 'w-20'        
        ]" 
    >

    <!-- LOGO -->
     <div class="flex items-center justify-center py-6 border-b">
        <img src="https://www.clipartmax.com/png/small/437-4371249_my-account-logo-png.png" alt="MyAccount" class="h-10 w-10 object-contain" />
        <span v-if="isOpen" class="ml-2 text-xl font-bold text-gray-700">My APP</span>
    </div>

    <!-- Tengah -->
     <nav class="flex-1 mt-6">
        <ul class="space-y-2">
            <li v-for="item in menu" :key="item.name">
                <RouterLink
                    :to="item.path"
                    class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-100 rounded-lg transition"
                >

                <component :is="item.icon" class="h-5 w-5" />
                <span v-if="isOpen" class="ml-3">{{ item.name }}</span>

                </RouterLink>
            </li>
        </ul>
     </nav>

     <!-- Bawah -->
      <div class="p-4 border-t">
        <button
            class="flex items-center w-full px-4 py-3 text-gray-700 hover:bg-blue-100 rounded-lg transition"
        >
        <img src="https://i.pravatar.cc/40" alt="User" class="w-8 h-8 rounded-full" />
        <span v-if="isOpen" class="ml-3">Akun</span>
      </button>

      </div>

    </aside>
</template>

<script setup>

    import { ref } from 'vue';
    import { Home, BookOpen } from "lucide-vue-next";

    const isOpen = ref(true);

    const menu = [
        { name: "Beranda", path: "/", icon: Home },
        { name: "Katalog", path: "/daftar_katalog", icon: BookOpen },
        { name: "Input Katalog", path: "/input_katalog", icon: BookOpen },
    ];

</script>