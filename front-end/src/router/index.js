import { createRouter, createWebHistory } from 'vue-router'
import RegisterLoginView from '@/views/RegisterLoginView.vue'
import HomeView from '@/views/HomeView.vue'
import ProductsView from '@/views/ProductsView.vue'
import TransactionsView from '@/views/TransactionsView.vue'

const routes = [
  {
    path: '/',
    name: 'RegisterLoginView',
    component: RegisterLoginView
  },
  {
    path: '/inicio',
    name: 'HomeView',
    component: HomeView,
    meta: { requiresAuth: true }
  },
  {
    path: '/transacoes',
    name: 'TransactionsView',
    component: TransactionsView,
    meta: { requiresAuth: true }
  },
  {
    path: '/produtos',
    name: 'ProductsView',
    component: ProductsView,
    meta: { requiresAuth: true }
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

// Adicione um guarda de navegação beforeEach para verificar a autenticação
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !isAuthenticated()) {
    // Se a rota requer autenticação e o usuário não está autenticado, redirecione para a página de login ou outra página de sua escolha
    next('/')
  } else {
    // Caso contrário, permita a navegação
    next()
  }
})

// Função auxiliar para verificar a autenticação (você precisa implementar sua própria lógica de autenticação)
function isAuthenticated() {
  // Verifique se o usuário está autenticado
  // Retorne true se autenticado, caso contrário, retorne false
  let token = localStorage.getItem("token")
  if (token && token != '') {
    return true;
  } else {
    return false;
  }  
}

export default router
