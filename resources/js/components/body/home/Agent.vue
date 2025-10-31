<template>
  <!-- Appel à l'action Début -->
  <div class="container-xxl py-5">
    <div class="container">
      <div class=" rounded p-3">
        <div
          class=" rounded p-4"
          style="border: 1px dashed rgba(0, 185, 142, .3); background-color: rgba(255, 255, 255, 0.8);">
          <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
              <img class="img-fluid rounded w-100" :src="ctaImage" alt="Agent immobilier" />
            </div>
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
              <div class="mb-4">
                <h1 class="mb-4">Pourquoi Choisir LaCasa ?</h1>
                  <strong class="mb-4">
                    <h6>
                      LaCasa apporte une solution précise et simplifiée pour vous aider. 
                    </h6>
                  </strong>
                  <p class="mt-4"><i class="fa fa-check text-primary me-3"></i><strong>Plus besoin de courir derrière des tableaux physiques exposés en plein air</strong></p>
                  <p><i class="fa fa-check text-primary me-3"></i><strong>Trouvez votre maison idéale rapidement avec précision(ville,quartier,prix..)</strong></p>
                  <p><i class="fa fa-check text-primary me-3"></i><strong>Quelque soit votre position, LaCasa simplifie tout</strong></p>
                  <!-- <p><i class="fa fa-check text-primary me-3"></i><strong></strong></p> -->
              </div>
              <!-- <a href="#" class="btn btn-primary py-3 px-4 me-2">
                <i class="fa fa-phone-alt me-2"></i>Appeler maintenant
              </a> -->
              <!-- <a href="#" class="btn btn-dark py-3 px-4">
                <i class="fa fa-calendar-alt me-2"></i>Prendre un rendez-vous
              </a> -->
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
