<script setup>
import { ref, onMounted } from 'vue'
import { getData } from '../plugin/api.js'

const produks = ref([])

onMounted(async () => {
  try {
    produks.value = await getData('produks')
  } catch (err) {
    console.error("Gagal fetch:", err)
  }
})
</script>



<template>
    <div class="custom-container">
        <div class="w-full px-4">
            <!--Bagian Atas Start-->

            <div class="flex items-center justify-between">
                <div class="mt-17 font-bold text-large text-shadow-slate-800">
                    <p>Daftar Properti</p>
                </div>

                <div class="mt-17 bg-slate-200 rounded-md px-2 py-1 w-48">
                    <input
                        class="placeholder:text-gray-500 placeholder:italic focus:outline-none"
                        placeholder="Cari Properti ..."
                        type="text"
                    />
                </div>
                
            </div>

            <!--Bagian Atas End-->

            <!-- Daftar Properti Start -->
             <div class="flex flex-wrap gap-2 justify-evenly">
                
                <div v-for="produk in produks" :key="produk.id" class="rounded-md shadow-md overflow-hidden px-2 py-2 mt-5 w-[190px]">
                    <img src="../img/properti/properti1.jpg" alt="Properti-1" class="w-[190px] h-[150px] rounded-md mx-auto">

                    <p class="mt-2 text-xs font-bold mx-3">{{produk.nama_produk}}</p>
                    <p class="mt-3.5 text-xs font-bold mx-3">{{produk.harga_produk}}</p>

                    <p class="mt-3.5 text-[10px] font-semibold text-slate-500 mx-3 mb-4">LT : 150m2/ LB : 120m2</p>
                    <p class="mt-3.5 text-[10px] font-semibold text-slate-500 mx-3 mb-4">Wilayah : Bandung</p>
                    <div class="mx-3 mb-4 ">
                        <a href="#" class="text-[13px] text-sky-500 font-semibold hover:text-sky-300 transition duration-200">Lihat Detail >></a>
                    </div>

                </div>

                <!-- <div class="rounded-md shadow-md overflow-hidden px-2 py-2 mt-5 w-[190px]">
                    <img src="../img/properti/gudang.jpg" alt="Properti-1" class="w-[190px] h-[150px] rounded-md mx-auto">

                    <p class="mt-2 text-xs font-bold mx-3">Rumah Keluarga Nyaman. Lokasi Dago</p>
                    <p class="mt-3.5 text-xs font-bold mx-3">Harga : Rp. 1.8 Milyar</p>

                    <p class="mt-3.5 text-[10px] font-semibold text-slate-500 mx-3 mb-4">LT : 150m2/ LB : 120m2</p>
                    <p class="mt-3.5 text-[10px] font-semibold text-slate-500 mx-3 mb-4">Wilayah : Bandung</p>
                    <div class="mx-3 mb-4 ">
                        <a href="#" class="text-[13px] text-sky-500 font-semibold hover:text-sky-300 transition duration-200">Lihat Detail >></a>
                    </div>
                    
                </div> -->

             </div>
        </div>
    </div>
</template>


