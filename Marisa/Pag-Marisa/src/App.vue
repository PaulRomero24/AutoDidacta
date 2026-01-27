<!-- App.vue -->
<script setup>
import { ref } from 'vue';
import AboutMe from '../component/AboutMe.vue';
import DondeIrComponent from '../component/DondeIrComponent.vue';
import ExperienciaComponent from '../component/ExperienciaComponent.vue';
import FlipCard from '../component/FlipCard.vue';
import MiMalargueComponent from '../component/MiMalargueComponent.vue';
import NavbarComponent from '../component/NavbarComponent.vue';

</script>

<<template>
  <!-- Carta introductoria (solo si showIntro es true) -->
  <FlipCard />

  <!-- Portfolio normal (solo si la carta está cerrada) -->
  <div id="app" v-show="!showIntro">
    <NavbarComponent />
    <section id="experiencia">
      <ExperienciaComponent />
    </section>
    <section id="dondeir">
      <DondeIrComponent />
    </section>
    <section id="mimalargue">
      <MiMalargueComponent />
    </section>
    <footer>
      <p>TODOS LOS DERECHOS RESERVADOS PIM PUM PAN</p>
    </footer>
  </div>
</template>

<style scoped>
  body{
    display: contents;
  }
</style>