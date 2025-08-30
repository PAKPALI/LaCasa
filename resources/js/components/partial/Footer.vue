<template>
  <div class="container-fluid bg-dark text-light footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">

        <!-- Section LaCasa -->
        <div class="col-lg-3 col-md-6">
          <h5 class="text-light mb-4">LaCasa</h5>
          <p>Votre solution simple et fiable pour trouver un logement au Togo.</p>
          <div class="d-flex pt-2">
            <a v-for="(icon, i) in socialIcons" :key="i" :href="icon.link" class="btn btn-outline-light btn-social me-1">
              <i :class="icon.class"></i>
            </a>
          </div>
        </div>

        <!-- Navigation -->
        <div class="col-lg-3 col-md-6">
          <h5 class="text-light mb-4">Navigation</h5>
          <a v-for="(nav, i) in navLinks" :key="i" :href="nav.link" class="btn btn-link text-light">
            {{ nav.name }}
          </a>
        </div>

        <!-- Contact -->
        <div class="col-lg-3 col-md-6">
          <h5 class="text-light mb-4">Contact</h5>
          <p><i class="fa fa-map-marker-alt me-3"></i>{{ contact.address }}</p>
          <p><i class="fa fa-phone-alt me-3"></i>{{ contact.phone }}</p>
          <p><i class="fa fa-envelope me-3"></i>{{ contact.email }}</p>
        </div>

        <!-- Galerie -->
        <div class="col-lg-3 col-md-6">
          <h5 class="text-light mb-4">Galerie</h5>
          <div class="row g-2">
            <div class="col-4" v-for="(img, i) in gallery" :key="i">
              <img class="img-fluid rounded bg-light p-1" :src="img" alt="Property image">
            </div>
          </div>
        </div>

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
import prop1 from '@images2/property-1.jpg'
import prop2 from '@images2/property-2.jpg'
import prop3 from '@images2/property-3.jpg'
import prop4 from '@images2/property-4.jpg'
import prop5 from '@images2/property-5.jpg'
import prop6 from '@images2/property-6.jpg'

// Données pour le footer
const socialIcons = [
  { class: 'fab fa-twitter', link: '#' },
  { class: 'fab fa-facebook-f', link: '#' },
  { class: 'fab fa-youtube', link: '#' },
  { class: 'fab fa-linkedin-in', link: '#' },
]

const navLinks = [
  { name: 'Accueil', link: '#' },
  { name: 'À propos', link: '#' },
  { name: 'Annonces', link: '#' },
  { name: 'Contact', link: '#' },
]

const contact = {
  address: 'Lomé, Togo',
  phone: '+228 90 00 00 00',
  email: 'support@lacasa.tg'
}

const gallery = [prop1, prop2, prop3, prop4, prop5, prop6]
</script>
