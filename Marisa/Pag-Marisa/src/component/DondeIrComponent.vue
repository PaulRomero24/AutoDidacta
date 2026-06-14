<script setup>
import caverna1 from "../assets/Lugares/Caverna1.jpg";
import caverna2 from "../assets/Lugares/Caverna2.jpg";
import payunia1 from "../assets/Lugares/Payunia1.jpg";
import payunia2 from "../assets/Lugares/Payunia2.jpg";
import valles1 from "../assets/Lugares/Valles1.jpg";
import valles2 from "../assets/Lugares/Valles2.jpg";
import valles3 from "../assets/Lugares/Valles3.jpg";
import malacara1 from "../assets/Lugares/Malacara1.jpg";
import malacara2 from "../assets/Lugares/Malacara2.jpg";

import { ref } from 'vue';


// Estado para el lightbox
const expandedImage = ref('');
const showExpanded = ref(false);

const openImage = (src) => {
    expandedImage.value = src;
    showExpanded.value = true;
};

const closeExpanded = () => {
    showExpanded.value = false;
    expandedImage.value = '';
};

//Lista de sitios
const lugares = ref([
    {
        nombre: "Caverna de las brujas",
        descripcion: `Caverna de las brujas es un lugar lleno de magia y belleza. Acá hacemos un recorrido dentro de una caverna moldeada por el tiempo, que se divide en diferentes salas.

        EXPLICACION OFICIAL:
        La caverna se encuentra sobre una formación jurásica de rocas calcáreas de origen marino. En los alrededores de la entrada se encuentran estratos expuestos con evidencia fósil de moluscos gasterópodos, especies dominantes en el jurásico y en cretácico. Las formas de las diversas galerías de la caverna se formaron por la acción del agua sobre rocas calcáreas, que ha erosionado profundas galerías subterráneas y creado, mediante el depósito de los materiales salinos en estado de disolución, diversas formas muy singulares de estalactitas, estalagmitas, velos, columnas, formaciones coralinas, etc. Algunos de los sitios que el turista visita son la Sala de la Virgen, La Gatera, La Sala de la Estalagmita Gigante y Sala de los Encuentros.
        `,
        imagenes: [caverna1, caverna2]
    },

    {
        nombre: "Payunia",
        descripcion: `Aunque pareciera una escena de alguna pelicula en el espacio, este lugar existe, y la sensacion que produce estar en el medio de casi 800 conos volcanicos es una experiencia imborrable.
        
        EXPLICACION OFICIAL:
        Constituye uno de los parques volcánicos de mayor densidad y diversidad del planeta. Por esta razón y por su belleza paisajística, gran parte de la reserva integra un sitio propuesto como candidato a Patrimonio Mundial Natural ante la Unesco. Posee extensos escenarios cubiertos de coloridos materiales volcánicos, en los cuales habita un santuario de fauna y flora.

Payunia comprende un amplio territorio de planicies y laderas totalmente cubiertas de materiales negros y rojizos que son la expresión de variadas manifestaciones volcánicas. La configuración del paisaje incluye alrededor de 800 conos volcánicos de retroarco, con extensos campos de roca fundida –lava- y materiales fragmentados –cenizas, lapillis y bombas- que deslumbran al visitante y ponen de relieve el poder, la dinámica y variabilidad de la geología terrestre. La actividad volcánica que dio forma al paisaje actual se produjo a finales del período terciario -considerado un tiempo geológico reciente- y continuó, aunque alternadamente, hasta épocas prehistóricas.
        `,
        imagenes: [payunia1, payunia2]
    },

    {
        nombre: "Malacara",
        descripcion:`Una experiencia unica de estar dentro de un volcan.
        
        EXPLICACION OFICIAL:
    Está ubicado a 42 km al sureste de la ciudad de Malargüe, camino a la reserva Laguna Llancanelo.  
Este volcán, tiene una altura de poco más de 1800 msnm. Su nombre se debe al parecido con la cara manchada de los caballos Malacara, cuya denominación desciende de la costumbre lingüística de los pobladores locales.
Un trekking que nos permite introducirnos en él a través de sus cárcavas y desde ahí comprender su origen y evolución. Por otra parte al subirlo obtenemos una vista panorámica privilegiada del norte de Payunia, de la imponente antena DS3 perteneciente a la Agencia Espacial Europea y de la apacible Laguna Llancanelo.
        ` ,
        imagenes: [malacara1, malacara2]
    },

    {
        nombre: "Valles",
        descripcion: `Hermoso complejo de valles para recorrer la ruta 222.
        
        LAGUNA NIÑA ENCANTADA:
        Esta es una hermosa laguna de 80 metros de diámetro y de aguas cristalinas de tono esmeralda, que provienen de ríos subterráneos. Sita en el escorial volcánico de El Infiernillo, un increíble campo de rocas volcánicas que, al reflejarse en el agua producen formaciones sugestivas que han originado, a través de los años, innumerables leyendas.
        VALLE DE LOS MOLLES:
        Piletas termales,el río, la montaña y la gastronomía son la combinación perfecta para poder disfrutar de uno de los valles más genuinos que tiene Malargüe, Los Molles.  El Valle alberga una creciente actividad hotelera que comenzó hace 90 años con el hotel Termas Lahuen-Co.
        POZO DE LAS ANIMAS:
        En este singular paisaje podrá observar dos dolinas que fueron erosionadas por los ríos subterráneos originando espejos de aguas verdes con casi 300 metros de diámetro cada uno.
        Algunos lo llaman Trolope-Co o agua del gritadero, en lengua Mapuche, su nombre es protagonista de varias leyendas que han transcurrido en la historia de los pobladores rurales de generación en generación. La entrada es libre y gratuita y también encontrará carteles informativos donde se explican estos procesos.
        LAS LEÑAS:
        Siendo uno de los Centros de esquí más importantes de Latinoamérica, incluye pistas y sectores fuera de pista accesibles por los medios de elevación. Máximo descenso ininterrumpido por pistas: 7 Km. y 16 medios de elevación.
        `,
        imagenes: [valles2, valles1,valles3]
    },
])


