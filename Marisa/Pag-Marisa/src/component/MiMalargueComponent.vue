<!-- src/components/MiniGaleria.vue -->
<script setup>
import { ref } from 'vue';

// Importa 4-6 fotos representativas
import caverna1 from "../assets/Lugares/Caverna1.jpg";
import payunia1 from "../assets/Lugares/Payunia1.jpg";
import valles1 from "../assets/Lugares/Valles1.jpg";
import malacara1 from "../assets/Lugares/Malacara1.jpg";
import GaleriaCompleta from '@/views/GaleriaCompleta.vue';

const miniFotos = ref([
    caverna1,
    payunia1,
    valles1,
    malacara1
]);

const showGaleria = ref(false);

const abrirGaleria = () => {
    showGaleria.value = true;
};
</script>

<template>
    <section class="mini-galeria">
        <h2>Galería de Malargüe</h2>
        <div class="mini-grid">
            <img v-for="(foto, index) in miniFotos" :key="index" :src="foto" :alt="`Foto ${index + 1}`"
                class="mini-img" />
        </div>
        <button @click="abrirGaleria" class="btn-galeria">
            Ver galería completa
        </button>

        <!-- Modal de galería -->
        <GaleriaCompleta :is-visible="showGaleria" @close="showGaleria = false" />
    </section>
</template>

<style scoped>
.mini-galeria {
    width: 100%;
    max-width: 1000px;
    margin: 3rem auto;
    padding: 0 1.5rem;
    text-align: center;
}

.mini-galeria h2 {
    font-size: 2rem;
    color: #182019;
    margin-bottom: 1.5rem;
}

.mini-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 0.8rem;
    margin-bottom: 1.5rem;
}

.mini-img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.2s;
}

.mini-img:hover {
    transform: scale(1.05);
}

.btn-galeria {
    background: #182019;
    color: white;
    border: none;
    padding: 0.8rem 2rem;
    font-size: 1.1rem;
    border-radius: 30px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-galeria:hover {
    background: #2c3e50;
}
</style>