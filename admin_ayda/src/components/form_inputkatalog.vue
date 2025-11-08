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

        <div class="mt-4">
        <label class="font-semibold">Fasilitas:</label>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
          <div v-for="item in fasilitasList" :key="item.id">
            <label class="flex items-center gap-2">
              <input
                type="checkbox"
                :value="item.id"
                v-model="form.selectedFasilitas"
                class="rounded border-gray-300"
              />
              <span>{{ item.nama_fasilitas }}</span>
            </label>
          </div>
        </div>
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
            multiple
            @change="handleFileUpload"
            class="w-full border rounded p-2"
            accept="image/*"
          />
        </div>

          <!-- preview gambar baru-->
          <div v-if="previews.length" class="flex gap-2 mt-3 flex-wrap">
            <div v-for="(p, i) in previews" :key="i" class="relative">
              <img :src="p" class="w-24 h-24 object-cover rounded shadow" />
              <button type="button" @click="removeNewImage(i)" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 text-xs">×</button>
            </div>
          </div>

          <p class="text-sm text-gray-500 mt-2">
            Jumlah gambar sekarang: {{ previews.length }} / 5
          </p>
  
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
import { ref, watch, onMounted } from 'vue'
import { postProduk, updateProduk, getFasilitas  } from '../api/api.js'

  const fasilitasList = ref([])

  onMounted(async () => {
    fasilitasList.value = await getFasilitas()
  })

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
  selectedFasilitas: []
})

  const newFiles = ref([])      // menyimpan file yang dipilih
  const previews = ref([])      // menyimpan blob URL preview
  const editMode = ref(false)

watch(
  () => props.selectedProduk,
  (newVal) => {
    if (newVal) {
      editMode.value = true
      form.value = {
        nama_produk: newVal.nama_produk,
        harga_produk: newVal.harga_produk,
        deskripsi_produk: newVal.deskripsi_produk
      }
      // previewUrl.value = newVal.gambar_url || null
    } else {
      resetForm()
    }
  },
  { deep: true, immediate: true }
  
)

function handleFileUpload(event) {
  const files = Array.from(event.target.files)

  // batasi maksimal 5 file total
  const allowed = 5 - newFiles.value.length
  const selected = files.slice(0, allowed)

  selected.forEach(file => {
    newFiles.value.push(file)
    previews.value.push(URL.createObjectURL(file))
  })

  event.target.value = '' // reset input agar bisa pilih ulang file sama
}

// hapus gambar baru dari preview
function removeNewImage(index) {
  URL.revokeObjectURL(previews.value[index])
  previews.value.splice(index, 1)
  newFiles.value.splice(index, 1)
}

async function submitForm() {
  const formData = new FormData()
  formData.append('nama_produk', form.value.nama_produk)
  formData.append('harga_produk', form.value.harga_produk)
  formData.append('deskripsi_produk', form.value.deskripsi_produk)
  //formData.append('fasilitas', JSON.stringify(selectedFasilitas.value))

  form.value.selectedFasilitas.forEach((id) => {
  formData.append('fasilitas[]', id)
  })

  // TAMBAH: kirim semua file sebagai gambar[]
  newFiles.value.forEach((file) => {
    if (file) formData.append('gambar[]', file)
  })

  console.log('FormData yang dikirim:', [...formData])

  try {
    let res
    if (editMode.value && props.selectedProduk?.id) {
      res = await updateProduk(props.selectedProduk.id, formData)
      emit('produk-updated', res.data)
    } else {
      res = await postProduk(formData)
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

 /* END FUNCTION UNTUK PRODUK */

// reset semua data (termasuk preview)
function resetForm() {
  editMode.value = false
  form.value = {
    nama_produk: '',
    harga_produk: '',
    deskripsi_produk: '',
    selectedFasilitas: []
  }
  newFiles.value = []
  previews.value = []
}
</script>
  