<template>
  <!-- Loader global -->
  <div v-if="loadingGlobal" class="text-center my-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Chargement...</span>
    </div>
  </div>

  <!-- Modal Ajouter -->
  <div class="modal fade mt-5" id="addTownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-light">Ajouter une ville</h5>
          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent>
            <div class="mb-3">
              <label class="form-label">Nom</label>
              <input type="text" class="form-control" v-model="name">
              <label :class="labelNameLog">{{ nameLog }}</label>
            </div>

            <div class="mb-3">
              <label class="form-label">Pays</label>
              <v-select
                v-model="countryId"
                :options="filteredCountries"
                label="name"
                :reduce="c => c.id"
                placeholder="Rechercher un pays..."
                @input="onCountryChange"
              />
              <label :class="labelCountryLog">{{ countryLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-dark" @click="saveTown" :disabled="!isFormValid || loadingButton==='save'">
            <span v-if="loadingButton==='save'" class="spinner-border spinner-border-sm text-light"></span>
            <span v-else>Ajouter</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Modifier -->
  <div class="modal fade mt-5" id="updatedTownModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Modifier une ville</h5>
          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent>
            <div class="mb-3">
              <label class="form-label">Nom</label>
              <input type="text" class="form-control" v-model="name">
              <label :class="labelNameLog">{{ nameLog }}</label>
            </div>

            <div class="mb-3">
              <label class="form-label">Pays</label>
              <v-select
                v-model="countryId"
                :options="filteredCountries"
                label="name"
                :reduce="c => c.id"
                placeholder="Rechercher un pays..."
                @input="onCountryChange"
              />
              <label :class="labelCountryLog">{{ countryLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-warning" @click="updateTown" :disabled="!isFormValid || loadingButton==='update'">
            <span v-if="loadingButton==='update'" class="spinner-border spinner-border-sm text-dark"></span>
            <span v-else>Modifier</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Tableau + recherche -->
  <div v-if="!loadingGlobal" class="accordion-item mb-4">
    <h2 class="accordion-header">
      <button class="accordion-button bg-primary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTown" aria-expanded="true">
        SECTION VILLES
      </button>
    </h2>
    <div id="collapseTown" class="accordion-collapse collapse show">
      <div class="accordion-body bg-dark text-white">
        <div class="row mt-3 mb-3">
          <div class="col-8"><h2>Liste Villes</h2></div>
          <div class="col-4">
            <button class="btn btn-dark border mt-3 me-2" @click="addTown">+ Ajouter</button>
          </div>
        </div>

        <!-- Search input pour table -->
        <div class="mb-3">
          <input type="text" class="form-control" placeholder="Rechercher une ville ou un pays..." v-model="searchQuery">
        </div>

        <table v-if="!loadingTable" class="table table-primary table-bordered border-dark table-hover">
          <thead>
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
              <td>{{ town.country?.name || '—' }}</td>
              <td>
                <button class="btn btn-sm btn-info me-1" @click="viewTown(town)"><i class="bi bi-eye"></i></button>
                <button class="btn btn-sm btn-warning me-1" @click="townUpdated(town)" :disabled="loadingButton==='update'">
                  <span v-if="loadingButton==='update'" class="spinner-border spinner-border-sm text-dark"></span>
                  <i v-else class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-sm btn-danger" @click="deletedTown(town.id)" :disabled="loadingButton===town.id">
                  <span v-if="loadingButton===town.id" class="spinner-border spinner-border-sm text-light"></span>
                  <i v-else class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
            <tr v-if="filteredTowns.length === 0">
              <td colspan="4" class="text-center text-danger">Aucune ville trouvée</td>
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
import { ref, computed, watch, onMounted } from 'vue'
import { Modal } from 'bootstrap'
import axios from 'axios'
import Swal from 'sweetalert2'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

const name = ref('')
const countryId = ref('')
const searchQuery = ref('')
const searchCountryInput = ref('')
const searchCountry = ref('')
const Towns = ref([])
const Countries = ref([])
let townId = 0

const currentPage = ref(1)
const perPage = ref(5)
const loadingButton = ref('')
const loadingTable = ref(true)
const loadingCountries = ref(false)
const loadingGlobal = ref(true)
const selectedTown = ref(null)

// Debounce input recherche pays
let debounceTimer = null
watch(searchCountryInput, (val) => {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    searchCountry.value = val
  }, 300)
})

// Validations
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
const isFormValid = computed(() => name.value.length >= 3 && Number(countryId.value) > 0)

const filteredCountries = computed(() => {
  if (!searchCountry.value) return Countries.value
  return Countries.value.filter(c =>
    c.name.toLowerCase().includes(searchCountry.value.toLowerCase()) ||
    c.acronym.toLowerCase().includes(searchCountry.value.toLowerCase())
  )
})

const filteredTowns = computed(() => {
  if (!searchQuery.value) return Towns.value
  return Towns.value.filter(t => {
    const q = searchQuery.value.toLowerCase()
    return t.name.toLowerCase().includes(q) ||
           t.country?.name.toLowerCase().includes(q) ||
           t.country?.acronym.toLowerCase().includes(q)
  })
})
const totalPages = computed(() => Math.max(1, Math.ceil(filteredTowns.value.length / perPage.value)))
const paginatedTowns = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  return filteredTowns.value.slice(start, start + perPage.value)
})
function nextPage() { if (currentPage.value < totalPages.value) currentPage.value++ }
function prevPage() { if (currentPage.value > 1) currentPage.value-- }

async function getCountries() {
  loadingCountries.value = true
  try { Countries.value = (await axios.get('/api/country')).data }
  catch(e){ console.error(e); Swal.fire({icon:'error',title:'Erreur',text:'Impossible de récupérer les pays'}) }
  finally{ loadingCountries.value=false }
}

async function getTowns() {
  loadingTable.value = true
  try { Towns.value = (await axios.get('/api/town')).data }
  catch(e){ console.error(e) }
  finally{ loadingTable.value=false }
}

function resetForm() { name.value=''; countryId.value=''; searchCountryInput.value=''; searchCountry.value='' }
function onCountryChange() { }

async function addTown() { resetForm(); await getCountries(); new Modal(document.getElementById('addTownModal')).show() }
async function townUpdated(town) { resetForm(); townId = town.id; name.value = town.name; await getCountries(); countryId.value = town.country_id ?? (town.country?.id || ''); new Modal(document.getElementById('updatedTownModal')).show() }

// 🔹 Ajout fonctions manquantes 🔹
function viewTown(town) {
  selectedTown.value = town
  Swal.fire({
    title: `Ville: ${town.name}`,
    html: `
      <p><strong>Pays:</strong> ${town.country?.name || '—'}</p>
      <p><strong>Acronyme du pays:</strong> ${town.country?.acronym || '—'}</p>
    `,
    icon: 'info'
  })
}

async function deletedTown(id) {
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
    loadingButton.value = id
    try {
      await axios.delete(`/api/town/${id}`)
      Towns.value = Towns.value.filter(t => t.id !== id)
      Swal.fire('Supprimé !', 'La ville a été supprimée.', 'success')
    } catch (e) {
      console.error(e)
      Swal.fire('Erreur', 'Impossible de supprimer la ville.', 'error')
    } finally {
      loadingButton.value = ''
    }
  }
}

onMounted(async () => { await getCountries(); await getTowns(); loadingGlobal.value=false })
</script>
