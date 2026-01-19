<template>
  <!-- Liste des propriétés Début -->
  <div v-if="isAuthenticated" class="container-xxl py-5">
    <div class="container">
      <!-- 📌 En-tête + Boutons de filtre -->
      <div class="row g-0 gx-5 align-items-end">
        <div class="col-lg-6">
          <div class="text-start mx-auto mb-5">
            <h1 class="mb-3">Mes publications</h1>
            <h6>La liste de vos publications s'affichera ci-dessous une fois créées.</h6>
          </div>
        </div>
        <div class="col-lg-6 text-start text-lg-end">
          <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
            <li class="nav-item me-2">
              <button class="btn btn-outline-dark" :class="{ active: activeTab==='featured' }" @click="activeTab='featured'">Tous</button>
            </li>
            <li class="nav-item me-2">
              <button class="btn btn-outline-danger" :class="{ active: activeTab==='sell' }" @click="activeTab='sell'">À vendre</button>
            </li>
            <li class="nav-item me-0">
              <button class="btn btn-outline-info" :class="{ active: activeTab==='rent' }" @click="activeTab='rent'">À louer</button>
            </li>
          </ul>
        </div>
        <div class="row mb-4">
          <div class="col-md-12">
            <input type="text" class="form-control" placeholder="#Collez le code de la publication pour filtrer...." v-model="codeFilter"/>
          </div>
      </div>

      </div>

      <!-- 📌 Liste des publications -->
      <div class="tab-content">
        <div class="tab-pane fade show p-0 active">
          <div class="row g-4">
            <div v-for="(p, i) in filteredPublications" :key="p.id || i" class="col-lg-4 col-md-6 wow fadeInUp" :data-wow-delay="p.delay || '0.1s'" >
              <div class="property-item rounded overflow-hidden shadow">
                <div class="position-relative overflow-hidden">
                  <div v-if="p.images && p.images.length" :id="'carouselList' + i" class="carousel slide" data-bs-ride="carousel" :data-bs-interval="5000">
                    <div class="carousel-inner rounded">
                      <div v-for="(img, index) in p.images" :key="index" class="carousel-item" :class="{ active: index === 0 }">
                        <img :src="img.list" class="d-block w-100" style="height: 200px; object-fit: cover;" alt="">
                      </div>
                    </div>

                    <!-- Boutons ronds centrés verticalement -->
                    <button class="carousel-control-prev blink-btn rounded-circle border-0 d-flex align-items-center justify-content-center" type="button" :data-bs-target="'#carouselList' + i" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next blink-btn rounded-circle border-0 d-flex align-items-center justify-content-center" type="button" :data-bs-target="'#carouselList' + i" data-bs-slide="next">
                      <span class="carousel-control-next-icon"></span>
                    </button>
                  </div>
                  <div v-else>
                    <img class="img-fluid w-100" style="height: 200px; object-fit: cover;" :src="placeholderImage" alt="">
                  </div>

                  <!-- Type d'offre -->
                  <div :class="['rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3', p.offer_type === 'sale' ? 'bg-danger' : 'bg-info']">
                    {{ p.offer_type === 'sale' ? 'À vendre' : 'À louer' }}
                  </div>

                  <!-- Catégorie -->
                  <div class="bg-dark text-light rounded-top text-primary position-absolute start-0 bottom-0 mx-0 pt-1 px-5">
                    {{ p.category_name || 'Type inconnu' }} {{ p.code || ' - JHSHS4568D45SDJ' }}
                  </div>

                  <!-- 👁 Bouton voir plus avec animation -->
                  <!-- <button class="btn eye-alert-btn position-absolute end-0 top-0 m-2 shadow" @click="openModal(p)">
                    <i class="fa fa-eye"></i>
                  </button> -->
                </div>

                <!-- Attributs sous le prix / titre -->
                <div class="mb-2 d-flex flex-wrap px-4 pt-3" @click="openModal(p)">
                  <span v-for="attr in p.attributes || []" :key="attr.id" class="badge bg-dark me-1 mb-1">
                    {{ attr.name }}
                  </span>
                </div>

                <!-- Contenu principal en deux colonnes côte-à-côte -->
                <div class="p-2  pb-0" @click="openModal(p)">
                  <div class="row bg-dark text-light border-top gx-2">
                    <div class="col-12 col-md-10 text-center">
                      <h5 class="text-light text-center mb-3">
                        {{ formatPrice(p.price) }} / {{ formatPeriod(p.price_period) }}
                      </h5>

                      <a class="d-block h4 text-light mb-2" href="#">{{ p.title || 'Titre non défini' }}</a>
                      <p class="mb-3"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ p.district_name }} || {{ p.town_name }}</p>
                    </div>
                  </div>
                </div>

                <!-- Date de publication -->
                <div class="d-flex">
                  <small class="flex-fill text-center py-2">
                    <marquee class="" behavior="scroll" direction="left" scrollamount="6">
                      <strong>Publiée {{ formatRelativeDate(p.created_at) }} .</strong>
                    </marquee>
                  </small>
                </div>

                <!-- Boutons Modifier -->
                <div class="d-flex border">
                  <small class="flex-fill text-center py-2">
                    <button title="Modifier"
                      @click="openEditModal(p.id)" 
                      class="btn btn-sm btn-secondary mb-2"
                      :disabled="loadingEdit === p.id"
                    >
                      <span v-if="loadingEdit === p.id" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                      <i class="fa fa-edit text-light me-0"></i>
                    </button>
                  </small>
                  <!-- Nouveau bouton Statut -->
                  <small class="flex-fill text-center py-2">
                    <button
                      :title="p.is_active ? 'Publication active' : 'Publication inactive'"
                      class="status-indicator-btn mb-2"
                      :class="{ active: p.is_active, inactive: !p.is_active }"
                    ></button>
                  </small>
                  <!-- Boutons Supprimer -->
                  <small class="flex-fill text-center py-2">
                    <button title="Supprimer" class="btn btn-sm btn-danger" @click="deletePublication(p.id)"><i class="fa fa-trash text-light me-0"></i></button>
                  </small>
                </div>

                <!-- Téléphones -->
                <div class="d-flex bg-dark text-light border-top">
                  <small v-if="p.phone1 && p.phone1 !== 'null' && p.phone1.trim() !== ''" class="flex-fill text-center py-2"><i class="fa fa-phone text-primary me-2"></i>{{ p.phone1 }}</small>
                  <small v-if="p.phone2 && p.phone2 !== 'null' && p.phone2.trim() !== ''" class="flex-fill text-center py-2"><i class="fa fa-phone text-primary me-2"></i>{{ p.phone2 }}</small>
                </div>

                <div class="d-flex bg-dark text-light border-top">
                  <small v-if="p.surface" class="flex-fill text-center py-2"><i class="fa fa-ruler-combined text-primary me-2"></i>{{ p.surface || 0 }} m²</small>
                  <small v-if="p.bathroom" class="flex-fill text-center py-2"><i class="fa fa-bed text-primary me-2"></i>{{ p.bathroom || 0 }} Chambres</small>
                </div>
              </div>
            </div>
            <div class="col-12 text-center py-5">
              <div v-if="loadingPublications" class="d-flex justify-content-center align-items-center" style="height: 200px;">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Chargement...</span>
                </div>
              </div>
              <div v-else-if="filteredPublications.length === 0">
                <img class="img-fluid" :src="gif" alt="gif" style="width: auto;">
                 <p class="text-danger"><strong>Aucune publication pour ce code saisi.</strong> </p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <div v-if="!isAuthenticated" class="card shadow-lg bg p-4 rounded">
    <!-- <h4 class="text-center text-light">Veuillez vous connecter pour accéder à votre liste de publications.</h4> -->
    <!-- 404 Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-danger">
                    <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                    <h1 class="display-1 ">Erreur 404</h1>
                    <h1 class="mb-4">Liste non trouvée</h1>
                    <p class="mb-4">Veuillez vous connecter pour accéder à votre liste de publications.</p>
                    <a class="btn btn-primary py-3 px-5" @click="goToLogin">Se connecter</a>
                </div>
            </div>
        </div>
    </div>
    <!-- 404 End -->
  </div>

  <!-- 📌 Modal de détails de la publication -->
  <div class="modal fade mt-5 mb-5" id="publicationModal" tabindex="-1" aria-hidden="true" ref="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-light">{{ selectedPublication?.title || 'Détails de la publication' }}</h5>
          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Carrousel d'images modal -->
          <div v-if="selectedPublication?.images?.length" id="carouselImages" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner rounded">
              <div v-for="(img, index) in selectedPublication.images" :key="index" class="carousel-item" :class="{ active: index === 0 }">
                <img :src="img.detail" class="d-block w-100" style=" object-fit: cover;" alt="Image de la publication">
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
          <div class="mt-4">
            <table class="table custom-info-table">
              <tbody>
                <tr><th>Code</th><td>{{ selectedPublication?.code || 'JHSHS4568D45SDJ' }}</td></tr>
                <tr><th>Catégorie</th><td>{{ selectedPublication?.category_name || 'Catégorie inconnue' }}</td></tr>
                <tr><th>Type d'offre</th><td>{{ selectedPublication?.offer_type === 'sale' ? 'À vendre' : 'À louer' }}</td></tr>
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
                <tr><th>Prix</th><td>{{ formatPrice(selectedPublication?.price) }}<strong v-if="selectedPublication?.category_name !== 'Terrain'"> / {{ formatPeriod(selectedPublication?.price_period) }}</strong></td></tr>
                <tr v-if="selectedPublication?.category_name !== 'Terrain'">
                  <th>Caution</th><td>{{ selectedPublication?.deposit }} {{ selectedPublication?.deposit>12?'F CFA':'Mois' }}</td>
                </tr>
                <tr v-if="selectedPublication?.category_name !== 'Terrain'">
                  <th>Avance</th><td>{{ selectedPublication?.advance }} {{ selectedPublication?.advance>12?'F CFA':'Mois' }}</td>
                </tr>
                <tr v-if="selectedPublication?.category_name !== 'Terrain'"><th>Commission</th><td>{{ selectedPublication?.commission || '-'}} F CFA</td></tr>
                <tr v-if="selectedPublication?.category_name !== 'Terrain'"><th>Visite</th><td>{{ formatPrice(selectedPublication?.visit) }}</td></tr>
                <tr>
                  <th>Localisation</th><td>{{ selectedPublication?.district_name || selectedPublication?.town_name || selectedPublication?.country_name || 'Non définie' }}</td>
                </tr>
                <tr v-if="selectedPublication?.category_name !== 'Terrain'"><th>Ménage</th><td>{{ selectedPublication?.surface || '-'}} </td></tr>
                <tr v-if="selectedPublication?.category_name !== 'Terrain'"><th>Chambres</th><td>{{ selectedPublication?.bathroom || '-' }}</td></tr>
                <tr><th>Description</th><td>{{ selectedPublication?.description || 'Aucune description disponible' }}</td></tr>
                <tr>
                  <th>Téléphones</th>
                  <td>
                    <p v-if="selectedPublication?.phone1 && selectedPublication.phone1 !== 'null' && selectedPublication.phone1.trim() !== ''">
                      <i class="fa fa-phone text-primary me-2">1</i>: {{ selectedPublication.phone1 }}
                    </p>
                    <p v-if="selectedPublication?.phone2 && selectedPublication.phone2 !== 'null' && selectedPublication.phone2.trim() !== ''">
                      <i class="fa fa-phone text-primary me-2">2</i>: {{ selectedPublication.phone2 }}
                    </p>
                    <span v-if="!selectedPublication?.phone1 && !selectedPublication?.phone2">Pas de Numéro Disponible</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- <div class="modal-footer">
          <button class="btn btn-outline-primary" :disabled="!selectedPublication?.phone1"
                  @click="window.location.href = 'tel:' + selectedPublication.phone1">
            <i class="fa fa-phone me-2"></i> Contacter
          </button>
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div> -->
      </div>
    </div>
  </div>

  <!-- Modal d'édition -->
  <EditPublication 
    v-if="showEditModal" 
    :publication-id="editingPubId" 
    @close="showEditModal = false" 
    @updated="fetchPublications" 
  />
