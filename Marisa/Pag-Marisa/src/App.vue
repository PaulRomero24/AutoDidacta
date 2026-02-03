<!-- App.vue -->
<script setup>
import NavbarComponent from './component/NavbarComponent.vue';

</script>

<template>
  <!-- Componentes fijos (siempre visibles) -->
  <NavbarComponent />

  <!-- 👉 Aquí se muestra la página actual (HomeView, GaleriaCompleta, etc.) -->
  <router-view />

  <!-- Footer (opcional, si quieres que siempre esté) -->
  <footer class="main-footer">
    <p>TODOS LOS DERECHOS RESERVADOS PIM PUM PAN</p>
  </footer>
</template>

  <style scoped></style>