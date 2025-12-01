<template>
    <Starfall2 />
  <section class="a-propos-section mt-5">
    <!-- Titre principal -->
    <div class="container text-center mb-5 animate-on-scroll">
      <h1 class="fw-bold display-5 text-gradient mb-3">
        À propos de <span class="text-primary">LaCasa</span>
      </h1>
      <div class="lead text-muted">
        <h4>Bouger simplement — La plateforme qui transforme la recherche de logement au Togo.</h4>
      </div>

    </div>

    <!-- Introduction -->
    <div class="container intro-section animate-on-scroll">
      <div class="row align-items-center">
        <div class="col-md-6 floating-img">
          <img src="@images/immo1.avif" class="img-fluid rounded-4 shadow-lg" alt="LaCasa Illustration" />
        </div>
        <div class="col-md-6 ps-md-5 mt-4 mt-md-0">
          <h2 class="fw-bold text-primary mb-3"><strong>Notre mission</strong></h2>
          <p>
            LaCasa est une plateforme web qui connecte les
            <strong>démarcheurs immobiliers</strong> et les <strong>clients</strong> au Togo.
            Elle facilite la <strong>publication</strong> et la <strong>consultation</strong>
            d’annonces de logements à louer ou à vendre.
          </p>
          <p>
            Nous souhaitons numériser un processus encore manuel et vulnérable, pour offrir
            un outil moderne, sécurisé et simple d’utilisation, adapté aux réalités locales.
          </p>
        </div>
      </div>
    </div>

    <!-- Origine du nom -->
    <div class="container my-5 animate-on-scroll origin-section">
      <div class="row align-items-center flex-md-row-reverse">
        <div class="col-md-6 floating-img">
          <img src="@images/city.avif" class="img-fluid rounded-4 shadow-lg" alt="Origine du nom LaCasa" />
        </div>
        <div class="col-md-6 pe-md-5 mt-4 mt-md-0">
          <h2 class="fw-bold text-primary mb-3"><strong>Pourquoi “LaCasa” ?</strong></h2>
          <p>
            Le nom <strong>LaCasa</strong> a été choisi car il évoque les origines et le lien fort
            avec la notion de <em>« chez soi »</em>. Il rappelle la simplicité et la chaleur d’un foyer,
            inspiré par les maisons traditionnelles de village où ont dormi les grands-parents.
          </p>
          <p>
            Ce nom symbolise notre engagement à faciliter la recherche rapide et fiable d’un logement
            adapté, pour que chacun puisse trouver facilement <strong>sa maison</strong>, son espace de vie,
            en toute confiance et pouvoir bouger tout simplement.
          </p>
        </div>
      </div>
    </div>

    <!-- Problématique & Solution -->
    <div class="container my-5 animate-on-scroll">
      <div class="row g-4">
        <div class="col-md-6">
          <div class="card shadow-sm border-0 h-100 hover-card">
            <div class="card-body">
              <h4 class="fw-bold text-light mb-3">Problématique</h4>
              <p>
                La recherche de logements repose encore sur des tableaux physiques exposés dans la rue,
                vulnérables aux intempéries, sans mise à jour ni fiabilité. Les clients doivent se déplacer
                pour consulter les offres, souvent obsolètes.
              </p>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card shadow-sm border-0 h-100 hover-card">
            <div class="card-body">
              <h4 class="fw-bold text-light mb-3">Notre solution</h4>
              <p>
                Une plateforme numérique simple et intuitive où les démarcheurs publient des annonces
                détaillées : <strong>photos, prix, localisation, disponibilité</strong>.  
                Les clients peuvent filtrer.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Vision -->
    <div class="vision-section text-center text-white py-5 mt-5 animate-on-scroll">
      <div class="container">
        <h2 class="fw-bold mb-3 text-light">Notre Vision</h2>
        <p class="lead mb-4">
          Devenir la <strong>référence de la location immobilière en ligne</strong> au Togo et en Afrique de l’Ouest,
          avec une plateforme fiable, rapide et respectueuse des réalités locales.
        </p>
        <blockquote class="blockquote">
          <p class="mb-0">« Trouver sa maison n’a jamais été aussi simple. »</p>
        </blockquote>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted } from 'vue'
import Starfall2 from '../../partial/Starfall2.vue'
import { useRouter } from 'vue-router'

/* ✨ Animation d'apparition au scroll */
onMounted(() => {
  const elements = document.querySelectorAll('.animate-on-scroll')
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible')
          observer.unobserve(entry.target)
        }
      })
    },
    { threshold: 0.2 }
  )
  elements.forEach((el) => observer.observe(el))
})
</script>

<style scoped>
/* ---- Cartes ---- */
.hover-card {
  border-radius: 16px;
  background: linear-gradient(145deg, #00b98e, #009e7a);
  color: #fff;
  transition: all 0.5s ease;
  box-shadow: 0 10px 25px rgba(0, 80, 60, 0.4);
}

.hover-card:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 20px 40px rgba(0, 80, 60, 0.55);
}

.hover-card .card-body h4 {
  color: #ffffff;
}

.hover-card .card-body p {
  color: #f1fdf8;
}


.a-propos-section {
  /* background: linear-gradient(180deg, #f8fdfc 0%, #ffffff 100%); */
  background: rgba(14, 107, 79, 0.1);
  overflow: hidden;
  color: #333;
  padding-top: 60px;
}

/* ---- Apparition au scroll ---- */
.animate-on-scroll {
  opacity: 0;
  transform: translateY(60px);
  transition: opacity 1s ease, transform 1s ease;
}
.animate-on-scroll.visible {
  opacity: 1;
  transform: translateY(0);
}

/* ---- Effet flottant sur les images ---- */
.floating-img img {
  animation: floating 2s ease-in-out infinite;
  background: transparent;
}
@keyframes floating {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-8px); }
}

/* ---- Cartes ---- */
/* ---- Cartes ---- */
.hover-card {
  border-radius: 16px;
  background: linear-gradient(145deg, #00b98e, #009e7a);
  color: #fff;
  transition: all 0.5s ease;
  box-shadow: 0 10px 25px rgba(0, 80, 60, 0.4);
}

.hover-card:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 20px 40px rgba(0, 80, 60, 0.55);
  background: rgba(14, 107, 79, 0.7);
}

.hover-card .card-body h4 {
  color: #ffffff;
}

.hover-card .card-body p {
  color: #f1fdf8;
}


/* ---- Vision ---- */
.vision-section {
  background: linear-gradient(135deg, #00b98e, #007f6c);
  border-radius: 24px 24px 0 0;
  position: relative;
  overflow: hidden;
  background: rgba(14, 107, 79, 0.95);
}
@keyframes bgPulse {
  0%, 100% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
}

.vision-section::before {
  content: "";
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: url('@images/city.avif') center/cover;
  opacity: 0.25;
  z-index: 0;
}

.vision-section .container {
  position: relative;
  z-index: 1;
  animation: fadeIn 1.5s ease forwards;
}

/* ---- TEXT ---- */
.text-gradient {
  background: linear-gradient(90deg, #00b98e, #007f6c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* ---- Responsive ---- */
@media (max-width: 768px) {
  .floating-img img {
    animation: floating 5s ease-in-out infinite;
  }
  .intro-section img,
  .origin-section img { width: 100%; }
}
</style>
