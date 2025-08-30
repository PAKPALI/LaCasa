<template>
  <!-- Appel à l'action Début -->
  <div class="container-xxl py-5" style="background-color: rgba(255, 255, 255, 0.7);">
    <div class="container">
      <div class="bg-light rounded p-3" style="background-color: rgba(255, 255, 255, 0.7);">
        <div
          class="bg-white rounded p-4"
          style="border: 1px dashed rgba(0, 185, 142, .3); background-color: rgba(255,255,255,0.85);"
        >
          <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
              <img class="img-fluid rounded w-100" :src="ctaImage" alt="Agent immobilier" />
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
              <div class="mb-4">
                <h1 class="mb-3">Contactez nos agents certifiés</h1>
                <p>
                  Nous mettons à votre disposition une équipe qualifiée pour répondre à toutes vos
                  préoccupations. Prenez rendez-vous ou appelez-nous directement pour plus
                  d’informations.
                </p>
              </div>
              <a href="#" class="btn btn-primary py-3 px-4 me-2">
                <i class="fa fa-phone-alt me-2"></i>Appeler maintenant
              </a>
              <a href="#" class="btn btn-dark py-3 px-4">
                <i class="fa fa-calendar-alt me-2"></i>Prendre un rendez-vous
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Appel à l'action Fin -->
</template>

<script setup>
// Import de l'image
import ctaImage from '@images2/call-to-action.jpg'
</script>