</script>
<template>
    <div class="lugares-container">
        <h2 class="section-title">Lugares donde puedes ir</h2>
        <div v-for="lugar in lugares" :key="lugar.nombre" class="lugar-card">
            <h2>{{ lugar.nombre }}</h2>

            <div class="imagenes">
                <div v-for="(imagen, index) in lugar.imagenes" :key="index" class="img-wrapper"
                    @click="openImage(imagen)">
                    <img :src="imagen" :alt="`${lugar.nombre} imagen ${index + 1}`" />
                </div>
            </div>

            <p class="descripcion">{{ lugar.descripcion }}</p>
        </div>
    </div>
    <!-- Lightbox -->
    <div v-if="showExpanded" class="lightbox" @click="closeExpanded">
        <img :src="expandedImage" alt="Imagen expandida" class="expanded-img" />
    </div>
</template>

<style scoped>
.lugares-container {
    display: flex;
    flex-direction: column;
    gap: 2.5rem;
    width: 100%;
    padding: 0 1.5rem;
    max-width: 1600px;
    /* ← Añade padding horizontal aquí */
    margin: 2rem auto;
}

.lugar-card {
    position: relative;
    padding: 5rem 4rem;
    background: var(--arena);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    /* sin border-radius para que llegue a los bordes */
    width: 100vw;
    margin-left: calc(-50vw + 50%);
}

.lugar-card h2{
    text-align: center;
    margin-bottom: 2.5rem;
    font-size: 2rem;
    font-weight: bolder;
}

.section-title {
    margin-top: 2rem;
    text-align: center;
    font-size: 2.2rem;
    color: #182019;
    width: 100%;
}

.descripcion{
    font-size: 24px;
    text-align: center;
    font-style:italic;
    white-space: pre-line;
}
.imagenes {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    justify-content: center;
    width: 100%;
}


/* Wrapper de imagen con lupa */
.img-wrapper{
    position: relative;
    cursor:pointer;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    width: 100%;
    height: 300px;
}

.img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}


.img-wrapper::after {
    content: "👁";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    font-size: 1.5rem;
    color: white;
    background: rgba(0, 0, 0, 0.6);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    line-height: 60px;   /* ← centra verticalmente */
    text-align: center;  /* ← centra horizontalmente */
    transition: transform 0.3s ease;
    z-index: 2;
}

.img-wrapper:hover::after {
    transform: translate(-50%, -50%) scale(1.2);
}

.img-wrapper:hover img {
    transform: scale(1.03);
}


/* Lightbox */
.lightbox {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.92);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 5000;
    cursor:zoom-in;
}

.expanded-img {
    max-width: 90vw;
    max-height: 90vh;
    object-fit: contain;
    border: 4px solid white;
    border-radius: 8px;
}


/* Responsive */
/* Móvil */
@media (max-width: 768px) {
    .imagenes {
        flex-direction: column;
        align-items: center;
    }

    .img-wrapper {
        height: 220px;
        width: 100%;
        /* usa todo el ancho */
    }

    .img-wrapper::after {
        display: none;
    }

    .img-wrapper:hover img {
        transform: scale(1);
    }
}

@media (max-width: 1024px) {
    .imagenes {
        flex-direction: column;
        align-items: center;
    }

    .lugar-img {
        height: 200px;

    }

    .descripcion {
        font-size: 1rem;
    }
}
</style>