<template>
  <div class="container-xxl py-5">
    <div class="container">

      <!-- FILTRAGE -->
      <div class="container-fluid bg-dark mb-5 p-3">
        <div class="container">
          <div class="row g-2">
            <div class="col-md-12">
              <div class="row text-light g-2">
                <label class="form-label mb-3 text-light">
                  <marquee class="border" behavior="scroll" direction="left" scrollamount="6">
                    <strong>Veuillez remplir les champs pour affiner votre recherche.</strong>
                    <i class="fa fa-search me-2"></i>
                  </marquee>
                </label>

                <!-- Pays -->
                <div class="col-md-4 mt-2 mb-2">
                  <label class="form-label fw-semibold text-light"><strong>Pays</strong></label>
                  <v-select v-model="filters.country_id" :options="countries" label="name" :reduce="c => c.id"
                    placeholder="Sélectionner un pays" :filterable="true" :loading="loadingCountries"></v-select>
                </div>

                <!-- Ville -->
                <div class="col-md-4 mt-2 mb-2">
                  <label class="form-label fw-semibold text-light"><strong>Ville</strong></label>
                  <v-select v-model="filters.town_id" :options="towns" label="name" :reduce="t => t.id"
                    placeholder="Sélectionner une ville" :filterable="true" :loading="loadingTowns"
                    :disabled="!filters.country_id || towns.length === 0"></v-select>
                </div>

                <!-- Quartier -->
                <div class="col-md-4 mt-2 mb-2">
                  <label class="form-label fw-semibold text-light"><strong>Quartier</strong></label>
                  <v-select v-model="filters.district_id" :options="districts" label="name" :reduce="d => d.id" placeholder="Sélectionner un ou plusieurs quartiers"
                    :filterable="true" :loading="loadingDistricts" :disabled="!filters.town_id || districts.length === 0" :multiple="true">
                  </v-select>
                </div>

                <!-- Catégorie -->
                <div class="col-md-4 mt-2 mb-2">
                  <label class="form-label fw-semibold text-light"><strong>Catégorie</strong></label>
                  <v-select v-model="filters.category_id" :options="categories" label="name" :reduce="c => c.id" placeholder="Sélectionner une catégorie"
                    :filterable="true" :loading="loadingCategories" :disabled="!filters.country_id || categories.length === 0">
                  </v-select>
                </div>

                <!-- Type de publication -->
                <div class="col-md-4 mt-2 mb-2">
                  <label class="form-label fw-semibold text-light"><strong>Type de propiété</strong></label>
                  <v-select v-model="filters.pub_type_id" :options="pubTypes" label="name" :reduce="pt => pt.id"
                    placeholder="Sélectionner un type" :filterable="true" :loading="loadingPubTypes"
                    :disabled="!filters.category_id || pubTypes.length === 0"></v-select>
                </div>

                <!-- Attributs -->
                <div class="col-md-4 mb-2">
                  <label class="form-label fw-semibold text-light"><strong>Attributs</strong></label>
                  <v-select v-model="filters.attribute_ids" :options="attributes" label="name" :reduce="a => a.id"
                    placeholder="Sélectionner des attributs" :multiple="true" :filterable="true"
                    :loading="loadingAttributes" :disabled="!filters.pub_type_id || attributes.length === 0"></v-select>
                </div>
                <!-- Prix 1 -->
                <div class="col-md-6 mb-2">
                  <label class="form-label fw-semibold text-light"><strong>Prix 1 (FCFA)</strong></label>
                  <!-- <input type="number" class="form-control" placeholder="Saisissez votre premier prix" v-model="price1" /> -->
                  <input type="number" class="form-control" placeholder="Prix 1" v-model.number="price1" />
                </div>

                <!-- Prix 2 -->
                <div class="col-md-6 mb-2">
                  <label class="form-label fw-semibold text-light"><strong>Prix 2 (FCFA)</strong></label>
                  <!-- <input type="number" class="form-control" placeholder="Saisissez votre deuxième prix" v-model="price2" /> -->
                  <input type="number" class="form-control" placeholder="Prix 2" v-model.number="price2" />
                </div>
              </div>
            </div>

            <!-- Bouton rechercher -->
            <div class="col-md-12 text-center mt-2 mb-2">
              <button class="btn btn-primary border-0 w-15 py-3" @click="searchPublications"
                :disabled="loadingPublications">
                <span v-if="loadingPublications" class="spinner-border spinner-border-sm"></span>
                <span v-else>
                  <i class="fa fa-search me-2"></i> Rechercher
                </span>
              </button>
            </div>

          </div>
        </div>
      </div>

      <!-- EN-TETE + BOUTONS -->
      <div class="row g-0 gx-5 align-items-end mb-3">
        <div class="col-lg-8">
          <div class="text-start mx-auto mb-3">
            <h1 class="mb-3">Les publications</h1>
            <p>La liste des publications s'affichera ci-dessous.</p>
          </div>
          <div class="text-end mx-auto mb-3">
            <router-link to='/createPub' class="btn btn-dark border-0 w-15"> + Ajouter publication </router-link>
          </div>
        </div>
        
        <marquee class="bg-dark text-light mb-2" behavior="scroll" direction="left" scrollamount="6">
          <strong>Filtrer par offre !....</strong>
        </marquee>
        <div class="col-lg-4 text-start text-lg-end">
          <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
            
            <li class="nav-item me-2">
              <button class="btn btn-outline-primary" :class="{ active: activeTab === 'featured' }"
                @click="activeTab = 'featured'">Tous</button>
            </li>
            <li class="nav-item me-2">
              <button class="btn btn-outline-danger" :class="{ active: activeTab === 'sell' }" @click="activeTab = 'sell'">À
                vendre</button>
            </li>
            <li class="nav-item me-0">
              <button class="btn btn-outline-info" :class="{ active: activeTab === 'rent' }" @click="activeTab = 'rent'">À
                louer</button>
            </li>
          </ul>
        </div>
      </div>

      <!-- LISTE DES PUBLICATIONS -->
      <div class="tab-content">
        <div class="tab-pane fade show p-0 active">
          <div class="row g-4">
            <div v-for="(p, i) in filteredPublications" :key="p.id || i" class="col-lg-4 col-md-6 wow fadeInUp"
              :data-wow-delay="p.delay || '0.1s'">
              <!-- CARD PROPRIETE -->
              <div class="property-item rounded overflow-hidden shadow">
                <div class="position-relative overflow-hidden">

                  <!-- CAROUSEL IMAGE -->
                  <div v-if="p.images?.length" :id="'carouselList' + i" class="carousel slide" data-bs-ride="carousel"
                    :data-bs-interval="5000">
                    <div class="carousel-inner rounded">
                      <div v-for="(img, index) in p.images" :key="index" class="carousel-item"
                        :class="{ active: index === 0 }">
                        <img :src="img" class="d-block w-100" style="height: 200px; object-fit: cover;" alt="">
                      </div>
                    </div>
                    <button
                      class="carousel-control-prev blink-btn rounded-circle border-0 d-flex align-items-center justify-content-center"
                      type="button" :data-bs-target="'#carouselList' + i" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button
                      class="carousel-control-next blink-btn rounded-circle border-0 d-flex align-items-center justify-content-center"
                      type="button" :data-bs-target="'#carouselList' + i" data-bs-slide="next">
                      <span class="carousel-control-next-icon"></span>
                    </button>
                  </div>

                  <div v-else>
                    <img class="img-fluid w-100" style="height: 200px; object-fit: cover;" :src="placeholderImage"
                      alt="">
                  </div>

                  <div
                    :class="['rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3', p.offer_type === 'sale' ? 'bg-danger' : 'bg-info']">
                    {{ p.offer_type === 'sale' ? 'À vendre' : 'À louer' }}
                  </div>
                  <div
                    class="bg-dark text-light rounded-top text-primary position-absolute start-0 bottom-0 mx-0 pt-1 px-5">
                    {{ p.category_name || 'Type inconnu' }}
                  </div>
                  <button class="btn eye-alert-btn position-absolute end-0 top-0 m-2 shadow" @click="openModal(p)">
                    <i class="fa fa-eye"></i>
                  </button>
                </div>

                <!-- ATTRIBUTES -->
                <div class="mb-2 d-flex flex-wrap px-4 pt-3">
                  <span v-for="attr in p.attributes || []" :key="attr.id" class="badge bg-success me-1 mb-1">{{ attr.name
                    }}</span>
                </div>

                <!-- DESCRIPTION -->
                <div class="p-2 pb-0">
                  <div class="row bg-dark text-light border-top gx-2">
                    <div class="col-12 col-md-12 text-center">
                      <h5 class="text-light text-center mb-3">{{ formatPrice(p.price) }} / Mois</h5>
                      <a class="d-block h4 text-light mb-2" href="#">{{ p.title || 'Titre non défini' }}</a>
                      <p class="mb-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ p.district_name }} || {{p.town_name }}</p>
                    </div>
                    <marquee class="border" behavior="scroll" direction="left" scrollamount="6">
                      <strong>Publiée {{ formatRelativeDate(p.created_at) }} .</strong>
                    </marquee>
                    
                  </div>
                  <div v-if="isAuthenticated" class="d-flex align-items-center justify-content-center mt-2">
                    <!-- <p><strong>Publié par :</strong></p> -->
                    <img :src="p.user.profile_image" class="rounded-circle" alt="Profil"
                      style="width: 50px; height: 50px; object-fit: cover;"
                    >
                    <div>
                      <small class="d-block fw-bold text-dark">{{ p.user?.name || 'Utilisateur inconnu' }}</small>
                      <small class="text-success">
                        {{ p.user?.user_type === 2 ? 'Agence immobilière' : 'Particulier' }}
                      </small>
                    </div>
                  </div>
                </div>

                <!-- CONTACT -->
                <div class="d-flex bg-dark text-light border-light border-top">
                  <small v-if="p.phone1?.trim()" class="flex-fill text-center py-2">
                    <i class="fa fa-phone text-primary me-2"></i>
                    <span :class="{ 'blur-phone': !isAuthenticated.value }">{{ p.phone1 }}</span>
                  </small>
                  <small v-if="p.phone2?.trim()" class="flex-fill text-center py-2">
                    <i class="fa fa-phone text-primary me-2"></i>
                    <span :class="{ 'blur-phone': !isAuthenticated.value }">{{ p.phone2 }}</span>
                  </small>
                </div>

                <!-- SURFACE / CHAMBRES -->
                <div class="d-flex bg-dark text-light border-top">
                  <small v-if="p.surface" class="flex-fill text-center py-2"><i
                      class="fa fa-ruler-combined text-primary me-2"></i>{{ p.surface }} m²</small>
                  <small v-if="p.bathroom" class="flex-fill text-center py-2"><i
                      class="fa fa-bed text-primary me-2"></i>{{ p.bathroom }} Chambres</small>
                </div>

              </div>
            </div>

            <div class="col-12 text-center py-5">
              <div v-if="loadingPublications" class="d-flex justify-content-center align-items-center"
                style="height: 200px;">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Chargement...</span>
                </div>
              </div>
              <div v-else-if="filteredPublications.length === 0">
                <p class="text-danger">Désolée, aucune publication correspondante au filtrage n'a été trouvée!</p>
              </div>
            </div>

          </div>
        </div>
      </div>

    </div>

    <!-- MODAL -->
    <div class="modal fade" id="publicationModal" tabindex="-1">
      <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header text-light">
            <h5 class="modal-title">{{ selectedPublication?.title }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <!-- Carrousel d'images modal -->
            <div v-if="selectedPublication?.images?.length" id="carouselImages" class="carousel slide"
              data-bs-ride="carousel" data-bs-interval="5000">
              <div class="carousel-inner rounded">
                <div v-for="(img, index) in selectedPublication.images" :key="index" class="carousel-item"
                  :class="{ active: index === 0 }">
                  <img :src="img" class="d-block w-100" style="max-height: 400px; object-fit: cover;"
                    alt="Image de la publication">
                </div>
              </div>
              <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
              </button>
              <button class="carousel-control-next" type="button" data-bs-target="#carouselImages" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
              </button>
            </div>

            <!-- Infos détaillées -->
            <div class="mt-4 bg-dark">
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
                        <span v-for="attr in selectedPublication.attributes" :key="attr.id"
                          class="badge bg-success me-1 mb-1">
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
                    <th>Caution</th>
                    <td>{{ selectedPublication?.deposit }} Mois</td>
                  </tr>
                  <tr>
                    <th>Avance</th>
                    <td>{{ selectedPublication?.advance }} Mois</td>
                  </tr>
                  <tr>
                    <th>Visit</th>
                    <td>{{ formatPrice(selectedPublication?.visit) }}</td>
                  </tr>
                  <tr>
                    <th>Localisation</th>
                    <td>{{ selectedPublication?.district_name || selectedPublication?.town_name ||
                      selectedPublication?.country_name || 'Non définie' }}</td>
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
                    <th>Téléphones</th>
                    <td>
                      <p v-if="selectedPublication?.phone1">
                        <i class="fa fa-phone text-primary me-2">1</i>: 
                        <span :class="{ 'blur-phone': !isAuthenticated.value }">{{ selectedPublication.phone1 }}</span>
                      </p>
                      <p v-if="selectedPublication?.phone2">
                        <i class="fa fa-phone text-primary me-2">2</i>: 
                        <span :class="{ 'blur-phone': !isAuthenticated.value }">{{ selectedPublication.phone2 }}</span>
                      </p>
                      <span v-if="!selectedPublication?.phone1 && !selectedPublication?.phone2">Pas de Numéro
                        Disponible</span>
                    </td>
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

  </div>
</template>

<script setup>
  import { ref, computed, watch, nextTick } from 'vue'
  import axios from 'axios'
  import { Modal, Carousel } from 'bootstrap'
  import vSelect from "vue-select"
  import "vue-select/dist/vue-select.css"
  import { user, isAuthenticated } from '../../auth/auth.js'

  console.log(user.value)            // données de l'utilisateur connecté
  console.log(isAuthenticated.value) // true/false


  // -----------------
  // REFS
  // -----------------
  const filters = ref({
    keyword: '',
    country_id: null,
    town_id: null,
    district_id: [],
    category_id: null,
    pub_type_id: null,
    attribute_ids: [],
  })
  const price1 = ref(null)
  const price2 = ref(null)


  const countries = ref([])
  const towns = ref([])
  const districts = ref([])
  const categories = ref([])
  const pubTypes = ref([])
  const publicationsList = ref([])
  const selectedPublication = ref(null)
  const placeholderImage = '/images2/property-placeholder.jpg'
  const loadingPublications = ref(false)
  const activeTab = ref('featured')
  const attributes = ref([])
  const loadingAttributes = ref(false)

  const loadingCountries = ref(false)
  const loadingTowns = ref(false)
  const loadingDistricts = ref(false)
  const loadingCategories = ref(false)
  const loadingPubTypes = ref(false)

  // -----------------
  // FILTRAGE FRONT (TAB)
  // -----------------
  const filteredPublications = computed(() => {
    let list = [...publicationsList.value]

    if (activeTab.value === 'sell') list = list.filter(p => p.offer_type === 'sale')
    if (activeTab.value === 'rent') list = list.filter(p => p.offer_type === 'rent')

    return list
  })

  // -----------------
  // FETCH OPTIONS
  // -----------------
  const fetchCountries = async () => {
    loadingCountries.value = true
    try {
      const res = await axios.get('/api/country')
      countries.value = res.data
    } finally { loadingCountries.value = false }
  }

  const fetchTowns = async () => {
    if (!filters.value.country_id) { towns.value = []; districts.value = []; filters.value.town_id = null; filters.value.district_id = null; return }
    loadingTowns.value = true
    try {
      const res = await axios.get(`/api/town?country_id=${filters.value.country_id}`)
      towns.value = res.data
      filters.value.town_id = null
      districts.value = []
      filters.value.district_id = null
    } finally { loadingTowns.value = false }
  }

  const fetchDistricts = async () => {
    if (!filters.value.town_id) { districts.value = []; filters.value.district_id = null; return }
    loadingDistricts.value = true
    try {
      const res = await axios.get(`/api/district?town_id=${filters.value.town_id}`)
      districts.value = res.data
      filters.value.district_id = null
    } finally { loadingDistricts.value = false }
  }

  const fetchCategories = async () => {
    // Si aucun pays sélectionné, on vide les catégories et on sort
    if (!filters.value.country_id) {
      categories.value = [];
      filters.value.category_id = null;
      return;
    }

    loadingCategories.value = true;
    try {
      // On passe le country_id en paramètre pour récupérer seulement les catégories disponibles dans ce pays
      const res = await axios.get('/api/category', { params: { country_id: filters.value.country_id } });
      categories.value = res.data;
      filters.value.category_id = null;
    } finally {
      loadingCategories.value = false;
    }
  };

  const fetchPubTypes = async () => {
    if (!filters.value.category_id) { pubTypes.value = []; filters.value.pub_type_id = null; return }
    loadingPubTypes.value = true
    try {
      const res = await axios.get(`/api/pub-type?category_id=${filters.value.category_id}`)
      pubTypes.value = res.data
      filters.value.pub_type_id = null
    } finally { loadingPubTypes.value = false }
  }

  watch(() => filters.value.pub_type_id, async (val) => {
    if (!val) {
      attributes.value = []
      filters.value.attribute_ids = []
      return
    }
    loadingAttributes.value = true
    try {
      const res = await axios.get(`/api/attribute?pub_type_id=${val}`)
      attributes.value = res.data
      filters.value.attribute_ids = []
    } finally { loadingAttributes.value = false }
  })

  // -----------------
  // FETCH PUBLICATIONS
  // -----------------
  const initCarousels = () => {
    publicationsList.value.forEach((p, i) => {
      const el = document.getElementById('carouselList' + i)
      if (el && !el._bs_carousel) {  // <- NE recrée QUE si pas déjà initialisé
        new Carousel(el)
      }
    })
  }

  const fetchPublicationsInitial = async () => {
    loadingPublications.value = true
    try {
      const res = await axios.get('/api/publication', { params: { limit: 150 } })
      publicationsList.value = res.data
      nextTick(initCarousels)
    } catch (err) {
      console.error(err)
    } finally { loadingPublications.value = false }
  }

  const searchPublications = async () => {
    loadingPublications.value = true
    try {
      const res = await axios.get('/api/publication', {
        params: {
          keyword: filters.value.keyword,
          country_id: filters.value.country_id,
          town_id: filters.value.town_id,
          district_id: filters.value.district_id,
          category_id: filters.value.category_id,
          pub_type_id: filters.value.pub_type_id,
          attribute_ids: filters.value.attribute_ids,
          price1: price1.value,
          price2: price2.value
        }
      })

      console.log('Publications reçues:', res.data)
      publicationsList.value = res.data
      nextTick(initCarousels)
    } catch (err) {
      console.error(err)
    } finally { loadingPublications.value = false }
  }



  // -----------------
  // WATCHERS OPTIONS
  // -----------------
  watch(() => filters.value.country_id, fetchTowns)
  watch(() => filters.value.country_id, fetchCategories)
  watch(() => filters.value.town_id, fetchDistricts)
  watch(() => filters.value.category_id, fetchPubTypes)

  // --- MODAL ---
  const modalInstance = ref(null)

  const openModal = (pub) => {
    selectedPublication.value = pub

    nextTick(() => {
      const modalEl = document.getElementById('publicationModal')

      // Création du modal s'il n'existe pas encore
      if (!modalInstance.value) {
        modalInstance.value = new Modal(modalEl)
      }

      // Toujours s'assurer de réinitialiser le carousel dans le modal
      const carouselEl = document.getElementById('carouselImages')
      if (carouselEl) {
        if (carouselEl._bs_carousel) carouselEl._bs_carousel.dispose()
        new Carousel(carouselEl)
      }

      // Ouvre le modal
      modalInstance.value.show()
    })
  }

  // -----------------
  // FORMAT PRIX
  // -----------------
  const formatPrice = (price) => {
    if (!price) return '0 FCFA'
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(price)
  }

  // -----------------
  // INIT
  // -----------------
  fetchCountries()
  fetchCategories()
  fetchPublicationsInitial()

  const formatRelativeDate = (date) => {
    const now = new Date();
    const published = new Date(date);
    const diff = now - published; // différence en ms

    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours   = Math.floor(minutes / 60);
    const days    = Math.floor(hours / 24);

    if (days > 0) return `il y a ${days} jour${days > 1 ? 's' : ''}`;
    if (hours > 0) return `il y a ${hours} heure${hours > 1 ? 's' : ''}`;
    if (minutes > 0) return `il y a ${minutes} minute${minutes > 1 ? 's' : ''}`;
    return `il y a quelques secondes`;
  }

</script>

<style scoped>
  .eye-alert-btn {
    background-color: white;
    color: black;
    border-radius: 50%;
    animation: blink 4s infinite;
    transition: all 0.3s ease;
  }

  .eye-alert-btn:hover {
    transform: scale(1.1);
  }

  .custom-info-table {
    border-collapse: separate;
    border-spacing: 0 8px;
    width: 100%;
  }

  .custom-info-table th {
    width: 35%;
    font-weight: 600;
    color: #ffffff;
    background-color: rgba(14, 46, 80);
    padding: 10px 15px;
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
  }

  .custom-info-table td {
    color: #ffffff;
    background-color: rgba(14, 46, 80);
    padding: 10px 15px;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
    box-shadow: 0 1px 3px rgba(247, 246, 246, 0.842);
  }

  .custom-info-table tr:nth-child(even) td,
  .custom-info-table tr:nth-child(even) th {
    background-color: rgba(14, 46, 80);
  }

  @keyframes blink {

    0%,
    100% {
      background-color: white;
      color: black;
      opacity: 1;
    }

    50% {
      background-color: black;
      color: white;
      opacity: 0.3;
    }
  }

  .blink-btn {
    animation: blink 3s infinite;
    width: 40px;
    height: 40px;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(255, 255, 255, 0.8);
    color: black;
    z-index: 5;
  }

  .carousel-control-prev.blink-btn {
    left: 10px;
  }

  .carousel-control-next.blink-btn {
    right: 10px;
  }

  .blink-btn span {
    background-size: 100% 100%;
  }

  /* Bord du select */
  ::v-deep(.vs__dropdown-toggle) {
    border: 2px solid #ffffff;
    border-radius: 8px;
  }

  /* Texte du placeholder */
  ::v-deep(.vs__placeholder) {
    color: #000000;
    font-style: italic;
  }

  /* Texte sélectionné */
  ::v-deep(.vs__selected) {
    background-color: #198754 !important;
    color: #ffffff;
    font-weight: bold;
  }

  /* Fond de toutes les options */
  ::v-deep(.vs__dropdown-option) {
    background-color: #ffffff;
    color: #000000;
  }

  /* Option survolée */
  ::v-deep(.vs__dropdown-option--highlight) {
    background-color: #1fad6b;
    color: #fff !important;
  }

  /* Option sélectionnée dans le menu */
  ::v-deep(.vs__dropdown-option--selected) {
    background-color: #198754 !important;
    color: white !important;
  }

  /* Couleur de la croix (clear icon) */
  ::v-deep(.vs__clear) {
    fill: #dc3545;
    cursor: pointer;
  }

  ::v-deep(.vs__clear:hover) {
    fill: #a71d2a;
  }

  /* Couleur de la flèche (dropdown icon) */
  ::v-deep(.vs__open-indicator) {
    fill: #ffffff;
    transition: transform 0.2s ease, fill 0.2s ease;
  }

  ::v-deep(.vs--open .vs__open-indicator) {
    fill: #f1f4f8;
    transform: rotate(180deg);
  }

  .blur-phone {
  filter: blur(5px);
  cursor: not-allowed;
  user-select: none;
  transition: filter 0.3s ease;
}

/* Optionnel : on peut afficher un tooltip au hover */
.blur-phone:hover::after {
  content: "Connectez-vous pour voir le numéro";
  position: absolute;
  background: rgba(0,0,0,0.7);
  color: #fff;
  padding: 2px 6px;
  border-radius: 4px;
  font-size: 12px;
  margin-left: 5px;
}

</style>
