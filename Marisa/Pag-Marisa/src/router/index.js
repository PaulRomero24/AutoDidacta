import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import EstudiantilesView from '../views/EstudiantilesView.vue'
import GaleriaView from '../views/GaleriaView.vue'

const routes = [
    { path: '/', component: HomeView },
    { path: '/estudiantiles', component: EstudiantilesView },
    { path: '/galeria', component: GaleriaView }, // ← nueva
]

export default createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        // Si viene con hash, scroll a esa sección
        if (to.hash) {
            return {
                el: to.hash,
                behavior: 'smooth',
                top: 80
            }
        }

        // Si hay posición guardada (botón atrás del browser), la restaura
        if (savedPosition) {
            return savedPosition
        }

        // Solo scrollea arriba si viene del home hacia otra página
        if (from.path === '/' && to.path !== '/') {
            return { top: 0 }  // sin smooth para que no se note
        }

        return false  // ← no hace nada, deja la posición como está
    }
})