<script setup>



import { ref, computed, onMounted, onUnmounted } from 'vue';

const expandido = ref(false);
const indiceActual = ref(0);
const videoRef = ref(null);
const containerRef = ref(null);
let observer = null;

// Reemplazá con tus videos reales
const videos = ref([
    {
        tipo: 'youtube',
        src: 'hG6L4EjAp5M',
        titulo: 'Entrevista - Radio Mitre',
    },
    {
        tipo: 'youtube',
        id: 'VoV_8IXDFww',
        titulo: 'Payunia - Rumbos TV - "Consumidor Final"',
    },
    {
        tipo: 'youtube',
        id: 'KAgzjdyr2bw',
        titulo: 'Saludos - Chile',
    },
    {
        tipo: 'youtube',
        src: '7Q215dG-Pjg',
        titulo: 'Malacara- Pablo Gamba',
    },
]);

const videoActual = computed(() => videos.value[indiceActual.value]);

const youtubeUrl = computed(() => {
    if (videoActual.value.tipo !== 'youtube') return '';
    return `https://www.youtube.com/embed/${videoActual.value.id}?autoplay=0&rel=0&modestbranding=1`;
});

const toggleExpandido = () => {
    expandido.value = !expandido.value;
    if (!expandido.value && videoRef.value) {
        videoRef.value.pause();
    }
};

const anterior = () => {
    if (videoRef.value) videoRef.value.pause();
    indiceActual.value = (indiceActual.value - 1 + videos.value.length) % videos.value.length;
};

const siguiente = () => {
    if (videoRef.value) videoRef.value.pause();
    indiceActual.value = (indiceActual.value + 1) % videos.value.length;
};

const irA = (index) => {
    if (videoRef.value) videoRef.value.pause();
    indiceActual.value = index;
};

// Pausa si sale del viewport
onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting && videoRef.value) {
                    videoRef.value.pause();
                }
            });
        },
        { threshold: 0.3 }
    );
    if (containerRef.value) observer.observe(containerRef.value);
});

onUnmounted(() => {
    if (observer) observer.disconnect();
});
</script>

<template>
    <div class="video-widget" :class="{ expandido }" ref="containerRef">
        <!-- Cabecera siempre visible — click para expandir -->
        <p class="widget-label">Entrevistas</p>
        <div class="widget-header" @click="toggleExpandido">
            <span class="play-icon">{{ expandido ? '▼' : '▶' }}</span>
            <span class="widget-titulo">{{ videoActual.titulo }}</span>
            <span class="contador">{{ indiceActual + 1 }}/{{ videos.length }}</span>
        </div>

        <!-- Player expandible -->
        <div class="widget-body">
            <div class="player-wrapper">

                <iframe v-if="videoActual.tipo === 'youtube'" :src="youtubeUrl" :key="`yt-${indiceActual}`"
                    class="video-player" frameborder="0" allow="fullscreen; picture-in-picture"
                    allowfullscreen></iframe>

                <video v-else ref="videoRef" :key="`local-${indiceActual}`" class="video-player" controls playsinline
                    preload="metadata">
                    <source :src="videoActual.src" type="video/mp4" />
                </video>

            </div>

            <!-- Navegación -->
            <div class="controles">
                <button class="btn-nav" @click.stop="anterior">←</button>
                <div class="indicadores">
                    <button v-for="(_, i) in videos" :key="i" class="indicador" :class="{ activo: i === indiceActual }"
                        @click.stop="irA(i)"></button>
                </div>
                <button class="btn-nav" @click.stop="siguiente">→</button>
            </div>
        </div>

    </div>
</template>

<style scoped>
.video-widget {
    position: absolute;
    bottom: -100px;
    left: 50%;
    transform: translateX(-50%);
    width: 200px;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid white;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.25);
    background: var(--verde-bosque);
    z-index: 2;
    transition: width 0.4s cubic-bezier(0.34, 1.56, 0.64, 1),
        bottom 0.4s ease;
    cursor: pointer;
}

.widget-label {
    text-align: center;
    font-family: var(--font-titulo);
    font-size: 1rem;
    font-style: italic;
    color: var(--arena);
    padding: 8px 14px 0;
    margin: 0;
    letter-spacing: 0.05em;
}

/* Expandido */
.video-widget.expandido {
    width: 520px;
    bottom: -180px;
    cursor: default;
}

/* Header */
.widget-header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    cursor: pointer;
    user-select: none;
}

.play-icon {
    color: var(--arena);
    font-size: 0.85rem;
    flex-shrink: 0;
    transition: transform 0.3s ease;
}

.widget-titulo {
    font-family: var(--font-cuerpo);
    font-size: 0.78rem;
    color: var(--humo);
    letter-spacing: 0.05em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}

.contador {
    font-family: var(--font-cuerpo);
    font-size: 0.7rem;
    color: var(--arena);
    flex-shrink: 0;
    opacity: 0.7;
}

/* Body — oculto por defecto */
.widget-body {
    max-height: 278px;
    overflow: hidden;
    transition: max-height 0.4s ease;
}

.video-widget.expandido .widget-body {
    max-height: 400px;
}

/* Player */
.player-wrapper {
    width: 100%;
    aspect-ratio: 16/9;
    background: #000;
}

.video-player {
    width: 100%;
    height: 100%;
    display: block;
}

/* Controles */
.controles {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    padding: 8px;
    background: rgba(0, 0, 0, 0.2);
}

.btn-nav {
    background: var(--arena);
    color: var(--verde-bosque);
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-nav:hover {
    background: var(--arena-claro);
    transform: scale(1.1);
}

.indicadores {
    display: flex;
    gap: 6px;
}

.indicador {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    border: none;
    background: rgba(245, 240, 232, 0.3);
    cursor: pointer;
    padding: 0;
    transition: all 0.3s ease;
}

.indicador.activo {
    background: var(--arena);
    transform: scale(1.3);
}

/* Responsive */
@media (max-width: 768px) {
    .video-widget {
        position: relative;
        bottom: auto;
        left: auto;
        transform: none;
        width: 100%;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .video-widget.expandido {
        width: 100%;
        bottom: auto;
    }
}
</style>