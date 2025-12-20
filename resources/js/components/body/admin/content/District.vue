<template>
  <!-- Loader global -->
  <div v-if="loadingTable" class="text-center my-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Chargement...</span>
    </div>
  </div>

  <!-- Modals -->
  <!-- Ajouter District -->
  <div class="modal fade mt-5" id="addDistrictModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-light">Ajouter un quartier</h5>
          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label">Nom du quartier</label>
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
              />
              <label :class="labelCountryLog">{{ countryLog }}</label>
            </div>

            <div class="mb-3">
              <label class="form-label">Ville</label>
              <v-select
                v-model="townId"
                :options="Towns"
                label="name"
                :reduce="t => t.id"
                placeholder="Rechercher une ville..."
                :disabled="!countryId || loadingTowns"
              />
              <label :class="labelTownLog">{{ townLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-dark" @click="saveDistrict" :disabled="!isFormValid || loadingButton==='save'">
            <span v-if="loadingButton==='save'" class="spinner-border spinner-border-sm text-light"></span>
            <span v-if="loadingButton!=='save'">Ajouter</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modifier District -->
  <div class="modal fade mt-5" id="updateDistrictModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Modifier un quartier</h5>
          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label">Nom du quartier</label>
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
              />
              <label :class="labelCountryLog">{{ countryLog }}</label>
            </div>

            <div class="mb-3">
              <label class="form-label">Ville</label>
              <v-select
                v-model="townId"
                :options="Towns"
                label="name"
                :reduce="t => t.id"
                placeholder="Rechercher une ville..."
                :disabled="!countryId || loadingTowns"
              />
              <label :class="labelTownLog">{{ townLog }}</label>
            </div>
          </form>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button class="btn btn-warning" @click="updateDistrict" :disabled="!isFormValid || loadingButton==='update'">
            <span v-if="loadingButton==='update'" class="spinner-border spinner-border-sm text-dark"></span>
            <span v-if="loadingButton!=='update'">Modifier</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Visualiser District -->
  <div class="modal fade mt-5" id="viewDistrictModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title text-dark">Détails du quartier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p><strong>Nom :</strong> {{ selectedDistrict ? selectedDistrict.name : '' }}</p>
          <p><strong>Ville :</strong> {{ selectedDistrict && selectedDistrict.town ? selectedDistrict.town.name : '—' }}</p>
          <p><strong>Pays :</strong> {{ selectedDistrict && selectedDistrict.town && selectedDistrict.town.country ? selectedDistrict.town.country.name : '—' }}</p>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Accordéon Districts -->
  <div class="accordion-item mb-4">
    <h2 class="accordion-header" id="headingDistrict">
      <button class="accordion-button bg-primary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDistrict" aria-expanded="true" aria-controls="collapseDistrict">
        SECTION QUARTIERS
      </button>
    </h2>

    <div id="collapseDistrict" class="accordion-collapse collapse show" aria-labelledby="headingDistrict" data-bs-parent="#accordionExample">
      <div class="accordion-body bg-dark text-white">

        <p>Vous pouvez enregistrer le quartier, modifier, visualiser ou supprimer.</p>

        <!-- Ajouter -->
        <div class="row mt-3 mb-3">
          <div class="col-8"><h2>Liste des quartiers</h2></div>
          <div class="col-4"><button class="btn btn-dark border mt-3 me-2" @click="addDistrict">+ Ajouter</button></div>
        </div>

        <!-- Search -->
        <div class="mb-3">
          <input type="text" class="form-control" placeholder="Rechercher un quartier, ville ou pays..." v-model="searchQuery">
        </div>

        <!-- Tableau -->
        <table v-if="!loadingTable" class="table table-primary table-bordered border-dark table-hover">
          <thead class="table-primary border-dark">
            <tr>
              <th>#</th>
              <th>Quartier</th>
              <th>Ville</th>
              <th>Pays</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(district, index) in paginatedDistricts" :key="district.id">
              <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
              <td>{{ district.name }}</td>
              <td>{{ district.town ? district.town.name : '—' }}</td>
              <td>{{ district.town && district.town.country ? district.town.country.name : '—' }}</td>
              <td>
                <button class="btn btn-sm btn-info me-1" @click="viewDistrict(district)"><i class="bi bi-eye"></i></button>
                <button class="btn btn-sm btn-warning me-1" @click="districtUpdated(district)" :disabled="loadingButton==='update'"><i class="bi bi-pencil-square"></i></button>
                <button class="btn btn-sm btn-danger" v-if="isAuthenticated && user.role == 1"  @click="deletedDistrict(district.id)" :disabled="loadingButton===district.id"><i class="bi bi-trash"></i></button>
              </td>
            </tr>
            <tr v-if="filteredDistricts.length===0">
              <td colspan="5" class="text-center text-danger">Aucun quartier trouvé</td>
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
import { ref, computed, onMounted, watch } from 'vue'
import { Modal } from 'bootstrap'
import axios from 'axios'
import Swal from 'sweetalert2'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import { user, isAuthenticated } from '../../../auth/auth.js'

// Données
const name = ref('')
const countryId = ref('')
const townId = ref('')
const Districts = ref([])
const Countries = ref([])
const Towns = ref([])
let districtId = 0
const selectedDistrict = ref(null)
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(5)
const loadingButton = ref('')
const loadingTable = ref(true)
const loadingCountries = ref(false)
const loadingTowns = ref(false)

// Validation
const nameLog = computed(() => !name.value ? 'Aucun nom' : name.value.length < 3 ? 'Le nom doit contenir au moins 3 caractères' : 'Nom valide ✅')
const labelNameLog = computed(() => name.value.length >= 3 ? 'text-success' : 'text-danger')

const countryLog = computed(() => {
  if (!countryId.value) return 'Aucun pays sélectionné'
  const c = Countries.value.find(x => x.id === Number(countryId.value))
  return c ? `Pays sélectionné : ${c.name}` : 'Pays sélectionné'
})
const labelCountryLog = computed(() => countryId.value ? 'text-success' : 'text-danger')

const townLog = computed(() => {
  if (!townId.value) return 'Aucune ville sélectionnée'
  const t = Towns.value.find(x => x.id === Number(townId.value))
  return t ? `Ville sélectionnée : ${t.name}` : 'Ville sélectionnée'
})
const labelTownLog = computed(() => townId.value ? 'text-success' : 'text-danger')

const isFormValid = computed(() =>
  name.value.length >= 3 &&
  countryId.value &&
  townId.value
)

// Filter + Pagination districts
const filteredDistricts = computed(() => {
  if (!searchQuery.value) return Districts.value
  const q = searchQuery.value.toLowerCase()
  return Districts.value.filter(d => {
    const townName = d.town ? d.town.name.toLowerCase() : ''
    const countryName = d.town && d.town.country ? d.town.country.name.toLowerCase() : ''
    return d.name.toLowerCase().includes(q) || townName.includes(q) || countryName.includes(q)
  })
})
const totalPages = computed(() => Math.max(1, Math.ceil(filteredDistricts.value.length / perPage.value)))
const paginatedDistricts = computed(() => {
  const start = (currentPage.value - 1) * perPage.value
  const end = start + perPage.value
  return filteredDistricts.value.slice(start, end)
})
function nextPage() { if (currentPage.value < totalPages.value) currentPage.value++ }
function prevPage() { if (currentPage.value > 1) currentPage.value-- }

// API
async function getCountries() {
  loadingCountries.value = true
  try { Countries.value = (await axios.get('/api/country')).data } 
  catch(e){ console.error('getCountries', e) }
  finally{ loadingCountries.value=false }
}

async function getTowns(country = null) {
  if (!country) { Towns.value = []; return } // ne jamais charger toutes les villes
  loadingTowns.value = true
  try { Towns.value = (await axios.get('/api/town', { params:{ country_id: country } })).data } 
  catch(e){ console.error('getTowns', e) }
  finally{ loadingTowns.value=false }
}

async function getDistricts() {
  loadingTable.value = true
  try { Districts.value = (await axios.get('/api/district')).data }
  catch(e){ console.error('getDistricts', e) }
  finally{ loadingTable.value=false }
}

// Modals
function addDistrict() { resetForm(); getCountries(); new Modal(document.getElementById('addDistrictModal')).show() }

function districtUpdated(district) {
  districtId = district.id
  name.value = district.name
  countryId.value = district.town?.country_id ?? ''
  townId.value = district.town_id ?? ''
  getCountries()
  getTowns(countryId.value)
  new Modal(document.getElementById('updateDistrictModal')).show()
}

function resetForm() { name.value = ''; countryId.value=''; townId.value='' }

// Watch countryId pour charger les villes uniquement quand un pays est choisi
watch(countryId, async (newVal) => {
  townId.value = ''
  await getTowns(newVal)
})

// CRUD District
async function saveDistrict() {
  loadingButton.value='save'
  try {
    const res = await axios.post('/api/district', { name: name.value, town_id: townId.value })
    if(res.data.status){
      Swal.fire({toast:true,position:'top-end',icon:'success',title:res.data.message,showConfirmButton:false,timer:3000})
      resetForm(); await getDistricts()
      Modal.getInstance(document.getElementById('addDistrictModal')).hide()
    } else Swal.fire({icon:'error',title:res.data.title||'Erreur',text:res.data.message})
  } catch(e){ Swal.fire({icon:'error',title:'Erreur Serveur',text:e.response?.data?.message||'Une erreur est survenue.'}) }
  loadingButton.value=''
}

async function updateDistrict() {
  loadingButton.value='update'
  try {
    const res = await axios.put('/api/district/'+districtId,{name:name.value,town_id:townId.value})
    if(res.data.status){
      Swal.fire({toast:true,position:'top-end',icon:'success',title:res.data.message,showConfirmButton:false,timer:3000})
      await getDistricts()
      Modal.getInstance(document.getElementById('updateDistrictModal')).hide()
    } else Swal.fire({icon:'error',title:res.data.title||'Erreur',text:res.data.message})
  } catch(e){ Swal.fire({icon:'error',title:'Erreur',text:e.response?.data?.message||'Une erreur est survenue.'}) }
  loadingButton.value=''
}

async function deletedDistrict(id) {
  const result = await Swal.fire({title:'Es-tu sûr ?',text:"Cette action est irréversible !",icon:'warning',showCancelButton:true,confirmButtonColor:'#d33',cancelButtonColor:'#3085d6',confirmButtonText:'Oui, supprimer',cancelButtonText:'Annuler'})
  if(result.isConfirmed){
    loadingButton.value=id
    try{
      const res = await axios.delete('/api/district/'+id)
      if(res.data?.status===false) Swal.fire({icon:'error',title:res.data.title||'Suppression refusée',text:res.data.message})
      else { Swal.fire({toast:true,position:'top-end',icon:'success',title:res.data.message||'Quartier supprimé ✅',showConfirmButton:false,timer:3000}); await getDistricts() }
    } catch(e){ Swal.fire({icon:'error',title:'Erreur',text:e.response?.data?.message||'Une erreur est survenue.'}) }
    loadingButton.value=''
  }
}

function viewDistrict(district){ selectedDistrict.value=district; new Modal(document.getElementById('viewDistrictModal')).show() }

onMounted(async ()=>{
  await getCountries()
  await getDistricts()
})
</script>