<script setup>
import AboutMe from '../component/AboutMe.vue';
import DondeIrComponent from '../component/DondeIrComponent.vue';
import ExperienciaComponent from '../component/ExperienciaComponent.vue';
import MiMalargueComponent from '../component/MiMalargueComponent.vue';
import NavbarComponent from '../component/NavbarComponent.vue';

</script>

<template>
  <body>
      <header>
      </header>
      <main>
        <NavbarComponent />
        <!--ACERCA DE MI-->
        <section>
          <AboutMe />
        </section>
        <!--ESTUDIOS-->
        <section>
          <ExperienciaComponent />
        </section>
        <!--DONDE IR-->
        <section>
          <DondeIrComponent />
        </section>
        <!--GALERIA-->
        <section>
          <MiMalargueComponent />
        </section>

        <footer>

        </footer>
      </main>
  </body>

  <footer>
    <p>TODOS LOS DERECHOS RESERVADOS PIM PUM PAN</p>
  </footer>
</template>

<style scoped></style>
