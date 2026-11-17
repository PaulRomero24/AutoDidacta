<script setup>
import { ref, computed, watch, onBeforeUnmount } from "vue";

const props = defineProps({
    abierto: Boolean,
    noticias: { type: Array, default: () => [] },
});
const emit = defineEmits(["cerrar"]);

const indice = ref(0);
const total = computed(() => props.noticias.length);
const actual = computed(() => props.noticias[indice.value]);
const imagenes = computed(() =>
    actual.value
        ? [actual.value.imagen1, actual.value.imagen2].filter(Boolean)
        : [],
);

const siguiente = () => {
    if (total.value > 1) indice.value = (indice.value + 1) % total.value;
};
const anterior = () => {
    if (total.value > 1)
        indice.value = (indice.value - 1 + total.value) % total.value;
};

// Imagen ampliada
const expandedImage = ref("");
const showExpanded = ref(false);

const openImage = (src) => {
    expandedImage.value = src;
    showExpanded.value = true;
};

const closeExpanded = () => {
    showExpanded.value = false;
};

const teclado = (e) => {
    if (e.key === "Escape") {
        showExpanded.value ? closeExpanded() : emit("cerrar");
    } else if (showExpanded.value) {
        return; // con la imagen ampliada, las flechas no cambian de noticia
    } else if (e.key === "ArrowRight") siguiente();
    else if (e.key === "ArrowLeft") anterior();
};
// Deslizar con el dedo en el celular
let inicioX = 0;
const tocarInicio = (e) => {
    inicioX = e.changedTouches[0].clientX;
};
const tocarFin = (e) => {
    const dx = e.changedTouches[0].clientX - inicioX;
    if (Math.abs(dx) > 50) dx < 0 ? siguiente() : anterior();
};

const formatearFecha = (fecha) =>
    new Date(fecha).toLocaleDateString("es-AR", {
        day: "2-digit",
        month: "long",
        year: "numeric",
    });

watch(
    () => props.abierto,
    (abierto) => {
        if (abierto) {
            indice.value = 0;
            window.addEventListener("keydown", teclado);
            document.body.style.overflow = "hidden";
        } else {
            window.removeEventListener("keydown", teclado);
            document.body.style.overflow = "";
        }
    },
);

onBeforeUnmount(() => {
    window.removeEventListener("keydown", teclado);
    document.body.style.overflow = "";
});
</script>

<template>
    <div v-if="abierto && total > 0" class="noticias-overlay" @click="emit('cerrar')">
        <div class="noticias-modal" role="dialog" aria-modal="true" aria-label="Noticias de la semana" @click.stop
            @touchstart="tocarInicio" @touchend="tocarFin">
            <button class="cerrar" @click="emit('cerrar')" aria-label="Cerrar">
                ✕
            </button>

            <p class="etiqueta">Noticias de la semana</p>

            <article :key="actual.id" class="noticia">
                <div v-if="imagenes.length" class="imagenes" :class="{ doble: imagenes.length === 2 }">
                    <img v-for="(src, i) in imagenes" :key="i" :src="src" :alt="`${actual.titulo} (${i + 1})`"
                        class="zoomable" @click="openImage(src)" />
                </div>
                <h3>{{ actual.titulo }}</h3>
                <p class="fecha">{{ formatearFecha(actual.fecha) }}</p>
                <p class="texto">{{ actual.texto }}</p>
            </article>

            <div v-if="total > 1" class="navegacion">
                <button class="flecha" @click="anterior" aria-label="Noticia anterior">
                    ‹
                </button>
                <div class="puntos">
                    <button v-for="(n, i) in noticias" :key="n.id" class="punto" :class="{ activo: i === indice }"
                        @click="indice = i" :aria-label="`Ir a la noticia ${i + 1}`"></button>
                </div>
                <button class="flecha" @click="siguiente" aria-label="Noticia siguiente">
                    ›
                </button>
            </div>
        </div>

        <div v-if="showExpanded" class="lightbox" @click.stop="closeExpanded">
            <button class="lightbox-close" @click.stop="closeExpanded" aria-label="Cerrar imagen">✕</button>
            <img :src="expandedImage" alt="Imagen ampliada" class="expanded-img" @click.stop />
        </div>
    </div> <!-- cierra .noticias-overlay -->
</template>

<style scoped>
.noticias-overlay {
    position: fixed;
    inset: 0;
    background: rgba(5, 10, 5, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9000;
    backdrop-filter: blur(4px);
    padding: 1rem;
}

.noticias-modal {
    position: relative;
    background: var(--humo, #f5f0e8);
    color: var(--texto-oscuro, #3d3d2e);
    border-radius: 16px;
    width: 100%;
    max-width: 768px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 1.75rem 1.5rem 1.25rem;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
}

.cerrar {
    position: absolute;
    top: 0.75rem;
    right: 0.75rem;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.08);
    cursor: pointer;
    font-size: 0.9rem;
}

.cerrar:hover {
    background: rgba(0, 0, 0, 0.16);
}

.etiqueta {
    font-size: 0.72rem;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--tierra, #8b4513);
    margin-bottom: 1rem;
}

.imagenes {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.imagenes.doble {
    grid-template-columns: 1fr 1fr;
}

.imagenes img {
    width: 100%;
    aspect-ratio: 4 / 3;
    object-fit: cover;
    border-radius: 10px;
    display: block;
}

h3 {
    font-family: var(--font-titulo, serif);
    font-size: 1.4rem;
    color: var(--verde-bosque, #1a2e1a);
    margin: 0 0 0.25rem;
    overflow-wrap: anywhere;
}

.fecha {
    font-size: 0.78rem;
    font-style: italic;
    opacity: 0.6;
    margin-bottom: 0.75rem;
}

.texto {
    line-height: 1.6;
    white-space: pre-line;
    overflow-wrap: anywhere;
}

.navegacion {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.25rem;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
}

.flecha {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: var(--verde-bosque, #1a2e1a);
    color: var(--arena, #d4a96a);
    font-size: 1.6rem;
    line-height: 1;
    cursor: pointer;
}

.flecha:hover {
    opacity: 0.85;
}

.puntos {
    display: flex;
    gap: 8px;
}

.punto {
    width: 10px;
    height: 10px;
    padding: 0;
    border: none;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.2);
    cursor: pointer;
}

.punto.activo {
    background: var(--tierra, #8b4513);
}

.zoomable {
    cursor: zoom-in;
    transition: transform 0.2s ease;
}

.zoomable:hover {
    transform: scale(1.02);
}

.lightbox {
    position: fixed;
    inset: 0;
    background: rgba(5, 10, 5, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9100;
    cursor: zoom-out;
    padding: 1rem;
}

.expanded-img {
    max-width: 94vw;
    max-height: 88vh;
    object-fit: contain;
    border-radius: 6px;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.8);
    cursor: default;
}

.lightbox-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    color: white;
    font-size: 1rem;
    cursor: pointer;
}

.lightbox-close:hover {
    background: rgba(255, 255, 255, 0.25);
}
</style>
