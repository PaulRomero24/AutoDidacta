<!-- src/components/MiniGaleria.vue -->
<script setup>

import { ref } from 'vue';
import { useRouter } from 'vue-router'
const router = useRouter()

// Importa 4-6 fotos representativas
import caverna1 from "../assets/Lugares/Caverna1.jpg";
import payunia1 from "../assets/Lugares/Payunia1.jpg";
import valles1 from "../assets/Lugares/Valles1.jpg";
import malacara1 from "../assets/Lugares/Malacara1.jpg";


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
        <button @click="router.push('/galeria')" class="btn-galeria">
            Ver galería completa
        </button>

    </section>
</template>

<style scoped>
.mini-galeria {
    width: 100%;
    margin: 3rem auto;
    padding: 0 1.5rem 2rem 1.5rem;
    text-align: center;
}

.mini-galeria h2 {
    font-size: 2rem;
    margin-bottom: 1.5rem;
}

.mini-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(224px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.mini-img {
    width: 100%;
    height: 224px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.5s;
    border:2px solid white;
}

.mini-img:hover {
    transform: scale(1.5);
}

.btn-galeria {
    background: #182019;
    color: white;
    border: none;
    padding: 0.8rem 2rem;
    font-size: 2rem;
    border-radius: 30px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-galeria:hover {
    background: #2c3e50;
}
</style>