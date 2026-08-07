<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

// Importar imágenes existentes
import caverna1 from "../assets/Lugares/Caverna1.jpg";
import caverna2 from "../assets/Lugares/Caverna2.jpg";
import payunia1 from "../assets/Lugares/Payunia1.jpg";
import payunia2 from "../assets/Lugares/Payunia2.jpg";
import valles1 from "../assets/Lugares/Valles1.jpg";
import valles2 from "../assets/Lugares/Valles2.jpg";
import malacara1 from "../assets/Lugares/Malacara1.jpg";
import malacara2 from "../assets/Lugares/Malacara2.jpg";

const router = useRouter();

const destinos = ref([
    {
        nombre: 'Caverna de las Brujas',
        descripcion: 'El mundo subterráneo de Malargüe',
        fotos: [
            { src: caverna1, autor: 'Marisa Berdu' },
            { src: caverna2, autor: 'Marisa Berdu' },
        ]
    },
    {
        nombre: 'Payunia',
        descripcion: 'El mar de volcanes',
        fotos: [
            { src: payunia1, autor: 'Marisa Berdu' },
            { src: payunia2, autor: 'Marisa Berdu' },
        ]
    },
    {
        nombre: 'Valles',
        descripcion: 'Ruta hacia Las Leñas',
        fotos: [
            { src: valles1, autor: 'Marisa Berdu' },
            { src: valles2, autor: 'Marisa Berdu' },
        ]
    },
    {
        nombre: 'Malacara',
        descripcion: 'Paisajes volcánicos únicos',
        fotos: [
            { src: malacara1, autor: 'Marisa Berdu' },
            { src: malacara2, autor: 'Marisa Berdu' },
        ]
    },
]);

// Lightbox
const expandedImage = ref('');
const showExpanded = ref(false);
const expandedAutor = ref('');

const openImage = (src, autor) => {
    expandedImage.value = src;
    expandedAutor.value = autor;
    showExpanded.value = true;
};
const closeExpanded = () => { showExpanded.value = false; };

// Modal subir foto
const showUpload = ref(false);
const uploadEnviado = ref(false);

const formFoto = ref({
    nombre: '',
    destino: '',
    preview: null,
    archivo: null,
});

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    formFoto.value.archivo = file;
    formFoto.value.preview = URL.createObjectURL(file);
};

const enviarFoto = () => {
    // Simulación EmailJS — reemplazar con llamada real cuando esté configurado
    console.log('Simulando envío:', {
        nombre: formFoto.value.nombre,
        destino: formFoto.value.destino,
        archivo: formFoto.value.archivo?.name,
    });

    /* Cuando tengas EmailJS configurado, reemplazá esto:
    emailjs.send('service_xxx', 'template_xxx', {
      nombre: formFoto.value.nombre,
      destino: formFoto.value.destino,
    }, 'public_key_xxx').then(() => { uploadEnviado.value = true })
    */

    uploadEnviado.value = true;
};

const cerrarUpload = () => {
    showUpload.value = false;
    uploadEnviado.value = false;
    formFoto.value = { nombre: '', destino: '', preview: null, archivo: null };
};
</script>

