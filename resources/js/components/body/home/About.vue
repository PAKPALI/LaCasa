<template>
  <!-- À propos Début -->
  <section ref="aboutSection" class="about-section container-xxl py-5 mt-5">
    <div class="container">
      <div class="row g-5 align-items-center content-box">
        <!-- Image -->
        <div class="col-lg-6 wow fadeIn" data-wow-delay="3s">
          <div class="position-relative overflow-hidden rounded-4 shadow-sm">
            <img
              class="img-fluid w-100 rounded-4"
              :src="aboutImage"
              alt="À propos LaCasa"
              style="transition: transform 0.5s;"
              @mouseover="hoverImage = true"
              @mouseleave="hoverImage = false"
              :style="{ transform: hoverImage ? 'scale(1.05)' : 'scale(1)' }"
            />
          </div>
        </div>

        <!-- Texte -->
        <div class="col-lg-6 wow fadeIn text-content" data-wow-delay="0.5s">
          <h1 class="mb-4 fw-bold text-gradient">
            <strong>Trouvez rapidement le logement qui vous correspond</strong>
          </h1>
          <h6 class="mb-4 fs-5">
            LaCasa met à votre disposition une plateforme fiable et intuitive, 
            avec un large choix de logements adaptés à vos besoins : location, achat ou investissement. 
            Nos annonces sont vérifiées pour vous offrir une expérience transparente et sécurisée.
          </h6>

          <!-- Features -->
          <div class="mb-3 d-flex align-items-start gap-3" v-for="(feature, idx) in features" :key="idx">
            <i :class="feature.icon + ' floating-icon'"></i>
            <p class="mb-0 fw-semibold typing-text">
              <strong>{{ animatedTexts[idx] }}</strong>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- À propos Fin -->
</template>

<script setup>
import { ref, onMounted } from 'vue'
import aboutImage from '@images2/about.jpg'

const hoverImage = ref(false)

const features = [
  { icon: 'fa fa-check-circle', text: 'Des annonces vérifiées et fiables' },
  { icon: 'fa fa-user-tie', text: 'Des agents expérimentés et disponibles' },
  { icon: 'fa fa-search', text: 'Recherche intelligente et personnalisée' },
  { icon: 'fa fa-mobile-alt', text: 'Accessible depuis mobile et desktop' },
]

const animatedTexts = ref(features.map(() => ""))

const aboutSection = ref(null)

// Animation "machine à écrire"
function startTypeWriter() {
  features.forEach((feature, idx) => {
    let charIndex = 0
    let paused = false

    const typeWriter = () => {
      if (!paused) {
        if (charIndex <= feature.text.length) {
          animatedTexts.value[idx] = feature.text.slice(0, charIndex)
          charIndex++
        } else {
          paused = true
          setTimeout(() => {
            charIndex = 0
            animatedTexts.value[idx] = ""
            paused = false
          }, 3000)
        }
      }
    }

    setInterval(typeWriter, 100 + idx * 50)
  })
}

onMounted(() => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          startTypeWriter()
          observer.disconnect()
        }
      })
    },
    { threshold: 0.5 }
  )

  if (aboutSection.value) observer.observe(aboutSection.value)
})
</script>

<style scoped>
/* --- Section principale --- */
.about-section {
  border-radius: 15px;
  background: rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(1px);
  transition: all 0.6s ease;
  overflow: hidden;
}

/* --- Contenu principal --- */
.content-box {
  border-radius: 15px;
  transition: all 0.6s ease;
  border: 1px dashed rgba(0, 185, 142, 0.3);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  padding: 1.5rem;
}

/* --- Effet de survol section --- */
.about-section:hover {
  background: linear-gradient(135deg, #00b98e, #007f6c);
  color: white;
}

.about-section:hover .text-content,
.about-section:hover h1,
.about-section:hover h6,
.about-section:hover strong,
.about-section:hover .typing-text {
  color: #fff !important;
}

.about-section:hover .text-gradient {
  -webkit-text-fill-color: #fff;
  background: none;
}

.about-section:hover .floating-icon {
  color: #fff !important;
}

/* --- Titre avec effet gradient --- */
.text-gradient {
  background: linear-gradient(90deg, #00b98e, #007f6c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  transition: all 0.5s ease;
}

/* --- Icônes flottantes --- */
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-8px); }
  100% { transform: translateY(0px); }
}

.floating-icon {
  font-size: 1.5rem;
  color: #00b98e;
  animation: float 3s ease-in-out infinite;
  transition: transform 0.3s, color 0.3s;
}

/* --- Machine à écrire --- */
.typing-text {
  border-right: 2px solid #7ab7a8;
  white-space: nowrap;
  overflow: hidden;
  padding-right: 2px;
}

/* --- Responsive --- */
@media (max-width: 768px) {
  .about-section {
    text-align: center;
  }

  /* .floating-icon {
    display: none;
  } */
}
</style>
