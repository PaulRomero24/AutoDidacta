<script setup>


import { ref } from 'vue';

import malargueicon from "../assets/svg/malargue_marca-1.png";
import clima from "../assets/svg/clima.png";
import destino from "../assets/svg/destino_malargue.svg";
import servicios from "@/assets/Lugares/Servicios.jpg";


//Lista de sitios
const enlaces = ref([
    {
        nombre: "Mis servicios",
        src: servicios,
        descripcion: "Por si quieres conocer sobre guiadas educacionales,visitas guiadas particulares o en caravana.",
        enlace: "/estudiantiles"
    },

    {
        nombre: "Municipalidad de Malargue",
        src: malargueicon,
        descripcion: "Pagina creada por la municipalidad de Malargüe de los lugares que se pueden visitar y lo que podes hacer en esta bella ciudad",
        enlace: "https://www.malargue.tur.ar/"
    },


    {
        nombre: "Clima",
        src: clima,
        descripcion: "Por si tienes dudas de si en tu visita te espera buen clima. ",
        enlace: "https://www.meteored.com.ar/tiempo-en_Malargue-America+Sur-Argentina-Mendoza-SAMM-1-16758.html"
    },

    {
        nombre: "Destino Malargüe",
        src: destino,
        descripcion: "Portal de turismo de Malargüe, con información sobre actividades, alojamientos y eventos.",
        enlace: "https://destinomalargue.com/es"
    }
])
</script>

<template>
    <section class="seccion-enlaces">
        <h2>Enlaces de interés</h2>
        <ul class="enlaces">
            <li v-for="link in enlaces" :key="link.nombre" class="enlace-item">
                <a :href="link.enlace" target="_blank" rel="noopener noreferrer" class="enlace-card">
                    <h3>{{ link.nombre }}</h3>
                    <img :src="link.src" :alt="link.nombre" class="enlaces-icons"/>
                    <p>{{ link.descripcion }}</p>
                </a>
            </li>
        </ul>
    </section>
</template>

<style scoped>
.seccion-enlaces h2,
.seccion-enlaces h3 {
    text-align: center;
    font-size: 2rem;
    color: #182019;
    margin-bottom: 2rem;
}

.enlaces {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: row;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
    /* permite que se ajusten en pantallas pequeñas */
}

.enlace-item {
    display: flex;
    justify-content: center;
}

.enlace-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #c7d8ce;
    padding: 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid #9bb3ca;
    max-width: 300px;
}

.enlace-card:hover {
    background: #9bb3ca;
    transform: translateY(-3px);
}

.enlaces-icons {
    width: 240px;
    height: 120px;
    object-fit: contain;
    margin-bottom: 0.8rem;
}

.enlace-card h3 {
    color: #182019;
    margin: 0 0 0.8rem 0;
    font-size: 1.4rem;
    text-align: center;
}

.enlace-card p {
    color: #495057;
    line-height: 1.6;
    margin: 0;
    font-size: 1rem;
    text-align: center;
    font-weight: 700;
}

/* Responsive */
@media (max-width: 768px) {
    .enlaces {
        flex-direction: column;
        align-items: center;
    }

    .seccion-enlaces h2 {
        font-size: 1.8rem;
    }

    .enlace-card {
        padding: 1.2rem;
        width: 90%;
    }

    .enlace-card h3 {
        font-size: 1.3rem;
    }
}
</style>