</template>

<script setup>
  import gif from '@images2/empty.gif'
  import { ref, computed, watch, nextTick } from 'vue'
  import { Modal, Carousel } from 'bootstrap'
  import EditPublication from './edit.vue'
  import axios from 'axios'
  import Swal from 'sweetalert2'
  import { user, isAuthenticated } from '../../auth/auth.js'
  import { useRouter } from 'vue-router'
  const router = useRouter()
  const codeFilter = ref('')

  const goToLogin = () => {
    router.push('/login')
  }

  const formatPeriod = (period) => {
    switch(period){
      case 'month': return 'Mois'
      case 'week': return 'Semaine'
      case 'day': return 'Jour'
      default: return ''
    }
  }

  const showEditModal = ref(false)
  const editingPubId = ref(null)
  const publicationsList = ref([])
  const selectedPublication = ref(null)
  const modalInstance = ref(null)
  const placeholderImage = '/images2/property-placeholder.jpg'
  const loadingEdit = ref(null)
  const loadingPublications = ref(false)

  const props = defineProps({
    publications: { type: Array, required: true }
  })

  // Copier les publications du parent
  watch(() => props.publications, (newList) => {
    publicationsList.value = [...newList]
  }, { immediate: true })

  // Initialiser tous les carrousels des publications
  const initCarousels = () => {
    nextTick(() => {
      publicationsList.value.forEach((p, i) => {
        const el = document.getElementById('carouselList' + i)
        if (el) new Carousel(el)
      })
    })
  }

  if (!isAuthenticated.value) {
    console.warn("Page accessible uniquement aux utilisateurs connectés.")
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title: 'Page accessible uniquement aux utilisateurs connectés.',
      showConfirmButton: false,
      timer: 3000
    })
  }

  // Charger les publications
  const fetchPublications = async () => {
    loadingPublications.value = true
    try {
      const res = await axios.get('/getMyPublication')
      publicationsList.value = res.data
      initCarousels()
    } catch (err) {
      console.error('Erreur lors du chargement des publications', err)
    } finally {
      loadingPublications.value = false
    }
  }

  // Filtrage par onglet
  const activeTab = ref('featured')
  // const filteredPublications = computed(() => {
  //   if (!publicationsList.value) return []
  //   if (activeTab.value === 'featured') return publicationsList.value
  //   if (activeTab.value === 'sell') return publicationsList.value.filter(p => p.offer_type === 'sale')
  //   if (activeTab.value === 'rent') return publicationsList.value.filter(p => p.offer_type === 'rent')
  //   return publicationsList.value
  // })

  // Ouvrir le modal et initialiser le carrousel
  const openModal = (pub) => {
    console.log('Publication sélectionnée:', pub); 
    selectedPublication.value = pub
    nextTick(() => {
      if (!modalInstance.value) modalInstance.value = new Modal(document.getElementById('publicationModal'))
      modalInstance.value.show()

      // Initialiser carrousel du modal
      const carouselEl = document.getElementById('carouselImages')
      if (carouselEl) new Carousel(carouselEl)
    })
  }

  // Modal d'édition
  const openEditModal = async (id) => {
    editingPubId.value = id
    loadingEdit.value = id
    showEditModal.value = true
    setTimeout(() => loadingEdit.value = null, 8000)
  }

  // Formatage du prix
  const formatPrice = (price) => {
    if (!price) return '0 FCFA'
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'XOF' }).format(price)
  }

  // Supprimer une publication
  const deletePublication = async (id) => {
    const result = await Swal.fire({
      title: 'Êtes-vous sûr ?',
      text: "Cette action est irréversible !",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Oui, supprimer',
      cancelButtonText: 'Annuler'
    })

    if (result.isConfirmed) {
      try {
        await axios.delete(`/api/publication/${id}`)
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: 'Publication supprimée ✅',
          showConfirmButton: false,
          timer: 3000
        })
        fetchPublications()
      } catch (err) {
        console.error('Erreur lors de la suppression', err)
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          title: 'Impossible de supprimer la publication',
          showConfirmButton: false,
          timer: 3000
        })
      }
    }
  }

  const filteredPublications = computed(() => {
    if (!publicationsList.value) return []

    let list = publicationsList.value

    // Filtre par onglet
    if (activeTab.value === 'sell') list = list.filter(p => p.offer_type === 'sale')
    else if (activeTab.value === 'rent') list = list.filter(p => p.offer_type === 'rent')

    // Filtre par code uniquement si longueur > 3
    if (codeFilter.value.trim().length > 3) {
      list = list.filter(p => p.code?.toLowerCase() === codeFilter.value.trim().toLowerCase())
    }

    return list
  })

  const formatRelativeDate = (date) => {
    const now = new Date();
    const published = new Date(date);
    
    const diff = now - published; // différence en ms

    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours   = Math.floor(minutes / 60);
    const days    = Math.floor(hours / 24);
    const months  = Math.floor(days / 30);
    const years   = Math.floor(days / 365);

    if (years > 0) {
      const remainingMonths = Math.floor((days % 365) / 30);
      return `il y a ${years} an${years > 1 ? 's' : ''}${remainingMonths > 0 ? ` ${remainingMonths} mois` : ''}`;
    }

    if (months > 0) {
      const remainingDays = days % 30;
      return `il y a ${months} mois${remainingDays > 0 ? ` ${remainingDays} jour${remainingDays > 1 ? 's' : ''}` : ''}`;
    }

    if (days > 0) return `il y a ${days} jour${days > 1 ? 's' : ''}`;
    if (hours > 0) return `il y a ${hours} heure${hours > 1 ? 's' : ''}`;
    if (minutes > 0) return `il y a ${minutes} minute${minutes > 1 ? 's' : ''}`;
    return `il y a quelques secondes`;
  };

