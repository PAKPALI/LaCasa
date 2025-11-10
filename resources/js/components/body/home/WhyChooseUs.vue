<template>
  <!-- Appel à l'action Début -->
  <section class="cta-section py-5 position-relative">
    <!-- Icônes flottants -->
    <i class="fa fa-home floating-icon icon-1"></i>
    <i class="fa fa-map-marker-alt floating-icon icon-2"></i>
    <i class="fa fa-key floating-icon icon-3"></i>

    <div class="container position-relative">
      <div class="rounded p-3">
        <div class="rounded-4 p-4 content-box">
          <div class="row g-5 align-items-center">
            <!-- Image -->
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
              <img
                class="img-fluid rounded-4 shadow-lg w-100"
                :src="ctaImage"
                alt="Agent immobilier"
              />
            </div>

            <!-- Texte -->
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
              <div class="mb-4 text-dark text-container">
                <h1 class="fw-bold mb-3 text-gradient">Pourquoi choisir LaCasa ?</h1>
                <p class="fs-5 text-muted">
                  <strong>LaCasa</strong> transforme la façon de chercher un logement au Togo.
                  Grâce à une plateforme intuitive, chaque utilisateur accède rapidement à des
                  annonces <strong>fiables, vérifiées et actualisées en temps réel</strong>.
                </p>

                <ul class="list-unstyled mt-4">
                  <li class="mb-3">
                    <i class="fa fa-check-circle text-success me-2"></i>
                    <strong>Fini les longues recherches sur le terrain : trouvez tout depuis votre téléphone.</strong>
                  </li>
                  <li class="mb-3">
                    <i class="fa fa-check-circle text-success me-2"></i>
                    <strong>Filtrez par ville, quartier, budget ou type de bien en quelques secondes.</strong>
                  </li>
                  <li class="mb-3">
                    <i class="fa fa-check-circle text-success me-2"></i>
                    <strong>Des agents partenaires certifiés pour un accompagnement personnalisé.</strong>
                  </li>
                </ul>
                <router-link to='/about' class="nav-link">
                  <a href="#" class="btn btn-primary py-3 px-4 mt-3 shadow-lg cta-btn">
                    <i class="fa fa-plus me-2"></i> En savoir plus
                  </a>
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Appel à l'action Fin -->
</template>

<script setup>
import ctaImage from '@images2/son.avif'
</script>

<style scoped>
/* --- Section principale --- */
.cta-section {
  background: radial-gradient(
    circle at top left,
    rgba(0, 185, 142, 0.15),
    rgba(255, 255, 255, 0.9)
  );
  position: relative;
  overflow: hidden;
  transition: all 0.6s ease;
}

/* --- Boîte de contenu --- */
.content-box {
  background: rgba(255, 255, 255, 0.9);
  border: 1px dashed rgba(0, 185, 142, 0.3);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(6px);
  transition: all 0.8s ease;
}

/* --- Texte et boutons --- */
.text-gradient {
  background: linear-gradient(90deg, #00b98e, #007f6c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.cta-btn {
  background: linear-gradient(90deg, #00b98e, #007f6c);
  border: none;
  transition: all 0.5s ease;
}
.cta-btn:hover {
  background: linear-gradient(90deg, #fff, #fff);
  color: #007f6c !important;
}

/* --- Effet hover global (inversion des couleurs) --- */
.content-box:hover {
  background: linear-gradient(120deg, #00b98e, #007f6c);
  color: #fff !important;
  border: 1px solid rgba(255, 255, 255, 0.4);
  transform: scale(1.02);
  box-shadow: 0 15px 35px rgba(0, 185, 142, 0.3);
}

.content-box:hover .text-gradient,
.content-box:hover p,
.content-box:hover li,
.content-box:hover strong {
  color: #fff !important;
  -webkit-text-fill-color: #fff !important;
}

.content-box:hover .fa-check-circle {
  color: #fff !important;
}

.content-box:hover .cta-btn {
  background: #fff;
  color: #00b98e !important;
}

/* --- Icônes flottants --- */
.floating-icon {
  position: absolute;
  font-size: 2.5rem;
  color: rgba(3, 84, 65, 0.25);
  z-index: 0;
  animation: floatIcon 8s ease-in-out infinite;
}

.icon-1 { top: 10%; left: 5%; animation-delay: 0s; }
.icon-2 { top: 50%; right: 10%; animation-delay: 3s; }
.icon-3 { bottom: 2%; left: 40%; animation-delay: 5s; }

@keyframes floatIcon {
  0%, 100% { transform: translateY(0) rotate(0deg); }
  50% { transform: translateY(-15px) rotate(10deg); }
}

/* --- Responsive --- */
@media (max-width: 992px) {
  .text-container {
    text-align: center;
  }
  .content-box {
    padding: 2rem;
  }
}
@media (max-width: 576px) {
  .content-box {
    background: rgba(255, 255, 255, 0.95);
  }
  .floating-icon {
    display: none;
  }
  .cta-btn {
    width: 100%;
  }
}
</style>
