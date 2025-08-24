<template>
  <Particles
    id="tsparticles"
    :options="options"
  />
</template>

<script setup>
import Particles from "vue3-particles"

const options = {
  background: {
    color: {
      value: "#ffffff", // fond blanc
    },
  },
  fpsLimit: 60,
  interactivity: {
    events: {
      onHover: { enable: true, mode: "repulse" },
      resize: true,
    },
    modes: {
      repulse: { distance: 100, duration: 0.4 },
    },
  },
  particles: {
    color: {
      value: ["#004d00", "#003300", "#001a00"], // ✅ tes couleurs vert foncé
    },
    links: {
      color: "#004d00",
      distance: 150,
      enable: true,
      opacity: 0.5,
      width: 1,
    },
    collisions: { enable: true },
    move: {
      direction: "none",
      enable: true,
      outModes: { default: "bounce" },
      random: false,
      speed: 1.5,
      straight: false,
    },
    number: {
      density: { enable: true, area: 800 },
      value: 50,
    },
    opacity: { value: 0.5 },
    shape: { type: "circle" },
    size: { value: { min: 3, max: 8 } },
  },
  detectRetina: true,
}
</script>