<template>
    <div class="galeria-page">

        <!-- Navbar simple -->
        <nav class="gal-navbar">
            <button class="btn-volver" @click="router.push('/')">
                ← Volver al inicio
            </button>
            <span class="gal-titulo">Galería de Malargüe</span>
        </nav>

        <!-- Hero minimal -->
        <div class="gal-hero">
            <h1>Malargüe a través de sus visitantes</h1>
            <p>Fotos de los destinos más hermosos del sur mendocino</p>
        </div>

        <!-- Secciones por destino -->
        <div class="galeria-contenido">
            <section v-for="destino in destinos" :key="destino.nombre" class="destino-seccion">
                <div class="destino-header">
                    <h2>{{ destino.nombre }}</h2>
                    <p>{{ destino.descripcion }}</p>
                    <div class="header-linea"></div>
                </div>

                <div class="fotos-grid">
                    <div v-for="(foto, index) in destino.fotos" :key="index" class="foto-item"
                        @click="openImage(foto.src, foto.autor)">
                        <img :src="foto.src" :alt="`${destino.nombre} ${index + 1}`" />
                        <div class="foto-overlay">
                            <span class="foto-zoom">🔍</span>
                            <span class="foto-autor">{{ foto.autor }}</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- CTA participar -->
        <div class="cta-participar">
            <h3>¿Estuviste en Malargüe?</h3>
            <p>Compartí tus fotos y formá parte de esta galería</p>
            <button class="btn-cta" @click="showUpload = true">
                📸 Subir mi foto
            </button>
        </div>

        <!-- Footer simple -->
        <footer class="gal-footer">
            <p>© 2026 Marisa Berdu — Guía de Turismo · Malargüe, Mendoza</p>
        </footer>

        <!-- Lightbox -->
        <div v-if="showExpanded" class="lightbox" @click="closeExpanded">
            <div class="lightbox-inner" @click.stop>
                <button class="lightbox-close" @click="closeExpanded">✕</button>
                <img :src="expandedImage" alt="Imagen expandida" class="expanded-img" />
                <p class="lightbox-autor">📷 {{ expandedAutor }}</p>
            </div>
        </div>

        <!-- Modal subir foto -->
        <div v-if="showUpload" class="modal-overlay" @click="cerrarUpload">
            <div class="modal" @click.stop>
                <button class="modal-close" @click="cerrarUpload">✕</button>

                <!-- Formulario -->
                <div v-if="!uploadEnviado">
                    <h3 class="modal-titulo">Participar en la galería</h3>
                    <p class="modal-subtitulo">Tu foto será revisada antes de publicarse</p>

                    <div class="form-grupo">
                        <label>Tu nombre</label>
                        <input v-model="formFoto.nombre" type="text" placeholder="Nombre y apellido" />
                    </div>

                    <div class="form-grupo">
                        <label>Destino</label>
                        <select v-model="formFoto.destino">
                            <option value="">Seleccioná un destino...</option>
                            <option v-for="d in destinos" :key="d.nombre" :value="d.nombre">
                                {{ d.nombre }}
                            </option>
                        </select>
                    </div>

                    <div class="form-grupo">
                        <label>Tu foto</label>
                        <div class="upload-area" @click="$refs.fileInput.click()">
                            <div v-if="!formFoto.preview" class="upload-placeholder">
                                <span>📷</span>
                                <p>Tocá para seleccionar una foto</p>
                                <small>JPG, PNG — máx. 5MB</small>
                            </div>
                            <img v-else :src="formFoto.preview" class="upload-preview" alt="Preview" />
                        </div>
                        <input ref="fileInput" type="file" accept="image/*" style="display:none"
                            @change="onFileChange" />
                    </div>

                    <button class="btn-enviar" :disabled="!formFoto.nombre || !formFoto.destino || !formFoto.archivo"
                        @click="enviarFoto">
                        Enviar para revisión
                    </button>
                </div>

                <!-- Confirmación -->
                <div v-else class="confirmacion">
                    <span class="confirmacion-icono">✓</span>
                    <h3>¡Gracias por participar!</h3>
                    <p>Tu foto fue recibida y será revisada antes de publicarse en la galería.</p>
                    <button class="btn-enviar" @click="cerrarUpload">Cerrar</button>
                </div>

            </div>
        </div>

    </div>
</template>

<style scoped>
.galeria-page {
    min-height: 100vh;
    background: #d6ccb8;
    font-family: var(--font-cuerpo);
}

/* ===== NAVBAR ===== */
.gal-navbar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: rgba(13, 26, 13, 0.97);
    backdrop-filter: blur(12px);
    display: flex;
    padding: 1rem 2rem;
    border-bottom: 1px solid rgba(212, 169, 106, 0.15);
}

