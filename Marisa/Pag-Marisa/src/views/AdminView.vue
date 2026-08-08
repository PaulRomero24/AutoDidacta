<script setup>
import { ref, onMounted } from 'vue';
import { loginAdmin, getFotosPendientes, getFotosAprobadasAdmin, aprobarFoto, rechazarFoto, eliminarFoto } from '../api.js';

const token = ref(sessionStorage.getItem('admin_token') || '');
const loginError = ref('');
const cargandoLogin = ref(false);
const formLogin = ref({ username: '', password: '' });
const fotosPendientes = ref([]);
const cargandoFotos = ref(false);
const mensajeAccion = ref('');

const estaLogueado = () => !!token.value;

const login = async () => {
    cargandoLogin.value = true;
    loginError.value = '';
    try {
        const data = await loginAdmin(formLogin.value.username, formLogin.value.password);
        token.value = data.access_token;
        sessionStorage.setItem('admin_token', data.access_token);
        await cargarPendientes();
    } catch (error) {
        loginError.value = 'Usuario o contraseña incorrectos';
    } finally {
        cargandoLogin.value = false;
    }
};

const logout = () => {
    token.value = '';
    sessionStorage.removeItem('admin_token');
    fotosPendientes.value = [];
};

const cargarPendientes = async () => {
    cargandoFotos.value = true;
    try {
        fotosPendientes.value = await getFotosPendientes(token.value);
    } catch (error) {
        console.error('Error cargando fotos:', error);
    } finally {
        cargandoFotos.value = false;
    }
};

const aprobar = async (id) => {
    try {
        await aprobarFoto(id, token.value);
        fotosPendientes.value = fotosPendientes.value.filter(f => f.id !== id);
        mostrarMensaje('✓ Foto aprobada — aparecerá en la galería');
    } catch (error) {
        console.error(error);
    }
};

const rechazar = async (id) => {
    try {
        await rechazarFoto(id, token.value);
        fotosPendientes.value = fotosPendientes.value.filter(f => f.id !== id);
        mostrarMensaje('✕ Foto rechazada');
    } catch (error) {
        console.error(error);
    }
};
// Nuevo estado
const fotosAprobadas = ref([]);
const seccionActiva = ref('pendientes'); // 'pendientes' o 'aprobadas'

// Nueva función
const cargarAprobadas = async () => {
    try {
        fotosAprobadas.value = await getFotosAprobadasAdmin(token.value);
    } catch (error) {
        console.error(error);
    }
};

const eliminar = async (id) => {
    if (!confirm('¿Seguro que querés eliminar esta foto de la galería?')) return;
    try {
        await eliminarFoto(id, token.value);
        fotosAprobadas.value = fotosAprobadas.value.filter(f => f.id !== id);
        mostrarMensaje('🗑 Foto eliminada de la galería');
    } catch (error) {
        console.error(error);
    }
};

const mostrarMensaje = (msg) => {
    mensajeAccion.value = msg;
    setTimeout(() => { mensajeAccion.value = ''; }, 3000);
};

const formatearFecha = (fecha) => {
    return new Date(fecha).toLocaleDateString('es-AR', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
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
                    <input v-model="formLogin.username" type="text" placeholder="Usuario" @keydown.enter="login" />
                </div>

                <div class="form-grupo">
                    <label>Contraseña</label>
                    <input v-model="formLogin.password" type="password" placeholder="Contraseña"
                        @keydown.enter="login" />
                </div>

                <p v-if="loginError" class="error-msg">{{ loginError }}</p>

                <button class="btn-login" @click="login" :disabled="cargandoLogin">
                    {{ cargandoLogin ? 'Ingresando...' : 'Ingresar' }}
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
                    <button class="tab" :class="{ activo: seccionActiva === 'pendientes' }"
                        @click="seccionActiva = 'pendientes'; cargarPendientes()">
                        Pendientes
                        <span class="tab-badge" v-if="fotosPendientes.length > 0">
                            {{ fotosPendientes.length }}
                        </span>
                    </button>
                    <button class="tab" :class="{ activo: seccionActiva === 'aprobadas' }"
                        @click="seccionActiva = 'aprobadas'; cargarAprobadas()">
                        Aprobadas
                    </button>
                </div>

                <!-- Pendientes -->
                <div v-if="seccionActiva === 'pendientes'">
                    <div v-if="cargandoFotos" class="estado-msg">Cargando...</div>
                    <div v-else-if="fotosPendientes.length === 0" class="estado-msg">✓ No hay fotos pendientes</div>
                    <div v-else class="fotos-grid">
                        <div v-for="foto in fotosPendientes" :key="foto.id" class="foto-card">
                            <div class="foto-img-wrapper">
                                <img :src="foto.url_foto" :alt="`Foto de ${foto.nombre_autor}`" />
                            </div>
                            <div class="foto-info">
                                <p class="foto-autor">📷 {{ foto.nombre_autor }}</p>
                                <p class="foto-destino">📍 {{ foto.destino }}</p>
                                <p class="foto-fecha">🕐 {{ formatearFecha(foto.fecha) }}</p>
                            </div>
                            <div class="foto-acciones">
                                <button class="btn-aprobar" @click="aprobar(foto.id)">✓ Aprobar</button>
                                <button class="btn-rechazar" @click="rechazar(foto.id)">✕ Rechazar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aprobadas -->
                <div v-if="seccionActiva === 'aprobadas'">
                    <div v-if="fotosAprobadas.length === 0" class="estado-msg">No hay fotos aprobadas</div>
                    <div v-else class="fotos-grid">
                        <div v-for="foto in fotosAprobadas" :key="foto.id" class="foto-card">
                            <div class="foto-img-wrapper">
                                <img :src="foto.url_foto" :alt="`Foto de ${foto.nombre_autor}`" />
                            </div>
                            <div class="foto-info">
                                <p class="foto-autor">📷 {{ foto.nombre_autor }}</p>
                                <p class="foto-destino">📍 {{ foto.destino }}</p>
                                <p class="foto-fecha">🕐 {{ formatearFecha(foto.fecha) }}</p>
                            </div>
                            <div class="foto-acciones" style="grid-template-columns: 1fr">
                                <button class="btn-rechazar" @click="eliminar(foto.id)">🗑 Eliminar</button>
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

.login-card>p {
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
</style>