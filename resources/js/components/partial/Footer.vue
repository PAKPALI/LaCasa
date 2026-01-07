<template>
  <div class="container-fluid text-light footer mt-2 pt-3 wow fadeIn" data-wow-delay="0.1s" style="background-color: rgba(14, 46, 80, 0.9);">
    <div class="container py-3">
      <div class="row g-5">

        <!-- Section LaCasa -->
        <div class="col-lg-3 col-md-6">
          <!-- <h5 class="text-light mb-4">LaCasa</h5> -->
          <img :src="logo2" alt="logo2" class="img-fluid w-35 mb-4" />
          <!-- <p>Votre solution simple et fiable pour trouver un logement au Togo.</p> -->
          <div class="d-flex pt-2">
            <a v-for="(icon, i) in socialIcons" :key="i" :href="icon.link" class="btn btn-outline-light btn-social me-1">
              <i :class="icon.class"></i>
            </a>
          </div>
        </div>

        <!-- Navigation -->
        <div class="col-lg-3 col-md-6">
          <h5 class="text-light mb-4">Navigation</h5>
          <div v-for="(nav, i) in navLinks" :key="i">
            <router-link :to="nav.link" class="btn btn-link nav-item">
              {{ nav.name }}
            </router-link>
          </div>
        </div>

        <!-- Contact -->
        <div class="col-lg-3 col-md-6">
          <h5 class="text-light mb-4">Contact</h5>
          <p><i class="text-primary fa fa-map-marker-alt me-3"></i>{{ contact.address }}</p>
          <p>
            <i class="text-primary fa fa-phone-alt me-3"></i>
            <a :href="`tel:${contact.phone}`" class="text-light">
              {{ contact.phone }}
            </a>
          </p>

          <p>
            <i class="text-primary fa fa-envelope me-3"></i>
            <a :href="`mailto:${contact.email}`" class="text-light">
              {{ contact.email }}
            </a>
          </p>
        </div>

        <!-- Galerie -->
        <!-- <div class="col-lg-3 col-md-6">
          <h5 class="text-light mb-4">Galerie</h5>
          <div class="row g-2">
            <div class="col-4" v-for="(img, i) in gallery" :key="i">
              <img class="img-fluid rounded bg-light p-1" :src="img" alt="Property image">
            </div>
          </div>
        </div> -->

      </div>
    </div>

    <!-- Footer bottom -->
    <div class="container-fluid text-center border-top border-light py-4">
      <p class="mb-0">&copy; 2025 LaCasa - Tous droits réservés</p>
    </div>
  </div>
</template>

<script setup>
// Import des images de galerie
import logo2 from '@images/logo2.png'
// import logo2 from "@images/logo_noel2.png"
import prop1 from '@images2/property-1.jpg'
import prop2 from '@images2/property-2.jpg'
import prop3 from '@images2/property-3.jpg'
import prop4 from '@images2/property-4.jpg'
import prop5 from '@images2/property-5.jpg'
import prop6 from '@images2/property-6.jpg'

// Données pour le footer
const socialIcons = [
  // { class: 'fab fa-twitter', link: '#' },
  { class: 'fab fa-facebook-f', link: '#' },
  { class: 'fab fa-youtube', link: '#' },
  // { class: 'fab fa-linkedin-in', link: '#' },
]

const navLinks = [
  { name: 'Accueil', link: '/home' },
  { name: 'Publications', link: '/publications' },
  { name: 'À propos', link: '/about' },
  { name: 'Vos avis', link: '/review'},
  { name: 'S\'inscrire', link: '/register'},
  { name: 'Se connecter', link: '/login'},
]


const contact = {
  address: 'Lomé, Togo',
  phone: '+22879685106',
  email: 'support@lacasaweb.com'
}

// const gallery = [prop1, prop2, prop3, prop4, prop5, prop6]
</script>
<style scoped>
  .nav-item {
    color: #fff !important;
  }

  /* Survol */
  .nav-item:hover {
    color: var(--bs-primary) !important;
  }

  /* Lien actif */
  .router-link-active {
    color: var(--bs-primary) !important;
    font-weight: bold;
  }
</style>