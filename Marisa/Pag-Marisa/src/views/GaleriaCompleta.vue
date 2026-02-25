<!-- src/component/GaleriaModal.vue -->
<script setup>
import { ref, computed } from 'vue';
import caverna1 from "../assets/Lugares/Caverna1.jpg";
import caverna2 from "../assets/Lugares/Caverna2.jpg";
import payunia1 from "../assets/Lugares/Payunia1.jpg";
import payunia2 from "../assets/Lugares/Payunia2.jpg";
import valles1 from "../assets/Lugares/Valles1.jpg";
import valles2 from "../assets/Lugares/Valles2.jpg";
import malacara1 from "../assets/Lugares/Malacara1.jpg";
import malacara2 from "../assets/Lugares/Malacara2.jpg";


const props = defineProps({
    isVisible: Boolean,
    lugarSeleccionado: String // opcional: si quieres filtrar por lugar
});

const emit = defineEmits(['close']);


const galeria = ref([
    {
        nombre: "Caverna",
        imagenes: [caverna1, caverna2]
    },
    {
        nombre: "Payunia",
        imagenes: [payunia1, payunia2]
    },
    {
        nombre: "Malacara",
        imagenes: [malacara1, malacara2]
    },
    {
        nombre: "Valles",
        imagenes: [valles1, valles2]
    }
]);
// Imagen expandida
const expandedImage = ref('');
const showExpanded = ref(false);

const openImage = (src) => {
    expandedImage.value = src;
    showExpanded.value = true;
};

const closeExpanded = () => {
    showExpanded.value = false;
};

const closeGallery = () => {
    emit('close');
};
</script>

<template>
    <div v-if="isVisible" class="galeria-modal-overlay" @click="closeGallery">
        <div class="galeria-modal" @click.stop>
            <button class="close-btn" @click="closeGallery">✕</button>

            <!-- Contenido de la galería -->
            <div class="galeria-contenido">
                <div v-for="lugar in galeria" :key="lugar.nombre" class="seccion-lugar">
                    <h3>{{ lugar.nombre }}</h3>
                    <div class="grid-lugar">
                        <div v-for="(imagen, index) in lugar.imagenes" :key="index" class="img-wrapper"
                            @click="openImage(imagen)">
                            <img :src="imagen" :alt="`${lugar.nombre} ${index + 1}`" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightbox para imagen expandida -->
        <div v-if="showExpanded" class="lightbox" @click.stop="closeExpanded">
            <img :src="expandedImage" alt="Imagen expandida" class="expanded-img" />
        </div>
    </div>
</template>

<style scoped>
.galeria-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.95);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 6000;
    padding: 1rem;
    overflow-y: auto;
}

.galeria-modal {
    background: #8ab5df;
    border-radius: 16px;
    max-width: 1200px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    padding: 2rem 1.5rem;
}

.close-btn {
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    background: #182019;
    color: white;
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 1.2rem;
    cursor: pointer;
}

.galeria-contenido {
    margin-top: 2rem;
}

.seccion-lugar {
    margin-bottom: 2.5rem;
}

.seccion-lugar h3 {
    color: #182019;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.grid-lugar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, max-content));
    gap: 1rem;
    justify-content: center;
}

.img-wrapper {
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.img-wrapper img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    display: block;
}

/* Lightbox */
.lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.92);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 7000;
    cursor: pointer;
}

.expanded-img {
    max-width: 90vw;
    max-height: 90vh;
    object-fit: contain;
    border: 4px solid white;
    border-radius: 8px;
}

@media (max-width: 768px) {
    .grid-lugar {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.8rem;
    }
}
</style>