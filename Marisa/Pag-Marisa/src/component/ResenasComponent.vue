<script setup>
import { ref, onMounted } from "vue";
import { getResenas, enviarResena } from "../api.js";

const MAX_CHARS = 140;

const resenas = ref([]);
const cargando = ref(true);
const errorCarga = ref("");
const abiertas = ref({}); // id -> true si está expandida

const mostrarForm = ref(false);
const nombre = ref("");
const puntuacion = ref(0);
const comentario = ref("");
const sitioWeb = ref(""); // honeypot: debe quedar vacío
const enviando = ref(false);
const exito = ref("");
const errorForm = ref("");

function esLargo(texto) {
  return texto.length > MAX_CHARS;
}
function textoVisible(r) {
  if (abiertas.value[r.id] || !esLargo(r.comentario)) return r.comentario;
  return r.comentario.slice(0, MAX_CHARS).trimEnd() + "…";
}
function alternar(id) {
  abiertas.value[id] = !abiertas.value[id];
}

async function cargar() {
  try {
    resenas.value = await getResenas(5);
  } catch (e) {
    errorCarga.value = "No pudimos cargar las reseñas.";
  } finally {
    cargando.value = false;
  }
}

async function enviar() {
  errorForm.value = "";
  exito.value = "";
  if (!nombre.value.trim()) return (errorForm.value = "Escribí tu nombre.");
  if (!puntuacion.value) return (errorForm.value = "Elegí una puntuación.");
  if (comentario.value.trim().length < 10)
    return (errorForm.value =
      "El comentario debe tener al menos 10 caracteres.");

  enviando.value = true;
  try {
    await enviarResena({
      nombre: nombre.value.trim(),
      puntuacion: puntuacion.value,
      comentario: comentario.value.trim(),
      sitio_web: sitioWeb.value,
    });
    exito.value = "¡Gracias! Tu reseña se publicará una vez que sea revisada.";
    nombre.value = "";
    puntuacion.value = 0;
    comentario.value = "";
    mostrarForm.value = false;
 } catch (e) {
  console.error('Error reseñas:', e)
  errorCarga.value = 'No pudimos cargar las reseñas.'
} finally {
    enviando.value = false;
  }
}

onMounted(cargar);
</script>

<template>
  <section id="resenas" class="resenas">
    <h2>Lo que dicen quienes ya recorrieron Malargüe</h2>

    <p v-if="cargando" class="msg">Cargando reseñas…</p>
    <p v-else-if="errorCarga" class="msg">{{ errorCarga }}</p>
    <p v-else-if="!resenas.length" class="msg">
      Todavía no hay reseñas. ¡Sé el primero!
    </p>

    <div v-else class="lista">
      <article v-for="r in resenas" :key="r.id" class="card">
        <div class="estrellas" :aria-label="r.puntuacion + ' de 5'">
          <span v-for="n in 5" :key="n" :class="{ on: n <= r.puntuacion }"
            >★</span
          >
        </div>
        <p class="texto">{{ textoVisible(r) }}</p>
        <button
          v-if="esLargo(r.comentario)"
          class="mas"
          @click="alternar(r.id)"
        >
          {{ abiertas[r.id] ? "Leer menos" : "Leer más" }}
        </button>
        <p class="autor">— {{ r.nombre }}</p>
      </article>
    </div>

    <p v-if="exito" class="ok">{{ exito }}</p>

    <div class="acciones">
      <button v-if="!mostrarForm" class="btn" @click="mostrarForm = true">
        Dejar una reseña
      </button>
    </div>

    <form v-if="mostrarForm" class="form" @submit.prevent="enviar">
      <input
        v-model="nombre"
        type="text"
        placeholder="Tu nombre"
        maxlength="60"
      />

      <div class="selector" role="radiogroup" aria-label="Puntuación">
        <button
          v-for="n in 5"
          :key="n"
          type="button"
          class="star"
          :class="{ on: n <= puntuacion }"
          @click="puntuacion = n"
          :aria-label="n + ' estrellas'"
        >
          ★
        </button>
      </div>

      <textarea
        v-model="comentario"
        rows="4"
        maxlength="1000"
        placeholder="Contanos tu experiencia"
      ></textarea>
      <small class="cont">{{ comentario.length }}/1000</small>

      <!-- honeypot: oculto para personas -->
      <input
        v-model="sitioWeb"
        type="text"
        name="sitio_web"
        class="hp"
        tabindex="-1"
        autocomplete="off"
        aria-hidden="true"
      />

      <p v-if="errorForm" class="err">{{ errorForm }}</p>

      <div class="fila">
        <button class="btn" type="submit" :disabled="enviando">
          {{ enviando ? "Enviando…" : "Enviar reseña" }}
        </button>
        <button class="btn sec" type="button" @click="mostrarForm = false">
          Cancelar
        </button>
      </div>
    </form>

    <router-link to="/galeria" class="galeria-link"
      >👉 ¿Querés subir fotos con Mari? 👈</router-link
    >
  </section>
</template>

<style scoped>
.resenas {
  padding: clamp(2rem, 6vw, 4rem) 1rem;
  max-width: 1100px;
  margin: 0 auto;
  text-align: center;
}
h2 {
  margin-bottom: 1.5rem;
  font-size: 2rem;
  font-family: 'Courier New', Courier, monospace;
}
.msg {
  opacity: 0.8;
}

.lista {
  display: flex;
  flex-wrap: wrap;
  gap: 1rem;
  justify-content: center;
  align-items: flex-start; /* una tarjeta expandida no estira a las demás */
}
.card {
  flex: 1 1 200px;
  max-width: 320px;
  background: #c7d8ce;
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 12px;
  padding: 1rem;
  text-align: left;
  color: #000;
}
.estrellas {
  color: #777;
  letter-spacing: 2px;
  font-size: 1.5rem;
}
.estrellas .on {
  color: #f8fc09;
}
.texto {
  margin: 0.6rem 0;
  line-height: 1.5;
  white-space: pre-line;
  overflow-wrap: anywhere;
}
.mas {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
  color: inherit;
  text-decoration: underline;
  font-size: 0.9rem;
}
.autor {
  margin-top: 0.6rem;
  font-style: italic;
  opacity: 0.85;
}

.acciones {
  margin: 1.5rem 0 0.5rem;
}
.btn {
  background: #4f7a4a;
  color: #fff;
  border: none;
  border-radius: 999px;
  padding: 0.6rem 1.4rem;
  cursor: pointer;
  font-size: 1rem;
}
.btn:disabled {
  opacity: 0.6;
  cursor: default;
}
.btn.sec {
  background: transparent;
  border: 1px solid currentColor;
  color: inherit;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
  max-width: 480px;
  margin: 1rem auto;
  text-align: left;
}
.form input[type="text"],
.form textarea {
  padding: 0.6rem;
  border-radius: 8px;
  border: 1px solid #ccc;
  font: inherit;
}
.selector {
  display: flex;
  gap: 0.2rem;
}
.star {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.8rem;
  color: #888;
  padding: 0;
}
.star.on {
  color: #f5b301;
}
.cont {
  align-self: flex-end;
  opacity: 0.7;
}
.fila {
  display: flex;
  gap: 0.6rem;
}
.hp {
  position: absolute;
  left: -9999px;
  width: 1px;
  height: 1px;
  opacity: 0;
}
.ok {
  color: #8fd18a;
  margin-top: 1rem;
}
.err {
  color: #ff8b8b;
}
.galeria-link {
  display: inline-block;
  margin-top: 1.5rem;
  color:#ebe2b0;
  font-size: 2rem;
  text-decoration:solid;
}
</style>
