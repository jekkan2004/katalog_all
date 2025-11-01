<template>
  <div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold mb-4">Daftar Produk</h2>

    <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
      <thead class="bg-gray-100">
        <tr>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Nama Produk</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Harga</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Deskripsi</th>
          <th class="py-2 px-4 text-left text-sm font-medium text-gray-600">Aksi</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="produk in produks" :key="produk.id" class="border-t hover:bg-gray-50">
          <td class="py-2 px-4">{{ produk.nama_produk }}</td>
          <td class="py-2 px-4">Rp {{ Number(produk.harga_produk).toLocaleString() }}</td>
          <td class="py-2 px-4">{{ produk.deskripsi_produk || '-' }}</td>
          <td class="py-2 px-4 space-x-2">
            <button
              @click="$emit('edit', produk)"
              class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
            >
              Edit
            </button>
            <button
              @click="$emit('delete', produk.id)"
              class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
            >
              Hapus
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-if="!produks | !produks.length" class="text-gray-500 mt-4 text-center">Belum ada produk</p>
  </div>
</template>

<script setup>
defineProps({
  produks: {
    type: Array,
    default: () => [],
  },
})
defineEmits(['edit', 'delete'])
</script>
