<template>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-6">Manajemen Produk</h1>
  
      <!-- Form Input Produk -->
      <ProdukForm
        v-if="showForm"
        :selectedProduk="produkEdit"
        @produk-added="handleAdded"
        @produk-updated="handleUpdated"
        @cancel-edit="cancelEdit"
      />
  
      <!-- Tabel Daftar Produk -->
      <ProdukTable
        :produks="produks"
        @edit="editProduk"
        @delete="deleteProduk"
      />
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import ProdukForm from '../components/form_inputkatalog.vue'
  import ProdukTable from '../components/daftar_katalog.vue'
  import { getData, deleteData } from '../api/api.js'
  
  const produks = ref([])
  const selectedProduk = ref(null)
  const showForm = ref(false)
  const produkEdit = ref(null)
  
  // ambil data produk dari backend
  async function fetchProduk() {
    try {
      const res = await getData()
      produks.value = res.data || res
    } catch (err) {
      console.error('Gagal ambil data produk:', err)
    }
  }
  
  // handler edit
  function editProduk(produk) {
    produkEdit.value = { ...produk }
    showForm.value = true
    window.scrollTo({ top: 0, behavior: 'smooth' })
  }
  
  // handler delete
  async function deleteProduk(id) {
    if (!confirm('Yakin ingin menghapus produk ini?')) return
    try {
      await deleteData(id)
      alert('Produk berhasil dihapus!')
      fetchProduk()
    } catch (err) {
      console.error('Gagal hapus produk:', err)
    }
  }
  
  // handler dari form
  function handleAdded() {
    showForm.value = false
    fetchProduk()
  }
  
  function handleUpdated() {
    showForm.value = false
    fetchProduk()
  }
  
  function cancelEdit() {
    showForm.value = false
    selectedProduk.value = null
  }
  
  // load data saat halaman dibuka
  onMounted(fetchProduk)
  </script>
  