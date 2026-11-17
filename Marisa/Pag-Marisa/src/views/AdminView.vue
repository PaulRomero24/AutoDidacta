<script setup>
import { ref, onMounted, computed } from "vue";
import {
  loginAdmin,
  getFotosPendientes,
  getFotosAprobadasAdmin,
  aprobarFoto,
  rechazarFoto,
  eliminarFoto,
  getResenasAdmin,
  moderarResena,
  eliminarResena,
  getNoticiasAdmin,
  crearNoticia,
  editarNoticia,
  cambiarVisibilidadNoticia,
  eliminarNoticia,
} from "../api.js";
const token = ref(sessionStorage.getItem("admin_token") || "");
const loginError = ref("");
const cargandoLogin = ref(false);
const formLogin = ref({ username: "", password: "" });
const fotosPendientes = ref([]);
const cargandoFotos = ref(false);
const mensajeAccion = ref("");

const estaLogueado = () => !!token.value;

const login = async () => {
  cargandoLogin.value = true;
  loginError.value = "";
  try {
    const data = await loginAdmin(
      formLogin.value.username,
      formLogin.value.password,
    );
    token.value = data.access_token;
    sessionStorage.setItem("admin_token", data.access_token);
    await cargarPendientes();
  } catch (error) {
    loginError.value = "Usuario o contraseña incorrectos";
  } finally {
    cargandoLogin.value = false;
  }
};

const logout = () => {
  token.value = "";
  sessionStorage.removeItem("admin_token");
  fotosPendientes.value = [];
  resenasPendientes.value = [];
  resenasAprobadas.value = [];
    noticias.value = [];
};

const cargarPendientes = async () => {
  cargandoFotos.value = true;
  try {
    fotosPendientes.value = await getFotosPendientes(token.value);
  } catch (error) {
    manejarError(error);
  } finally {
    cargandoFotos.value = false;
  }
};

const aprobar = async (id) => {
  try {
    await aprobarFoto(id, token.value);
    fotosPendientes.value = fotosPendientes.value.filter((f) => f.id !== id);
    mostrarMensaje("✓ Foto aprobada — aparecerá en la galería");
  } catch (error) {
    manejarError(error);
  }
};

const rechazar = async (id) => {
  try {
    await rechazarFoto(id, token.value);
    fotosPendientes.value = fotosPendientes.value.filter((f) => f.id !== id);
    mostrarMensaje("✕ Foto rechazada");
  } catch (error) {
    manejarError(error);
  }
};
// Nuevo estado
const fotosAprobadas = ref([]);
const seccionActiva = ref("pendientes"); // 'pendientes' o 'aprobadas'

// Nueva función
const cargarAprobadas = async () => {
  try {
    fotosAprobadas.value = await getFotosAprobadasAdmin(token.value);
  } catch (error) {
    manejarError(error);
  }
};

const eliminar = async (id) => {
  if (!confirm("¿Seguro que querés eliminar esta foto de la galería?")) return;
  try {
    await eliminarFoto(id, token.value);
    fotosAprobadas.value = fotosAprobadas.value.filter((f) => f.id !== id);
    mostrarMensaje("🗑 Foto eliminada de la galería");
  } catch (error) {
    manejarError(error);
  }
};

const mostrarMensaje = (msg) => {
  mensajeAccion.value = msg;
  setTimeout(() => {
    mensajeAccion.value = "";
  }, 3000);
};