</script>


<style scoped>
.eye-alert-btn {
  background-color: white;
  color: black;
  border-radius: 50%;
  animation: blink 4s infinite;
  transition: all 0.3s ease;
}
.eye-alert-btn:hover { transform: scale(1.1); }

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
.custom-info-table tr:nth-child(even) th { background-color: rgba(14, 46, 80); }

@keyframes blink {
  0%, 100% { background-color: white; color: black; opacity: 1; }
  50% { background-color: black; color: white; opacity: 0.3; }
}
.blink-btn {
  animation: blink 3s infinite;
  width: 40px;
  height: 40px;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background-color: rgba(255,255,255,0.8);
  color: black;
  z-index: 5;
}
.carousel-control-prev.blink-btn { left: 10px; }
.carousel-control-next.blink-btn { right: 10px; }
.blink-btn span { background-size: 100% 100%; }

/* ✅ Bouton indicateur de statut (actif/inactif) */
.status-indicator-btn {
  width: 18px;
  height: 18px;
  border-radius: 50%;
  border: none;
  outline: none;
  cursor: default;
  display: inline-block;
  animation: blink 1s infinite;
}

/* Vert clignotant si actif */
.status-indicator-btn.active {
  background-color: #1fad6b;
  box-shadow: 0 0 10px #1fad6b;
}

/* Rouge clignotant si inactif */
.status-indicator-btn.inactive {
  background-color: #ff2e2e;
  box-shadow: 0 0 10px #ff2e2e;
}

/* Animation de clignotement */
@keyframes blink {
  0%, 100% {
    opacity: 1;
    transform: scale(1);
  }
  50% {
    opacity: 0.6;
    transform: scale(0.9);
  }
}

</style>
