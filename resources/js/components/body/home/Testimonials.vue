<template>
  <div class="container-xxl py-5">
    <div class="container">
      <!-- Titre section -->
      <div class="text-center mx-auto mb-5" style="max-width: 600px;">
        <h1 class="mb-3">Témoignages</h1>
        <p>Découvrez ce que nos utilisateurs disent de LaCasa</p>
      </div>

      <!-- Carousel -->
      <div class="carousel shadow-lg rounded bg-white">
        <div class="carousel-item" v-for="(t, i) in testimonials" :key="i" :class="{ active: i === currentIndex }">
          <p class="message">"{{ t.message }}"</p>
          <h6 class="name">{{ t.name }}</h6>
          <small class="role">{{ t.role }}</small>
        </div>

        <!-- Boutons -->
        <button class="carousel-btn prev" @click="prevSlide">&#10094;</button>
        <button class="carousel-btn next" @click="nextSlide">&#10095;</button>

        <!-- Dots -->
        <!-- <div class="carousel-dots">
          <span 
            v-for="(t, i) in testimonials" 
            :key="i" 
            :class="{ active: i === currentIndex }" 
            @click="currentIndex = i"
          ></span>
        </div> -->
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const testimonials = [
  { name: "Jean K.", role: "Client", message: "J’ai trouvé un appartement en 2 jours grâce à LaCasa !" },
  { name: "Amina D.", role: "Démarcheuse", message: "La plateforme est simple et m’aide à publier mes annonces facilement." },
  { name: "Paul M.", role: "Client", message: "Service rapide et efficace, je recommande LaCasa !" }
]

const currentIndex = ref(0)

const nextSlide = () => {
  currentIndex.value = (currentIndex.value + 1) % testimonials.length
}

const prevSlide = () => {
  currentIndex.value = (currentIndex.value - 1 + testimonials.length) % testimonials.length
}

// Auto-slide toutes les 5 secondes
onMounted(() => {
  setInterval(nextSlide, 5000)
})
</script>

<style scoped>
.carousel {
  position: relative;
  max-width: 700px;
  width: 90%; /* rend le carousel flexible */
  margin: 0 auto;
  overflow: hidden;
  padding: 40px 20px;
  background-color: #f9f9f9;
  border-radius: 15px;
}

/* Slide */
.carousel-item {
  display: none;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  transition: all 0.5s ease;
}

.carousel-item.active {
  display: flex;
}

.message {
  font-size: 1.1rem;
  color: #333;
  margin-bottom: 15px;
  font-style: italic;
}

.name {
  font-weight: 600;
  color: #006400;
  margin-bottom: 5px;
}

.role {
  font-size: 0.9rem;
  color: #666;
}

/* Boutons */
.carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: rgba(0, 100, 0, 0.6);
  color: white;
  border: none;
  padding: 12px;
  cursor: pointer;
  font-size: 22px;
  border-radius: 50%;
  transition: all 0.3s ease;
  z-index: 2;
}

.carousel-btn.prev { left: 5px; }
.carousel-btn.next { right: 5px; }

.carousel-btn:hover {
  background-color: rgba(0, 100, 0, 0.9);
}

/* Dots */
.carousel-dots {
  text-align: center;
  margin-top: 20px;
}

.carousel-dots span {
  display: inline-block;
  width: 12px;
  height: 12px;
  margin: 0 6px;
  background-color: #ccc;
  border-radius: 50%;
  cursor: pointer;
  transition: all 0.3s ease;
}

.carousel-dots span.active {
  background-color: #006400;
  transform: scale(1.2);
}

/* RESPONSIVE */
@media (max-width: 992px) {
  .carousel {
    padding: 30px 15px;
  }

  .message {
    font-size: 1rem;
  }
}

@media (max-width: 768px) {
  .carousel {
    width: 95%;
    padding: 20px 10px;
  }

  .carousel-btn {
    padding: 10px;
    font-size: 20px;
  }

  .message {
    font-size: 0.95rem;
  }
}

@media (max-width: 480px) {
  .carousel {
    width: 100%;
    padding: 15px 5px;
  }

  .carousel-btn {
    padding: 8px;
    font-size: 18px;
  }

  .message {
    font-size: 0.9rem;
  }
}

</style>