.btn-volver {
    background: none;
    border: 1px solid rgba(212, 169, 106, 0.4);
    color: var(--arena);
    padding: 6px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-family: var(--font-cuerpo);
    font-size: 0.9rem;
    letter-spacing: 0.08em;
    transition: all 0.3s ease;
}

.btn-volver:hover {
    background: rgba(228, 179, 106, 0.1);
    border-color: var(--arena);
}

.gal-titulo {
    font-family: var(--font-titulo);
    color: var(--arena);
    font-size: 2rem;
    font-style: italic;
    text-align: center;
    margin: 0 auto;
}


.btn-subir:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(139, 69, 19, 0.4);
}

/* ===== HERO ===== */
.gal-hero {
    background: linear-gradient(160deg, var(--verde-bosque), #2d4a2d);
    padding: 4rem 2rem;
    text-align: center;
}

.gal-hero h1 {
    font-family: var(--font-titulo);
    font-size: 2.2rem;
    color: var(--humo);
    margin-bottom: 0.75rem;
}

.gal-hero p {
    color: rgba(245, 240, 232, 0.6);
    font-size: 1.5rem;
    font-style: italic;
}

/* ===== SECCIONES ===== */
.galeria-contenido {
    max-width: 1200px;
    margin: 0 auto;
    padding: 3rem 1.5rem;
}

.destino-seccion {
    margin-bottom: 4rem;
}

.destino-header {
    margin-bottom: 1.5rem;
}

.destino-header h2 {
    font-family: var(--font-titulo);
    font-size: 2rem;
    color: var(--verde-bosque);
    margin-bottom: 0.3rem;
}

.destino-header p {
    color: var(--texto-medio);
    font-size: 1.2rem;
    font-style: italic;
    margin-bottom: 0.75rem;
}

.header-linea {
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, var(--tierra), transparent);
    border-radius: 2px;
}

/* ===== GRID ===== */
.fotos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1rem;
}

.foto-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    aspect-ratio: 4/3;
    background: var(--verde-bosque);
}

.foto-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
    display: block;
}

.foto-item:hover img {
    transform: scale(1.05);
}

.foto-overlay {
    position: absolute;
    inset: 0;
    background: rgba(13, 26, 13, 0);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: background 0.3s ease;
}

.foto-item:hover .foto-overlay {
    background: rgba(13, 26, 13, 0.55);
}

.foto-zoom {
    font-size: 1.8rem;
    opacity: 0;
    transform: scale(0.5);
    transition: all 0.3s ease;
}

.foto-autor {
    font-family: var(--font-cuerpo);
    font-size: 0.75rem;
    color: var(--arena);
    letter-spacing: 0.08em;
    opacity: 0;
    transition: opacity 0.3s ease 0.05s;
}

.foto-item:hover .foto-zoom {
    opacity: 1;
    transform: scale(1);
}

.foto-item:hover .foto-autor {
    opacity: 1;
}

/* ===== CTA ===== */
.cta-participar {
    background: var(--verde-bosque);
    text-align: center;
    padding: 4rem 2rem;
}

.cta-participar h3 {
    font-family: var(--font-titulo);
    font-size: 1.8rem;
    color: var(--arena);
    margin-bottom: 0.5rem;
}

.cta-participar p {
    color: rgba(245, 240, 232, 0.6);
    margin-bottom: 1.5rem;
    font-style: italic;
}

.btn-cta {
    background: linear-gradient(135deg, var(--tierra), var(--tierra-claro));
    color: var(--humo);
    border: none;
    padding: 14px 32px;
    border-radius: 30px;
    font-family: var(--font-cuerpo);
    font-size: 0.9rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(139, 69, 19, 0.4);
}

.btn-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(139, 69, 19, 0.5);
}

/* ===== FOOTER ===== */
.gal-footer {
    background: #0d1a0d;
    text-align: center;
    padding: 1.5rem;
    color: rgba(245, 240, 232, 0.4);
    font-size: 0.8rem;
}

