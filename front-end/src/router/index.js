import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import ProductsView from '@/views/ProductsView.vue'
import TransactionsView from '@/views/TransactionsView.vue'

const routes = [
  {
    path: '/',
    name: 'HomeView',
    component: HomeView
  },
  {
    path: '/transacoes',
    name: 'TransactionsView',
    component: TransactionsView
  },
  {
    path: '/produtos',
    name: 'ProductsView',
    component: ProductsView
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
