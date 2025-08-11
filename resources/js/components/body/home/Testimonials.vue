<template>
  <div class="site-section bg-light">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-md-7 text-center">
          <h2 class="site-section-title">What Our Clients Say</h2>
        </div>
      </div>

      <div class="testimonial-carousel">
        <div class="testimonial-item" v-for="(testimonial, index) in testimonials" :key="index" v-show="index === currentIndex">
          <blockquote>
            <p>"{{ testimonial.text }}"</p>
          </blockquote>
          <p class="testimonial-author">
            — {{ testimonial.name }}, <em>{{ testimonial.position }}</em>
          </p>
        </div>

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
    text: "Great experience! The agents were professional and very helpful throughout the process.",
    name: "Alice Johnson",
    position: "Homeowner"
  },
  {
    text: "I found my dream apartment thanks to this team. Highly recommended!",
    name: "Mark Thompson",
    position: "Renter"
  },
  {
    text: "Excellent service and fantastic listings. Made buying a home easy and stress-free.",
    name: "Sara Lee",
    position: "Buyer"
  }
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
.site-section {
  padding: 3rem 0;
}

.testimonial-carousel {
  position: relative;
  max-width: 600px;
  margin: 0 auto;
  text-align: center;
}

.testimonial-item blockquote {
  font-style: italic;
  font-size: 1.2rem;
  margin-bottom: 1rem;
}

.testimonial-author {
  font-weight: bold;
  color: #555;
}

.testimonial-controls {
  margin-top: 1rem;
}

.testimonial-controls button {
  background: #2d995b;
  border: none;
  color: white;
  font-size: 1.2rem;
  padding: 0.5rem 1rem;
  margin: 0 0.5rem;
  cursor: pointer;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.testimonial-controls button:hover {
  background: #267a47;
}
</style>
