<template>
  <div class="container my-5">
    <h2 class="mb-4 fw-bold text-primary">Créer une publication</h2>
    <form @submit.prevent="submitPublication">

      <!-- 🔵 SECTION : Informations obligatoires -->
      <div class="p-3 mb-4 rounded shadow-sm border bg-dark ">
        <h5 class="fw-bold text-light border-bottom mb-3">Informations obligatoires</h5>

        <div class="row">
          <!-- Pays -->
          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold text-light">Pays</label>
            <v-select class="text-light my-custom-select"
              v-model="selectedCountry"
              :options="countries"
              label="name"
              :reduce="c => c.id"
              placeholder="Sélectionnez un pays"
              :filterable="true"
              :loading="loadingCountries"
            />
          </div>

          <!-- Ville -->
          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold text-light">Ville</label>
            <v-select
              v-model="selectedTown"
              :options="towns"
              label="name"
              :reduce="t => t.id"
              placeholder="Sélectionnez une ville"
              :filterable="true"
              :loading="loadingTowns"
              :disabled="!selectedCountry"
            />
          </div>

          <!-- Quartier -->
          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold text-light">Quartier</label>
            <v-select
              v-model="selectedDistrict"
              :options="districts"
              label="name"
              :reduce="d => d.id"
              placeholder="Sélectionnez un quartier"
              :filterable="true"
              :loading="loadingDistricts"
              :disabled="!selectedTown"
            />
          </div>

          <!-- Catégorie -->
          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold text-light">Catégorie</label>
            <v-select
              v-model="selectedCategory"
              :options="categories"
              label="name"
              :reduce="c => c.id"
              placeholder="Sélectionnez une catégorie"
              :filterable="true"
              :loading="loadingCategories"
              :disabled="!selectedTown"
            />
          </div>

          <!-- Type de publication -->
          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold text-light">Type de publication</label>
            <v-select
              v-model="selectedPubType"
              :options="pubTypes"
              label="name"
              :reduce="p => p.id"
              placeholder="Sélectionnez un type"
              :filterable="true"
              :loading="loadingPubTypes"
              :disabled="!selectedCategory"
            />
          </div>
        </div>

        <!-- Attributs -->
        <div class="mb-3">
          <label class="form-label fw-semibold text-light">Attributs</label>
          <div class="p-2 border rounded bg-light">
            <div v-for="(attr, index) in selectedAttributes" :key="index" class="d-flex mb-1">
              <v-select
                v-model="selectedAttributes[index]"
                :options="attributes"
                label="name"
                :reduce="a => a.id"
                placeholder="Choisissez un attribut"
                class="flex-grow-1"
              />
              <button type="button" class="btn btn-outline-danger ms-2" @click="removeAttribute(index)">🗑</button>
            </div>
          </div>
          <button type="button" class="btn btn-outline-primary btn-sm mt-2" @click="addAttribute">+ Ajouter un attribut</button>
        </div>

        <!-- Photos -->
        <div class="mb-3">
          <label class="form-label fw-semibold text-light">Photos</label>
          <div class="p-2 border rounded bg-light">
            <div v-for="(file, index) in form.images" :key="index" class="d-flex align-items-center mb-2">
              <input type="file" class="form-control" @change="onSingleFileChange($event, index)" />
              <img v-if="previewImages[index]" :src="previewImages[index]" class="img-thumbnail ms-2" style="width: 80px; height: 60px;">
              <button type="button" class="btn btn-outline-danger ms-2" @click="removeImage(index)">🗑</button>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" @click="addImage">+ Ajouter une image</button>
          </div>
        </div>
      </div>

      <!-- 🟢 SECTION : Informations complémentaires -->
      <div class="p-3 mb-4 rounded shadow-sm bg-dark text-light border">
        <h5 class="fw-bold text-light border-bottom mb-3">Informations complémentaires</h5>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Prix</label>
            <input type="number" class="form-control" v-model="form.price" />
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Chambres</label>
            <input type="number" class="form-control" v-model="form.bathroom" />
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Surface (m²)</label>
            <input type="number" class="form-control" v-model="form.surface" />
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Avance</label>
            <input type="number" class="form-control" v-model="form.advance" />
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Caution</label>
            <input type="number" class="form-control" v-model="form.deposit" />
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Prix de la visite</label>
            <input type="number" class="form-control" v-model="form.visit" />
          </div>
        </div>

        <!-- Description -->
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control" rows="4" v-model="form.description"></textarea>
        </div>
      </div>

      <!-- 🟠 SECTION : Statuts -->
      <div class="p-3 mb-4 rounded shadow-sm bg-dark border text-light">
        <h5 class="fw-bold text-light border-bottom mb-3">Statuts</h5>
        <div class="row">
          <div class="col-md-6">
            <label class="form-label">À louer ou à vendre</label>
            <select class="form-select" v-model="form.sale_or_rent">
              <option value="">-- Sélectionnez --</option>
              <option value="sale">À vendre</option>
              <option value="rent">À louer</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Statut</label>
            <select class="form-select" v-model="form.status">
              <option value="">-- Sélectionnez --</option>
              <option value="active">Actif</option>
              <option value="inactive">Inactif</option>
            </select>
          </div>
        </div>
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-primary btn-lg px-4">💾 Enregistrer</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css"

