<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const recorridos = ref([
    {
        nombre: 'Caverna de las Brujas',
        descripcion: 'Un recorrido único bajo la tierra, explorando formaciones de estalactitas y estalagmitas en un ambiente controlado y seguro para grupos escolares.',
        duracion: '3 horas',
        dificultad: 'Baja',
        edad: 'Desde 8 años',
        img: null, // reemplazá con: import img from '../assets/...'
    },
    {
        nombre: 'Reserva Natural Payunia',
        descripcion: 'La mayor concentración de volcanes del mundo. Una experiencia geológica y paisajística incomparable, ideal para ciencias naturales y geografía.',
        duracion: 'Día completo',
        dificultad: 'Media',
        edad: 'Desde 12 años',
        img: null,
    },
    {
        nombre: 'Circuito de los Valles',
        descripcion: 'Recorrido por la Niña Encantada, Pozo de las Ánimas y Termas de los Molles. Historia, geología y naturaleza en un solo día.',
        duracion: 'Día completo',
        dificultad: 'Baja',
        edad: 'Desde 6 años',
        img: null,
    },
    {
        nombre: 'Malacara',
        descripcion: 'Paisajes volcánicos únicos del sur mendocino. Perfecto para actividades de educación ambiental y contacto con la naturaleza patagónica.',
        duracion: 'Medio día',
        dificultad: 'Baja',
        edad: 'Desde 8 años',
        img: null,
    },
]);

const servicios = ref([
    { icono: '🧭', titulo: 'Guía Profesional', descripcion: 'Más de 25 años de experiencia guiando grupos estudiantiles en Malargüe.' },
    { icono: '🚌', titulo: 'Transporte', descripcion: 'Coordinación con transporte habilitado para grupos escolares.' },
    { icono: '🛡️', titulo: 'Seguro de excursión', descripcion: 'Cobertura completa para todos los participantes durante el recorrido.' },
    { icono: '🍱', titulo: 'Almuerzo', descripcion: 'Opciones de almuerzo incluidas o coordinadas según el paquete elegido.' },
    { icono: '📋', titulo: 'Material educativo', descripcion: 'Información didáctica adaptada al nivel escolar del grupo.' },
    { icono: '📸', titulo: 'Registro fotográfico', descripcion: 'Documentación del recorrido para la institución educativa.' },
]);

const paquetes = ref([
    {
        nombre: 'Paquete Básico',
        precio: 'Consultar',
        duracion: 'Medio día',
        incluye: ['Guía profesional', 'Seguro de excursión', 'Material educativo'],
        destacado: false,
    },
    {
        nombre: 'Paquete Completo',
        precio: 'Consultar',
        duracion: 'Día completo',
        incluye: ['Guía profesional', 'Transporte coordinado', 'Seguro de excursión', 'Almuerzo', 'Material educativo', 'Registro fotográfico'],
        destacado: true,
    },
    {
        nombre: 'Paquete Multi-día',
        precio: 'Consultar',
        duracion: '2-3 días',
        incluye: ['Todo lo del paquete completo', 'Alojamiento coordinado', 'Múltiples destinos', 'Actividades nocturnas'],
        destacado: false,
    },
]);

const formData = ref({
    institucion: '',
    responsable: '',
    email: '',
    telefono: '',
    cantidadAlumnos: '',
    nivelEducativo: '',
    fechaEstimada: '',
    mensaje: '',
});

const enviado = ref(false);

const enviarFormulario = () => {
    // Placeholder — conectar con backend o EmailJS
    console.log('Formulario enviado:', formData.value);
    enviado.value = true;
};

const volverInicio = () => {
    router.push('/');
};
</script>

