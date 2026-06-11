<script setup>
import { ref } from 'vue'
const flipped = ref(false)
</script>

<template>
    <div
        style="perspective: 1000px; display:flex; justify-content:center; align-items:center; height:100vh; background:#111;">
        <div @click="flipped = !flipped" :style="{
            width: '300px',
            height: '400px',
            position: 'relative',
            transformStyle: 'preserve-3d',
            transition: 'transform 0.8s',
            transform: flipped ? 'rotateY(180deg)' : 'rotateY(0deg)',
            cursor: 'pointer'
        }">
            <!-- FRENTE -->
            <div :style="{
                position: 'absolute', inset: 0,
                backfaceVisibility: 'hidden',
                WebkitBackfaceVisibility: 'hidden',
                background: 'green',
                borderRadius: '12px',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                color: 'white', fontSize: '2rem'
            }">FRENTE</div>

            <!-- REVERSO -->
            <div :style="{
                position: 'absolute', inset: 0,
                backfaceVisibility: 'hidden',
                WebkitBackfaceVisibility: 'hidden',
                transform: 'rotateY(180deg)',
                background: 'brown',
                borderRadius: '12px',
                display: 'flex', alignItems: 'center', justifyContent: 'center',
                color: 'white', fontSize: '2rem'
            }">REVERSO</div>
        </div>
    </div>
</template>