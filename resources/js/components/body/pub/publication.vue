<template>
  <!-- Liste des propriétés Début -->
  <div class="container-xxl py-5">
    <div class="container">
      <!-- 📌 En-tête + Boutons de filtre -->
      <div class="row g-0 gx-5 align-items-end">
        <div class="col-lg-6">
          <div class="text-start mx-auto mb-5">
            <h1 class="mb-3">Mes publications</h1>
            <p>La liste de vos publications s'affichera ci-dessous une fois créées.</p>
          </div>
        </div>
        <div class="col-lg-6 text-start text-lg-end">
          <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
            <li class="nav-item me-2">
              <button class="btn btn-outline-primary" :class="{ active: activeTab==='featured' }" @click="activeTab='featured'">À la une</button>
            </li>
            <li class="nav-item me-2">
              <button class="btn btn-outline-danger" :class="{ active: activeTab==='sell' }" @click="activeTab='sell'">À vendre</button>
            </li>
            <li class="nav-item me-0">
              <button class="btn btn-outline-info" :class="{ active: activeTab==='rent' }" @click="activeTab='rent'">À louer</button>
            </li>
          </ul>
        </div>
      </div>

      <!-- 📌 Liste des publications -->
      <div class="tab-content">
        <div class="tab-pane fade show p-0 active">
          <div class="row g-4">
            <div v-for="(p, i) in filteredPublications" :key="p.id || i" class="col-lg-4 col-md-6 wow fadeInUp" :data-wow-delay="p.delay || '0.1s'">
              <div class="property-item rounded overflow-hidden shadow">
                <div class="position-relative overflow-hidden">
                  <img class="img-fluid" :src="p.images && p.images.length ? p.images[0] : placeholderImage" alt="">
                  
                  <!-- Type d'offre -->
                  <div :class="['rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3', p.offer_type === 'sale' ? 'bg-danger' : 'bg-info']">
                    {{ p.offer_type === 'sale' ? 'À vendre' : 'À louer' }}
                  </div>

                  <!-- Catégorie -->
                  <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">
                    {{ p.category_name || 'Type inconnu' }}
                  </div>

                  <!-- 👁 Bouton voir plus avec animation -->
                  <button class="btn eye-alert-btn position-absolute end-0 top-0 m-2 shadow" @click="openModal(p)">
                    <i class="fa fa-eye"></i>
                  </button>
                </div>

                <!-- Attributs sous le prix / titre -->
                <div class="mb-2 d-flex flex-wrap">
                  <span v-for="attr in p.attributes" :key="attr.id" class="badge bg-success me-1 mb-1">
                    {{ attr.name }}
                  </span>
                </div>

                <div class="p-4 pb-0">
                  <h5 class="text-primary mb-3">{{ formatPrice(p.price) }}</h5>
                  <a class="d-block h5 mb-2" href="#">{{ p.title || 'Titre non défini' }}</a>
                  <p><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ p.district_name || p.town_name || p.country_name || 'Localisation non définie' }}</p>
                  <p class="border-rounded text-center p-2" style="background-color: #f9f9f9;">
                    <span v-if="p.phone1">
                      <i class="fa fa-phone text-primary me-2"></i>{{ p.phone1 }}
                    </span>
                    <span v-if="p.phone2" class="ms-3">
                      <i class="fa fa-phone text-primary me-2"></i>{{ p.phone2 }}
                    </span>
                  </p>
                </div>

                <div class="d-flex border-top">
                  <small class="flex-fill text-center border-end py-2"><i class="fa fa-ruler-combined text-primary me-2"></i>{{ p.surface || 0 }} m²</small>
                  <small class="flex-fill text-center border-end py-2"><i class="fa fa-bed text-primary me-2"></i>{{ p.bathroom || 0 }} Chambres</small>
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

  <!-- 📌 Modal de détails de la publication -->
  <div class="modal fade" id="publicationModal" tabindex="-1" aria-hidden="true" ref="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 class="modal-title">
            {{ selectedPublication?.title || 'Détails de la publication' }}
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Carrousel d'images -->
          <div v-if="selectedPublication?.images?.length" id="carouselImages" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner rounded">
              <div v-for="(img, index) in selectedPublication.images" :key="index" class="carousel-item" :class="{ active: index === 0 }">
                <img :src="img" class="d-block w-100" style="max-height: 400px; object-fit: cover;" alt="Image de la publication">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
          </div>

          <!-- 📊 Infos détaillées sous forme de tableau -->
          <div class="mt-4">
            <table class="table custom-info-table">
              <tbody>
                <tr>
                  <th>Catégorie</th>
                  <td>{{ selectedPublication?.category_name || 'Catégorie inconnue' }}</td>
                </tr>
                <tr>
                  <th>Type d'offre</th>
                  <td>{{ selectedPublication?.offer_type === 'sale' ? 'À vendre' : 'À louer' }}</td>
                </tr>
                <tr v-if="selectedPublication?.attributes?.length">
                  <th>Attributs</th>
                  <td>
                    <div class="d-flex flex-wrap">
                      <span v-for="attr in selectedPublication.attributes" :key="attr.id" class="badge bg-success me-1 mb-1">
                        {{ attr.name }}
                      </span>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th>Prix</th>
                  <td>{{ formatPrice(selectedPublication?.price) }}</td>
                </tr>
                <tr>
                  <th>Localisation</th>
                  <td>{{ selectedPublication?.district_name || selectedPublication?.town_name || selectedPublication?.country_name || 'Non définie' }}</td>
                </tr>
                <tr>
                  <th>Superficie</th>
                  <td>{{ selectedPublication?.surface || 0 }} m²</td>
                </tr>
                <tr>
                  <th>Chambres</th>
                  <td>{{ selectedPublication?.bathroom || 0 }}</td>
                </tr>
                <tr>
                  <th>Description</th>
                  <td>{{ selectedPublication?.description || 'Aucune description disponible' }}</td>
                </tr>
                <tr>
                  <th>Téléphone 1</th>
                  <td>{{ selectedPublication?.phone1 || 'Non défini' }}</td>
                </tr>
                <tr>
                  <th>Téléphone 2</th>
                  <td>{{ selectedPublication?.phone2 || 'Non défini' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="modal-footer">
          <button class="btn btn-outline-primary" :disabled="!selectedPublication?.phone1"
                  @click="window.location.href = 'tel:' + selectedPublication.phone1">
            <i class="fa fa-phone me-2"></i> Contacter
          </button>
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Modal } from 'bootstrap'

const placeholderImage = '/images2/property-placeholder.jpg'

const props = defineProps({
  publications: {
    type: Array,
    required: true
  }
})

const activeTab = ref('featured')
const selectedPublication = ref(null)
const modalInstance = ref(null)

const filteredPublications = computed(() => {
  if (!props.publications) return []
  if (activeTab.value === 'featured') return props.publications
  if (activeTab.value === 'sell') return props.publications.filter(p => p.offer_type === 'sale')
  if (activeTab.value === 'rent') return props.publications.filter(p => p.offer_type === 'rent')
  return props.publications
})

const openModal = (pub) => {
  selectedPublication.value = pub
  if (!modalInstance.value) {
    modalInstance.value = new Modal(document.getElementById('publicationModal'))
  }
  modalInstance.value.show()
}

const formatPrice = (price) => {
  if (!price) return '0 FCFA'
  return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(price)
}
</script>

<style scoped>
/* 🎯 Animation clignotante du bouton œil */
@keyframes blink {
  0%, 100% {
    background-color: white;
    color: black;
  }
  50% {
    background-color: black;
    color: white;
  }
}

.eye-alert-btn {
  background-color: white;
  color: black;
  border-radius: 50%;
  animation: blink 1.5s infinite;
  transition: all 0.3s ease;
}

.eye-alert-btn:hover {
  transform: scale(1.1);
}

.custom-info-table {
  border-collapse: separate;
  border-spacing: 0 8px; /* espace entre les lignes */
  width: 100%;
}

.custom-info-table th {
  width: 35%;
  font-weight: 600;
  color: #ffffff;
  background-color: #00050a;
  padding: 10px 15px;
  border-top-left-radius: 8px;
  border-bottom-left-radius: 8px;
}

.custom-info-table td {
  color: #ffffff;
  background-color: #000000;
  padding: 10px 15px;
  border-top-right-radius: 8px;
  border-bottom-right-radius: 8px;
  box-shadow: 0 1px 3px rgba(247, 246, 246, 0.842);
}

.custom-info-table tr:nth-child(even) td,
.custom-info-table tr:nth-child(even) th {
  background-color: #000301;
}
</style>
