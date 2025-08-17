<template>
  <div class="floating-icons">
    <canvas ref="canvas"></canvas>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue"

const canvas = ref(null)
const mouse = ref({ x: null, y: null, radius: 100 })

onMounted(() => {
  const c = canvas.value
  const ctx = c.getContext("2d")
  c.width = window.innerWidth
  c.height = window.innerHeight

  const particles = []
  const particleCount = 50
  const maxDistance = 150

  // Créer les particules
  for (let i = 0; i < particleCount; i++) {
    particles.push({
      x: Math.random() * c.width,
      y: Math.random() * c.height,
      dx: (Math.random() - 0.7) * 4,  // vitesse par défaut
      dy: (Math.random() - 0.7) * 4,
      size: 5 + Math.random() * 15,
      glow: 5 + Math.random() * 10,
    })
  }

  // Événement souris
  window.addEventListener("mousemove", (e) => {
    mouse.value.x = e.clientX
    mouse.value.y = e.clientY
  })
  window.addEventListener("mouseout", () => {
    mouse.value.x = null
    mouse.value.y = null
  })

  function animate() {
    ctx.clearRect(0, 0, c.width, c.height)

    particles.forEach(p => {
      // Interaction avec la souris (dispersion)
      if (mouse.value.x && mouse.value.y) {
        const dx = p.x - mouse.value.x
        const dy = p.y - mouse.value.y
        const distance = Math.sqrt(dx * dx + dy * dy)
        if (distance < mouse.value.radius) {
          const force = (mouse.value.radius - distance) / mouse.value.radius
          const angle = Math.atan2(dy, dx)
          p.dx += Math.cos(angle) * force * 0.5
          p.dy += Math.sin(angle) * force * 0.5
        }
      }

      // Cercle vert foncé avec gradient
      const gradient = ctx.createRadialGradient(p.x, p.y, p.size * 0.1, p.x, p.y, p.size)
      gradient.addColorStop(0, "#004d00")
      gradient.addColorStop(0.5, "#003300")
      gradient.addColorStop(1, "#001a00")

      ctx.fillStyle = gradient
      ctx.shadowColor = "rgba(0,77,0,0.6)"
      ctx.shadowBlur = p.glow

      ctx.beginPath()
      ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2)
      ctx.fill()

      // Déplacement par défaut
      p.x += p.dx
      p.y += p.dy

      // Limiter la vitesse pour mouvement fluide
      p.dx *= 0.99
      p.dy *= 0.99

      // Rebondir sur les bords
      if (p.x < 0 || p.x > c.width) p.dx *= -1
      if (p.y < 0 || p.y > c.height) p.dy *= -1
    })

    // Lignes entre particules proches
    for (let i = 0; i < particleCount; i++) {
      for (let j = i + 1; j < particleCount; j++) {
        const dx = particles[i].x - particles[j].x
        const dy = particles[i].y - particles[j].y
        const dist = Math.sqrt(dx * dx + dy * dy)
        if (dist < maxDistance) {
          const alpha = 1 - dist / maxDistance
          ctx.strokeStyle = `rgba(0,77,0,${alpha})`
          ctx.lineWidth = 1
          ctx.beginPath()
          ctx.moveTo(particles[i].x, particles[i].y)
          ctx.lineTo(particles[j].x, particles[j].y)
          ctx.stroke()
        }
      }
    }

    requestAnimationFrame(animate)
  }

  animate()
})
</script>

<style scoped>
.floating-icons {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
}
canvas {
  width: 100%;
  height: 100%;
  display: block;
}
</style>
