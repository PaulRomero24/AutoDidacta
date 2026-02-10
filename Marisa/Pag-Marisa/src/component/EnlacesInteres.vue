<script setup>
    import { ref } from 'vue';
//Lista de sitios
const enlaces = ref([
    {
        nombre: "Municipalidad de Malargue",
        descripcion: "lorem qwepqowijasdkflkfm lorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfm",
        enlace: "https://www.malargue.gov.ar/"
    },

    {
        nombre: "Direccion de Turismo",
        descripcion: "lorem qwepqowijasdkflkfm lorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfm",
        enlace: "https://www.malargue.tur.ar/"
    },

    {
        nombre: "Clima",
        descripcion: "lorem qwepqowijasdkflkfm lorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfmlorem qwepqowijasdkflkfm",
        enlace: "https://www.meteored.com.ar/tiempo-en_Malargue-America+Sur-Argentina-Mendoza-SAMM-1-16758.html"
    },
])
</script>

<template>
    <section class="seccion-enlaces">
        <h2>Enlaces de interés</h2>
        <ul class="enlaces">
            <li v-for="link in enlaces" :key="link.nombre" class="enlace-item">
                <a :href="link.enlace" target="_blank" rel="noopener noreferrer" class="enlace-card">
                    <h3>{{ link.nombre }}</h3>
                    <p>{{ link.descripcion }}</p>
                </a>
            </li>
        </ul>
    </section>
</template>

<style scoped>

.seccion-enlaces h2,h3 {
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
}

.enlace-item {
    display: block;
}

.enlace-card {
    display: block;
    background: #a1c4e7;
    padding: 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 2px solid #9bb3ca;
}

.enlace-card:hover {
    background: #9bb3ca;
    transform: translateY(-3px);
}

.enlace-card h3 {
    color: #182019;
    margin: 0 0 0.8rem 0;
    font-size: 1.4rem;
}

.enlace-card p {
    color: #495057;
    line-height: 1.6;
    margin: 0;
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {

    .enlaces{
        flex-direction: column;
    }

    .seccion-enlaces h2 {
        font-size: 1.8rem;
    }

    .enlace-card {
        padding: 1.2rem;
    }

    .enlace-card h3 {
        font-size: 1.3rem;
    }
}
</style>