const countries = ref([])
const towns = ref([])
const districts = ref([])
const categories = ref([])
const pubTypes = ref([])
const attributes = ref([])

const selectedCountry = ref(null)
const selectedTown = ref(null)
const selectedDistrict = ref(null)
const selectedCategory = ref(null)
const selectedPubType = ref(null)
const selectedAttributes = ref([])

const loadingCountries = ref(false)
const loadingTowns = ref(false)
const loadingDistricts = ref(false)
const loadingCategories = ref(false)
const loadingPubTypes = ref(false)
const loadingAttributes = ref(false)

const form = ref({
  price: '',
  bathroom: '',
  surface: '',
  advance: '',
  deposit: '',
  description: '',
  visit: '',
  sale_or_rent: '',
  status: '',
  images: []
})

const previewImages = ref([])

// Fetch Countries
const fetchCountries = async () => {
  loadingCountries.value = true
  countries.value = (await axios.get('/api/country')).data
  loadingCountries.value = false
}

// Watchers pour filtrage dynamique
watch(selectedCountry, async (newVal) => {
  selectedTown.value = null
  towns.value = []
  selectedDistrict.value = null
  districts.value = []
  selectedCategory.value = null
  categories.value = []
  selectedPubType.value = null
  pubTypes.value = []
  selectedAttributes.value = []
  attributes.value = []

  if (!newVal) return

  loadingTowns.value = true
  towns.value = (await axios.get(`/api/town?country_id=${newVal}`)).data
  categories.value = (await axios.get(`/api/category?country_id=${newVal}`)).data
  loadingTowns.value = false
})

watch(selectedTown, async (newVal) => {
  selectedDistrict.value = null
  districts.value = []
  if (!newVal) return
  loadingDistricts.value = true
  districts.value = (await axios.get(`/api/district?town_id=${newVal}`)).data
  loadingDistricts.value = false
})

// watch(selectedDistrict, async (newVal) => {
//   selectedPubType.value = null
//   pubTypes.value = []
//   selectedAttributes.value = []
//   attributes.value = []

//   if (!newVal) return
// })

watch(selectedCategory, async (newVal) => {
  selectedPubType.value = null
  pubTypes.value = []
  selectedAttributes.value = []
  attributes.value = []

  if (!newVal) return

  loadingPubTypes.value = true
  pubTypes.value = (await axios.get(`/api/pub-type?category_id=${newVal}`)).data
  loadingPubTypes.value = false
})

watch(selectedPubType, async (newVal) => {
  selectedAttributes.value = []
  attributes.value = []

  if (!newVal) return

  loadingAttributes.value = true
  attributes.value = (await axios.get(`/api/attribute?pub_type_id=${newVal}`)).data
  loadingAttributes.value = false
})


// Attributs dynamiques
const addAttribute = () => selectedAttributes.value.push(null)
const removeAttribute = (i) => selectedAttributes.value.splice(i, 1)