const formatearFecha = (fecha) => {
  return new Date(fecha).toLocaleDateString("es-AR", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

// ===== RESEÑAS =====
const resenasPendientes = ref([]);
const resenasAprobadas = ref([]);

const cargarResenas = async () => {
  try {
    resenasPendientes.value = await getResenasAdmin("pendiente", token.value);
    resenasAprobadas.value = await getResenasAdmin("aprobada", token.value);
  } catch (error) {
    manejarError(error);
  }
};

const moderarRes = async (id, accion) => {
  try {
    await moderarResena(id, accion, token.value);
    await cargarResenas();
    mostrarMensaje(
      accion === "aprobar" ? "✓ Reseña aprobada" : "✕ Reseña rechazada",
    );
  } catch (error) {
    manejarError(error);
  }
};

const eliminarRes = async (id) => {
  if (!confirm("¿Seguro que querés eliminar esta reseña?")) return;
  try {
    await eliminarResena(id, token.value);
    resenasAprobadas.value = resenasAprobadas.value.filter((r) => r.id !== id);
    mostrarMensaje("🗑 Reseña eliminada");
  } catch (error) {
    manejarError(error);
  }
};


// ===== NOTICIAS =====
const MAX_IMG = 5 * 1024 * 1024;
const TIPOS_IMG = ["image/jpeg", "image/png", "image/webp"];

const noticias = ref([]);
const editandoId = ref(null); // null = creando una nueva
const guardandoNoticia = ref(false);
const errorNoticia = ref("");
const claves = ref({ 1: 0, 2: 0 }); // para reiniciar los <input type="file">

const formVacio = () => ({
  titulo: "",
  texto: "",
  archivo1: null,
  archivo2: null,
  preview1: "",
  preview2: "",
  actual1: "",
  actual2: "",
  quitar1: false,
  quitar2: false,
});
const formNoticia = ref(formVacio());

// Las 3 noticias activas más recientes son las que ve el público en el modal
const noticiasEnModal = computed(() =>
  noticias.value
    .filter((n) => n.activa === 1)
    .slice(0, 3)
    .map((n) => n.id),
);

const cargarNoticias = async () => {
  try {
    noticias.value = await getNoticiasAdmin(token.value);
  } catch (error) {
    manejarError(error);
  }
};

const liberarPreviews = () => {
  [1, 2].forEach((n) => {
    const p = formNoticia.value["preview" + n];
    if (p) URL.revokeObjectURL(p);
  });
};

const limpiarFormNoticia = () => {
  liberarPreviews();
  formNoticia.value = formVacio();
  editandoId.value = null;
  errorNoticia.value = "";
  claves.value[1]++;
  claves.value[2]++;
};

// Lo que se muestra en cada casillero: imagen nueva elegida, o la actual si no se quitó
const imagenMostrada = (n) =>
  formNoticia.value["preview" + n] ||
  (formNoticia.value["quitar" + n] ? "" : formNoticia.value["actual" + n]);

const elegirImagen = (e, n) => {
  const file = e.target.files[0];
  if (!file) return;
  if (!TIPOS_IMG.includes(file.type)) {
    errorNoticia.value = "Solo se permiten imágenes JPG, PNG o WEBP.";
    claves.value[n]++;
    return;
  }
  if (file.size > MAX_IMG) {
    errorNoticia.value = "La imagen supera los 5 MB.";
    claves.value[n]++;
    return;
  }
  errorNoticia.value = "";
  const anterior = formNoticia.value["preview" + n];
  if (anterior) URL.revokeObjectURL(anterior);
  formNoticia.value["archivo" + n] = file;
  formNoticia.value["preview" + n] = URL.createObjectURL(file);
  formNoticia.value["quitar" + n] = false;
};

const quitarImagen = (n) => {
  if (formNoticia.value["preview" + n]) {
    // descarta la imagen nueva que se había elegido
    URL.revokeObjectURL(formNoticia.value["preview" + n]);
    formNoticia.value["preview" + n] = "";
    formNoticia.value["archivo" + n] = null;
    claves.value[n]++;
  } else {
    // marca la imagen existente para borrarla al guardar
    formNoticia.value["quitar" + n] = true;
  }
};

const editarNoticiaForm = (n) => {
  limpiarFormNoticia();
  editandoId.value = n.id;
  formNoticia.value.titulo = n.titulo;
  formNoticia.value.texto = n.texto;
  formNoticia.value.actual1 = n.imagen1 || "";
  formNoticia.value.actual2 = n.imagen2 || "";
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const guardarNoticia = async () => {
  errorNoticia.value = "";
  const f = formNoticia.value;
  if (!f.titulo.trim() || !f.texto.trim()) {
    errorNoticia.value = "El título y el texto son obligatorios.";
    return;
  }

  const fd = new FormData();
  fd.append("titulo", f.titulo.trim());
  fd.append("texto", f.texto.trim());
  [1, 2].forEach((n) => {
    if (f["archivo" + n]) fd.append("imagen" + n, f["archivo" + n]);
    else if (editandoId.value && f["quitar" + n]) fd.append("quitar_imagen" + n, "1");
  });

  guardandoNoticia.value = true;
  try {
    if (editandoId.value) {
      await editarNoticia(editandoId.value, fd, token.value);
      mostrarMensaje("✓ Noticia actualizada");
    } else {
      await crearNoticia(fd, token.value);
      mostrarMensaje("✓ Noticia publicada");
    }
    limpiarFormNoticia();
    await cargarNoticias();
  } catch (error) {
    errorNoticia.value = error.message || "No se pudo guardar la noticia.";
    manejarError(error);
  } finally {
    guardandoNoticia.value = false;
  }
};

const alternarVisibilidad = async (n) => {
  try {
    await cambiarVisibilidadNoticia(n.id, n.activa ? "ocultar" : "mostrar", token.value);
    await cargarNoticias();
    mostrarMensaje(n.activa ? "Noticia oculta" : "✓ Noticia visible");
  } catch (error) {
    manejarError(error);
  }
};

const borrarNoticia = async (n) => {
  if (!confirm(`¿Borrar la noticia "${n.titulo}"? También se borran sus imágenes.`)) return;
  try {
    await eliminarNoticia(n.id, token.value);
    noticias.value = noticias.value.filter((x) => x.id !== n.id);
    if (editandoId.value === n.id) limpiarFormNoticia();
    mostrarMensaje("🗑 Noticia eliminada");
  } catch (error) {
    manejarError(error);
  }
};
// Si el token venció (401), cerramos sesión para volver a loguear
const manejarError = (error) => {
  console.error(error);
  if (error.status === 401) logout();
};

onMounted(() => {
  if (estaLogueado()) cargarPendientes();
});
</script>

<template>
  <div class="admin-page">
    <!-- Login -->
    <div v-if="!estaLogueado()" class="login-wrapper">
      <div class="login-card">
        <div class="login-logo">✦</div>
        <h2>Panel de administración</h2>
        <p>Marisa Berdu — Galería</p>

        <div class="form-grupo">
          <label>Usuario</label>
          <input
            v-model="formLogin.username"
            type="text"
            placeholder="Usuario"
            @keydown.enter="login"
          />
        </div>

        <div class="form-grupo">
          <label>Contraseña</label>
          <input
            v-model="formLogin.password"
            type="password"
            placeholder="Contraseña"
            @keydown.enter="login"
          />
        </div>

        <p v-if="loginError" class="error-msg">{{ loginError }}</p>

        <button class="btn-login" @click="login" :disabled="cargandoLogin">
          {{ cargandoLogin ? "Ingresando..." : "Ingresar" }}
        </button>
      </div>
    </div>

    <!-- Panel admin -->
    <div v-else class="panel">
      <nav class="admin-nav">
        <span class="admin-nav-titulo">✦ Panel Admin — Marisa Berdu</span>
        <button class="btn-logout" @click="logout">Cerrar sesión</button>
      </nav>

      <div v-if="mensajeAccion" class="mensaje-accion">{{ mensajeAccion }}</div>

      <div class="panel-contenido">
        <!-- Tabs -->
        <div class="tabs">
          <button
            class="tab"
            :class="{ activo: seccionActiva === 'pendientes' }"
            @click="
              seccionActiva = 'pendientes';
              cargarPendientes();
            "
          >
            Pendientes
            <span class="tab-badge" v-if="fotosPendientes.length > 0">
              {{ fotosPendientes.length }}
            </span>
          </button>
          <button
            class="tab"
            :class="{ activo: seccionActiva === 'aprobadas' }"
            @click="
              seccionActiva = 'aprobadas';
              cargarAprobadas();
            "
          >
            Aprobadas
          </button>

          <button
            class="tab"
            :class="{ activo: seccionActiva === 'resenas' }"
            @click="
              seccionActiva = 'resenas';
              cargarResenas();
            "
          >
            Reseñas
            <span class="tab-badge" v-if="resenasPendientes.length > 0">
              {{ resenasPendientes.length }}
            </span>
          </button>
                    <button
            class="tab"
            :class="{ activo: seccionActiva === 'noticias' }"
            @click="
              seccionActiva = 'noticias';
              cargarNoticias();
            "
          >
            Noticias
          </button>
        </div>

        <!-- Pendientes -->
        <div v-if="seccionActiva === 'pendientes'">
          <div v-if="cargandoFotos" class="estado-msg">Cargando...</div>
          <div v-else-if="fotosPendientes.length === 0" class="estado-msg">
            ✓ No hay fotos pendientes
          </div>
          <div v-else class="fotos-grid">
            <div
              v-for="foto in fotosPendientes"
              :key="foto.id"
              class="foto-card"
            >
              <div class="foto-img-wrapper">
                <img
                  :src="foto.url_foto"
                  :alt="`Foto de ${foto.nombre_autor}`"
                />
              </div>
              <div class="foto-info">
                <p class="foto-autor">📷 {{ foto.nombre_autor }}</p>
                <p class="foto-destino">📍 {{ foto.destino }}</p>
                <p class="foto-fecha">🕐 {{ formatearFecha(foto.fecha) }}</p>
              </div>
              <div class="foto-acciones">
                <button class="btn-aprobar" @click="aprobar(foto.id)">
                  ✓ Aprobar
                </button>
                <button class="btn-rechazar" @click="rechazar(foto.id)">
                  ✕ Rechazar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Aprobadas -->
        <div v-if="seccionActiva === 'aprobadas'">
          <div v-if="fotosAprobadas.length === 0" class="estado-msg">
            No hay fotos aprobadas
          </div>
          <div v-else class="fotos-grid">
            <div
              v-for="foto in fotosAprobadas"
              :key="foto.id"
              class="foto-card"
            >
              <div class="foto-img-wrapper">
                <img
                  :src="foto.url_foto"
                  :alt="`Foto de ${foto.nombre_autor}`"
                />
              </div>
              <div class="foto-info">
                <p class="foto-autor">📷 {{ foto.nombre_autor }}</p>
                <p class="foto-destino">📍 {{ foto.destino }}</p>
                <p class="foto-fecha">🕐 {{ formatearFecha(foto.fecha) }}</p>
              </div>
              <div class="foto-acciones" style="grid-template-columns: 1fr">
                <button class="btn-rechazar" @click="eliminar(foto.id)">
                  🗑 Eliminar
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Reseñas -->
        <div v-if="seccionActiva === 'resenas'">
          <h3 class="sub-titulo">Pendientes</h3>
          <div v-if="resenasPendientes.length === 0" class="estado-msg">
            ✓ No hay reseñas pendientes
          </div>
          <div v-else class="fotos-grid">
            <div v-for="r in resenasPendientes" :key="r.id" class="foto-card">
              <div class="foto-info">
                <p class="foto-autor">
                  {{ r.nombre }} — {{ "★".repeat(r.puntuacion) }}
                </p>
                <p class="res-texto">{{ r.comentario }}</p>
                <p class="foto-fecha">🕐 {{ formatearFecha(r.fecha) }}</p>
              </div>
              <div class="foto-acciones">
                <button
                  class="btn-aprobar"
                  @click="moderarRes(r.id, 'aprobar')"
                >
                  ✓ Aprobar
                </button>
                <button
                  class="btn-rechazar"
                  @click="moderarRes(r.id, 'rechazar')"
                >
                  ✕ Rechazar
                </button>
              </div>
            </div>
          </div>

          <h3 class="sub-titulo">Aprobadas</h3>
          <div v-if="resenasAprobadas.length === 0" class="estado-msg">
            No hay reseñas aprobadas
          </div>
          <div v-else class="fotos-grid">
            <div v-for="r in resenasAprobadas" :key="r.id" class="foto-card">
              <div class="foto-info">
                <p class="foto-autor">
                  {{ r.nombre }} — {{ "★".repeat(r.puntuacion) }}
                </p>
                <p class="res-texto">{{ r.comentario }}</p>
                <p class="foto-fecha">🕐 {{ formatearFecha(r.fecha) }}</p>
              </div>
              <div class="foto-acciones" style="grid-template-columns: 1fr">
                <button class="btn-rechazar" @click="eliminarRes(r.id)">
                  🗑 Eliminar
                </button>
              </div>
            </div>
          </div>
        </div>
                <!-- Noticias -->
        <div v-if="seccionActiva === 'noticias'">
          <form class="noticia-form" @submit.prevent="guardarNoticia">
            <h3 class="sub-titulo" style="margin-top: 0">
              {{ editandoId ? "Editar noticia" : "Nueva noticia" }}
            </h3>

            <label class="nf-campo">
              <span>Título</span>
              <input v-model="formNoticia.titulo" type="text" maxlength="120" />
            </label>

            <label class="nf-campo">
              <span>Texto <small>{{ formNoticia.texto.length }}/2000</small></span>
              <textarea v-model="formNoticia.texto" rows="5" maxlength="2000"></textarea>
            </label>

            <div class="nf-imagenes">
              <div v-for="n in [1, 2]" :key="n" class="nf-imagen">
                <span>Imagen {{ n }} (opcional)</span>
                <img v-if="imagenMostrada(n)" :src="imagenMostrada(n)" alt="" />
                <input
                  :key="claves[n]"
                  type="file"
                  accept="image/jpeg,image/png,image/webp"
                  @change="elegirImagen($event, n)"
                />
                <button
                  v-if="imagenMostrada(n)"
                  type="button"
                  class="nf-link"
                  @click="quitarImagen(n)"
                >
                  Quitar imagen
                </button>
              </div>
            </div>

            <p v-if="errorNoticia" class="error-msg">{{ errorNoticia }}</p>

            <div class="nf-acciones">
              <button class="btn-guardar" type="submit" :disabled="guardandoNoticia">
                {{ guardandoNoticia ? "Guardando..." : editandoId ? "Guardar cambios" : "Publicar noticia" }}
              </button>
              <button v-if="editandoId" type="button" class="btn-cancelar" @click="limpiarFormNoticia">
                Cancelar edición
              </button>
            </div>
          </form>

          <h3 class="sub-titulo">Noticias cargadas</h3>
          <p class="nota">En la web se muestran, en el modal, las 3 noticias activas más recientes.</p>

          <div v-if="noticias.length === 0" class="estado-msg">No hay noticias cargadas</div>
          <div v-else class="fotos-grid">
            <div v-for="n in noticias" :key="n.id" class="foto-card" :class="{ oculta: !n.activa }">
              <div v-if="n.imagen1 || n.imagen2" class="not-imgs">
                <img v-if="n.imagen1" :src="n.imagen1" alt="" />
                <img v-if="n.imagen2" :src="n.imagen2" alt="" />
              </div>
              <div class="foto-info">
                <p class="foto-autor">{{ n.titulo }}</p>
                <p class="res-texto">{{ n.texto }}</p>
                <p class="foto-fecha">🕐 {{ formatearFecha(n.fecha) }}</p>
                <p>
                  <span v-if="!n.activa" class="chip chip-gris">Oculta</span>
                  <span v-else-if="noticiasEnModal.includes(n.id)" class="chip chip-verde">En el modal</span>
                  <span v-else class="chip chip-gris">Activa, fuera del modal</span>
                </p>
              </div>
              <div class="acciones-3">
                <button class="btn-aprobar" @click="editarNoticiaForm(n)">✎ Editar</button>
                <button class="btn-neutro" @click="alternarVisibilidad(n)">
                  {{ n.activa ? "Ocultar" : "Mostrar" }}
                </button>
                <button class="btn-rechazar" @click="borrarNoticia(n)">🗑 Borrar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.admin-page {
  min-height: 100vh;
  background: var(--humo);
  font-family: var(--font-cuerpo);
}

.login-wrapper {
  min-height: 100vh;
  background: linear-gradient(160deg, #0d1a0d 0%, #1a2e1a 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.login-card {
  background: rgba(255, 255, 255, 0.04);
  border: 1px solid rgba(212, 169, 106, 0.2);
  border-radius: 16px;
  padding: 2.5rem;
  width: 100%;
  max-width: 380px;
  text-align: center;
}

.login-logo {
  font-size: 2rem;
  color: var(--arena);
  margin-bottom: 1rem;
}

.login-card h2 {
  font-family: var(--font-titulo);
  color: var(--arena-claro);
  font-size: 1.4rem;
  margin-bottom: 0.25rem;
}

.login-card > p {
  color: rgba(245, 240, 232, 0.4);
  font-size: 0.82rem;
  margin-bottom: 2rem;
  font-style: italic;
}

.form-grupo {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 1rem;
  text-align: left;
}

.form-grupo label {
  font-size: 0.72rem;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: rgba(245, 240, 232, 0.4);
}

.form-grupo input {
  background: rgba(255, 255, 255, 0.06);
  border: 1px solid rgba(212, 169, 106, 0.2);
  border-radius: 8px;
  padding: 10px 14px;
  color: var(--humo);
  font-family: var(--font-cuerpo);
  font-size: 0.9rem;
  outline: none;
  transition: border-color 0.3s;
}

.form-grupo input:focus {
  border-color: rgba(212, 169, 106, 0.5);
}

.error-msg {
  color: #e74c3c;
  font-size: 0.82rem;
  margin-bottom: 1rem;
  font-style: italic;
}

.btn-login {
  width: 100%;
  background: linear-gradient(135deg, var(--tierra), var(--tierra-claro));
  color: var(--humo);
  border: none;
  padding: 12px;
  border-radius: 8px;
  font-family: var(--font-cuerpo);
  font-size: 0.88rem;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  cursor: pointer;
  margin-top: 0.5rem;
  transition: all 0.3s ease;
}

.btn-login:hover:not(:disabled) {
  opacity: 0.9;
  transform: translateY(-1px);
}

.btn-login:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.admin-nav {
  background: var(--verde-bosque);
  padding: 1rem 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid rgba(212, 169, 106, 0.15);
  position: sticky;
  top: 0;
  z-index: 100;
}

.admin-nav-titulo {
  font-family: var(--font-titulo);
  color: var(--arena);
  font-size: 1rem;
  font-style: italic;
}

.btn-logout {
  background: none;
  border: 1px solid rgba(212, 169, 106, 0.3);
  color: var(--arena);
  padding: 6px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 0.78rem;
  transition: all 0.3s ease;
}

.btn-logout:hover {
  background: rgba(212, 169, 106, 0.1);
}

.mensaje-accion {
  background: var(--verde-medio);
  color: var(--humo);
  text-align: center;
  padding: 0.75rem;
  font-size: 0.88rem;
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.panel-contenido {
  max-width: 1100px;
  margin: 0 auto;
  padding: 2rem 1.5rem;
}

.tabs {
  display: flex;
  gap: 0;
  margin-bottom: 2rem;
  border-bottom: 2px solid var(--humo-oscuro);
}

.tab {
  background: none;
  border: none;
  padding: 10px 24px;
  cursor: pointer;
  font-family: var(--font-cuerpo);
  font-size: 0.88rem;
  color: var(--texto-medio);
  border-bottom: 2px solid transparent;
  margin-bottom: -2px;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.tab.activo {
  color: var(--verde-bosque);
  border-bottom-color: var(--verde-bosque);
  font-weight: 600;
}

.tab-badge {
  background: var(--tierra);
  color: white;
  font-size: 0.7rem;
  padding: 2px 7px;
  border-radius: 10px;
}

.btn-refrescar {
  background: none;
  border: 1px solid var(--verde-claro);
  color: var(--verde-claro);
  padding: 6px 16px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 0.82rem;
  transition: all 0.3s ease;
}

.btn-refrescar:hover {
  background: var(--verde-claro);
  color: white;
}

.estado-msg {
  text-align: center;
  color: var(--texto-medio);
  font-size: 1rem;
  padding: 4rem;
  font-style: italic;
}

.fotos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.foto-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(26, 46, 26, 0.08);
  transition: transform 0.3s ease;
}

.foto-card:hover {
  transform: translateY(-2px);
}

.foto-img-wrapper {
  width: 100%;
  aspect-ratio: 4/3;
  overflow: hidden;
  background: var(--humo-oscuro);
}

.foto-img-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.foto-info {
  padding: 1rem;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.foto-autor {
  font-size: 0.88rem;
  color: var(--texto-oscuro);
  font-weight: 600;
}

.foto-destino {
  font-size: 0.82rem;
  color: var(--verde-claro);
}

.foto-fecha {
  font-size: 0.75rem;
  color: rgba(61, 61, 46, 0.5);
}

.foto-acciones {
  display: grid;
  grid-template-columns: 1fr 1fr;
  border-top: 1px solid var(--humo-oscuro);
}

.btn-aprobar,
.btn-rechazar {
  border: none;
  padding: 10px;
  cursor: pointer;
  font-family: var(--font-cuerpo);
  font-size: 0.82rem;
  transition: all 0.2s ease;
}

.btn-aprobar {
  background: rgba(74, 124, 74, 0.1);
  color: var(--verde-claro);
  border-right: 1px solid var(--humo-oscuro);
}

.btn-aprobar:hover {
  background: var(--verde-claro);
  color: white;
}

.btn-rechazar {
  background: rgba(231, 76, 60, 0.05);
  color: #e74c3c;
}

.btn-rechazar:hover {
  background: #e74c3c;
  color: white;
}

.noticia-form {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 4px 20px rgba(26, 46, 26, 0.08);
  margin-bottom: 1rem;
}
.nf-campo {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 1rem;
}
.nf-campo span,
.nf-imagen > span {
  font-size: 0.75rem;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--texto-medio);
}
.nf-campo input,
.nf-campo textarea {
  border: 1px solid var(--humo-oscuro);
  border-radius: 8px;
  padding: 10px 14px;
  font-family: var(--font-cuerpo);
  font-size: 0.9rem;
  color: var(--texto-oscuro);
  background: white;
  resize: vertical;
  outline: none;
}
.nf-campo input:focus,
.nf-campo textarea:focus {
  border-color: var(--verde-claro);
}
.nf-imagenes {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}
.nf-imagen {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.nf-imagen img {
  width: 100%;
  max-height: 150px;
  object-fit: cover;
  border-radius: 8px;
}
.nf-link {
  align-self: flex-start;
  background: none;
  border: none;
  color: #e74c3c;
  text-decoration: underline;
  cursor: pointer;
  font-size: 0.8rem;
  padding: 0;
}
.nf-acciones {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}
.btn-guardar {
  background: linear-gradient(135deg, var(--verde-bosque), var(--verde-medio));
  color: var(--humo);
  border: none;
  padding: 10px 22px;
  border-radius: 8px;
  cursor: pointer;
  font-family: var(--font-cuerpo);
  font-size: 0.85rem;
  letter-spacing: 0.06em;
}
.btn-guardar:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
.btn-cancelar {
  background: none;
  border: 1px solid var(--humo-oscuro);
  color: var(--texto-medio);
  padding: 10px 18px;
  border-radius: 8px;
  cursor: pointer;
}
.nota {
  font-size: 0.85rem;
  font-style: italic;
  color: var(--texto-medio);
  margin-bottom: 1rem;
}
.not-imgs {
  display: grid;
  grid-auto-flow: column;
  grid-auto-columns: 1fr;
  gap: 2px;
}
.not-imgs img {
  width: 100%;
  aspect-ratio: 4/3;
  object-fit: cover;
}
.foto-card.oculta {
  opacity: 0.6;
}
.acciones-3 {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  border-top: 1px solid var(--humo-oscuro);
}
.btn-neutro {
  border: none;
  border-right: 1px solid var(--humo-oscuro);
  background: rgba(0, 0, 0, 0.03);
  color: var(--texto-medio);
  padding: 10px;
  cursor: pointer;
  font-family: var(--font-cuerpo);
  font-size: 0.82rem;
}
.btn-neutro:hover {
  background: rgba(0, 0, 0, 0.1);
}
.chip {
  display: inline-block;
  font-size: 0.7rem;
  padding: 2px 8px;
  border-radius: 10px;
}
.chip-verde {
  background: rgba(74, 124, 74, 0.15);
  color: var(--verde-claro);
}
.chip-gris {
  background: rgba(0, 0, 0, 0.08);
  color: var(--texto-medio);
}
@media (max-width: 768px) {
  .nf-imagenes {
    grid-template-columns: 1fr;
  }
}
@media (max-width: 768px) {
  .panel-header {
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
  }

  .fotos-grid {
    grid-template-columns: 1fr;
  }
}
.sub-titulo {
  color: var(--verde-bosque);
  margin: 1.5rem 0 1rem;
}
.res-texto {
  font-size: 0.88rem;
  color: var(--texto-oscuro);
  white-space: pre-line;
  overflow-wrap: anywhere;
  max-height: 180px;
  overflow-y: auto;
}
</style>
