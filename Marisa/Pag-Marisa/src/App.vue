<!-- App.vue -->
<script setup>
import NavbarComponent from './component/NavbarComponent.vue';

</script>

<template>
  <!-- Componentes fijos (siempre visibles) -->
  <NavbarComponent />

  <!-- 👉 Aquí se muestra la página actual (HomeView, GaleriaCompleta, etc.) -->
  <router-view />

</template>

  <style scoped></style>