import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/index.vue';
import DaftarKatalog from '../views/adminProduk.vue';
import FormInputKatalog from '../components/form_inputkatalog.vue';
import AdminProduk from '../views/adminProduk.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
  },

  {
    path: '/daftar_katalog',
    name: 'DaftarKatalog',
    component: DaftarKatalog,
  },

  {
    path: '/input_katalog',
    name: 'FormInputKatalog',
    component: FormInputKatalog,
  },

  // {
  //   path: '/admin_produk',
  //   name: 'AdminProduk',
  //   component: AdminProduk,
  // },

//   {
//     path: '/daftar_properti',
//     name: 'DaftarProperti',
//     component: DaftarProperti,
//   },

];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