// Images dynamiques
const addImage = () => {
  form.value.images.push(null)
  previewImages.value.push(null)
}
const removeImage = (i) => {
  form.value.images.splice(i, 1)
  previewImages.value.splice(i, 1)
}
const onSingleFileChange = (e, i) => {
  const file = e.target.files[0]
  form.value.images[i] = file
  previewImages.value[i] = URL.createObjectURL(file)
}

// Submit
import Swal from 'sweetalert2'

const submitPublication = async () => {
  // Vérification des champs obligatoires
  if (!selectedCountry.value) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez sélectionner un pays', showConfirmButton: false, timer: 3000 })
  }
  if (!selectedTown.value) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez sélectionner une ville', showConfirmButton: false, timer: 3000 })
  }
  if (!selectedDistrict.value) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez sélectionner un quartier', showConfirmButton: false, timer: 3000 })
  }
  if (!selectedCategory.value) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez sélectionner une catégorie', showConfirmButton: false, timer: 3000 })
  }
  if (!selectedPubType.value) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez sélectionner un type de publication', showConfirmButton: false, timer: 3000 })
  }
  if (selectedAttributes.value.length === 0 || selectedAttributes.value.includes(null)) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez sélectionner au moins un attribut', showConfirmButton: false, timer: 3000 })
  }
  if (form.value.images.length === 0 || form.value.images.includes(null)) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez ajouter au moins une photo', showConfirmButton: false, timer: 3000 })
  }
  if (!form.value.price) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez renseigner le prix', showConfirmButton: false, timer: 3000 })
  }
  if (!form.value.bathroom) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez renseigner le nombre de chambres', showConfirmButton: false, timer: 3000 })
  }
  if (!form.value.surface) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez renseigner la surface', showConfirmButton: false, timer: 3000 })
  }
  if (!form.value.advance) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez renseigner l\'avance', showConfirmButton: false, timer: 3000 })
  }
  if (!form.value.deposit) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez renseigner la caution', showConfirmButton: false, timer: 3000 })
  }
  if (!form.value.sale_or_rent) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez sélectionner si c\'est à vendre ou à louer', showConfirmButton: false, timer: 3000 })
  }
  if (!form.value.status) {
    return Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Veuillez sélectionner le statut', showConfirmButton: false, timer: 3000 })
  }

  // Si tout est ok, on soumet
  try {
    const payload = new FormData()
    payload.append('country_id', selectedCountry.value)
    payload.append('town_id', selectedTown.value)
    payload.append('district_id', selectedDistrict.value)
    payload.append('category_id', selectedCategory.value)
    payload.append('pub_type_id', selectedPubType.value)
    selectedAttributes.value.forEach(attr => payload.append('attributes[]', attr))
    for (const key in form.value) {
      if (key !== 'images') payload.append(key, form.value[key])
    }
    form.value.images.forEach(file => payload.append('images[]', file))

    const res = await axios.post('/api/publication', payload)
    
    Swal.fire({ toast:true, position:'top-end', icon:'success', title: res.data.message || 'Publication créée ✅', showConfirmButton:false, timer:3000 })
  } catch (err) {
    console.error(err)
    Swal.fire({ toast:true, position:'top-end', icon:'error', title: 'Une erreur est survenue lors de la création', showConfirmButton:false, timer:3000 })
  }
}


fetchCountries()
</script>

<style scoped>
  /* Bord du select */
  ::v-deep(.vs__dropdown-toggle) {
    border: 2px solid #ffffff; /* couleur du bord */
    border-radius: 8px;          /* arrondi si tu veux */
  }

  /* Texte du placeholder */
  ::v-deep(.vs__placeholder) {
    color: #ffffff;
    font-style: italic;
  }

  /* Texte sélectionné */
  ::v-deep(.vs__selected) {
    color: #ffffff; /* vert */
    font-weight: bold;
  }

  /* Fond de toutes les options */
  ::v-deep(.vs__dropdown-option) {
    background-color: #ffffff; /* couleur par défaut des options */
    color: #000000;
  }

  /* Option survolée */
  ::v-deep(.vs__dropdown-option--highlight) {
    background-color: #1fad6b;
    color: rgb(255, 255, 255) !important;
  }

  /* Option sélectionnée dans le menu */
  ::v-deep(.vs__dropdown-option--selected) {
    background-color: #198754 !important;
    color: white !important;
  }
</style>