<template>
    <div class="estudiantiles-page">

        <!-- Navbar simple -->
        <nav class="est-navbar">
            <button class="btn-volver" @click="volverInicio">
                ← Volver al inicio
            </button>
            <span class="est-logo">Marisa Berdu — Turismo Estudiantil</span>
        </nav>

        <!-- Hero -->
        <section class="hero">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="hero-badge">Turismo Educativo</span>
                <h1>Excursiones Estudiantiles<br>en Malargüe</h1>
                <p>Experiencias únicas diseñadas para grupos escolares. Aprendizaje, aventura y naturaleza en el sur de
                    Mendoza.</p>
                <a href="#contacto-est" class="hero-cta">Solicitar información</a>
            </div>
        </section>

        <!-- Recorridos -->
        <section class="seccion recorridos-seccion">
            <div class="seccion-inner">
                <h2 class="seccion-titulo">Recorridos disponibles</h2>
                <p class="seccion-subtitulo">Cada destino adaptado al nivel educativo y las necesidades del grupo</p>

                <div class="recorridos-grid">
                    <div v-for="lugar in recorridos" :key="lugar.nombre" class="recorrido-card">
                        <div class="card-img-placeholder">
                            <img v-if="lugar.img" :src="lugar.img" :alt="lugar.nombre" />
                            <div v-else class="img-placeholder">
                                <span>📍</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3>{{ lugar.nombre }}</h3>
                            <p>{{ lugar.descripcion }}</p>
                            <div class="card-tags">
                                <span class="tag">⏱ {{ lugar.duracion }}</span>
                                <span class="tag">📊 {{ lugar.dificultad }}</span>
                                <span class="tag">👦 {{ lugar.edad }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Servicios -->
        <section class="seccion servicios-seccion">
            <div class="seccion-inner">
                <h2 class="seccion-titulo">Servicios incluidos</h2>
                <p class="seccion-subtitulo">Todo lo que necesita su institución para una excursión exitosa</p>

                <div class="servicios-grid">
                    <div v-for="servicio in servicios" :key="servicio.titulo" class="servicio-item">
                        <span class="servicio-icono">{{ servicio.icono }}</span>
                        <h4>{{ servicio.titulo }}</h4>
                        <p>{{ servicio.descripcion }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Paquetes -->
        <section class="seccion paquetes-seccion">
            <div class="seccion-inner">
                <h2 class="seccion-titulo">Paquetes</h2>
                <p class="seccion-subtitulo">Opciones flexibles para cada institución</p>

                <div class="paquetes-grid">
                    <div v-for="paquete in paquetes" :key="paquete.nombre" class="paquete-card"
                        :class="{ destacado: paquete.destacado }">
                        <div v-if="paquete.destacado" class="paquete-badge">Más elegido</div>
                        <h3>{{ paquete.nombre }}</h3>
                        <div class="paquete-duracion">{{ paquete.duracion }}</div>
                        <div class="paquete-precio">{{ paquete.precio }}</div>
                        <ul class="paquete-lista">
                            <li v-for="item in paquete.incluye" :key="item">
                                <span class="check">✓</span> {{ item }}
                            </li>
                        </ul>
                        <a href="#contacto-est" class="paquete-cta">
                            {{ paquete.destacado ? 'Solicitar ahora' : 'Consultar' }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Formulario de contacto -->
        <section class="seccion contacto-seccion" id="contacto-est">
            <div class="seccion-inner contacto-inner">
                <div class="contacto-info">
                    <h2>¿Querés organizar una excursión?</h2>
                    <p>Completá el formulario y nos ponemos en contacto para armar el recorrido ideal para tu
                        institución.</p>
                    <div class="contacto-datos">
                        <p>📧 marisa@ejemplo.com</p>
                        <p>📱 +54 260 000-0000</p>
                        <p>📍 Malargüe, Mendoza, Argentina</p>
                    </div>
                </div>

                <form class="contacto-form" @submit.prevent="enviarFormulario" v-if="!enviado">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Institución educativa</label>
                            <input v-model="formData.institucion" type="text" placeholder="Nombre de la escuela"
                                required />
                        </div>
                        <div class="form-group">
                            <label>Responsable</label>
                            <input v-model="formData.responsable" type="text" placeholder="Nombre y apellido"
                                required />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email</label>
                            <input v-model="formData.email" type="email" placeholder="correo@escuela.com" required />
                        </div>
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input v-model="formData.telefono" type="tel" placeholder="+54 260 000-0000" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Cantidad de alumnos</label>
                            <input v-model="formData.cantidadAlumnos" type="number" placeholder="Ej: 30" min="1" />
                        </div>
                        <div class="form-group">
                            <label>Nivel educativo</label>
                            <select v-model="formData.nivelEducativo">
                                <option value="">Seleccioná...</option>
                                <option>Primaria</option>
                                <option>Secundaria</option>
                                <option>Terciario / Universidad</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Fecha estimada</label>
                        <input v-model="formData.fechaEstimada" type="date" />
                    </div>
                    <div class="form-group">
                        <label>Mensaje adicional</label>
                        <textarea v-model="formData.mensaje" placeholder="Contanos más sobre lo que necesitás..."
                            rows="4"></textarea>
                    </div>
                    <button type="submit" class="form-submit">Enviar consulta</button>
                </form>

                <!-- Confirmación -->
                <div v-else class="form-confirmacion">
                    <span class="confirmacion-icono">✓</span>
                    <h3>¡Consulta enviada!</h3>
                    <p>Nos pondremos en contacto a la brevedad.</p>
                </div>

            </div>
        </section>

        <!-- Footer simple -->
        <footer class="est-footer">
            <p>© 2025 Marisa Berdu — Guía de Turismo · Malargüe, Mendoza</p>
        </footer>

    </div>
</template>

<style scoped>
.estudiantiles-page {
    font-family: var(--font-cuerpo);
    background: var(--humo);
    min-height: 100vh;
}

/* ===== NAVBAR ===== */
.est-navbar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: var(--verde-bosque);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 2rem;
    border-bottom: 1px solid rgba(212, 169, 106, 0.2);
}

.btn-volver {
    background: none;
    border: 1px solid rgba(212, 169, 106, 0.4);
    color: var(--arena);
    padding: 6px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-family: var(--font-cuerpo);
    font-size: 0.82rem;
    letter-spacing: 0.08em;
    transition: all 0.3s ease;
}

.btn-volver:hover {
    background: rgba(212, 169, 106, 0.15);
    border-color: var(--arena);
}

.est-logo {
    font-family: var(--font-titulo);
    color: var(--arena);
    font-size: 1rem;
    font-style: italic;
}

/* ===== HERO ===== */
.hero {
    position: relative;
    min-height: 480px;
    background:
        linear-gradient(160deg, var(--verde-bosque) 0%, #2d4a2d 50%, #1a3020 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    overflow: hidden;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Ccircle cx='30' cy='30' r='1' fill='rgba(212,169,106,0.08)'/%3E%3C/svg%3E");
    pointer-events: none;
}

.hero-content {
    position: relative;
    z-index: 2;
    padding: 3rem 2rem;
    max-width: 700px;
}

.hero-badge {
    display: inline-block;
    font-size: 0.72rem;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--arena);
    border: 1px solid rgba(212, 169, 106, 0.4);
    padding: 4px 16px;
    border-radius: 20px;
    margin-bottom: 1.5rem;
}

.hero-content h1 {
    font-family: var(--font-titulo);
    font-size: 2.8rem;
    color: var(--humo);
    line-height: 1.2;
    margin-bottom: 1rem;
}

.hero-content p {
    font-size: 1.05rem;
    color: rgba(245, 240, 232, 0.75);
    line-height: 1.7;
    margin-bottom: 2rem;
}

.hero-cta {
    display: inline-block;
    background: linear-gradient(135deg, var(--tierra), var(--tierra-claro));
    color: var(--humo);
    text-decoration: none;
    padding: 14px 32px;
    border-radius: 30px;
    font-size: 0.88rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(139, 69, 19, 0.4);
}

.hero-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(139, 69, 19, 0.5);
}

/* ===== SECCIONES ===== */
.seccion {
    padding: 5rem 1.5rem;
}

.seccion-inner {
    max-width: 1100px;
    margin: 0 auto;
}

.seccion-titulo {
    font-family: var(--font-titulo);
    font-size: 2rem;
    color: var(--verde-bosque);
    text-align: center;
    margin-bottom: 0.75rem;
}

.seccion-subtitulo {
    text-align: center;
    color: var(--texto-medio);
    font-size: 1rem;
    margin-bottom: 3rem;
    font-style: italic;
}

/* ===== RECORRIDOS ===== */
.recorridos-seccion {
    background: var(--humo);
}

.recorridos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
}

