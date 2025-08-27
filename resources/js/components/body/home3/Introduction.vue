<template>
  <div class="site-section testimonials-section">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-md-7 text-center">
          <h2 class="site-section-title">Plateforme immobilière moderne</h2>
        </div>
      </div>

      <div class="testimonial-carousel">
        <transition name="fade" mode="out-in">
          <div
            :key="`testimonial-${currentIndex}`"
            class="testimonial-item"
          >
            <blockquote>
              <h6><strong>"{{ testimonials[currentIndex].text }}"</strong></h6>
            </blockquote>
            <p class="testimonial-author">
              {{ testimonials[currentIndex].name }}
              <em>{{ testimonials[currentIndex].position }}</em>
            </p>
          </div>
        </transition>

        <div class="testimonial-controls">
          <button @click="prev" aria-label="Previous testimonial">&lt;</button>
          <button @click="next" aria-label="Next testimonial">&gt;</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const testimonials = [
  {
    text: "LaCasa est une plateforme web (et futur mobile) dédiée à la mise en relation entre démarcheurs immobiliers et clients au Togo...",
    name: "1",
  },
  {
    text: "Le nom LaCasa a été choisi car il évoque les origines et le lien fort avec la notion de « chez soi »...",
    name: "2",
  },
]

const currentIndex = ref(0)

function next() {
  currentIndex.value = (currentIndex.value + 1) % testimonials.length
}

function prev() {
  currentIndex.value = (currentIndex.value - 1 + testimonials.length) % testimonials.length
}
</script>

<style scoped>
.testimonials-section {
  padding: 3rem 0;
  border-radius: 20px;
  color: rgb(0, 0, 0);
}

h2 {
  font-size: 2.2rem;
  font-weight: bold;
  color: #fff;
  padding: 15px 30px;
  border-radius: 12px;
  text-align: center;
  background: linear-gradient(135deg, #122f02, rgb(0, 0, 0) 70%, #0e2501d9);
  /* 🔥 Ombre allégée */
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  display: inline-block;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

blockquote p {
  position: relative;
  display: inline-block;
  padding: 20px 25px;
  font-size: 1.05rem;
  color: #fff;
  background: linear-gradient(135deg, rgb(22, 52, 3), rgba(46, 111, 9, 0.9));
  border-radius: 15px;
  /* 🔥 Ombre simplifiée */
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
  text-align: center;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  /* ❌ supprimé backdrop-filter */
}

.testimonial-carousel {
  position: relative;
  max-width: 600px;
  margin: 0 auto;
  text-align: center;
}

.testimonial-item blockquote {
  font-style: italic;
  font-size: 1.1rem;
  margin-bottom: 1rem;
}

.testimonial-author {
  font-weight: bold;
  color: #0f6e2d;
}

.testimonial-controls {
  margin-top: 1rem;
}

.testimonial-controls button {
  background: #1d4a03;
  border: none;
  color: white;
  font-size: 1.2rem;
  padding: 0.5rem 1rem;
  margin: 0 0.5rem;
  cursor: pointer;
  border-radius: 6px;
  transition: background-color 0.3s;
}

.testimonial-controls button:hover {
  background: #267a47;
}

/* Animation optimisée */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* 🔥 Boost GPU pour fluidité */
.testimonial-item {
  will-change: opacity, transform;
}
</style>
