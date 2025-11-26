<template>
  <div v-if="isAuthenticated && (user.role == 1 || user.role == 2)" class="site-section mt-5">
    <div class="container">

      <div class="row justify-content-center mt-5">
        <div class="col-md-7 text-center mt-5">
          <div class="site-section-title">
            <h2>Section admin</h2>
          </div>
          <p>Cette section permet d'interagir avec les outils dont le site aura besoin pour se développer</p>
        </div>
      </div>

      <div class="row justify-content-center" data-aos="fade" data-aos-delay="100">
        <div class="accordion" id="accordionExample">
          <Country accordionId="adminAccordion" />
          <Town accordionId="adminAccordion" />
          <District accordionId="adminAccordion" />
          <Category accordionId="adminAccordion" />
          <PubType accordionId="adminAccordion" />
          <Attribute accordionId="adminAccordion" />
          <User v-if="isAuthenticated && (user.role == 1 || user.role == 2)" accordionId="adminAccordion" />
          <Payment accordionId="adminAccordion" />
        </div>
      </div>
    </div>
  </div>
  <!-- <div  class="site-section bg-danger mt-5">
    <div class="col-md-7 text-center mt-5">
      <p class="text-danger">Désolée, aucune publication correspondante au filtrage n'a été trouvée!</p>
    </div>
  </div> -->
  <div v-if="!isAuthenticated && user.role == 3" class="card shadow-lg bg-danger mt-5 mb-5 p-4 rounded">
      <!-- <h4 class="text-center text-light">Veuillez vous connecter pour accéder à votre profil.</h4> -->
      <!-- 404 Start -->
      <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
          <div class="container text-center">
              <div class="row justify-content-center">
                  <div class="col-lg-6 text-danger">
                      <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                      <h1 class="display-1 ">Erreur 404</h1>
                      <h1 class="mb-4">Liste non trouvée</h1>
                      <p class="mb-4">Désolée, l'acces est restreint</p>
                      <a class="btn btn-primary py-3 px-5" @click="goToLogin">Se connecter</a>
                  </div>
              </div>
          </div>
      </div>
      <!-- 404 End -->
  </div>
</template>

<script setup>
 import { user, isAuthenticated } from '../../auth/auth.js'

  import Country from './content/Country.vue'
  import Town from './content/Town.vue'
  import District from './content/District.vue'
  import Category from './content/Category.vue'
  import PubType from './content/PubType.vue'
  import Attribute from './content/Attribute.vue'
  import User from './content/User.vue'
  import Payment from './content/Payment.vue'

  import { useRouter } from 'vue-router'
  const router = useRouter()

  const goToLogin = () => {
    router.push('/login')
  }
</script>
