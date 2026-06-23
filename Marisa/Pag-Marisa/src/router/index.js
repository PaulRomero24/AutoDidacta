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
    routes
})