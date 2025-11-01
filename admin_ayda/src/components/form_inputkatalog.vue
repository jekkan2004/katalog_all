<template>
    <div class="custom-container mt-20">
      <h2 class="text-2xl font-bold text-center mb-6">Tambah Produk</h2>
  
      <form @submit.prevent="submitForm" class="max-w-md mx-auto space-y-4">
        <div>
          <label class="block font-medium mb-1">Nama Produk</label>
          <input
            v-model="form.nama_produk"
            type="text"
            class="w-full border rounded p-2"
            placeholder="Masukkan nama produk"
          />
        </div>
  
        <div>
          <label class="block font-medium mb-1">Harga Produk</label>
          <input
            v-model.number="form.harga_produk"
            type="number"
            class="w-full border rounded p-2"
            placeholder="Masukkan harga"
          />
        </div>

        <div>
          <label class="block font-medium mb-1">Deskripsi Produk</label>
          <textarea
            v-model="form.deskripsi_produk"
            type="text"
            class="w-full border rounded p-2"
            placeholder="Masukkan deskripsi produk"
          ></textarea>
        </div>

        <div>
          <label class="block font-medium mb-1">Gambar Produk</label>
          <input
            type="file"
            @change="handleFileUpload"
            class="w-full border rounded p-2"
            accept="image/*"
          />
          
          <p v-if="previewUrl" class="mt-2 text-sm text-gray-600">Preview:</p>
          <img
            v-if="previewUrl"
            :src="previewUrl"
            alt="Preview"
            class="w-32 mt-2 rounded shadow"
          />
        </div>
  
        <div class="flex gap-2">
        <button
          type="submit"
          :class="[
            'text-white font-semibold px-4 py-2 rounded-lg',
            editMode ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700'
          ]"
        >
          {{ editMode ? 'Update Produk' : 'Simpan Produk' }}
        </button>
        <button
          type="button"
          v-if="editMode"
          @click="cancelEdit"
          class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg"
        >
          Batal
        </button>
      </div>

      </form>

    </div>
  </template>
  

<script setup>
import { ref, watch } from 'vue'
import { postProduk, updateProduk } from '../api/api.js'

const props = defineProps({
  selectedProduk: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['produk-added', 'produk-updated', 'cancel-edit'])

const form = ref({
  nama_produk: '',
  harga_produk: '',
  deskripsi_produk: '',
  gambar: null
})

const editMode = ref(false)
const previewUrl = ref(null)

watch(
  () => props.selectedProduk,
  (newVal) => {
    if (newVal) {
      editMode.value = true
      form.value = {
        nama_produk: newVal.nama_produk,
        harga_produk: newVal.harga_produk,
        deskripsi_produk: newVal.deskripsi_produk,
        gambar: null
      }
      previewUrl.value = newVal.gambar_url || null
    } else {
      resetForm()
    }
  },
  { deep: true, immediate: true }
  
)

function handleFileUpload(event) {
  const file = event.target.files[0]
  form.value.gambar = file
  previewUrl.value = file ? URL.createObjectURL(file) : null
}

async function submitForm() {
  const formData = new FormData()
  formData.append('nama_produk', form.value.nama_produk)
  formData.append('harga_produk', form.value.harga_produk)
  formData.append('deskripsi_produk', form.value.deskripsi_produk)
  if (form.value.gambar) formData.append('gambar', form.value.gambar)

  try {
    let res
    if (editMode.value && props.selectedProduk?.id) {
      res = await updateProduk(props.selectedProduk.id, formData)
      emit('produk-updated', res.data)
    } else {
      res = await postProduk('produks', formData)
      emit('produk-added', res.data)
    }

    alert(res.message || 'Berhasil disimpan!')
    resetForm()
  } catch (err) {
    console.error('Error saat simpan produk:', err)
    alert('Gagal menyimpan produk')
  }
}

function cancelEdit() {
  emit('cancel-edit')
  resetForm()
}

function resetForm() {
  editMode.value = false
  form.value = {
    nama_produk: '',
    harga_produk: '',
    deskripsi_produk: '',
    gambar: null
  }
  previewUrl.value = null
}
</script>
  