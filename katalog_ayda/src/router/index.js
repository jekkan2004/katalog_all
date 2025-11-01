import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/index.vue';
import DetailProperti from '../views/detail_properti.vue';
import DaftarProperti from '../views/daftar_properti.vue';
import InputProduk from '../views/form_produk.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },

  {
    path: '/detail_properti',
    name: 'DetailProperti',
    component: DetailProperti,
  },

  {
    path: '/daftar_properti',
    name: 'DaftarProperti',
    component: DaftarProperti,
  },

  {
    path: '/form_produk',
    name: 'InputProduk',
    component: InputProduk,
  },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
