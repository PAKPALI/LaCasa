<template>
  <!-- À propos Début -->
  <div class="container-xxl py-5">
    <div class="container">
      <div class="row g-5 align-items-center">

        <!-- Image -->
        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
          <div class="about-img position-relative overflow-hidden p-5 pe-0">
            <img class="img-fluid w-100" :src="aboutImage" alt="À propos LaCasa">
          </div>
        </div>

        <!-- Texte -->
        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
          <h1 class="mb-4">La meilleure plateforme pour trouver votre bien idéal</h1>
          <p class="mb-4">
            Découvrez un large choix de propriétés adaptées à vos besoins. Que vous souhaitiez acheter,
            louer, notre équipe met à votre disposition des annonces fiables et vérifiées
            afin de vous garantir la meilleure expérience.
          </p>
          <p><i class="fa fa-check text-primary me-3"></i>Des offres variées et mises à jour</p>
          <p><i class="fa fa-check text-primary me-3"></i>Des agents certifiés à votre écoute</p>
          <p><i class="fa fa-check text-primary me-3"></i>Une recherche simple et personnalisée</p>
          <a class="btn btn-primary py-3 px-5 mt-3" href="#">En savoir plus</a>
        </div>

      </div>
    </div>
  </div>
  <!-- À propos Fin -->
</template>

<script setup>
// Import de l'image
import aboutImage from '@images2/about.jpg'
</script>
