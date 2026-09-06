<!-- App.vue -->
<script setup>
</script>

<template>
  <RouterView />
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Lato:wght@300;400;700&family=Cinzel:wght@400;600&display=swap');


/* ===== CSS VARIABLES ===== */
:root {
  --verde-bosque: #1a2e1a;
  --verde-medio: #2d4a2d;
  --verde-claro: #4a7c4a;
  --tierra: #8B4513;
  --tierra-claro: #a0852dcc;
  --arena: #ebc07f;
  --arena-claro: #E8C98A;
  --humo: #f5f0e8dc;
  --humo-oscuro: #E8E0D0;
  --blanco: #FAFAF7;
  --texto-oscuro: #1a1a14;
  --texto-medio: #3d3d2e;
  --sombra: rgba(26, 46, 26, 0.3);
  --font-display: 'Cinzel', serif;
  --font-titulo: 'Playfair Display', serif;
  --font-cuerpo: 'Lato', sans-serif;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  scroll-behavior: smooth;
}

body {
  font-family: var(--font-cuerpo);
  background-color: var(--humo-oscuro);
  color: var(--humo-oscuro);
  overflow-x: hidden;
}

/* Textura de fondo sutil para toda la página */
body::before {
  content: '';
  position: fixed;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
  pointer-events: none;
  z-index: 0;
}

.main-content {
  position: relative;
  z-index: 1;
}

section {
  position: relative;
  margin-top: 20px;
  margin-bottom: 20px;
}

h1,
h2,
h3 {
  font-family: var(--font-titulo);
  text-transform: uppercase;
  font-style: italic;
  color: var(--humo);
  text-wrap: balance;
}

p {
  font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
  letter-spacing: 1px;
  line-height: 1.5;
  font-style: italic;
  text-wrap: pretty;
}

/* Scrollbar personalizado */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: var(--verde-bosque);
}

::-webkit-scrollbar-thumb {
  background: var(--arena);
  border-radius: 4px;
}

/* Lightbox global */
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
  animation: fadeIn 0.2s ease;
}

.lightbox .expanded-img {
  max-width: 90vw;
  max-height: 90vh;
  object-fit: contain;
  border-radius: 4px;
  box-shadow: 0 25px 80px rgba(0, 0, 0, 0.8);
  animation: zoomIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

@keyframes zoomIn {
  from {
    transform: scale(0.8);
    opacity: 0;
  }

  to {
    transform: scale(1);
    opacity: 1;
  }
}
</style>