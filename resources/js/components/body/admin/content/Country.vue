<template>
  <!-- Loader global avant table -->
  <div v-if="loadingTable" class="text-center my-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Chargement...</span>
    </div>
  </div>

  <!-- Modals -->
  <div class="modal fade mt-5" id="addCountryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-light">Ajouter un pays</h5>
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
              <label class="form-label">Acronyme</label>
              <input type="text" class="form-control" v-model="acronym">
              <label :class="labelAcronymLog">{{ acronymLog }}</label>
            </div>
            <div class="mb-3">
              <label class="form-label">Code</label>
              <input type="text" class="form-control" v-model="code">
              <label :class="labelCodeLog">{{ codeLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-dark" @click="saveCountry" :disabled="!isFormValid || loadingButton==='save'">
            <span v-if="loadingButton==='save'" class="spinner-border spinner-border-sm text-light"></span>
            <span v-if="loadingButton!=='save'">Ajouter</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade mt-5" id="updatedCountryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Modifier un pays</h5>
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
              <label class="form-label">Acronyme</label>
              <input type="text" class="form-control" v-model="acronym">
              <label :class="labelAcronymLog">{{ acronymLog }}</label>
            </div>
            <div class="mb-3">
              <label class="form-label">Code</label>
              <input type="text" class="form-control" v-model="code">
              <label :class="labelCodeLog">{{ codeLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-warning" @click="updateCountry" :disabled="!isFormValid || loadingButton==='update'">
            <span v-if="loadingButton==='update'" class="spinner-border spinner-border-sm text-dark"></span>
            <span v-if="loadingButton!=='update'">Modifier</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Accordéon pays -->
  <div class="accordion-item mb-4">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button bg-primary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
        SECTION PAYS
      </button>
    </h2>

    <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body bg-dark text-white">

        <p>Vous pouvez enregistrer le pays, modifier ou supprimer</p>

        <!-- Ajouter -->
        <div class="row mt-3 mb-3">
          <div class="col-8">
            <h2>Liste Pays</h2>
          </div>
          <div class="col-4">
            <button class="btn btn-dark border mt-3 me-2" @click="addCountry">+ Ajouter</button>
          </div>
        </div>

        <!-- Search -->
        <div class="mb-3">
          <input type="text" class="form-control" placeholder="Rechercher un pays..." v-model="searchQuery">
        </div>

        <!-- Tableau -->
        <table v-if="!loadingTable" class="table table-primary table-bordered border-dark table-hover">
          <thead class="table-primary border-dark">
            <tr>
              <th>#</th>
              <th>Nom</th>
              <th>Acronyme</th>
              <th>Code</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(country, index) in paginatedCountries" :key="country.id">
              <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
              <td>{{ country.name }}</td>
              <td>{{ country.acronym }}</td>
              <td>{{ country.code }}</td>
              <td>
                <button class="btn btn-sm btn-warning me-1" @click="countryUpdated(country)" :disabled="loadingButton==='update'">
                  <span v-if="loadingButton==='update'" class="spinner-border spinner-border-sm text-dark"></span>
                  <span v-if="loadingButton!=='update'"><i class="bi bi-pencil-square"></i></span>
                </button>
                <button class="btn btn-sm btn-danger" @click="deletedCountry(country.id)" :disabled="loadingButton===country.id">
                  <span v-if="loadingButton===country.id" class="spinner-border spinner-border-sm text-light"></span>
                  <span v-if="loadingButton!==country.id"><i class="bi bi-trash"></i></span>
                </button>
              </td>
            </tr>
            <tr v-if="filteredCountries.length===0">
              <td colspan="6" class="text-center text-danger">Aucun pays trouvé</td>
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

// Données
const name = ref('')
const acronym = ref('')
const code = ref('')
const Countries = ref([])
let countryId = 0
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(5)
const loadingButton = ref('') // "save", "update" ou id pour delete
const loadingTable = ref(true)

// Computed validations
const nameLog = computed(() => {
  if (!name.value) return "Aucun nom pour le moment"
  if (name.value.length < 3) return "Le nom doit contenir au moins 3 caractères"
  return "Nom valide ✅"
})
const labelNameLog = computed(() => name.value.length >= 3 ? 'text-success' : 'text-danger')

const acronymLog = computed(() => {
  if (!acronym.value) return "Aucun acronyme pour le moment"
  if (acronym.value.length < 2) return "Acronyme trop court"
  return `Acronyme valide (${acronym.value}) ✅`
})
const labelAcronymLog = computed(() => acronym.value.length >= 2 ? 'text-success' : 'text-danger')

const codeLog = computed(() => {
  if (!code.value) return "Aucun code pour le moment"
  if (isNaN(Number(code.value))) return "Le code doit être numérique"
  return `Code valide (${code.value}) ✅`
})
const labelCodeLog = computed(() => isNaN(Number(code.value)) || !code.value ? 'text-danger' : 'text-success')

const isFormValid = computed(() =>
  name.value.length >= 3 &&
  acronym.value.length >= 2 &&
  code.value !== '' &&
  !isNaN(Number(code.value))
)

// Filter + Pagination
const filteredCountries = computed(() => {
  if (!searchQuery.value) return Countries.value
  return Countries.value.filter(c =>
    c.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    c.acronym.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
    c.code.toString().includes(searchQuery.value)
  )
})
const totalPages = computed(() => Math.ceil(filteredCountries.value.length / perPage.value))
const paginatedCountries = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  const end = start + perPage.value
  return filteredCountries.value.slice(start, end)
})
function nextPage() { if (currentPage.value < totalPages.value) currentPage.value++ }
function prevPage() { if (currentPage.value > 1) currentPage.value-- }

// CRUD
async function getCountries() {
  loadingTable.value = true
  try {
    const response = await axios.get('/api/country', { headers: { Accept: 'application/json' } })
    Countries.value = response.data
  } catch (error) { console.error(error) }
  loadingTable.value = false
}

function addCountry() {
  resetForm()
  new Modal(document.getElementById('addCountryModal')).show()
}

function countryUpdated(country) {
  countryId = country.id
  name.value = country.name
  acronym.value = country.acronym
  code.value = country.code
  new Modal(document.getElementById('updatedCountryModal')).show()
}

function resetForm() { name.value=''; acronym.value=''; code.value='' }

async function saveCountry() {
  loadingButton.value = 'save'
  try {
    const response = await axios.post('/api/country', { name: name.value, acronym: acronym.value, code: code.value }, { headers:{ Accept:'application/json' } })
    if(response.data.status){
      Swal.fire({ toast:true, position:'top-end', icon:'success', title:response.data.message, showConfirmButton:false, timer:3000 })
      resetForm()
      getCountries()
      Modal.getInstance(document.getElementById('addCountryModal')).hide()
    } else {
      Swal.fire({ icon:'error', title:response.data.title, text:response.data.message })
    }
  } catch(error){
    Swal.fire({ icon:'error', title:"Erreur Serveur", text:error.response?.data?.message || "Une erreur est survenue." })
    console.error(error)
  }
  loadingButton.value = ''
}

async function updateCountry() {
  loadingButton.value = 'update'
  try {
    const response = await axios.put('/api/country/'+countryId, { name:name.value, acronym:acronym.value, code:code.value }, { headers:{ Accept:'application/json' } })
    if(response.data.status){
      Swal.fire({ toast:true, position:'top-end', icon:'success', title:response.data.message, showConfirmButton:false, timer:3000 })
      getCountries()
      Modal.getInstance(document.getElementById('updatedCountryModal')).hide()
    } else {
      Swal.fire({ icon:'error', title:response.data.title, text:response.data.message })
    }
  } catch(error){
    Swal.fire({ icon:'error', title:"Erreur Serveur", text:error.response?.data?.message || "Une erreur est survenue." })
    console.error(error)
  }
  loadingButton.value = ''
}

async function deletedCountry(id) {
  const result = await Swal.fire({ title:'Es-tu sûr ?', text:"Cette action est irréversible !", icon:'warning', showCancelButton:true, confirmButtonColor:'#d33', cancelButtonColor:'#3085d6', confirmButtonText:'Oui, supprimer', cancelButtonText:'Annuler' })
  if(result.isConfirmed){
    loadingButton.value = id
    try{
      await axios.delete('/api/country/'+id, { headers:{ Accept:'application/json' } })
      Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Pays supprimé ✅', showConfirmButton:false, timer:3000 })
      getCountries()
    } catch(error){ console.error(error) }
    loadingButton.value = ''
  }
}

onMounted(()=>getCountries())
</script>
