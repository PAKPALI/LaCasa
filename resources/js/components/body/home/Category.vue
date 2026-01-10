<template>
  <div class="container-xxl py-5">
    <div class="container">
      <div class="text-center mx-auto mb-5" style="max-width: 600px;">
        <h1 class="mb-3 fw-bold text-dark">Types de logements</h1>
        <h6 class="text-muted"><strong class="text-dark">Découvrez les catégories de logements disponibles sur LaCasa.
          Vous avez la possibilité de filtrer vos recherches selon la catégorie au niveau de la partie publication pour trouver rapidement l'annonce qui correspond à vos besoins.
        </strong></h6>
      </div>

      <div class="row g-4">
        <div class="col-lg-4 col-md-6 col-sm-6" v-for="(item, index) in categories" :key="index">
          <router-link to='/publications' class="nav-link neon-text">
            <div class="cat-card  text-center rounded-4 shadow-sm p-4 position-relative" style="background-color: rgba(255, 255, 255, 0.7);">
              <div class="floating-icon">
                <img class="img-fluid" :src="getIcon(item.name)" :alt="item.name" />
              </div>

              <div class="mt-5 pt-3 card-content">
                <h5 class="fw-semibold mb-1">{{ item.name }}</h5>
                <p class="text-muted small mb-0">{{ item.publications_count }} annonces</p>
              </div>

              <div class="hover-bar"></div>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

// Import des icônes locales
import iconApartment from '@images2/icon-apartment.png'
import iconVilla from '@images2/icon-villa.png'
import iconHouse from '@images2/icon-house.png'
import iconOffice from '@images2/icon-housing.png'
import iconBuilding from '@images2/icon-building.png'
import iconShop from '@images2/icon-condominium.png'

// Liste réactive des catégories
const categories = ref([])

// Associer icône selon le nom
const getIcon = (name) => {
  const normalized = name.toLowerCase()
  if (normalized.includes('appartement')) return iconApartment
  if (normalized.includes('villa')) return iconVilla
  if (normalized.includes('maison')) return iconHouse
  if (normalized.includes('bureau')) return iconOffice
  if (normalized.includes('immeuble')) return iconBuilding
  if (normalized.includes('boutique')) return iconShop
  return iconHouse
}

// Charger les catégories depuis Laravel
onMounted(async () => {
  try {
    const response = await axios.get('/categories')
    categories.value = response.data
  } catch (error) {
    console.error('Erreur de chargement des catégories :', error)
  }
})
</script>

<style scoped>
/* === Style global des cartes === */
.cat-card .card-content p {
  position: relative;
  overflow: hidden;
  transition: all 0.35s ease;
  cursor: pointer;
  background: linear-gradient(90deg, #0e2e50, #00b894);
  color: #fff !important;
  box-shadow: 0 12px 30px rgba(15, 4, 4, 0.014);
}

.cat-card:hover {
  transform: translateY(-8px);
  background: linear-gradient(90deg, #0e2e50, #00b894);
  box-shadow: 0 12px 30px rgba(247, 243, 243, 0.1);
}

/* === Icône flottante === */
.floating-icon {
  position: absolute;
  top: 0px; /* moitié visible */
  left: 50%;
  transform: translateX(-50%);
  background: #fff;
  border-radius: 50%;
  padding: 14px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  z-index: 10;
}

.floating-icon img {
  width: 50px;
  height: 50px;
  animation: float 3s ease-in-out infinite;
  filter: none !important;
  transition: none !important;
}

/* === Effet de flottement continu === */
@keyframes float {
  0% { transform: translateY(0); }
  50% { transform: translateY(-6px); }
  100% { transform: translateY(0); }
}

/* === Contenu texte === */
.card-content {
  transition: color 0.3s ease;
}

.cat-card:hover .card-content h5,
.cat-card:hover .card-content p {
  color: #fff !important;
  box-shadow: 0 12px 30px rgba(255, 255, 255, 0.281);
}

/* === Barre décorative au survol === */
.hover-bar {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  width: 0;
  background: linear-gradient(90deg, #0e2e50, #00b894);
  transition: width 0.4s ease;
}

.cat-card:hover .hover-bar {
  width: 100%;
}

/* === Texte === */
h5 {
  font-size: 1.1rem;
}

.text-muted {
  color: #6c757d !important;
}
</style>
