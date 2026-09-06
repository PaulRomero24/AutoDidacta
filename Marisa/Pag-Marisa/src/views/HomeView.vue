<!-- App.vue -->
<script setup>
// ✅ Poner esto
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper';

// 2. Importar los estilos de Swiper
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';



import { ref } from 'vue';
import FlipCard from '../component/FlipCard.vue';
import NavbarComponent from '../component/NavbarComponent.vue';
import ExperienciaComponent from '../component/ExperienciaComponent.vue';
import DondeIrComponent from '../component/DondeIrComponent.vue';
import MiMalargueComponent from '../component/MiMalargueComponent.vue';
import EnlacesInteres from '../component/EnlacesInteres.vue';
import FooterComponent from '../component/footerComponent.vue';
import fondo1 from '../assets/Fondos/Fondo1.webp';
import fondo2 from '../assets/Fondos/Fondo2.webp';
import fondo3 from '../assets/Fondos/Fondo3.webp';
import fondo4 from '../assets/Fondos/Fondo4.webp';
import fondo5 from '../assets/Fondos/Fondo5.webp';
import fondo6 from '../assets/Fondos/Fondo6.webp';
import fondo7 from '../assets/Fondos/Fondo7.webp';
import fondo8 from '../assets/Fondos/Fondo8.webp';
import fondo9 from '../assets/Fondos/Fondo9.webp';


// Después — solo muestra la carta si es la primera vez
const showIntro = ref(!sessionStorage.getItem('introVista'))

const handleIntroClose = () => {
    showIntro.value = false
    sessionStorage.setItem('introVista', 'true')
}


// Aquí iría tu array de imágenes
const imagenes = [
    { id: 1, url: fondo1 },
    { id: 2, url: fondo2 },
    { id: 3, url: fondo3 },
    { id: 4, url: fondo4 },
    { id: 5, url: fondo5 },
    { id: 6, url: fondo6 },
    { id: 7, url: fondo7 },
    { id: 8, url: fondo8 },
    { id: 9, url: fondo9 }
];
console.log('Imágenes:', imagenes.map(i => i.url));

// Módulos que vas a usar
const modules = [Navigation, Pagination, Autoplay, EffectFade];

const onSwiper = (swiper) => {
    console.log('Swiper inicializado', swiper);
};
const onSlideChange = () => {
    console.log('Diapositiva cambiada');
};
</script>

<template>
    <div id="app">
        <swiper class="hero-swiper" :modules="modules" :slides-per-view="1" :space-between="0" effect="fade"
            :fadeEffect="{ crossFade: true }" :autoplay="{ delay: 8000, disableOnInteraction: false }" :loop="true"
            @swiper="onSwiper" @slideChange="onSlideChange">

            <swiper-slide v-for="imagen in imagenes" :key="imagen.id" class="hero-slide"
                :style="{ backgroundImage: `url('${imagen.url}')` }">
                <div class="overlay"></div>
                <!-- Aquí va el contenido que quieras superponer (texto, botones, etc.) -->
                <div class="slide-content">
                    <h2>{{ imagen.titulo }}</h2>
                    <p>{{ imagen.descripcion }}</p>
                </div>
            </swiper-slide>

        </swiper>

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
.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(77, 73, 73, 0.4);
    /* Capa negra semitransparente */
    z-index: 1;
}

.hero-swiper {
    position: fixed;
    inset: 0;
    z-index: 0;
    width: 100%;
    height: 100vh;
    min-height: 420px;
    pointer-events: none;
}

.hero-swiper :deep(.swiper-slide) {
    height: 100%;
    opacity: 0;
    transition: opacity 1.5s ease-in-out !important;
}

.hero-swiper :deep(.swiper-slide-active) {
    opacity: 1;
}

.hero-swiper :deep(.swiper-pagination),
.hero-swiper :deep(.swiper-button-prev),
.hero-swiper :deep(.swiper-button-next) {
    pointer-events: auto;
}

.hero-slide {
    background-image: var(--slide-background);
    background-position: center;
    background-repeat: no-repeat;
    background-size:cover;
}

.slide-content {
    position: relative;
    z-index: 2;
    /* Por encima del overlay */
}
</style>