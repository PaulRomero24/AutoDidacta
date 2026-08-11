<script setup>
import { ref, computed } from 'vue';

import frontal from "../assets/CartaReverso/Frontal.jpg";
import reverso1 from "../assets/CartaReverso/Reverso1.jpg";
import reverso2 from "../assets/CartaReverso/Reverso2.jpg";
import reverso3 from "../assets/CartaReverso/Reverso3.jpg";
import reverso4 from "../assets/CartaReverso/Reverso4.jpg";

const emit = defineEmits(['close']);
const isFlipped = ref(false);
const expandedImage = ref('');
const showExpanded = ref(false);

const cornerImages = {
    topLeft: reverso1,
    topRight: reverso2,
    bottomLeft: reverso3,
    bottomRight: reverso4,
};

// Estilos críticos del flip como objetos JS (igual que el test que funcionó)
const cardStyle = computed(() => {
    const isMobile = window.innerWidth <= 768
    return {
        width: isMobile ? '90vw' : '1024px',
        height: isMobile ? '85vh' : '840px',
        position: 'relative',
        transformStyle: 'preserve-3d',
        transition: 'transform 0.8s cubic-bezier(0.645,0.045,0.355,1)',
        cursor: 'pointer',
    }
})

const faceStyle = {
    position: 'absolute',
    inset: '0',
    backfaceVisibility: 'hidden',
    WebkitBackfaceVisibility: 'hidden',
    borderRadius: '16px',
    overflow: 'hidden',
    border: '1px solid rgba(212,169,106,0.2)',
    pointerEvents: 'none',
};

const backFaceStyle = {
    ...faceStyle,
    transform: 'rotateY(180deg)',
    pointerEvents: 'none',
};

const openImage = (src) => { expandedImage.value = src; showExpanded.value = true; };
const closeExpanded = () => { showExpanded.value = false; };
const closeCard = () => { document.body.style.overflow = ''; emit('close'); };
const frontPointer = computed(() => isFlipped.value ? 'none' : 'auto')
const backPointer = computed(() => isFlipped.value ? 'auto' : 'none')

</script>

<template>
    <div class="fullscreen-overlay" @click="closeCard">
        <div class="card-container" @click.stop>

            <div class="flip-hint" :class="{ hidden: isFlipped }">
                <span>Tocá para descubrir</span>
                <div class="hint-arrow">👆</div>
            </div>

            <!-- Estilos críticos inline -->
            <div :style="[cardStyle, { transform: isFlipped ? 'rotateY(180deg)' : 'rotateY(0deg)' }]"
                @click="isFlipped = !isFlipped">
                <!-- FRENTE -->
                <div :style="[faceStyle, { pointerEvents: frontPointer }]" class="face-front">
                    <div class="card-front-bg"></div>
                    <div class="card-front-content">
                        <div class="card-badge">Guía de Turismo</div>
                        <div class="card-portrait-frame">
                            <img :src="frontal" alt="Marisa Berdu" class="portrait-img" />
                        </div>
                        <h1 class="card-name">MARISA BERDU</h1>
                        <p class="card-subtitle">Malargüe · Mendoza · Argentina</p>
                        <div class="card-divider"></div>
                        <p class="card-years">24 años de experiencia</p>
                    </div>
                    <div class="card-corners">
                        <span class="corner tl"></span>
                        <span class="corner tr"></span>
                        <span class="corner bl"></span>
                        <span class="corner br"></span>
                    </div>
                </div>

                <!-- REVERSO -->
                <div :style="[backFaceStyle, { pointerEvents: backPointer }]" class="face-back">
                    <div class="card-back-bg"></div>
                    <div class="center-text">
                        <h2>¿Quién soy?</h2>
                        <p>
                            Soy una guía de turismo en Malargüe, llevo en actividad
                            24 años y aún ejerciendo. Ejercí como Profesora, también he sido dueña de
                            una agencia de turismo. Amo a mi Malargüe y espero que ustedes también.
                        </p>
                        <button class="close-button" @click.stop="() => { console.log('click botón'); closeCard(); }">
                            Entrar al sitio <span class="btn-arrow">⬅︎</span>
                        </button>
                    </div>
                    <div class="corner-photos">
                        <div v-for="key in ['topLeft', 'topRight', 'bottomLeft', 'bottomRight']" :key="key"
                            class="corner-photo-wrapper" :class="key.replace(/([A-Z])/g, '-$1').toLowerCase()"
                            @click.stop="openImage(cornerImages[key])">
                            <img :src="cornerImages[key]" :alt="key" />
                            <div class="photo-overlay"><span>+</span></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div v-if="showExpanded" class="lightbox" @click.stop="closeExpanded">
            <img :src="expandedImage" alt="Imagen expandida" class="expanded-img" />
        </div>
    </div>
