<template>
    <div class="custom-container">
        <div class="w-full px-4 mt-20">
            <h2 class="font-bold text-2xl mt-10 text-black text-center">Form Produk</h2>
            <form @submit.prevent="simpanData">
                <div class="mb-3">
                    <label class="block font-medium mb-1">Nama Produk</label>

                    <input 
                        v-model="form.nama_produk"
                        type="text"
                        class="border border-gray-300 rounded p-2 w-full focus:ring focus:ring-blue-200"
                        placeholder="Masukkan nama produk"
                        required
                    />
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Alias Produk</label>

                    <input 
                        v-model="form.slug"
                        type="text"
                        class="border border-gray-300 rounded p-2 w-full focus:ring focus:ring-blue-200"
                        placeholder="Masukkan Alias Produk"
                        required
                    />
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Deskripsi Produk</label>

                    <input 
                        v-model="form.deskripsi_produk"
                        type="text"
                        class="border border-gray-300 rounded p-2 w-full focus:ring focus:ring-blue-200"
                        placeholder="Masukkan Deskripsi Produk"
                        required
                    />
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Gambar Produk</label>

                    <input 
                    type="file"
                    @change="handleFileUpload"
                    class="border border-gray-300 rounded p-2 w-full"
                    accept="image/*"
                    />
                </div>

                <div class="mb-3">
                    <label class="block font-medium mb-1">Harga Produk</label>

                    <input 
                        v-model="form.harga_produk"
                        type="number"
                        class="border border-gray-300 rounded p-2 w-full focus:ring focus:ring-blue-200"
                        placeholder="Masukkan Harga produk"
                        required
                    />

                    <button
                        type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-3"
                    >
                        Simpan
                    </button>
                    <p v-if="message" class="mt-4 text-green-600">{{ message }}</p>
                </div>

            </form>
        </div>
    </div>

</template>

<script setup>
import { ref } from "vue"
import {  submitFormMultipart } from '../plugin/api.js'

const form = ref({
  nama_produk: "",
  slug: "",
  deskripsi_produk: "",
  harga_produk: "",
  gambar: null
})

const handleFileUpload = (e) => {
  form.value.gambar = e.target.files[0]
}

const message = ref("")

function simpanData() {
    const formData = new FormData()
    formData.append('nama_produk', form.value.nama_produk)
    formData.append('slug', form.value.slug)
    formData.append('deskripsi_produk', form.value.deskripsi_produk)
    formData.append('harga_produk', form.value.harga_produk)
    if (form.value.gambar) {
      formData.append('gambar', form.value.gambar)
    }
    submitFormMultipart('create/product', formData)
    console.log("Form submitted:", formData)
}

</script>