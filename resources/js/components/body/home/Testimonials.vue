<template>
  <div class="container-xxl py-5 testimonials-section">
    <div class="container">
      <!-- Titre section -->
      <div class="text-center mx-auto mb-5 section-header">
        <h1 class="mb-3 fw-bold">Témoignages</h1>
        <p>Découvrez ce que nos utilisateurs disent de <strong>LaCasa</strong></p>
      </div>

      <!-- Carousel -->
      <div class="carousel shadow-lg rounded">
        <div
          class="carousel-item"
          v-for="(t, i) in testimonials"
          :key="i"
          :class="{ active: i === currentIndex }"
        >
          <p class="message">"{{ t.message }}"</p>
          <h6 class="name">{{ t.name }}</h6>
          <small class="role">{{ t.role }}</small>
        </div>

        <!-- Boutons -->
        <button class="carousel-btn prev" @click="prevSlide">&#10094;</button>
        <button class="carousel-btn next" @click="nextSlide">&#10095;</button>
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

onMounted(() => {
  setInterval(nextSlide, 5000)
})
</script>

<style scoped>
/* --- SECTION GLOBALE --- */
.testimonials-section {
  background: rgba(255, 255, 255, 0.5);
  border-radius: 20px;
  backdrop-filter: blur(1px);
  transition: all 0.6s ease;
  padding: 60px 0;
}

/* --- HEADER SECTION --- */
.section-header h1 {
  font-size: 2.2rem;
  background: linear-gradient(90deg, #00b98e, #007f6c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  transition: all 0.5s ease;
}

.section-header p {
  color: #333;
  transition: all 0.5s ease;
}

/* Quand on survole toute la section */
.testimonials-section:hover {
  background: linear-gradient(135deg, #00b98e, #007f6c);
  color: #fff;
  box-shadow: 0 15px 35px rgba(0, 185, 142, 0.3);
}

/* Rendre le texte extérieur blanc */
.testimonials-section:hover .section-header h1,
.testimonials-section:hover .section-header p,
.testimonials-section:hover .section-header strong {
  color: #fff !important;
  -webkit-text-fill-color: #fff !important;
  background: none;
}

/* --- CAROUSEL --- */
.carousel {
  position: relative;
  max-width: 700px;
  width: 90%;
  margin: 0 auto;
  overflow: hidden;
  padding: 40px 20px;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 15px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  transition: all 0.6s ease;
}

/* Quand la section est survolée → le fond devient translucide */
.testimonials-section:hover .carousel {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.5);
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
  line-height: 1.6;
  transition: color 0.4s ease;
}

.name {
  font-weight: 600;
  color: #006400;
  margin-bottom: 5px;
  transition: color 0.4s ease;
}

.role {
  font-size: 0.9rem;
  color: #666;
  transition: color 0.4s ease;
}

/* Quand survolé, texte du carousel devient blanc */
.testimonials-section:hover .message,
.testimonials-section:hover .name,
.testimonials-section:hover .role {
  color: #fff !important;
}

/* --- BOUTONS --- */
.carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: linear-gradient(135deg, #00b98e, #007f6c);
  color: white;
  border: none;
  padding: 12px;
  cursor: pointer;
  font-size: 22px;
  border-radius: 90%;
  transition: all 0.3s ease;
  z-index: 2;
}

.carousel-btn.prev { left: 5px; }
.carousel-btn.next { right: 5px; }

.carousel-btn:hover {
  transform: translateY(-50%) scale(1.2);
  background: white;
  color: #00b98e;
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.4);
}

/* --- RESPONSIVE --- */
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
