<template>
  <div class="floating-icons">
    <canvas ref="canvas"></canvas>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue"

const canvas = ref(null)
const mouse = ref({ x: null, y: null, radius: 80 })

onMounted(() => {
  const c = canvas.value
  const ctx = c.getContext("2d")

  function resizeCanvas() {
    c.width = window.innerWidth
    c.height = window.innerHeight
  }
  resizeCanvas()
  window.addEventListener("resize", resizeCanvas)

  const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent)
  const cores = navigator.hardwareConcurrency || 4

  let particleCount = isMobile ? 20 : 50
  let maxDistance = isMobile ? 80 : 150

  if (cores <= 2) { particleCount = isMobile ? 10 : 20; maxDistance = 60 }
  else if (cores <= 4) { particleCount = isMobile ? 15 : 35; maxDistance = isMobile ? 80 : 100 }

  const particles = []

  // Création progressive des particules
  let created = 0
  const batchSize = 5
  function initBatch() {
    for (let i = 0; i < batchSize && created < particleCount; i++, created++) {
      particles.push({
        x: Math.random() * c.width,
        y: Math.random() * c.height,
        dx: (Math.random() - 0.5) * (isMobile ? 1.5 : 2.5),
        dy: (Math.random() - 0.5) * (isMobile ? 1.5 : 2.5),
        size: (isMobile ? 3 : 4) + Math.random() * (isMobile ? 5 : 10),
        glow: (isMobile ? 1 : 3) + Math.random() * (isMobile ? 2 : 5),
      })
    }
    if (created < particleCount) requestAnimationFrame(initBatch)
  }
  initBatch()

  // Souris
  window.addEventListener("mousemove", e => { mouse.value.x = e.clientX; mouse.value.y = e.clientY })
  window.addEventListener("mouseout", () => { mouse.value.x = null; mouse.value.y = null })

  // Animation
  function animate() {
    ctx.clearRect(0, 0, c.width, c.height)

    particles.forEach(p => {
      // Interaction souris
      if (mouse.value.x && mouse.value.y) {
        const dx = p.x - mouse.value.x
        const dy = p.y - mouse.value.y
        const dist = Math.sqrt(dx * dx + dy * dy)
        if (dist < mouse.value.radius) {
          const force = (mouse.value.radius - dist) / mouse.value.radius
          const angle = Math.atan2(dy, dx)
          p.dx += Math.cos(angle) * 0.3
          p.dy += Math.sin(angle) * 0.3
        }
      }

      const gradient = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.size)
      gradient.addColorStop(0, "#004d00")
      gradient.addColorStop(1, "#001a00")
      ctx.fillStyle = gradient
      ctx.shadowColor = "rgba(0,77,0,0.5)"
      ctx.shadowBlur = p.glow

      ctx.beginPath()
      ctx.arc(p.x, p.y, p.size, 0, Math.PI*2)
      ctx.fill()

      p.x += p.dx; p.y += p.dy
      p.dx *= 0.98; p.dy *= 0.98

      if (p.x < 0 || p.x > c.width) p.dx *= -1
      if (p.y < 0 || p.y > c.height) p.dy *= -1
    })

    // Lignes si appareil puissant
    if (!isMobile || cores > 2) {
      for (let i = 0; i < particles.length; i++) {
        for (let j = i+1; j < particles.length; j++) {
          const dx = particles[i].x - particles[j].x
          const dy = particles[i].y - particles[j].y
          const dist = Math.sqrt(dx*dx + dy*dy)
          if (dist < maxDistance) {
            ctx.strokeStyle = `rgba(0,77,0,${1 - dist/maxDistance})`
            ctx.lineWidth = 1
            ctx.beginPath()
            ctx.moveTo(particles[i].x, particles[i].y)
            ctx.lineTo(particles[j].x, particles[j].y)
            ctx.stroke()
          }
        }
      }
    }

    requestAnimationFrame(animate)
  }

  // Lancer l'animation immédiatement
  requestAnimationFrame(animate)
})
</script>

<style scoped>
.floating-icons {
  position: fixed;
  top:0; left:0;
  width:100%; height:100%;
  z-index:-1;
}
canvas {
  width:100%;
  height:100%;
  display:block;
}
</style>
