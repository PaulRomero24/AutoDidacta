<!-- App.vue -->
<script setup>
import { ref } from 'vue';
import FlipCard from '../component/FlipCard.vue';
import NavbarComponent from '../component/NavbarComponent.vue';
import ExperienciaComponent from '../component/ExperienciaComponent.vue';
import DondeIrComponent from '../component/DondeIrComponent.vue';
import MiMalargueComponent from '../component/MiMalargueComponent.vue';
import EnlacesInteres from '../component/EnlacesInteres.vue';
import FooterComponent from '../component/footerComponent.vue';


// Después — solo muestra la carta si es la primera vez
const showIntro = ref(!sessionStorage.getItem('introVista'))

const handleIntroClose = () => {
    showIntro.value = false
    sessionStorage.setItem('introVista', 'true')
}
</script>

<template>
    <div id="app">
        <!-- Carta introductoria -->
        <FlipCard v-if="showIntro" @close="handleIntroClose" />

        <!-- Contenido principal -->
        <div v-show="!showIntro" class="main-content">
            <NavbarComponent />
            <section id="experiencia">
                <ExperienciaComponent />
            </section>
            <section id="dondeir">
                <DondeIrComponent />
            </section>
            <section id="mimalargue">
                <MiMalargueComponent />
            </section>
            <section id="links">
                <EnlacesInteres />
            </section>
            <footer>
                <FooterComponent />
            </footer>
        </div>
    </div>
</template>

<style scoped>
</style>