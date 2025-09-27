<template>
  <!-- Loader global -->
  <div v-if="loadingTable" class="text-center my-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Chargement...</span>
    </div>
  </div>

  <!-- Modal Ajouter -->
  <div class="modal fade mt-5" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-dark">
          <h5 class="modal-title text-light">Ajouter une catégorie</h5>
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
          <button class="btn btn-dark" @click="saveCategory" :disabled="!isFormValid || loadingButton==='save'">
            <span v-if="loadingButton==='save'" class="spinner-border spinner-border-sm text-light"></span>
            <span v-if="loadingButton!=='save'">Ajouter</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Modifier -->
  <div class="modal fade mt-5" id="updatedCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Modifier une catégorie</h5>
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
          <button class="btn btn-warning" @click="updateCategory" :disabled="!isFormValid || loadingButton==='update'">
            <span v-if="loadingButton==='update'" class="spinner-border spinner-border-sm text-dark"></span>
            <span v-if="loadingButton!=='update'">Modifier</span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Visualiser -->
  <div class="modal fade mt-5" id="viewCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title text-dark">Détails de la catégorie</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p><strong>Nom :</strong> {{ selectedCategory ? selectedCategory.name : '' }}</p>
          <p><strong>Pays :</strong> {{ selectedCategory?.country?.name ?? '—' }}</p>
        </div>
        <div class="modal-footer bg-light">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Accordéon catégories -->
  <div class="accordion-item mb-4">
    <h2 class="accordion-header" id="headingCategory">
      <button class="accordion-button bg-primary text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="true" aria-controls="collapseCategory">
        SECTION CATÉGORIES
      </button>
    </h2>
    <div id="collapseCategory" class="accordion-collapse collapse show" aria-labelledby="headingCategory" data-bs-parent="#accordionExample">
      <div class="accordion-body bg-dark text-white">
        <p>Vous pouvez enregistrer, modifier, visualiser ou supprimer une catégorie (Maison,Appartement,Immeuble,Boutique etc...).</p>

        <div class="row mt-3 mb-3">
          <div class="col-8">
            <h2>Liste Catégories</h2>
          </div>
          <div class="col-4">
            <button class="btn btn-dark border mt-3 me-2" @click="addCategory">+ Ajouter</button>
          </div>
        </div>

        <div class="mb-3">
          <input type="text" class="form-control" placeholder="Rechercher une catégorie ou un pays..." v-model="searchQuery">
        </div>

        <table v-if="!loadingTable" class="table table-primary table-bordered border-dark table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Catégorie</th>
              <th>Pays</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(category, index) in paginatedCategories" :key="category.id">
              <td>{{ (currentPage - 1) * perPage + index + 1 }}</td>
              <td>{{ category.name }}</td>
              <td>{{ category.country?.name ?? '—' }}</td>
              <td>
                <button class="btn btn-sm btn-info me-1" @click="viewCategory(category)"><i class="bi bi-eye"></i></button>
                <button class="btn btn-sm btn-warning me-1" @click="categoryUpdated(category)" :disabled="loadingButton==='update'">
                  <span v-if="loadingButton==='update'" class="spinner-border spinner-border-sm text-dark"></span>
                  <span v-else><i class="bi bi-pencil-square"></i></span>
                </button>
                <button class="btn btn-sm btn-danger" @click="deletedCategory(category.id)" :disabled="loadingButton===category.id">
                  <span v-if="loadingButton===category.id" class="spinner-border spinner-border-sm text-light"></span>
                  <span v-else><i class="bi bi-trash"></i></span>
                </button>
              </td>
            </tr>
            <tr v-if="filteredCategories.length===0">
              <td colspan="4" class="text-center text-danger">Aucune catégorie trouvée</td>
            </tr>
          </tbody>
        </table>

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

  const name = ref('')
  const countryId = ref('')
  const Categories = ref([])
  const Countries = ref([])
  let categoryId = 0
  const selectedCategory = ref(null)
  const searchQuery = ref('')
  const currentPage = ref(1)
  const perPage = ref(5)
  const loadingButton = ref('')
  const loadingTable = ref(true)
  const loadingCountries = ref(false)

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

  const filteredCategories = computed(() => {
    if (!searchQuery.value) return Categories.value
    return Categories.value.filter(c => {
      const q = searchQuery.value.toLowerCase()
      const countryName = c.country ? c.country.name.toLowerCase() : ''
      return c.name.toLowerCase().includes(q) || countryName.includes(q)
    })
  })
  const totalPages = computed(() => Math.max(1, Math.ceil(filteredCategories.value.length / perPage.value)))
  const paginatedCategories = computed(() => {
    const start = (currentPage.value - 1) * perPage.value
    return filteredCategories.value.slice(start, start + perPage.value)
  })

  function nextPage() { if (currentPage.value < totalPages.value) currentPage.value++ }
  function prevPage() { if (currentPage.value > 1) currentPage.value-- }

  async function getCategories() {
    loadingTable.value = true
    try {
      const res = await axios.get('/api/category', { headers: { Accept: 'application/json' } })
      Categories.value = res.data
    } finally {
      loadingTable.value = false
    }
  }
  async function getCountries() {
    loadingCountries.value = true
    try {
      const res = await axios.get('/api/country', { headers: { Accept: 'application/json' } })
      Countries.value = res.data
    } finally {
      loadingCountries.value = false
    }
  }
  function resetForm() {
    name.value = ''
    countryId.value = ''
  }
  function addCategory() {
    resetForm()
    getCountries()
    new Modal(document.getElementById('addCategoryModal')).show()
  }
  function categoryUpdated(category) {
    categoryId = category.id
    name.value = category.name
    countryId.value = category.country_id ?? (category.country ? category.country.id : '')
    getCountries()
    new Modal(document.getElementById('updatedCategoryModal')).show()
  }
  async function saveCategory() {
    loadingButton.value = 'save'
    try {
      const res = await axios.post('/api/category', { name: name.value, country_id: countryId.value })
      if (res.data.status) {
        Swal.fire({ toast:true, position:'top-end', icon:'success', title:res.data.message, showConfirmButton:false, timer:3000 })
        await getCategories()
        Modal.getInstance(document.getElementById('addCategoryModal')).hide()
      } else {
        Swal.fire({ icon:'error', title: res.data.title || 'Erreur', text: res.data.message })
      }
    } finally {
      loadingButton.value = ''
    }
  }
  async function updateCategory() {
    loadingButton.value = 'update'
    try {
      const res = await axios.put('/api/category/' + categoryId, { name: name.value, country_id: countryId.value })
      if (res.data.status) {
        Swal.fire({ toast:true, position:'top-end', icon:'success', title:res.data.message, showConfirmButton:false, timer:3000 })
        await getCategories()
        Modal.getInstance(document.getElementById('updatedCategoryModal')).hide()
      } else {
        Swal.fire({ icon:'error', title: res.data.title || 'Erreur', text: res.data.message })
      }
    } finally {
      loadingButton.value = ''
    }
  }
  async function deletedCategory(id) {
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
        const res = await axios.delete('/api/category/' + id)
        if (res.data.status === false) {
          Swal.fire({toast:true, position:'top-end', icon:'error', title:res.data.title || 'Suppression refusée', text:res.data.message, showConfirmButton:false, timer:3000 })
        } else {
          Swal.fire({ toast:true, position:'top-end', icon:'success', title: res.data.message || 'Catégorie supprimée ✅', showConfirmButton:false, timer:3000 })
          await getCategories()
        }
      } finally {
        loadingButton.value = ''
      }
    }
  }
  function viewCategory(category) {
    selectedCategory.value = category
    new Modal(document.getElementById('viewCategoryModal')).show()
  }

  onMounted(async () => {
    await getCountries()
    await getCategories()
  })
</script>
