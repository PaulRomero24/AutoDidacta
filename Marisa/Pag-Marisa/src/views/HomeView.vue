<!-- App.vue -->
<script setup>
import { ref } from 'vue';
import DondeIrComponent from '../component/DondeIrComponent.vue';
import ExperienciaComponent from '../component/ExperienciaComponent.vue';
import FlipCard from '../component/FlipCard.vue';
import MiMalargueComponent from '../component/MiMalargueComponent.vue';
import NavbarComponent from '../component/NavbarComponent.vue';
import EnlacesInteres from '@/component/EnlacesInteres.vue';
import FooterComponent from '@/component/footerComponent.vue';

</script>

<<template>
    <!-- Carta introductoria (solo si showIntro es true) -->
    <FlipCard />

    <!-- Portfolio normal (solo si la carta está cerrada) -->
    <div id="app" v-show="!showIntro">
        <NavbarComponent />
        <section id="dondeir">
            <DondeIrComponent />
        </section>
        <section id="mimalargue">
            <MiMalargueComponent />
        </section>
        <section id="experiencia">
            <ExperienciaComponent />
        </section>
        <section id="links">
            <EnlacesInteres />
        </section>
        <footer>
        <FooterComponent />
        </footer>
    </div>
</template>

    <style scoped></style>