.recorrido-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(26, 46, 26, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.recorrido-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(26, 46, 26, 0.15);
}

.card-img-placeholder {
    height: 180px;
    overflow: hidden;
}

.card-img-placeholder img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.img-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--verde-medio), var(--verde-bosque));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
}

.card-body {
    padding: 1.25rem;
}

.card-body h3 {
    font-family: var(--font-titulo);
    font-size: 1.1rem;
    color: var(--verde-bosque);
    margin-bottom: 0.5rem;
}

.card-body p {
    font-size: 0.88rem;
    color: var(--texto-medio);
    line-height: 1.6;
    margin-bottom: 1rem;
}

.card-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.tag {
    font-size: 0.72rem;
    background: var(--humo-oscuro);
    color: var(--texto-medio);
    padding: 3px 10px;
    border-radius: 20px;
}

/* ===== SERVICIOS ===== */
.servicios-seccion {
    background: var(--verde-bosque);
}

.servicios-seccion .seccion-titulo {
    color: var(--arena);
}

.servicios-seccion .seccion-subtitulo {
    color: rgba(245, 240, 232, 0.6);
}

.servicios-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.servicio-item {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(212, 169, 106, 0.15);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: background 0.3s ease;
}

.servicio-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

.servicio-icono {
    font-size: 2rem;
    display: block;
    margin-bottom: 0.75rem;
}

.servicio-item h4 {
    font-family: var(--font-titulo);
    color: var(--arena);
    margin-bottom: 0.5rem;
    font-size: 1rem;
}

.servicio-item p {
    font-size: 0.82rem;
    color: rgba(245, 240, 232, 0.65);
    line-height: 1.6;
}

