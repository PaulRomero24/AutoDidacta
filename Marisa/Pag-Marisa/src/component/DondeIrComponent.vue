<script setup>
import caverna1 from "../assets/Lugares/Caverna1.jpg";
import caverna2 from "../assets/Lugares/Caverna2.jpg";
import payunia1 from "../assets/Lugares/Payunia1.jpg";
import payunia2 from "../assets/Lugares/Payunia2.jpg";
import valles1 from "../assets/Lugares/Valles1.jpg";
import valles2 from "../assets/Lugares/Valles2.jpg";
import malacara1 from "../assets/Lugares/Malacara1.jpg";
import malacara2 from "../assets/Lugares/Malacara2.jpg";

import { ref } from 'vue';


// Estado para el lightbox
const expandedImage = ref('');
const showExpanded = ref(false);

const openImage = (src) => {
    expandedImage.value = src;
    showExpanded.value = true;
};

const closeExpanded = () => {
    showExpanded.value = false;
    expandedImage.value = '';
};

//Lista de sitios
const lugares = ref([
    {
        nombre: "Caverna de las brujas",
        descripcion: "Caverna de las brujas es un lugar llena de magia y belleza.Aca hacemos un recorrido dentro de una caverna moldeada por el tiempo, que se dividen en diferentes salas.",
        imagenes: [caverna1, caverna2]
    },

    {
        nombre: "Payunia",
        descripcion: "Aunque pareciera una escena de alguna pelicula en el espacio, este lugar existe, y la sensacion que produce estar en el medio de casi 800 conos volcanicos es una experiencia imborrable",
        imagenes: [payunia1, payunia2]
    },

    {
        nombre: "Malacara",
        descripcion: "lorem qwepqowijasdkflkfm lorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfm",
        imagenes: [malacara1, malacara2]
    },

    {
        nombre: "Valles",
        descripcion: "El complejo de los valles es un recorrido a travez de la ruta que lleva a 'Las Leñas',en el veremos 'La Niña Encantada','Pozo de las animas','Termas de los Molles' y por ultimo el complejo de nieve 'Las Leñas'  ",
        imagenes: [valles1, valles2]
    },
])


</script>
<template>
    <div class="lugares-container">
        <h2 class="section-title">Lugares donde puedes ir</h2>
        <div v-for="lugar in lugares" :key="lugar.nombre" class="lugar-card">
            <h2>{{ lugar.nombre }}</h2>

            <div class="imagenes">
                <div v-for="(imagen, index) in lugar.imagenes" :key="index" class="img-wrapper"
                    @click="openImage(imagen)">
                    <img :src="imagen" :alt="`${lugar.nombre} imagen ${index + 1}`" />
                </div>
            </div>

            <p class="descripcion">{{ lugar.descripcion }}</p>
        </div>
    </div>
    <!-- Lightbox -->
    <div v-if="showExpanded" class="lightbox" @click="closeExpanded">
        <img :src="expandedImage" alt="Imagen expandida" class="expanded-img" />
    </div>
</template>

<style scoped>
.lugares-container {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
    width: 100%;
    padding: 0 1.5rem;
    /* ← Añade padding horizontal aquí */
    margin: 2rem auto;
}

.lugar-card {
    background: #c7d8ce;
    border-radius: 16px;
    padding: 0.5rem;
    /* ← Un poco más de espacio interno */
    box-shadow: 0 4px 12px rgba(175, 34, 34, 0.1);
    width: 100%;
    /* ← ¡Ocupa todo el ancho disponible! */
    max-width: 1600px;
    /* ← Opcional: evita que sea demasiado ancha en 4K */
    margin: 0 auto;
    /* ← Centra si usas max-width */
}

.section-title {
    text-align: center;
    font-size: 2.2rem;
    margin-bottom: 2rem;
    color: #182019;
    width: 100%;
}

.descripcion{
    font-size: 24px;
    text-align: center;
    font-family: sans-serif;
    font-style: italic;
}
.imagenes {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    justify-content: center;
    width: 100%;
}


/* Wrapper de imagen con lupa */
.img-wrapper {
    position: relative;
    cursor: pointer;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    width: 100%;
    height: 250px;
}

.img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.img-wrapper::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    font-size: 2rem;
    color: white;
    background: rgba(0, 0, 0, 0.6);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: transform 0.3s ease;
    z-index: 2;
}

.img-wrapper:hover::after {
    transform: translate(-50%, -50%) scale(1);
}

.img-wrapper:hover img {
    transform: scale(1.03);
}

/* Móvil */
@media (max-width: 768px) {
    .imagenes {
        flex-direction: column;
        align-items: center;
    }

    .img-wrapper {
        height: 220px;
        width: 100%;
        /* usa todo el ancho */
    }

    .img-wrapper::after {
        display: none;
    }

    .img-wrapper:hover img {
        transform: scale(1);
    }
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
    z-index: 5000;
    cursor: pointer;
}

.expanded-img {
    max-width: 90vw;
    max-height: 90vh;
    object-fit: contain;
    border: 4px solid white;
    border-radius: 8px;
}


/* Responsive */
@media (max-width: 1024px) {
    .imagenes {
        flex-direction: column;
        align-items: center;
    }

    .lugar-img {
        height: 200px;

    }

    .descripcion {
        font-size: 1rem;
    }
}
</style>