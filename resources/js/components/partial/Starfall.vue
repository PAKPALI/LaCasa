<template>
  <canvas ref="canvas" class="starfall-canvas"></canvas>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const canvas = ref(null)
let ctx, animationId
let stars = []

const MAX_STARS_DESKTOP = 200
const MAX_STARS_MOBILE = 100

// Initialisation des étoiles
function initStars(width, height) {
  const isMobile = /Mobi|Android/i.test(navigator.userAgent)
  const starCount = isMobile ? MAX_STARS_MOBILE : MAX_STARS_DESKTOP
  stars = []

  for (let i = 0; i < starCount; i++) {
    stars.push({
      x: Math.random() * width - width / 2,
      y: Math.random() * height - height / 2,
      z: Math.random() * width,
      r: Math.random() * 1.5 + 0.5,
      speed: Math.random() * 2 + 0.5
    })
  }
}

// Animation 3D
function animateStars(width, height) {
  // Fond blanc
  ctx.fillStyle = '#00b88d'
  ctx.fillRect(0, 0, width, height)

  stars.forEach(star => {
    // perspective : plus proche -> plus grand et plus rapide
    const scale = 1 / (star.z * 0.001 + 0.001)
    const px = width / 2 + star.x * scale
    const py = height / 2 + star.y * scale
    const pr = star.r * scale * 3

    // Dessin étoile noire
    ctx.fillStyle = '#0e2e50'
    ctx.beginPath()
    ctx.arc(px, py, pr, 0, Math.PI * 2)
    ctx.fill()

    // Mouvement vers la caméra
    star.z -= star.speed
    if (star.z <= 0) {
      star.z = width
      star.x = Math.random() * width - width / 2
      star.y = Math.random() * height - height / 2
    }

    // Optionnel : léger mouvement diagonal pour effet 3D dynamique
    star.x += star.speed * 0.05
    star.y += star.speed * 0.025
  })
}

// Boucle d'animation
function animateLoop() {
  if (!canvas.value) return
  const width = canvas.value.width
  const height = canvas.value.height

  animateStars(width, height)
  animationId = requestAnimationFrame(animateLoop)
}

onMounted(() => {
  const c = canvas.value
  if (!c) return
  ctx = c.getContext('2d')

  const resizeCanvas = () => {
    c.width = window.innerWidth
    c.height = window.innerHeight
    initStars(c.width, c.height)
  }

  resizeCanvas()
  window.addEventListener('resize', resizeCanvas)
  animateLoop()

  onBeforeUnmount(() => {
    cancelAnimationFrame(animationId)
    window.removeEventListener('resize', resizeCanvas)
  })
})
</script>

<style scoped>
.starfall-canvas {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: -1;          /* arrière-plan */
  pointer-events: none;
}
</style>
