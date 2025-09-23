<template>
  <!-- Liste des propriétés Début -->
  <div class="container-xxl py-5">
    <div class="container">
      <div class="row g-0 gx-5 align-items-end">
        <div class="col-lg-6">
          <div class="text-start mx-auto mb-5">
            <h1 class="mb-3">Mes publications</h1>
            <p>
              la liste de vos publications s'affichera ci-dessous une fois créées.
            </p>
          </div>
        </div>
        <div class="col-lg-6 text-start text-lg-end">
          <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
            <li class="nav-item me-2">
              <button
                class="btn btn-outline-primary"
                :class="{ active: activeTab==='featured' }"
                @click="activeTab='featured'"
              >
                À la une
              </button>
            </li>
            <li class="nav-item me-2">
              <button
                class="btn btn-outline-danger"
                :class="{ active: activeTab==='sell' }"
                @click="activeTab='sell'"
              >
                À vendre
              </button>
            </li>
            <li class="nav-item me-0">
              <button
                class="btn btn-outline-info"
                :class="{ active: activeTab==='rent' }"
                @click="activeTab='rent'"
              >
                À louer
              </button>
            </li>
          </ul>
        </div>
      </div>

      <div class="tab-content">
        <div class="tab-pane fade show p-0 active">
          <div class="row g-4">
            <div
              v-for="(p, i) in filteredPublications"
              :key="p.id || i"
              class="col-lg-4 col-md-6 wow fadeInUp"
              :data-wow-delay="p.delay || '0.1s'"
            >
              <div class="property-item rounded overflow-hidden">
                <div class="position-relative overflow-hidden">
                  <a href="#">
                    <img class="img-fluid" :src="p.images && p.images.length ? p.images[0] : placeholderImage" alt="">
                  </a>
                  <div :class="['rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3', p.offer_type === 'sale' ? 'bg-danger' : 'bg-info']"
>
                    {{ p.offer_type === 'sale' ? 'À vendre' : 'À louer' }}
                  </div>
                  <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                    {{ p.category_name || 'Type inconnu' }}
                  </div>
                </div>
                <div class="p-4 pb-0">
                  <h5 class="text-primary mb-3">{{ formatPrice(p.price) }}</h5>
                  <a class="d-block h5 mb-2" href="#">{{ p.title || 'Titre non défini' }}</a>
                  <p>
                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                    {{ p.district_name || p.town_name || p.country_name || 'Localisation non définie' }}
                  </p>
                </div>
                <div class="d-flex border-top">
                  <small class="flex-fill text-center border-end py-2">
                    <i class="fa fa-ruler-combined text-primary me-2"></i>{{ p.surface || 0 }} m²
                  </small>
                  <small class="flex-fill text-center border-end py-2">
                    <i class="fa fa-bed text-primary me-2"></i>{{ p.bathroom || 0 }} Chambres
                  </small>
                  <!-- <small class="flex-fill text-center py-2">
                    <i class="fa fa-bath text-primary me-2"></i>{{ p.baths || 0 }} Salles de bain
                  </small> -->
                </div>
              </div>
            </div>

            <div v-if="filteredPublications.length === 0" class="col-12 text-center py-5">
              <p class="text-muted">Aucune publication pour cette catégorie pour le moment.</p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Liste des propriétés Fin -->
</template>

<script setup>
import { ref, computed } from 'vue'

// Placeholder image si aucune image n'est fournie
const placeholderImage = '/images2/property-placeholder.jpg'

// Props venant du parent (create.vue)
const props = defineProps({
  publications: {
    type: Array,
    required: true
  }
})

const activeTab = ref('featured')

// Filtrer les publications selon l'onglet actif
const filteredPublications = computed(() => {
  if (!props.publications) return []

  if (activeTab.value === 'featured') {
    return props.publications
  }
  if (activeTab.value === 'sell') {
    return props.publications.filter(p => p.offer_type === 'sale')
  }
  if (activeTab.value === 'rent') {
    return props.publications.filter(p => p.offer_type === 'rent')
  }
  return props.publications
})

// Petite fonction pour formater le prix
const formatPrice = (price) => {
  if (!price) return '0 FCFA'
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(price)
}
</script>
