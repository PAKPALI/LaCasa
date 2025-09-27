<template>
  <!-- Loader global avant table -->
  <div v-if="loadingTable" class="text-center my-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Chargement...</span>
    </div>
  </div>

  <!-- Modals -->

  <!-- Ajouter -->
  <div class="modal fade mt-5" id="addPubTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-light">Ajouter un type de pub</h5>
          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label">Nom</label>
              <input type="text" class="form-control" v-model="name">
              <label :class="labelNameLog">{{ nameLog }}</label>
            </div>
            <div class="mb-3">
              <label class="form-label">Catégorie</label>
              <v-select v-model="categoryId" :options="Categories" label="name" :reduce="c => c.id"
                placeholder="Rechercher une catégorie..." :filterable="true" :loading="loadingCategories" />
              <label :class="labelCategoryLog">{{ categoryLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-dark" @click="savePubType" :disabled="!isFormValid || loadingButton === 'save'">
            <span v-if="loadingButton === 'save'" class="spinner-border spinner-border-sm text-light"></span>
            <span v-if="loadingButton !== 'save'">Ajouter</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modifier -->
  <div class="modal fade mt-5" id="updatePubTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Modifier un type de pub</h5>
          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label">Nom</label>
              <input type="text" class="form-control" v-model="name">
              <label :class="labelNameLog">{{ nameLog }}</label>
            </div>
            <div class="mb-3">
              <label class="form-label">Catégorie</label>
              <v-select v-model="categoryId" :options="Categories" label="name" :reduce="c => c.id"
                placeholder="Rechercher une catégorie..." :filterable="true" :loading="loadingCategories" />
              <label :class="labelCategoryLog">{{ categoryLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-warning" @click="updatePubType" :disabled="!isFormValid || loadingButton === 'update'">
            <span v-if="loadingButton === 'update'" class="spinner-border spinner-border-sm text-dark"></span>
            <span v-if="loadingButton !== 'update'">Modifier</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Visualiser -->
  <div class="modal fade mt-5" id="viewPubTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title text-dark">Détails du type de pub</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p><strong>Nom :</strong> {{ selectedPubType ? selectedPubType.name : '' }}</p>
          <p><strong>Catégorie :</strong> {{ selectedPubType && selectedPubType.category ? selectedPubType.category.name: '—' }}</p>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Accordéon PubTypes -->
  <div class="accordion-item mb-4">
    <h2 class="accordion-header" id="headingPubType">
      <button class="accordion-button bg-primary text-light" type="button" data-bs-toggle="collapse"
        data-bs-target="#collapsePubType" aria-expanded="true" aria-controls="collapsePubType">
        SECTION TYPES DE PUB
      </button>
    </h2>
    <div id="collapsePubType" class="accordion-collapse collapse show" aria-labelledby="headingPubType"
      data-bs-parent="#accordionExample">
      <div class="accordion-body bg-dark text-white">
        <p>Vous pouvez enregistrer, modifier, visualiser ou supprimer un type de pub (Piece,chambre salon etc...).</p>

        <!-- Ajouter -->
        <div class="row mt-3 mb-3">
          <div class="col-8">
            <h2>Liste Types de pub</h2>
          </div>
          <div class="col-4">
            <button class="btn btn-dark border mt-3 me-2" @click="addPubType">+ Ajouter</button>
          </div>
        </div>

        <!-- Search -->
        <div class="mb-3">
          <input type="text" class="form-control" placeholder="Rechercher un type ou une catégorie..."
            v-model="searchQuery">
        </div>

        <!-- Tableau -->
        <table v-if="!loadingTable" class="table table-primary table-bordered border-dark table-hover">
          <thead class="table-primary border-dark">
            <tr>
              <th>#</th>
              <th>Nom</th>
              <th>Catégorie</th>
              <th>Pays</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(pubType, index) in paginatedPubTypes" :key="pubType.id">
              <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
              <td>{{ pubType.name }}</td>
              <td>{{ pubType.category ? pubType.category.name : '—' }}</td>
              <td>{{ pubType.category && pubType.category.country ? pubType.category.country.name : '—' }}</td>
              <td>
                <button class="btn btn-sm btn-info me-1" @click="viewPubType(pubType)">
                  <span><i class="bi bi-eye"></i></span>
                </button>
                <button class="btn btn-sm btn-warning me-1" @click="pubTypeUpdated(pubType)"
                  :disabled="loadingButton === 'update'">
                  <span v-if="loadingButton === 'update'" class="spinner-border spinner-border-sm text-dark"></span>
                  <span v-if="loadingButton !== 'update'"><i class="bi bi-pencil-square"></i></span>
                </button>
                <button class="btn btn-sm btn-danger" @click="deletedPubType(pubType.id)"
                  :disabled="loadingButton === pubType.id">
                  <span v-if="loadingButton === pubType.id" class="spinner-border spinner-border-sm text-light"></span>
                  <span v-if="loadingButton !== pubType.id"><i class="bi bi-trash"></i></span>
                </button>
              </td>
            </tr>
            <tr v-if="filteredPubTypes.length === 0">
              <td colspan="4" class="text-center text-danger">Aucun type trouvé</td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="!loadingTable" class="d-flex justify-content-between align-items-center mt-2">
          <button class="btn btn-sm btn-dark" @click="prevPage" :disabled="currentPage === 1">Précédent</button>
          <span>Page {{ currentPage }} / {{ totalPages }}</span>
          <button class="btn btn-sm btn-dark" @click="nextPage" :disabled="currentPage === totalPages">Suivant</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { Modal } from 'bootstrap'
import axios from 'axios'
import Swal from 'sweetalert2'
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css"

// Données
const name = ref('')
const categoryId = ref('')
const PubTypes = ref([])
const Categories = ref([])
let pubTypeId = 0
const selectedPubType = ref(null)
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(5)
const loadingButton = ref('')
const loadingTable = ref(true)
const loadingCategories = ref(false)

// Computed validations
const nameLog = computed(() => {
  if (!name.value) return "Aucun nom pour le moment"
  if (name.value.length < 3) return "Le nom doit contenir au moins 3 caractères"
  return "Nom valide ✅"
})
const labelNameLog = computed(() => name.value.length >= 3 ? 'text-success' : 'text-danger')
const categoryLog = computed(() => {
  if (!categoryId.value) return "Aucune catégorie sélectionnée"
  const c = Categories.value.find(x => x.id === Number(categoryId.value))
  return c ? `Catégorie sélectionnée : ${c.name}` : 'Catégorie sélectionnée'
})
const labelCategoryLog = computed(() => categoryId.value ? 'text-success' : 'text-danger')
const isFormValid = computed(() => name.value.length >= 3 && categoryId.value !== '' && Number(categoryId.value) > 0)

// Filter + Pagination
const filteredPubTypes = computed(() => {
  if (!searchQuery.value) return PubTypes.value
  return PubTypes.value.filter(p => {
    const q = searchQuery.value.toLowerCase()
    const catName = p.category ? p.category.name.toLowerCase() : ''
    return p.name.toLowerCase().includes(q) || catName.includes(q)
  })
})
const totalPages = computed(() => Math.max(1, Math.ceil(filteredPubTypes.value.length / perPage.value)))
const paginatedPubTypes = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  return filteredPubTypes.value.slice(start, start + perPage.value)
})
function nextPage() { if (currentPage.value < totalPages.value) currentPage.value++ }
function prevPage() { if (currentPage.value > 1) currentPage.value-- }

// CRUD
async function getCategories() {
  loadingCategories.value = true
  try {
    const res = await axios.get('/api/category', { headers: { Accept: 'application/json' } })
    Categories.value = res.data
  } catch (e) {
    Swal.fire({ icon: 'error', title: 'Erreur API', text: 'Impossible de récupérer les catégories' })
  } finally {
    loadingCategories.value = false
  }
}

async function getPubTypes() {
  loadingTable.value = true
  try {
    const res = await axios.get('/api/pub-type', { headers: { Accept: 'application/json' } })
    PubTypes.value = res.data
    console.log(res.data)
  } catch (e) { console.error(e) }
  loadingTable.value = false
}

function resetForm() { name.value = ''; categoryId.value = '' }

function addPubType() {
  resetForm()
  getCategories()
  new Modal(document.getElementById('addPubTypeModal')).show()
}

function pubTypeUpdated(pubType) {
  pubTypeId = pubType.id
  name.value = pubType.name
  categoryId.value = pubType.category_id ?? (pubType.category ? pubType.category.id : '')
  getCategories()
  new Modal(document.getElementById('updatePubTypeModal')).show()
}

async function savePubType() {
  loadingButton.value = 'save'
  try {
    const res = await axios.post('/api/pub-type', { name: name.value, category_id: categoryId.value })
    if (res.data.status) {
      Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: res.data.message, showConfirmButton: false, timer: 3000 })
      resetForm()
      await getPubTypes()
      Modal.getInstance(document.getElementById('addPubTypeModal')).hide()
    } else {
      Swal.fire({ icon: 'error', title: res.data.title || 'Erreur', text: res.data.message })
    }
  } catch (e) {
    Swal.fire({ icon: 'error', title: 'Erreur Serveur', text: e.response?.data?.message || 'Une erreur est survenue.' })
  }
  loadingButton.value = ''
}

