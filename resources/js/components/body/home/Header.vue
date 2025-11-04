<template>
  <header class="header-section mt-5">
    <!-- Fond tonnerre animé -->
    <div class="thunder-background">
      <div class="flash" v-for="n in 3" :key="n"></div>
    </div>

    <div class="container-fluid p-0 h-100 position-relative">
      <div class="row g-0 align-items-center h-100 flex-column-reverse flex-md-row">

        <!-- Texte animé -->
        <div class="col-md-6 p-5 mt-lg-5">
          <div class="text-content floating">
            <h1 class="display-5 fw-bold mb-3 fade-in">
              Trouvez <span class="text-primary">la maison idéale</span><br />
              pour vous et votre famille
            </h1>
            <h6 class="lead text-dark fade-in delay-1">
              LaCasa simplifie la recherche de logements au Togo.<br />
              Découvrez des annonces fiables, récentes et adaptées à vos besoins.
            </h6>
            <!-- <a href="#" class="btn btn-primary py-3 px-5 mt-3 shadow-lg fade-in delay-2">
              Commencer la recherche
            </a> -->
          </div>
        </div>

        <!-- Carousel d'images -->
        <div class="col-md-6 animated fadeIn">
          <div class="owl-carousel header-carousel">
            <div class="owl-carousel-item">
              <img class="img-fluid rounded-end" :src="carousel1" alt="Maison moderne" />
            </div>
            <div class="owl-carousel-item">
              <img class="img-fluid rounded-end" :src="carousel2" alt="Appartement élégant" />
            </div>
          </div>
        </div>

      </div>
    </div>
  </header>
</template>

<script setup>
import carousel1 from '@images2/carousel-1.jpg'
import carousel2 from '@images2/carousel-2.jpg'
</script>

<style scoped>
.header-section {
  position: relative;
  height: 100vh;
  overflow: hidden;
  background: linear-gradient(135deg, rgba(0, 0, 0, 0.3), rgba(10, 10, 10, 0.9));
  color: #fff;
}

/* === FOND TONNERRE ANIMÉ === */
.thunder-background {
  position: absolute;
  inset: 0;
  background: url('https://images.unsplash.com/photo-1656646549734-6e5f90a1a68a?ixlib=rb-4.1.0&auto=format&fit=crop&q=80&w=1171')
    center/cover no-repeat;
  z-index: 0;
  animation: backgroundMove 30s linear infinite;
  filter: brightness(0.6);
}

.flash {
  position: absolute;
  inset: 0;
  background: radial-gradient(circle, rgba(255,255,255,0.9) 0%, transparent 70%);
  opacity: 0;
  animation: flashThunder 12s infinite;
}

.flash:nth-child(2) { animation-delay: 5s; }
.flash:nth-child(3) { animation-delay: 9s; }

@keyframes flashThunder {
  0%, 97%, 100% { opacity: 0; }
  98% { opacity: 1; }
  99% { opacity: 0.3; }
}

@keyframes backgroundMove {
  0% { background-position: center; }
  50% { background-position: top right; }
  100% { background-position: center; }
}

/* === TEXTE === */
.text-content {
  position: relative;
  z-index: 2;
  background: rgba(255, 255, 255, 0.7);
  border-radius: 20px;
  padding: 3rem;
  box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  backdrop-filter: blur(2px);
}

/* effet flottant aléatoire */
.floating {
  animation: floatRandom 15s ease-in-out infinite;
}

@keyframes floatRandom {
  0% { transform: translate(0px, 0px) rotate(0deg); }
  10% { transform: translate(3px, -5px) rotate(1deg); }
  20% { transform: translate(-2px, 4px) rotate(-1deg); }
  30% { transform: translate(4px, -2px) rotate(0.5deg); }
  40% { transform: translate(-3px, 3px) rotate(-0.5deg); }
  50% { transform: translate(2px, -3px) rotate(1deg); }
  60% { transform: translate(-4px, 5px) rotate(-1deg); }
  70% { transform: translate(5px, -4px) rotate(0.5deg); }
  80% { transform: translate(-3px, 2px) rotate(-0.5deg); }
  90% { transform: translate(4px, -1px) rotate(1deg); }
  100% { transform: translate(0px, 0px) rotate(0deg); }
}

/* Animation d’apparition du texte */
.fade-in {
  opacity: 0;
  transform: translateY(15px);
  animation: fadeInUp 1s forwards;
}

.fade-in.delay-1 { animation-delay: 0.5s; }
.fade-in.delay-2 { animation-delay: 1s; }

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.text-primary {
  color: #00b98e !important;
}

/* Responsive */
@media (max-width: 768px) {
  .header-section { height: auto; padding-bottom: 2rem; }
  .text-content { text-align: center; }
}
</style>
