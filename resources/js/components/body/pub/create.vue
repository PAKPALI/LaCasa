<template>
  <div class="container mt-4">
    <h2>Créer une publication</h2>
    <form @submit.prevent="submitPublication">

      <!-- Pays -->
      <div class="mb-3">
        <label class="form-label">Pays</label>
        <v-select
          v-model="selectedCountry"
          :options="countries"
          label="name"
          :reduce="c => c.id"
          placeholder="Sélectionnez un pays"
          :filterable="true"
          :loading="loadingCountries"
          @input="onCountryChange"
        />
      </div>

      <!-- Ville -->
      <div class="mb-3">
        <label class="form-label">Ville</label>
        <v-select
          v-model="selectedTown"
          :options="towns"
          label="name"
          :reduce="t => t.id"
          placeholder="Sélectionnez une ville"
          :filterable="true"
          :loading="loadingTowns"
          @input="onTownChange"
          :disabled="!selectedCountry"
        />
      </div>

      <!-- Quartier -->
      <div class="mb-3">
        <label class="form-label">Quartier</label>
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
      <div class="mb-3">
        <label class="form-label">Catégorie</label>
        <v-select
          v-model="selectedCategory"
          :options="categories"
          label="name"
          :reduce="c => c.id"
          placeholder="Sélectionnez une catégorie"
          :filterable="true"
          :loading="loadingCategories"
          @input="onCategoryChange"
          :disabled="!selectedTown"
        />
      </div>

      <!-- Type de publication -->
      <div class="mb-3">
        <label class="form-label">Type de publication</label>
        <v-select
          v-model="selectedPubType"
          :options="pubTypes"
          label="name"
          :reduce="p => p.id"
          placeholder="Sélectionnez un type"
          :filterable="true"
          :loading="loadingPubTypes"
          @input="onPubTypeChange"
          :disabled="!selectedCategory"
        />
      </div>

      <!-- Attributs -->
      <div class="mb-3">
        <label class="form-label">Attributs</label>
        <!-- Multi-select pour les attributs -->
        <v-select
          v-model="selectedAttributes"
          :options="attributes"
          label="name"
          :reduce="a => a.id"
          placeholder="Sélectionnez un ou plusieurs attributs"
          multiple
          :filterable="true"
          :loading="loadingAttributes"
          :disabled="!selectedPubType"
        />
      </div>

      <!-- Informations supplémentaires -->
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

      <!-- Photos multi-upload -->
      <div class="mb-3">
        <label class="form-label">Photos</label>
        <input type="file" class="form-control" multiple @change="onFilesChange" />
        <div class="mt-2">
          <img v-for="(img, idx) in previewImages" :key="idx" :src="img" class="img-thumbnail me-2" style="width:100px;height:80px;" />
        </div>
      </div>

      <!-- Statuts -->
      <div class="row mb-3">
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

      <button type="submit" class="btn btn-primary">Enregistrer la publication</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
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
  status: ''
})

const previewImages = ref([])

const fetchCountries = async () => {
  loadingCountries.value = true
  try {
    const res = await axios.get('/api/country')
    countries.value = res.data
  } finally {
    loadingCountries.value = false
  }
}

const onCountryChange = async () => {
  selectedTown.value = null
  towns.value = []
  districts.value = []
  selectedDistrict.value = null
  selectedCategory.value = null
  categories.value = []
  selectedPubType.value = null
  pubTypes.value = []
  attributes.value = []
  selectedAttributes.value = []

  if (!selectedCountry.value) return
  loadingTowns.value = true
  try {
    const res = await axios.get(`/api/town?country_id=${selectedCountry.value}`)
    towns.value = res.data
  } finally {
    loadingTowns.value = false
  }
}

const onTownChange = async () => {
  selectedDistrict.value = null
  districts.value = []
  selectedCategory.value = null
  categories.value = []
  selectedPubType.value = null
  pubTypes.value = []
  attributes.value = []
  selectedAttributes.value = []

  if (!selectedTown.value) return

  loadingDistricts.value = true
  try {
    const res = await axios.get(`/api/district?town_id=${selectedTown.value}`)
    districts.value = res.data
  } finally {
    loadingDistricts.value = false
  }

  loadingCategories.value = true
  try {
    const res = await axios.get(`/api/category?town_id=${selectedTown.value}`)
    categories.value = res.data
  } finally {
    loadingCategories.value = false
  }
}

const onCategoryChange = async () => {
  selectedPubType.value = null
  pubTypes.value = []
  attributes.value = []
  selectedAttributes.value = []

  if (!selectedCategory.value) return

  loadingPubTypes.value = true
  try {
    const res = await axios.get(`/api/pub-type?category_id=${selectedCategory.value}`)
    pubTypes.value = res.data
  } finally {
    loadingPubTypes.value = false
  }
}

const onPubTypeChange = async () => {
  selectedAttributes.value = []
  attributes.value = []

  if (!selectedPubType.value) return

  loadingAttributes.value = true
  try {
    const res = await axios.get(`/api/attribute?pub_type_id=${selectedPubType.value}`)
    attributes.value = res.data
  } finally {
    loadingAttributes.value = false
  }
}

// Multi-upload
const onFilesChange = (e) => {
  const files = Array.from(e.target.files)
  previewImages.value = files.map(file => URL.createObjectURL(file))
  form.value.images = files
}

// Submit
const submitPublication = async () => {
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
    if (form.value.images) {
      form.value.images.forEach(file => payload.append('images[]', file))
    }

    const res = await axios.post('/api/publication', payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    alert('Publication créée ✅')
  } catch (err) {
    console.error(err)
    alert('Erreur lors de la création')
  }
}

fetchCountries()
</script>