</template>

<style scoped>
.fullscreen-overlay {
    position: fixed;
    inset: 0;
    background:
        radial-gradient(ellipse at 30% 20%, rgba(74, 124, 74, 0.15) 0%, transparent 60%),
        radial-gradient(ellipse at 70% 80%, rgba(139, 69, 19, 0.1) 0%, transparent 60%),
        linear-gradient(135deg, #0d1a0d 0%, #1a2e1a 40%, #0f1f0f 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    cursor: pointer;
    overflow: hidden;
}

.fullscreen-overlay::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 20% 30%, rgba(212, 169, 106, 0.08) 1px, transparent 1px),
        radial-gradient(circle at 80% 70%, rgba(212, 169, 106, 0.06) 1px, transparent 1px);
    background-size: 60px 60px, 80px 80px;
    pointer-events: none;
}

.flip-hint {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    color: var(--arena);
    opacity: 0.7;
    font-family: var(--font-cuerpo);
    font-size: 0.85rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    text-align: center;
    transition: opacity 0.4s ease;
    pointer-events: none;
    z-index: 2;
}

.flip-hint.hidden {
    opacity: 0;
}

.hint-arrow {
    animation: bounce 1.5s ease infinite;
    font-size: 1rem;
    margin-top: 4px;
}

@keyframes bounce {

    0%,
    100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(6px);
    }
}

.card-container {
    perspective: 1400px;
    cursor: default;
    animation: cardEntrance 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}

@keyframes cardEntrance {
    from {
        transform: scale(0.7) translateY(40px);
        opacity: 0;
    }

    to {
        transform: scale(1) translateY(0);
        opacity: 1;
    }
}

/* FRENTE */
.face-front {
    background: linear-gradient(160deg, #1a2e1a 0%, #0d1a0d 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-front-bg {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 0%, rgba(212, 169, 106, 0.12) 0%, transparent 60%);
    pointer-events: none;
}

.card-front-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 2rem;
    text-align: center;
    width: 100%;
    height: 100%;
}

.card-badge {
    font-family: var(--font-cuerpo);
    font-size: 0.7rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--arena);
    border: 1px solid rgba(212, 169, 106, 0.4);
    padding: 4px 16px;
    border-radius: 20px;
}

.card-portrait-frame {
    width: 75%;
    aspect-ratio: 3/4;
    border-radius: 78px;
    border: 2px solid rgba(212, 169, 106, 0.5);
    box-shadow: 0 0 0 8px rgba(212, 169, 106, 0.06), 0 0 30px rgba(212, 169, 106, 0.15);
    overflow: hidden;
    margin: 8px 0;
}

.portrait-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top;
}

.card-name {
    font-family: var(--font-display);
    font-size: 1.6rem;
    font-weight: 600;
    color: var(--arena-claro);
    letter-spacing: 0.15em;
    text-shadow: 0 2px 20px rgba(212, 169, 106, 0.3);
}

.card-subtitle {
    font-family: var(--font-cuerpo);
    font-size: 0.78rem;
    color: rgba(245, 240, 232, 0.5);
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

.card-divider {
    width: 60px;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--arena), transparent);
}

.card-years {
    margin-bottom: 50px;
    font-family: var(--font-cuerpo);
    font-size: 0.85rem;
    color: rgba(212, 169, 106, 0.8);
    font-style: italic;
}

.card-corners {
    position: absolute;
    inset: 12px;
    pointer-events: none;
    z-index: 3;
}

.corner {
    position: absolute;
    width: 20px;
    height: 20px;
    border-color: rgba(212, 169, 106, 0.3);
    border-style: solid;
}

.corner.tl {
    top: 0;
    left: 0;
    border-width: 1px 0 0 1px;
}