async function updatePubType() {
  loadingButton.value = 'update'
  try {
    const res = await axios.put('/api/pub-type/' + pubTypeId, { name: name.value, category_id: categoryId.value })
    if (res.data.status) {
      Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: res.data.message, showConfirmButton: false, timer: 3000 })
      await getPubTypes()
      Modal.getInstance(document.getElementById('updatePubTypeModal')).hide()
    } else {
      Swal.fire({ icon: 'error', title: res.data.title || 'Erreur', text: res.data.message })
    }
  } catch (e) {
    Swal.fire({ icon: 'error', title: 'Erreur Serveur', text: e.response?.data?.message || 'Une erreur est survenue.' })
  }
  loadingButton.value = ''
}

async function deletedPubType(id) {
  const result = await Swal.fire({
    title: 'Es-tu sûr ?',
    text: "Cette action est irréversible !",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Oui, supprimer',
    cancelButtonText: 'Annuler'
  })
  if (result.isConfirmed) {
    loadingButton.value = id
    try {
      const res = await axios.delete('/api/pub-type/' + id)
      if (res.data && res.data.status === false) {
        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: res.data.title || 'Suppression refusée', text: res.data.message, showConfirmButton: false, timer: 3000 })
      } else {
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: res.data.message || 'Type supprimé ✅', showConfirmButton: false, timer: 3000 })
        await getPubTypes()
      }
    } catch (e) {
      Swal.fire({ icon: 'error', title: 'Erreur Serveur', text: e.response?.data?.message || 'Une erreur est survenue.' })
    }
    loadingButton.value = ''
  }
}

function viewPubType(pubType) {
  selectedPubType.value = pubType
  new Modal(document.getElementById('viewPubTypeModal')).show()
}

onMounted(async () => {
  await getCategories()
  await getPubTypes()
})
</script>
