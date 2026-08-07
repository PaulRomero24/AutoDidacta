<!-- eslint-disable vue/multi-word-component-names -->
<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const navegacion = ref([
    { id: 1, nombre: "Experiencia", enlace: "/#experiencia" },
    { id: 2, nombre: "¿Dónde ir?", enlace: "/#dondeir" },
    { id: 3, nombre: "Mi Malargüe", enlace: "/#mimalargue" },
    { id: 4, nombre: "Enlaces", enlace: "/#links" },
]);

const menuVisible = ref(false);
const contactoVisible = ref(false);
const scrolled = ref(false);
const activeSection = ref('');

const toggleMenu = () => { menuVisible.value = !menuVisible.value; };
const closeMenu = () => { menuVisible.value = false; contactoVisible.value = false; };
const toggleContacto = () => { contactoVisible.value = !contactoVisible.value; };
const closeContacto = () => { contactoVisible.value = false; };

// Opciones del dropdown
const irEstudiantiles = () => {
    router.push('/estudiantiles');
    closeMenu();
};

const irWhatsApp = () => {
    // 1. Registrás el evento en Google Analytics usando window.gtag
    if (window.gtag) {
        window.gtag('event', 'clic_whatsapp', {
            'event_category': 'Contacto',
            'event_label': 'Boton WhatsApp Principal'
        });
    }

    // 2. Abrís WhatsApp normalmente
    const telefono = '5492604604130';
    const mensaje = encodeURIComponent('Hola Marisa! Vengo desde la página para consultar disponibilidad.');

    window.open(`https://wa.me/${telefono}?text=${mensaje}`, '_blank');
    closeMenu();
};
const abrirGaleria = () => {
    // TODO: abrir modal de subida de foto
    // Por ahora emitimos un evento
    closeMenu();
};

const handleScroll = () => {
    scrolled.value = window.scrollY > 60;
    const sections = ['experiencia', 'dondeir', 'mimalargue', 'links'];
    for (const id of [...sections].reverse()) {
        const el = document.getElementById(id);
        if (el && window.scrollY >= el.offsetTop - 120) {
            activeSection.value = `#${id}`;
            break;
        }
    }
};

// Cerrar dropdown al hacer click fuera
const handleClickOutside = (e) => {
    if (!e.target.closest('.contacto-wrapper')) {
        contactoVisible.value = false;
    }
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('click', handleClickOutside);
});
onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <!-- Botón hamburguesa -->
    <button class="menu-toggle" :class="{ active: menuVisible }" @click="toggleMenu" aria-label="Abrir menú">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- Navbar -->
    <nav class="navbar" :class="{ scrolled, open: menuVisible }">
        <div class="navbar-inner">
            <a href="#experiencia" class="navbar-logo" @click="closeMenu">
                <span class="logo-icon">✦</span>
                <span class="logo-text">Marisa Berdu</span>
            </a>

            <ul class="nav-list">
                <li v-for="nav in navegacion" :key="nav.id">
                    <a :href="nav.enlace" class="nav-item" :class="{ active: activeSection === nav.enlace }"
                        @click="closeMenu">
                        {{ nav.nombre }}
                    </a>
                </li>

                <!-- Botón Contacto con dropdown -->
                <li class="contacto-wrapper">
                    <button class="nav-cta" @click.stop="toggleContacto">
                        Contacto
                        <span class="cta-arrow" :class="{ rotado: contactoVisible }">▾</span>
                    </button>

                    <!-- Dropdown -->
                    <div class="contacto-dropdown" :class="{ visible: contactoVisible }">
                        <button class="dropdown-item" @click="irEstudiantiles">
                            <span class="item-icono">🎒</span>
                            <div class="item-texto">
                                <span class="item-titulo">Servicios</span>
                                <span class="item-desc">Excursiones para grupos cerrados</span>
                            </div>
                        </button>

                        <button class="dropdown-item" @click="irWhatsApp">
                            <span class="item-icono">💬</span>
                            <div class="item-texto">
                                <span class="item-titulo">Disponibilidad</span>
                                <span class="item-desc">Consultá fechas y precios</span>
                            </div>
                        </button>

                        <button class="dropdown-item" @click="abrirGaleria">
                            <span class="item-icono">📸</span>
                            <div class="item-texto">
                                <span class="item-titulo">Participar en la galería</span>
                                <span class="item-desc">Mandá tu foto de Malargüe</span>
                            </div>
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Overlay móvil -->
    <div v-if="menuVisible" class="overlay" @click="closeMenu"></div>
</template>

<style scoped>
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 900;
    transition: all 0.4s ease;
    padding: 0;
}

.navbar-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 1.2rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: padding 0.4s ease;
}

.navbar.scrolled .navbar-inner {
    padding: 0.8rem 2rem;
}

.navbar.scrolled {
    background: rgba(13, 26, 13, 0.95);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(212, 169, 106, 0.15);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
}

.navbar-logo {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: #d88c19;
    font-family: var(--font-display);
    font-size: 1.1rem;
    letter-spacing: 0.08em;
    transition: opacity 0.3s;
}

.navbar-logo:hover {
    opacity: 0.8;
}

.logo-icon {
    font-size: 0.9rem;
    color: var(--verde-claro);
}

