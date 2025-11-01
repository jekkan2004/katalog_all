<template>
    <header :class="[
      'fixed top-0 left-0 w-full z-50 transition-all',
      scrolled ? 'bg-sky-500/70 shadow-sm backdrop-blur-md' : 'bg-sky-500 shadow-sm'
    ]">
    <div class="custom-container">
      <div class="flex items-center justify-between py-3">
        
        <div class="text-lg font-bold text-white lg:text-xl">
          <p>AydaKANWIL I</p>
        </div>

        <!-- Hamburger Menu-->
         <div class="flex px-4 items-center">
          <button @click="toggleMenu" class="lg:hidden xl:hidden flex flex-col justify-center items-center w-8 h-8 relative z-50">
            
            <span :class="[
              'block h-0.5 w-6 bg-white transition-transform duration-300',
              isMenuOpen ? 'rotate-45 translate-y-1.5' : '-translate-y-1.5'
            ]"></span>

            <span :class="[
              'block h-0.5 w-6 bg-white transition-transform duration-300 my-1',
              isMenuOpen ? 'opacity-0' : 'opacity-100'
            ]"></span>

            <span :class="[
              'block h-0.5 w-6 bg-white transition-transform duration-300',
              isMenuOpen ? '-rotate-45 -translate-y-1.5' : 'translate-y-1.5'
            ]"></span>
            
          </button>

          <!-- Menu Mobile DropDown-->

          <div v-if="isMenuOpen" class="lg:hidden xl:hidden right-4 absolute bg-white max-w-[250px] w-full transition duration-300 top-full">
            <ul class="block">
                <li><router-link to="/" class="block py-2 text-slate-500 hover:text-sky-500">Beranda</router-link></li>
                <li><router-link to="/daftar_properti" class="block py-2 text-slate-500 hover:text-sky-500">Daftar Katalog</router-link></li>
                <li><a href="#" class="block py-2 text-slate-500 hover:text-sky-500">Kontak</a></li>
            </ul>            
          </div>

          <nav class="hidden lg:block lg:max-w-full lg:bg-transparent lg:static">
              <ul class="lg:flex lg:gap-9 lg:text-xl">
                <li><a href="#" class="block py-2 text-white hover:text-sky-800">Beranda</a></li>
                <li><a href="#" class="block py-2 text-white hover:text-sky-800">Katalog</a></li>
                <li><a href="#" class="block py-2 text-white hover:text-sky-800">Kontak</a></li>
              </ul>

          </nav>
        </div>

      </div>

      
    </div>
  </header>    
</template>
  
  <script setup>
  import { ref, onMounted, onUnmounted } from 'vue'
  
  const isMenuOpen = ref(false)
  const scrolled = ref(false)
  
  const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value
  }
  
  const handleScroll = () => {
    scrolled.value = window.scrollY > 20
  }
  
  onMounted(() => {
    window.addEventListener('scroll', handleScroll)
  })
  
  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
  })
  </script>
  