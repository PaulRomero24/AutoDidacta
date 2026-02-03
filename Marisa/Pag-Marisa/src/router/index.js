import { createRouter, createWebHistory } from 'vue-router'

// Importa tus componentes/vistas
import HomeView from '../views/HomeView.vue'
import GaleriaCompleta from '../views/GaleriaCompleta.vue'

// Define las rutas
const routes = [
    { path: '/', component: HomeView},
    { path: '/galeria', component: GaleriaCompleta}
]

// Crea el router
const router = createRouter({
    history: createWebHistory(), // Usa URLs limpias (sin #)
    routes
})

export default router