.corner.tr {
    top: 0;
    right: 0;
    border-width: 1px 1px 0 0;
}

.corner.bl {
    bottom: 0;
    left: 0;
    border-width: 0 0 1px 1px;
}

.corner.br {
    bottom: 0;
    right: 0;
    border-width: 0 1px 1px 0;
}

/* REVERSO */
.face-back {
    background: linear-gradient(160deg, #1e1409 0%, #2a1c0e 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: all;
}

.card-back-bg {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 50%, rgba(139, 69, 19, 0.15) 0%, transparent 70%);
    pointer-events: none;
}

.center-text {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.center-text h2 {
    font-family: var(--font-titulo);
    font-size: 1.5rem;
    color: var(--arena-claro);
    font-style: italic;
}

.center-text p {
    font-size: 1.25rem;
    line-height: 1.7;
    color: rgba(245, 240, 232, 0.75);
    max-width: 280px;
}

.close-button {
    position: relative;
    margin-top: 8px;
    background: linear-gradient(135deg, var(--tierra) 25%, var(--tierra-claro) 100%);
    color: var(--humo);
    border: none;
    padding: 12px 28px;
    border-radius: 30px;
    font-family: var(--font-cuerpo);
    font-size: 0.85rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(202, 167, 142, 0.4);
    z-index: 10;
}

.close-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(182, 94, 31, 0.6);
}

.btn-arrow {
    font-size: 2rem;
    transition: transform 0.3s ease;
}

.close-button:hover .btn-arrow {
    transform: translateX(4px);
}

/* Fotos esquinas reverso */
.corner-photos {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 1;
}

.corner-photo-wrapper {
    position: absolute;
    width: 320px;
    height: 320px;
    border-radius: 48px;
    overflow: hidden;
    border: 2px solid rgba(212, 169, 106, 0.25);
    cursor: zoom-in;
    pointer-events: all;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
}

.corner-photo-wrapper:hover {
    transform: scale(1.08);
    border-color: rgba(212, 169, 106, 0.6);
    z-index: 10;
}

.corner-photo-wrapper.top-left {
    top: 16px;
    left: 16px;
}

.corner-photo-wrapper.top-right {
    top: 16px;
    right: 16px;
}

.corner-photo-wrapper.bottom-left {
    bottom: 16px;
    left: 16px;
}

.corner-photo-wrapper.bottom-right {
    bottom: 16px;
    right: 16px;
}

.corner-photo-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-overlay {
    position: absolute;
    inset: 0;
    background: rgba(26, 46, 26, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--arena);
    font-size: 1.5rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.corner-photo-wrapper:hover .photo-overlay {
    opacity: 1;
}

/* Lightbox */
.lightbox {
    position: fixed;
    inset: 0;
    background: rgba(10, 18, 10, 0.92);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    cursor: zoom-out;
    backdrop-filter: blur(4px);
}

.expanded-img {
    max-width: 90vw;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 4px;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.8);
}

@media (max-width: 768px) {
    .card-container {
        width: 90vw;
        display: flex;
        justify-content: center;
    }

    .corner-photo-wrapper {
        width: 150px;
        height: 150px;
        border-radius: 12px;
    }
.center-text {
        padding: 1rem;
        gap: 8px;
    }
    
    .center-text p {
        font-size: 0.8rem;
        max-width: 200px;
    }
}

@media (min-width: 768px) and (max-width: 1024px) {
    .face-back {
        flex-direction: row;
        align-items: center;
        justify-content: space-evenly;
        padding: 1rem;
    }

    .center-text {
        order: 2;
        max-width: 300px;
    }

    .center-text p {
        font-size: 1.5rem;
        max-width: 260px;
    }

    .corner-photo-wrapper {
        width: 250px;
        height: 250px;
    }

    /* En row, reposicionamos las fotos */
    .corner-photo-wrapper.top-left {
        top: 10px;
        left: 10px;
    }

    .corner-photo-wrapper.top-right {
        top: 10px;
        right: 10px;
    }

    .corner-photo-wrapper.bottom-left {
        bottom: 10px;
        left: 10px;
    }

    .corner-photo-wrapper.bottom-right {
        bottom: 10px;
        right: 10px;
    }
}
</style>