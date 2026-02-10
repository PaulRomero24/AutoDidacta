<script setup>
import email from "../assets/svg/email.svg";
import facebook from "../assets/svg/facebook.svg";
import tiktok from "../assets/svg/tiktok.svg";
import instagram from "../assets/svg/instagram.svg";

import { ref } from 'vue';

const redes = ref([
    { nombre: "Instagram", svg: instagram, enlace: "https://instagram.com/tu-perfil" },
    { nombre: "Facebook", svg: facebook, enlace: "https://facebook.com/tu-perfil" },
    { nombre: "TikTok", svg: tiktok, enlace: "https://tiktok.com/@tu-perfil" },
    { nombre: "Email", svg: email, enlace: "mailto:tucorreo@ejemplo.com" }
]);
</script>

<template>
    <footer class="footer-redes">
        <div class="redes-contenedor">
            <h4>Mis redes</h4>
            <ul class="redes">
                <li v-for="red in redes" :key="red.nombre">
                    <a :href="red.enlace" target="_blank" rel="noopener noreferrer" :aria-label="red.nombre">
                        <img :src="red.svg" :alt="`Icono de ${red.nombre}`" />
                    </a>
                </li>
            </ul>
                <p>Todos los derechos reservados</p>
        </div>
    </footer>
</template>

<style scoped>
.footer-redes {
    text-align: center;
    padding: 2rem 1rem;
    background: #9ae7bdb9;
    color: #182019;
    margin-top: 3rem;
}

.redes-contenedor {
    margin-top: 1.5rem;
}

.redes-contenedor h4 {
    margin-bottom: 1rem;
    font-size: 3rem;
    color: #182019;
    text-decoration: underline;
}

.redes {
    list-style: none;
    display: flex;
    justify-content: center;
    gap: 2.5rem;
    padding: 1rem;
    margin: 0;
}

.redes li {
    display: block;
}

.redes img {
    width: 45px;
    height: 45px;
    transition: transform 0.2s;
}

.redes img:hover {
    transform: scale(1.2);
}

/* Responsive */
@media (max-width: 768px) {
    .redes {
        gap: 1.5rem;
    }

    .redes img {
        width: 28px;
        height: 28px;
    }
}
</style>