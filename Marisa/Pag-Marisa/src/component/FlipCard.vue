<script setup>
import { ref } from 'vue';

// Importaciones
import frontal from "../assets/CartaReverso/Frontal.jpg";
import reverso1 from "../assets/CartaReverso/Reverso1.jpg";
import reverso2 from "../assets/CartaReverso/Reverso2.jpg";
import reverso3 from "../assets/CartaReverso/Reverso3.jpg";
import reverso4 from "../assets/CartaReverso/Reverso4.jpg";

const isFlipped = ref(false);
const expandedImage = ref('');
const showExpanded = ref(false);
const isVisible = ref(true);

const cornerImages = {
    topLeft: reverso1,
    topRight: reverso2,
    bottomLeft: reverso3,
    bottomRight: reverso4,
};

const closeCard = () => {
    isVisible.value = false;
    document.body.style.overflow = '';

    // Ir al contenido después de cerrar
    setTimeout(() => {
        document.getElementById('experiencia')?.scrollIntoView({
            behavior: 'smooth'
        });
    }, 300);
};

const onCloseClick = () => {
    // Restaurar scroll inmediatamente
    document.body.style.overflow = '';

    // Opcional: forzar el enfoque después del scroll
    setTimeout(() => {
        const target = document.getElementById('acercademi');
        if (target) target.tabIndex = -1; // hace focusable
    }, 100);
};
const openImage = (src) => {
    expandedImage.value = src;
    showExpanded.value = true;
};

const closeExpanded = () => {
    showExpanded.value = false;
};
</script>

<template>
    <div v-if="isVisible" class="fullscreen-overlay" @click="closeCard">
        <div class="card-container" @click.stop>
            <div class="card" :class="{ flipped: isFlipped }" @click="isFlipped = !isFlipped">
                <div class="card-front">
                    <h1>MARISA BERDU</h1>
                    <img :src="frontal" alt="Portada" />
                </div>
                <div class="card-back">
                    <div class="center-text">
                        <h2>¿Quien soy?</h2>
                        <p>Soy una guia de turismo en Malargüe, llevo en actividad
                            25 años y contando. Ejerci como Profesora,tambien eh sido dueña de
                            una agencia. Amo a mi Malargüe y espero que ustedes tambien
                        </p>
                        <!-- ✅ Botón para cerrar e ir al contenido -->
                        <button class="close-button" @click.stop="closeCard">
                            Entrar al sitio
                        </button>
                    </div>
                    <img :src="cornerImages.topLeft" class="corner-img top-left"
                        @click.stop="openImage(cornerImages.topLeft)" />
                    <img :src="cornerImages.topRight" class="corner-img top-right"
                        @click.stop="openImage(cornerImages.topRight)" />
                    <img :src="cornerImages.bottomLeft" class="corner-img bottom-left"
                        @click.stop="openImage(cornerImages.bottomLeft)" />
                    <img :src="cornerImages.bottomRight" class="corner-img bottom-right"
                        @click.stop="openImage(cornerImages.bottomRight)" />
                    <!-- ... otras 3 miniaturas con @click.stop -->
                </div>
            </div>
        </div>

        <div v-if="showExpanded" class="lightbox" @click.stop="closeExpanded">
            <img :src="expandedImage" class="expanded-img" />
        </div>
    </div>
</template>

<style scoped>
/* Pantalla completa */
.fullscreen-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.95);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    cursor: pointer;
}

.card-container {
    width: 90vw;
    max-width: 1000px;
    height: 80vh;
    max-height: 700px;
    perspective: 1200px;
    /* ¡NADA de position: fixed aquí! */
    display: flex;
    justify-content: center;
    align-items: center;
}

.card {
    width: 100vw;
    max-width: 1200px;
    height: 100vh;
    max-height: 800px;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    cursor: pointer;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.card.flipped {
    transform: rotateY(180deg);
}

/* Frente */
.card-front,
.card-back {
    position: absolute;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
    border-radius: 20px;
    overflow: hidden;
}

.card-front h1 {
    position: absolute;
    top: 5%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    text-align: center;
    font-size: 4.5rem;
    /* Más responsive */
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.7);
    /* Para mejor legibilidad */
    z-index: 10;
    margin: 0;
    width: 100%;
    font-weight: bold;
}

.card-front img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Reverso */
.card-back {
    background: linear-gradient(135deg, #9bb3ca 0%, #c2d4e0 100%);
    color: #182019;
    padding: 2rem;
    transform: rotateY(180deg);
    position: relative;
}

/* Texto centrado */
.center-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    max-width: 600px;
    z-index: 10;
}

.center-text h2 {
    font-size: 2.4rem;
    margin: 1rem 0 0.5rem 0;
    font-weight: 700;
}

.center-text p {
    font-size: 1.2rem;
    line-height: 2.3;
    font-weight: bold;
}

.close-button {
    margin-top: 1.5rem;
    padding: 0.6rem 1.8rem;
    font-size: 1.1rem;
    background: #182019;
    color: white;
    border: none;
    border-radius: 30px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
}

.close-button:hover {
    background: #2c3e50;
    transform: translateY(-2px);
}

/* Imágenes en esquinas */
.corner-img {
    position: absolute;
    width: 240px;
    height: 240px;
    object-fit: cover;
    border-radius: 12px;
    border: 3px solid white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    z-index: 5;
}

.corner-img:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.top-left {
    top: 2rem;
    left: 2rem;
}

.top-right {
    top: 2rem;
    right: 2rem;
}

.bottom-left {
    bottom: 2rem;
    left: 2rem;
}

.bottom-right {
    bottom: 2rem;
    right: 2rem;
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
    z-index: 9998;
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

    .card-front h1 {
        font-size: 2.5rem;
        width: 100%;
        /* Ocupa todo el ancho */
        padding: 0 1.5rem;
        /* Espacio a los lados para no tocar los bordes */
        box-sizing: border-box;
        /* Incluye padding en el ancho */
        white-space: normal;
        /* Permite saltos de línea si el texto es largo */
        line-height: 1.2;
        /* Mejora la legibilidad */
    }

    .center-text p {
        font-size: 1rem;
        line-height: normal;
        font-weight: bold;
        padding: 0 1rem;
        /* Opcional: también ajusta el párrafo */
    }

    .corner-img {
        width: 120px;
        height: 120px;
    }

    .top-left {
        top: 1rem;
        left: 1rem;
    }

    .top-right {
        top: 1rem;
        right: 1rem;
    }

    .bottom-left {
        bottom: 1rem;
        left: 1rem;
    }

    .bottom-right {
        bottom: 1rem;
        right: 1rem;
    }
}
</style>