/* ===== PAQUETES ===== */
.paquetes-seccion {
    background: var(--humo-oscuro);
}

.paquetes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
    align-items: start;
}

.paquete-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(26, 46, 26, 0.08);
    position: relative;
    transition: transform 0.3s ease;
}

.paquete-card:hover {
    transform: translateY(-4px);
}

.paquete-card.destacado {
    background: var(--verde-bosque);
    box-shadow: 0 8px 40px rgba(26, 46, 26, 0.25);
    transform: scale(1.03);
}

.paquete-card.destacado:hover {
    transform: scale(1.03) translateY(-4px);
}

.paquete-badge {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--tierra);
    color: var(--humo);
    font-size: 0.7rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 4px 16px;
    border-radius: 20px;
}

.paquete-card h3 {
    font-family: var(--font-titulo);
    font-size: 1.3rem;
    color: var(--verde-bosque);
    margin-bottom: 0.5rem;
}

.paquete-card.destacado h3 {
    color: var(--arena);
}

.paquete-duracion {
    font-size: 0.8rem;
    color: var(--texto-medio);
    margin-bottom: 0.75rem;
    font-style: italic;
}

.paquete-card.destacado .paquete-duracion {
    color: rgba(245, 240, 232, 0.6);
}

.paquete-precio {
    font-size: 1.5rem;
    font-family: var(--font-titulo);
    color: var(--tierra);
    margin-bottom: 1.5rem;
}

.paquete-card.destacado .paquete-precio {
    color: var(--arena-claro);
}

.paquete-lista {
    list-style: none;
    margin-bottom: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.paquete-lista li {
    font-size: 0.85rem;
    color: var(--texto-medio);
    display: flex;
    align-items: center;
    gap: 8px;
}

.paquete-card.destacado .paquete-lista li {
    color: rgba(245, 240, 232, 0.8);
}

.check {
    color: var(--verde-claro);
    font-weight: bold;
}

.paquete-card.destacado .check {
    color: var(--arena);
}

.paquete-cta {
    display: block;
    text-align: center;
    text-decoration: none;
    padding: 10px;
    border-radius: 8px;
    font-size: 0.85rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    transition: all 0.3s ease;
    background: var(--humo-oscuro);
    color: var(--verde-bosque);
}

.paquete-card.destacado .paquete-cta {
    background: var(--tierra);
    color: var(--humo);
}

.paquete-cta:hover {
    opacity: 0.85;
}

/* ===== CONTACTO ===== */
.contacto-seccion {
    background: var(--humo);
}

.contacto-inner {
    display: grid;
    grid-template-columns: 1fr 1.6fr;
    gap: 4rem;
    align-items: start;
}

.contacto-info h2 {
    font-family: var(--font-titulo);
    font-size: 1.8rem;
    color: var(--verde-bosque);
    margin-bottom: 1rem;
}

.contacto-info p {
    color: var(--texto-medio);
    line-height: 1.7;
    margin-bottom: 1.5rem;
}

.contacto-datos {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.contacto-datos p {
    font-size: 0.9rem;
    color: var(--texto-medio);
    margin: 0;
}

/* Formulario */
.contacto-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group label {
    font-size: 0.78rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--texto-medio);
}

.form-group input,
.form-group select,
.form-group textarea {
    border: 1px solid var(--humo-oscuro);
    border-radius: 8px;
    padding: 10px 14px;
    font-family: var(--font-cuerpo);
    font-size: 0.9rem;
    color: var(--texto-oscuro);
    background: white;
    transition: border-color 0.3s ease;
    outline: none;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: var(--verde-claro);
}

.form-group textarea {
    resize: vertical;
}

.form-submit {
    background: linear-gradient(135deg, var(--verde-bosque), var(--verde-medio));
    color: var(--humo);
    border: none;
    padding: 14px;
    border-radius: 8px;
    font-family: var(--font-cuerpo);
    font-size: 0.88rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 0.5rem;
}

.form-submit:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* Confirmación */
.form-confirmacion {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(26, 46, 26, 0.08);
}

.confirmacion-icono {
    display: block;
    font-size: 3rem;
    color: var(--verde-claro);
    margin-bottom: 1rem;
}

.form-confirmacion h3 {
    font-family: var(--font-titulo);
    color: var(--verde-bosque);
    margin-bottom: 0.5rem;
}

/* ===== FOOTER ===== */
.est-footer {
    background: var(--verde-bosque);
    text-align: center;
    padding: 1.5rem;
    color: rgba(245, 240, 232, 0.5);
    font-size: 0.8rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 1.8rem;
    }

    .est-logo {
        display: none;
    }

    .contacto-inner {
        grid-template-columns: 1fr;
        gap: 2rem;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .paquete-card.destacado {
        transform: none;
    }
}
</style>