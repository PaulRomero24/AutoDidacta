<script setup>
import { ref } from 'vue';

//Lista de imagenes
import experiencia1 from "../assets/experiencias/experiencia1.jpg"
import experiencia2 from "../assets/experiencias/experiencia2.jpg"
import experiencia3 from "../assets/experiencias/experiencia3.jpg"
import experiencia4 from "../assets/experiencias/experiencia4.jpg"
import experiencia5 from "../assets/experiencias/experiencia5.jpg"

const expandedImage = ref('');
const showExpanded = ref(false);

const imagenes = {
    topLeft: experiencia1,
    topRight: experiencia2,
    bottomLeft: experiencia3,
    bottomRight: experiencia4,
    centerBottom: experiencia5,
}

const openImage = (src) => {
    expandedImage.value = src;
    showExpanded.value = true;
};

const closeExpanded = () => {
    showExpanded.value = false;
};

</script>

<template>
    <section class="experiencia-seccion">
        <!-- Contenedor relativo que incluye texto + imágenes -->
        <div class="contenido-con-imagenes">
            <div class="texto-contenido">
                <h2>Mi carrera como guía de turismo</h2>
                <p>
                    Me recibí como guía en el 2001, estuve guiando desde entonces. Me gustaba tanto el oficio, que creé
                    una agencia de turismo, pero con el tiempo me di cuenta que mi lugar era estar en los lugares, y no
                    en la ciudad. <br>Así que volví al ruedo, ahí compartí hermosos momentos con muchos colegas y aprendí un
                    montón de los experimentados. Hoy en dia tengo la fortuna de compartir este trabajo con mi hijo.
                    Seguí y sigo capacitándome con cada disertación o curso que dan sobre mi Malargüe, su fauna y flora.
                </p>
            </div>

            <!-- Imágenes posicionadas sobre/entre el texto -->
            <div class="imagenes-laterales">
            <img :src="imagenes.topLeft" class="corner-img top-left" @click.stop="openImage(imagenes.topLeft)"
                alt="Mis compañeras de aventuras" />
            <img :src="imagenes.topRight" class="corner-img top-right" @click.stop="openImage(imagenes.topRight)"
                alt="Animando la tropa" />
            <img :src="imagenes.bottomLeft" class="corner-img bottom-left" @click.stop="openImage(imagenes.bottomLeft)"
                alt="Genio incomprendido" />
            <img :src="imagenes.bottomRight" class="corner-img bottom-right"
                @click.stop="openImage(imagenes.bottomRight)" alt="Mi maestra y compañera" />
            <img :src="imagenes.centerBottom" class="corner-img center-bottom"
                @click.stop="openImage(imagenes.centerBottom)" alt="Mi hijo,quien me siguio los pasos" />
            </div>
        </div>
    </section>
    <!-- Lightbox -->
    <div v-if="showExpanded" class="lightbox" @click.stop="closeExpanded">
        <img :src="expandedImage" alt="Imagen expandida" class="expanded-img" />
    </div>
</template>

<style scoped>
.experiencia-seccion {
    position: relative;
    padding: 3rem 1rem;
    /* ← Más espacio arriba/abajo y laterales */
    max-width: 1428px;
    /* ← Un poco más ancho */
    margin: 4rem auto;
    /* ← Margen superior/inferior para separar de otras secciones */
    overflow: visible;
}

/* Contenedor que agrupa texto + imágenes */
.contenido-con-imagenes {
    position: relative;
    padding: 5rem 4rem;
    background: var(--arena);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    /* sin border-radius para que llegue a los bordes */
    width: 100vw;
    margin-left: calc(-50vw + 50%); /* ← truco para romper el max-width del padre */
}

.texto-contenido h2 {
    text-align: center;
    font-size: 2.2rem;
    margin-top: 3rem;
    margin-bottom: 2rem;
    /* ← Más espacio debajo del título */
}

.texto-contenido p {
    font-size: 24px;
    text-align: center;
    font-style:italic;
    white-space: pre-line;

}

/* Imágenes en esquinas - con transform para separarlas */
.corner-img {
    position: absolute;
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 12px;
    border: 3px solid white;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    cursor: pointer;
    z-index: 1;
    transition: transform 1s ease;
}

.corner-img:hover {
    transform: scale(1.25) rotate(0.2deg);
}

/* Posiciones con offset */
.top-left {
    top: 5%;
    left: 4.5%;
    transform: translate(-40%, -40%);
    /* ← Se mueve fuera del contenedor */
}

.top-right {
    top: 5%;
    right: 3.5%;
    transform: translate(30%, -40%);
}

.bottom-left {
    bottom: 0;
    left: 4.5%;
    transform: translate(-40%, 50%);
}

.bottom-right {
    bottom: 0;
    right: 4.5%;
    transform: translate(40%, 50%);
}

/* Imagen central - dentro del contenedor */
.center-bottom {
    bottom: -80px;
    /* ← Ajusta este valor según necesites */
    left: 50%;
    transform: translateX(-50%);
    width: 200px;
    height: 150px;
    object-fit: cover;
    object-position: top -0.5% right 20%;
    /* ← Mejor que "contain" para fotos */
    border-radius: 12px;
    border: 3px solid white;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    z-index: 2;
}

/* Lightbox completo */
.lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.92);
    display: flex;
    justify-content: center;
    /* ← Centra horizontalmente */
    align-items: center;
    /* ← Centra verticalmente */
    z-index: 5000;
    cursor: pointer;
}

.expanded-img {
    max-width: 90vw;
    /* ← Evita que se salga en móvil */
    max-height: 90vh;
    /* ← Evita scroll vertical */
    object-fit: contain;
    /* ← Mantiene proporción sin recortar */
    border: 4px solid white;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
}

@media (max-width: 768px) {
    .experiencia-seccion{
        margin: 1rem auto;
        padding: 0;
    }
    .contenido-con-imagenes {
        display: grid;
        grid-template-columns: 1fr 160px;
        /* texto | imágenes */
        gap: 1.2rem;
        margin-top: 2rem;
        padding: 1.5rem;
        align-items:center;
    }

    .texto-contenido {
        grid-column: 1;
        text-align: center;
    }

    .texto-contenido h2 {
        font-size: 1.6rem;
        margin-bottom: 1rem;
    }

    .texto-contenido p {
        font-size: 1rem;
        line-height: 1.6;
        font-style: italic;
    }

    /* Ocultamos las posiciones absolutas */
    .corner-img,
    .center-bottom {
        position: static !important;
        transform: none !important;
        width: 120px;
        height: 120px;
        margin-bottom: 0.8rem;
        border-radius: 8px;
    }

    /* Agrupamos las imágenes en una columna */
    .imagenes-lateral {
        grid-column: 2;
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
        align-items: center;
    }
}
</style>