/* ===== LIGHTBOX ===== */
.lightbox {
    position: fixed;
    inset: 0;
    background: rgba(5, 10, 5, 0.95);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9000;
    cursor: zoom-out;
    backdrop-filter: blur(4px);
    animation: fadeIn 0.2s ease;
}

.lightbox-inner {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    cursor: default;
    max-width: 90vw;
}

.lightbox-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.2s;
}

.lightbox-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.expanded-img {
    max-width: 90vw;
    max-height: 82vh;
    object-fit: contain;
    border-radius: 6px;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.8);
}

.lightbox-autor {
    font-size: 0.8rem;
    color: rgba(245, 240, 232, 0.5);
    font-style: italic;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

/* ===== MODAL SUBIR FOTO ===== */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(5, 10, 5, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9000;
    backdrop-filter: blur(4px);
    padding: 1rem;
}

.modal {
    background: var(--humo);
    border-radius: 16px;
    padding: 2rem;
    width: 100%;
    max-width: 460px;
    position: relative;
    box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
    animation: slideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: var(--humo-oscuro);
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 0.9rem;
    color: var(--texto-medio);
    transition: background 0.2s;
}

.modal-close:hover {
    background: var(--arena);
}

.modal-titulo {
    font-family: var(--font-titulo);
    font-size: 1.4rem;
    color: var(--verde-bosque);
    margin-bottom: 0.25rem;
}

.modal-subtitulo {
    font-size: 0.82rem;
    color: var(--texto-medio);
    font-style: italic;
    margin-bottom: 1.5rem;
}

.form-grupo {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 1rem;
}

.form-grupo label {
    font-size: 0.75rem;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--texto-medio);
}

.form-grupo input,
.form-grupo select {
    border: 1px solid var(--humo-oscuro);
    border-radius: 8px;
    padding: 10px 14px;
    font-family: var(--font-cuerpo);
    font-size: 0.9rem;
    color: var(--texto-oscuro);
    background: white;
    outline: none;
    transition: border-color 0.3s;
}

.form-grupo input:focus,
.form-grupo select:focus {
    border-color: var(--verde-claro);
}

.upload-area {
    border: 2px dashed var(--humo-oscuro);
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.3s, background 0.3s;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-area:hover {
    border-color: var(--verde-claro);
    background: rgba(74, 124, 74, 0.04);
}

.upload-placeholder span {
    font-size: 2rem;
    display: block;
    margin-bottom: 0.5rem;
}

.upload-placeholder p {
    font-size: 0.85rem;
    color: var(--texto-medio);
    margin-bottom: 0.25rem;
}

.upload-placeholder small {
    font-size: 0.72rem;
    color: rgba(61, 61, 46, 0.4);
}

.upload-preview {
    max-width: 100%;
    max-height: 160px;
    object-fit: contain;
    border-radius: 6px;
}

.btn-enviar {
    width: 100%;
    background: linear-gradient(135deg, var(--verde-bosque), var(--verde-medio));
    color: var(--humo);
    border: none;
    padding: 12px;
    border-radius: 8px;
    font-family: var(--font-cuerpo);
    font-size: 0.88rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    cursor: pointer;
    margin-top: 0.5rem;
    transition: all 0.3s ease;
}

.btn-enviar:hover:not(:disabled) {
    opacity: 0.9;
    transform: translateY(-1px);
}

.btn-enviar:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* Confirmación */
.confirmacion {
    text-align: center;
    padding: 1rem 0;
}

.confirmacion-icono {
    display: block;
    font-size: 3rem;
    color: var(--verde-claro);
    margin-bottom: 1rem;
}

.confirmacion h3 {
    font-family: var(--font-titulo);
    color: var(--verde-bosque);
    margin-bottom: 0.5rem;
}

.confirmacion p {
    font-size: 0.88rem;
    color: var(--texto-medio);
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .gal-titulo {
        display: none;
    }

    .gal-hero h1 {
        font-size: 1.6rem;
    }

    .fotos-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.6rem;
    }

    .destino-header h2 {
        font-size: 1.4rem;
    }
}
</style>