.nav-list {
    display: flex;
    list-style: none;
    align-items: center;
    gap: 0.5rem;
}

.nav-item {
    text-decoration: none;
    font-family: var(--font-cuerpo);
    font-size: 0.82rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--humo-oscuro);
    padding: 8px 14px;
    border-radius: 4px;
    transition: all 0.3s ease;
    position: relative;
}

.nav-item::after {
    content: '';
    position: absolute;
    bottom: 4px;
    left: 14px;
    right: 14px;
    height: 1px;
    background: var(--arena);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.nav-item:hover,
.nav-item.active {
    color: var(--arena);
}

.nav-item:hover::after,
.nav-item.active::after {
    transform: scaleX(1);
}

/* ===== CONTACTO WRAPPER ===== */
.contacto-wrapper {
    position: relative;
}

.nav-cta {
    display: flex;
    align-items: center;
    gap: 6px;
    font-family: var(--font-cuerpo);
    font-size: 0.78rem;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: var(--verde-bosque);
    background: linear-gradient(135deg, var(--arena) 0%, var(--arena-claro) 100%);
    padding: 8px 20px;
    border-radius: 30px;
    margin-left: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 12px rgba(212, 169, 106, 0.3);
}

.nav-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 169, 106, 0.4);
}

.cta-arrow {
    font-size: 0.7rem;
    transition: transform 0.3s ease;
    display: inline-block;
}

.cta-arrow.rotado {
    transform: rotate(180deg);
}

/* ===== DROPDOWN ===== */
.contacto-dropdown {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    width: 260px;
    background: rgba(13, 26, 13, 0.97);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(212, 169, 106, 0.2);
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    overflow: hidden;
    opacity: 0;
    transform: translateY(-8px);
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    z-index: 1000;
}

.contacto-dropdown.visible {
    opacity: 1;
    transform: translateY(0);
    pointer-events: all;
}

.dropdown-item {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    background: none;
    border: none;
    border-bottom: 1px solid rgba(212, 169, 106, 0.08);
    cursor: pointer;
    text-align: left;
    transition: background 0.2s ease;
}

.dropdown-item:last-child {
    border-bottom: none;
}

.dropdown-item:hover {
    background: rgba(212, 169, 106, 0.08);
}

.item-icono {
    font-size: 1.4rem;
    flex-shrink: 0;
}

.item-texto {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.item-titulo {
    font-family: var(--font-cuerpo);
    font-size: 0.85rem;
    color: var(--arena);
    letter-spacing: 0.05em;
}

.item-desc {
    font-family: var(--font-cuerpo);
    font-size: 0.72rem;
    color: rgba(245, 240, 232, 0.45);
}

/* ===== HAMBURGUESA ===== */
.menu-toggle {
    display: none;
    position: fixed;
    top: 16px;
    right: 20px;
    z-index: 1000;
    background: rgba(13, 26, 13, 0.9);
    border: 1px solid rgba(212, 169, 106, 0.3);
    border-radius: 8px;
    padding: 10px;
    cursor: pointer;
    flex-direction: column;
    gap: 5px;
    backdrop-filter: blur(8px);
}

.menu-toggle span {
    display: block;
    width: 22px;
    height: 2px;
    background: var(--arena);
    transition: all 0.3s ease;
    transform-origin: center;
}

.menu-toggle.active span:nth-child(1) {
    transform: translateY(7px) rotate(45deg);
}

.menu-toggle.active span:nth-child(2) {
    opacity: 0;
}

.menu-toggle.active span:nth-child(3) {
    transform: translateY(-7px) rotate(-45deg);
}

.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    z-index: 850;
    backdrop-filter: blur(2px);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .menu-toggle {
        display: flex;
    }

    .navbar {
        background: rgba(13, 26, 13, 0.97);
        border-bottom: 1px solid rgba(212, 169, 106, 0.15);
    }

    .navbar-inner {
        padding: 1rem 1.5rem;
    }

    .nav-list {
        position: fixed;
        top: 0;
        right: -100%;
        width: 280px;
        height: 100vh;
        background: linear-gradient(160deg, #0d1a0d 0%, #1a2e1a 100%);
        flex-direction: column;
        justify-content: center;
        gap: 1rem;
        padding: 3rem 2rem;
        transition: right 0.4s cubic-bezier(0.645, 0.045, 0.355, 1.000);
        z-index: 900;
        border-left: 1px solid rgba(212, 169, 106, 0.15);
        box-shadow: -10px 0 40px rgba(0, 0, 0, 0.4);
        overflow-y: auto;
    }

    .navbar.open .nav-list {
        right: 0;
    }

    .nav-item {
        font-size: 1rem;
        padding: 12px 0;
    }

    .nav-cta {
        margin-left: 0;
        width: 100%;
        justify-content: center;
        padding: 12px 20px;
    }

    /* Dropdown en móvil va hacia abajo sin absolute */
    .contacto-dropdown {
        position: static;
        width: 100%;
        transform: none;
        border-radius: 8px;
        margin-top: 8px;
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .contacto-dropdown.visible {
        opacity: 1;
        max-height: 300px;
        pointer-events: all;
        transform: none;
    }
}
</style>