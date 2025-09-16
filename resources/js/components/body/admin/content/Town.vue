<template>
  <!-- Loader global avant table -->
  <div v-if="loadingTable" class="text-center my-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Chargement...</span>
    </div>
  </div>

  <!-- Modals -->

  <!-- Ajouter -->
  <div class="modal fade mt-5" id="addTownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-light">Ajouter une ville</h5>
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
              <label class="form-label">Pays</label>
              <v-select
                v-model="countryId"
                :options="Countries"
                label="name"
                :reduce="c => c.id"
                placeholder="Rechercher un pays..."
                :filterable="true"
                :loading="loadingCountries"
              />
              <label :class="labelCountryLog">{{ countryLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-dark" @click="saveTown" :disabled="!isFormValid || loadingButton==='save'">
            <span v-if="loadingButton==='save'" class="spinner-border spinner-border-sm text-light"></span>
            <span v-if="loadingButton!=='save'">Ajouter</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modifier -->
  <div class="modal fade mt-5" id="updatedTownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Modifier une ville</h5>
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
              <label class="form-label">Pays</label>
              <v-select
                v-model="countryId"
                :options="Countries"
                label="name"
                :reduce="c => c.id"
                placeholder="Rechercher un pays..."
                :filterable="true"
                :loading="loadingCountries"
              />
              <label :class="labelCountryLog">{{ countryLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-warning" @click="updateTown" :disabled="!isFormValid || loadingButton==='update'">
            <span v-if="loadingButton==='update'" class="spinner-border spinner-border-sm text-dark"></span>
            <span v-if="loadingButton!=='update'">Modifier</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Visualiser -->
  <div class="modal fade mt-5" id="viewTownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title text-dark">Détails de la ville</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p><strong>Nom :</strong> {{ selectedTown ? selectedTown.name : '' }}</p>
          <p><strong>Pays :</strong> {{ selectedTown && selectedTown.country ? selectedTown.country.name : '—' }}</p>
          <p v-if="selectedTown && selectedTown.country"><strong>Acronyme pays :</strong> {{ selectedTown.country.acronym }}</p>
          <p v-if="selectedTown && selectedTown.country"><strong>Code pays :</strong> {{ selectedTown.country.code }}</p>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Accordéon villes -->
  <div class="accordion-item mb-4">
    <h2 class="accordion-header" id="headingTown">
      <button class="accordion-button bg-primary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTown" aria-expanded="true" aria-controls="collapseTown">
        SECTION VILLES
      </button>
    </h2>
    <div id="collapseTown" class="accordion-collapse collapse show" aria-labelledby="headingTown" data-bs-parent="#accordionExample">
      <div class="accordion-body bg-dark text-white">
        <p>Vous pouvez enregistrer la ville, modifier, visualiser ou supprimer (selon règles serveur).</p>

        <!-- Ajouter -->
        <div class="row mt-3 mb-3">
          <div class="col-8">
            <h2>Liste Villes</h2>
          </div>
          <div class="col-4">
            <button class="btn btn-dark border mt-3 me-2" @click="addTown">+ Ajouter</button>
          </div>
        </div>

        <!-- Search -->
        <div class="mb-3">
          <input type="text" class="form-control" placeholder="Rechercher une ville ou un pays..." v-model="searchQuery">
        </div>

        <!-- Tableau -->
        <table v-if="!loadingTable" class="table table-primary table-bordered border-dark table-hover">
          <thead class="table-primary border-dark">
            <tr>
              <th>#</th>
              <th>Ville</th>
              <th>Pays</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(town, index) in paginatedTowns" :key="town.id">
              <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
              <td>{{ town.name }}</td>
              <td>{{ town.country ? town.country.name : '—'}}</td>
              <td>
                <button class="btn btn-sm btn-info me-1" @click="viewTown(town)">
                  <span><i class="bi bi-eye"></i></span>
                </button>
                <button class="btn btn-sm btn-warning me-1" @click="townUpdated(town)" :disabled="loadingButton==='update'">
                  <span v-if="loadingButton==='update'" class="spinner-border spinner-border-sm text-dark"></span>
                  <span v-if="loadingButton!=='update'"><i class="bi bi-pencil-square"></i></span>
                </button>
                <button class="btn btn-sm btn-danger" @click="deletedTown(town.id)" :disabled="loadingButton===town.id">
                  <span v-if="loadingButton===town.id" class="spinner-border spinner-border-sm text-light"></span>
                  <span v-if="loadingButton!==town.id"><i class="bi bi-trash"></i></span>
                </button>
              </td>
            </tr>
            <tr v-if="filteredTowns.length===0">
              <td colspan="4" class="text-center text-danger">Aucune ville trouvée</td>
            </tr>
          </tbody>
        </table>

        <!-- Pagination -->
        <div v-if="!loadingTable" class="d-flex justify-content-between align-items-center mt-2">
          <button class="btn btn-sm btn-dark" @click="prevPage" :disabled="currentPage===1">Précédent</button>
          <span>Page {{ currentPage }} / {{ totalPages }}</span>
          <button class="btn btn-sm btn-dark" @click="nextPage" :disabled="currentPage===totalPages">Suivant</button>
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
const countryId = ref('')
const Towns = ref([])
const Countries = ref([])
let townId = 0
const selectedTown = ref(null)
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(5)
const loadingButton = ref('') // "save", "update" ou id pour delete
const loadingTable = ref(true)
const loadingCountries = ref(false)

// Computed validations
const nameLog = computed(() => {
  if (!name.value) return "Aucun nom pour le moment"
  if (name.value.length < 3) return "Le nom doit contenir au moins 3 caractères"
  return "Nom valide ✅"
})
const labelNameLog = computed(() => name.value.length >= 3 ? 'text-success' : 'text-danger')
const countryLog = computed(() => {
  if (!countryId.value) return "Aucun pays sélectionné"
  const c = Countries.value.find(x => x.id === Number(countryId.value))
  return c ? `Pays sélectionné : ${c.name}` : 'Pays sélectionné'
})
const labelCountryLog = computed(() => countryId.value ? 'text-success' : 'text-danger')
const isFormValid = computed(() => name.value.length >= 3 && countryId.value !== '' && Number(countryId.value) > 0)

// Filter + Pagination
const filteredTowns = computed(() => {
  if (!searchQuery.value) return Towns.value
  return Towns.value.filter(t => {
    const q = searchQuery.value.toLowerCase()
    const countryName = t.country ? t.country.name.toLowerCase() : ''
    const countryAcr = t.country ? t.country.acronym.toLowerCase() : ''
    return t.name.toLowerCase().includes(q) || countryName.includes(q) || countryAcr.includes(q)
  })
})
const totalPages = computed(() => Math.max(1, Math.ceil(filteredTowns.value.length / perPage.value)))
const paginatedTowns = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  return filteredTowns.value.slice(start, start + perPage.value)
})
function nextPage() { if (currentPage.value < totalPages.value) currentPage.value++ }
function prevPage() { if (currentPage.value > 1) currentPage.value-- }

// CRUD
async function getCountries() {
  loadingCountries.value = true
  try {
    const response = await axios.get('/api/country', { headers: { Accept: 'application/json' } })
    Countries.value = response.data
  } catch (error) {
    Swal.fire({ icon: 'error', title: 'Erreur API', text: 'Impossible de récupérer les pays' })
  } finally {
    loadingCountries.value = false
  }
}

async function getTowns() {
  loadingTable.value = true
  try {
    const response = await axios.get('/api/town', { headers: { Accept: 'application/json' } })
    Towns.value = response.data
  } catch (error) {
    console.error('getTowns', error)
  }
  loadingTable.value = false
}

function resetForm() {
  name.value = ''
  countryId.value = ''
}

function addTown() {
  resetForm()
  getCountries()
  new Modal(document.getElementById('addTownModal')).show()
}

function townUpdated(town) {
  townId = town.id
  name.value = town.name
  countryId.value = town.country_id ?? (town.country ? town.country.id : '')
  getCountries()
  new Modal(document.getElementById('updatedTownModal')).show()
}

async function saveTown() {
  loadingButton.value = 'save'
  try {
    const response = await axios.post('/api/town', { name: name.value, country_id: countryId.value })
    if (response.data.status) {
      Swal.fire({ toast:true, position:'top-end', icon:'success', title:response.data.message, showConfirmButton:false, timer:3000 })
      resetForm()
      await getTowns()
      Modal.getInstance(document.getElementById('addTownModal')).hide()
    } else {
      Swal.fire({ icon:'error', title: response.data.title || 'Erreur', text: response.data.message })
    }
  } catch (error) {
    Swal.fire({ icon:'error', title:'Erreur Serveur', text: error.response?.data?.message || 'Une erreur est survenue.' })
  }
  loadingButton.value = ''
}

async function updateTown() {
  loadingButton.value = 'update'
  try {
    const response = await axios.put('/api/town/' + townId, { name: name.value, country_id: countryId.value })
    if (response.data.status) {
      Swal.fire({ toast:true, position:'top-end', icon:'success', title:response.data.message, showConfirmButton:false, timer:3000 })
      await getTowns()
      Modal.getInstance(document.getElementById('updatedTownModal')).hide()
    } else {
      Swal.fire({ icon:'error', title: response.data.title || 'Erreur', text: response.data.message })
    }
  } catch (error) {
    Swal.fire({ icon:'error', title:'Erreur Serveur', text: error.response?.data?.message || 'Une erreur est survenue.' })
  }
  loadingButton.value = ''
}

async function deletedTown(id) {
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
      const response = await axios.delete('/api/town/' + id)
      if (response.data && response.data.status === false) {
        Swal.fire({toast: true, position: 'top-end', icon:'error', title: response.data.title || 'Suppression refusée', text: response.data.message , showConfirmButton:false, timer:3000 })
      } else {
        Swal.fire({ toast:true, position:'top-end', icon:'success', title: response.data.message || 'Ville supprimée ✅', showConfirmButton:false, timer:3000 })
        await getTowns()
      }
      // if (!response.data.status) { 
      //   Swal.fire({
      //     toast: true,
      //     position: 'top-end',
      //     icon: 'error',
      //     title: response.data.message,
      //     showConfirmButton: false,
      //     timer: 3000
      //   })
      // } else {
      //   Swal.fire({
      //     toast: true,
      //     position: 'top-end',
      //     icon: 'success',
      //     title: 'Pays supprimé ✅',
      //     showConfirmButton: false,
      //     timer: 3000
      //   })
      //   getCountries()
      // }
    } catch (error) {
      Swal.fire({ icon:'error', title:'Erreur Serveur', text: error.response?.data?.message || 'Une erreur est survenue.' })
    }
    loadingButton.value = ''
  }
}

function viewTown(town) {
  selectedTown.value = town
  new Modal(document.getElementById('viewTownModal')).show()
}

onMounted(async () => {
  await getCountries()
  await getTowns()
})
</script>
