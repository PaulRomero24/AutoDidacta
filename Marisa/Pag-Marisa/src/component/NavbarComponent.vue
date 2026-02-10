<!-- eslint-disable vue/multi-word-component-names -->
<script setup>
import { ref } from 'vue';

const navegacion = ref([
    { id: 1, nombre: "¿Donde ir?", enlace: "#dondeir" },
    { id: 2, nombre: "Mi Malargüe", enlace: "#mimalargue" },
    { id: 3, nombre: "Experiencia", enlace: "#experiencia" },
    { id: 4, nombre: "Enlaces de interes", enlace: "#links" },
]);

const menuVisible = ref(false);

const toggleMenu = () => {
    menuVisible.value = !menuVisible.value;
};

const closeMenu = () => {
    menuVisible.value = false;
};
</script>

<template>
    <!-- Botón hamburguesa (solo en móvil) -->
    <button class="menu-toggle" @click="toggleMenu" aria-label="Abrir menú">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Navbar -->
    <nav class="navbar" :class="{ 'navbar--open': menuVisible }">
        <ul class="nav-list">
            <li v-for="nav in navegacion" :key="nav.id">
                <a :href="nav.enlace" class="nav-item" @click="closeMenu">
                    {{ nav.nombre }}
                </a>
            </li>
        </ul>
    </nav>

    <!-- Overlay para móviles -->
    <div v-if="menuVisible" class="overlay" @click="closeMenu"></div>
</template>

<style scoped>
/* === Estilos comunes === */
.navbar {
    position: fixed;
    top: 0;
    z-index: 1050;
    background-color: #e4ec97;
    padding: 1rem 1.5rem;
    border-radius: 20px;
    font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 1.2rem;
}

.nav-item {
    display: block;
    color: rgb(24, 32, 25);
    text-decoration: none;
    padding: 8px 16px;
    border: 3px solid hsl(165, 44%, 96%);
    border-radius: 12px;
    transition: all 0.3s ease;
    text-align: center;
    font-size: 1.1rem;
    white-space: nowrap;
}

.nav-item:hover {
    background-color: hsla(160, 56%, 52%, 0.747);
    color: black;
}

/* === Desktop: centrado arriba === */
@media (min-width: 769px) {
    .menu-toggle {
        display: flex;
        position: fixed;
        top: 1.2rem;
        left: 1.2rem;
        flex-direction: row;
        justify-content: space-between;
        width: 20px;
        height: 14px;
        background: transparent;
        border: none;
        cursor: pointer;
        z-index: 1100;
        padding: 0;
    }


    .navbar {
        left: 50%;
        transform: translateX(-50%);
        width: fit-content;
        height: fit-content;
        /* Siempre visible */
    }

    .nav-list {
        flex-direction: row;
    }

    .overlay {
        display: none;
    }
}

/* === Móvil: lateral izquierda === */
@media (max-width: 768px) {
    .menu-toggle {
        display: flex;
        position: fixed;
        top: 1.2rem;
        left: 1.2rem;
        flex-direction: column;
        justify-content: space-between;
        width: 20px;
        height: 14px;
        background: transparent;
        border: none;
        cursor: pointer;
        z-index: 1100;
        padding: 0;
    }

    .menu-toggle span {
        display: block;
        height: 3px;
        width: 100%;
        background-color: #182019;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    /* Efecto X al abrir */
    .navbar--open~.overlay+.menu-toggle span:nth-child(1),
    .navbar--open+.overlay~.menu-toggle span:nth-child(1) {
        transform: rotate(45deg) translate(6px, 6px);
    }

    .navbar--open~.overlay+.menu-toggle span:nth-child(2),
    .navbar--open+.overlay~.menu-toggle span:nth-child(2) {
        opacity: 0;
    }

    .navbar--open~.overlay+.menu-toggle span:nth-child(3),
    .navbar--open+.overlay~.menu-toggle span:nth-child(3) {
        transform: rotate(-45deg) translate(6px, -6px);
    }

    .navbar {
        /* Comienza fuera de la pantalla */
        left: -100%;
        top: 0;
        width: 85vw;
        /* Ocupa el 85% del ancho de la pantalla */
        max-width: 300px;
        height: 100vh;
        padding: 2.5rem 1.2rem;
        border-radius: 0;
        transition: left 0.4s ease;
        flex-direction: column;
        align-items: stretch;
    }

    .navbar--open {
        left: 0;
    }

    .nav-list {
        flex-direction: column;
        gap: 1.4rem;
        width: 100%;
    }

    .nav-item {
        font-size: 1.2rem;
        padding: 12px;
        text-align: left;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
    }
}
</style>