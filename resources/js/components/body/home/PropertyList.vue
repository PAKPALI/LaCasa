<template>
  <!-- Liste des propriétés Début -->
  <div class="container-xxl py-5">
    <div class="container">
      <div class="row g-0 gx-5 align-items-end">
        <div class="col-lg-6">
          <div class="text-start mx-auto mb-5">
            <h1 class="mb-3">Les Propriétés</h1>
            <p>
              Découvrez une sélection de biens immobiliers disponibles à la vente et à la location.
              Trouvez facilement la maison, la villa ou le bureau qui correspond à vos besoins.
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
                class="btn btn-outline-primary"
                :class="{ active: activeTab==='sell' }"
                @click="activeTab='sell'"
              >
                À vendre
              </button>
            </li>
            <li class="nav-item me-0">
              <button
                class="btn btn-outline-primary"
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
              v-for="(p, i) in currentList"
              :key="i"
              class="col-lg-4 col-md-6 wow fadeInUp"
              :data-wow-delay="p.delay"
            >
              <div class="property-item rounded overflow-hidden">
                <div class="position-relative overflow-hidden">
                  <a href="#"><img class="img-fluid" :src="p.img" alt=""></a>
                  <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">{{ p.badge }}</div>
                  <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3">{{ p.type }}</div>
                </div>
                <div class="p-4 pb-0">
                  <h5 class="text-primary mb-3">{{ p.price }}</h5>
                  <a class="d-block h5 mb-2" href="#">{{ p.title }}</a>
                  <p><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ p.location }}</p>
                </div>
                <div class="d-flex border-top">
                  <small class="flex-fill text-center border-end py-2">
                    <i class="fa fa-ruler-combined text-primary me-2"></i>{{ p.size }}
                  </small>
                  <small class="flex-fill text-center border-end py-2">
                    <i class="fa fa-bed text-primary me-2"></i>{{ p.beds }} Chambres
                  </small>
                  <small class="flex-fill text-center py-2">
                    <i class="fa fa-bath text-primary me-2"></i>{{ p.baths }} Salles de bain
                  </small>
                </div>
              </div>
            </div>

            <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
              <a class="btn btn-primary py-3 px-5" href="#">Voir plus de propriétés</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Liste des propriétés Fin -->
</template>

<script setup>
import { computed, ref } from 'vue'

// Import des images
import property_1 from '@images2/property-1.jpg'
import property_2 from '@images2/property-2.jpg'
import property_3 from '@images2/property-3.jpg'
import property_4 from '@images2/property-4.jpg'
import property_5 from '@images2/property-5.jpg'
import property_6 from '@images2/property-6.jpg'

const activeTab = ref('featured')

// Liste de base des propriétés
const base = [
  { img: property_1, badge: 'À vendre', type: 'Appartement', delay: '0.1s' },
  { img: property_2, badge: 'À louer', type: 'Villa', delay: '0.3s' },
  { img: property_3, badge: 'À vendre', type: 'Bureau', delay: '0.5s' },
  // { img: property_4, badge: 'À louer', type: 'Immeuble', delay: '0.1s' },
  // { img: property_5, badge: 'À vendre', type: 'Maison', delay: '0.3s' },
  // { img: property_6, badge: 'À louer', type: 'Duplex', delay: '0.5s' }
].map(p => ({
  ...p,
  price: '12 345 000 FCFA',
  title: 'Superbe bien immobilier',
  location: 'Lomé, Togo',
  size: '250 m²',
  beds: 3,
  baths: 2
}))

const lists = {
  featured: base,
  sell: base.filter(p => p.badge === 'À vendre'),
  rent: base.filter(p => p.badge === 'À louer')
}

const currentList = computed(() => lists[activeTab.value])
</script>
