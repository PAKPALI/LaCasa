<template>
  <!-- À propos Début -->
  <div class="container-xxl py-5" style="background-color: rgba(255, 255, 255, 0.6);">
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
            <h6>
              <strong class="text-dark">
                Découvrez un large choix de propriétés adaptées à vos besoins. Que vous souhaitiez acheter,
                louer, notre équipe met à votre disposition des annonces fiables et vérifiées
                afin de vous garantir la meilleure expérience.
              </strong>
            </h6>
          </p>
          
          <p><i class="fa fa-check text-primary me-3"></i><strong>Des offres variées et mises à jour</strong></p>
          <p><i class="fa fa-check text-primary me-3"></i><strong>Des agents certifiés à votre écoute</strong></p>
          <p><i class="fa fa-check text-primary me-3"></i><strong>Une recherche simple et personnalisée</strong></p>
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
