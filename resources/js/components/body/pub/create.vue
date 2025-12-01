<template>
  <div class="container mt-5 my-5">
    <h2 class="mb-4 mt-5 pt-5 fw-bold text-dark">Créer une publication</h2> <hr>
    <form @submit.prevent="submitPublication" class="border-bottom pb-5">

      <!-- 🔵 SECTION : Informations obligatoires -->
      <div class="p-3 mb-4 rounded shadow-sm border " style="background-color: rgba(14, 46, 80, 0.85);">
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
          <div class="col-md-6 mb-3">
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
          <div class="col-md-6 mb-3">
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
          <div class="p-2 border rounded bg-dark">
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
              <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp"  @change="onSingleFileChange($event, index)" />
              <img v-if="previewImages[index]" :src="previewImages[index]" class="img-thumbnail ms-2" style="width: 80px; height: 60px;">
              <button type="button" class="btn btn-outline-danger ms-2" @click="removeImage(index)">🗑</button>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" @click="addImage">+ Ajouter une image</button>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label text-light">Numéro 1</label>
            <input type="number" value="{{ user.phone1 }}" class="form-control" v-model="form.phone1" placeholder="Ex: 90 00 00 00" />
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label text-light">Numéro 2</label>
            <input type="number" class="form-control" v-model="form.phone2" placeholder="Ex: 90 00 00 01" />
          </div>
        </div>
      </div>

      <!-- 🟢 SECTION : Informations complémentaires -->
      <div class="p-3 mb-4 rounded shadow-sm text-light border" style="background-color: rgba(14, 46, 80, 0.85);">
        <h5 class="fw-bold text-light border-bottom mb-3">Informations complémentaires</h5>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Prix (FCFA)</label>
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
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Avance (En mois)</label>
            <input type="number" class="form-control" v-model="form.advance" />
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Caution (En mois)</label>
            <input type="number" class="form-control" v-model="form.deposit" />
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Prix de la visite (FCFA)</label>
            <input type="number" class="form-control" v-model="form.visit" />
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Commission (FCFA)</label>
            <input type="number" class="form-control" v-model="form.commission" />
          </div>
        </div>

        <!-- Description -->
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea class="form-control" rows="4" v-model="form.description"></textarea>
        </div>
      </div>

      <!-- 🟠 SECTION : Statuts -->
      <div class="p-3 mb-4 rounded shadow-sm  border text-light" style="background-color: rgba(14, 46, 80, 0.85);">
        <h5 class="fw-bold text-light border-bottom mb-3">Statuts</h5>
        <div class="row">
          <div class="col-md-6">
            <label class="form-label">À louer ou à vendre</label>
            <v-select
              v-model="form.sale_or_rent"
              :options="saleOrRentOptions"
              label="label"
              :reduce="o => o.value"
              placeholder="-- Sélectionnez --"
            />
          </div>
          <div class="col-md-6">
            <label class="form-label">Statut</label>
            <v-select
              v-model="form.status"
              :options="statusOptions"
              label="label"
              :reduce="o => o.value"
              placeholder="-- Sélectionnez --"
            />
          </div>
        </div>
      </div>

      <div class="text-end">
        <button v-if="isUserAuth" type="submit" class="btn btn-primary btn-lg px-4" :disabled="isSubmitting">
          <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2" role="status"></span>
          💾 Enregistrer
        </button>

        <button v-if="!isUserAuth" class="btn btn-dark btn-lg px-4" disabled>
          💾 Enregistrer
        </button>
      </div>
    </form>

    <!-- Liste des publications -->
    <Publication :publications="publicationsList" />
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import vSelect from "vue-select"
import "vue-select/dist/vue-select.css"
import Swal from 'sweetalert2'
import Publication from './publication.vue'
import { user, isAuthenticated } from '../../auth/auth.js'

const isUserAuth = isAuthenticated.value

const saleOrRentOptions = [
  { value: 'sale', label: 'À vendre' },
  { value: 'rent',  label: 'À louer' }
]
const statusOptions = [
  { value: 'active',   label: 'Actif' },
  { value: 'inactive', label: 'Inactif' }
]

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
  price: '', bathroom: '', surface: '', advance: '', deposit: '', 
  description: '', visit: '', sale_or_rent: '', status: '', images: [],
  phone1: '', phone2: ''
})

  // form.value.phone1 = user.value.is_active

// Sinon attendre le chargement
watch(user, (newVal) => {
  if (newVal && newVal.phone1) {
    form.value.phone1 = newVal.phone1
  }
})

console.log(user.value.tiktok_link)
const previewImages = ref([])
const publicationsList = ref([])
const isSubmitting = ref(false)

// Fetch Countries
const fetchCountries = async () => {
  loadingCountries.value = true
  countries.value = (await axios.get('/api/country')).data
  loadingCountries.value = false
}
fetchCountries()

// Fetch Publications
const fetchPublications = async () => {
  try {
    const res = await axios.get('getMyPublication')
    publicationsList.value = res.data
  } catch (err) {
    console.error('Erreur lors de la récupération des publications :', err)
    Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Impossible de récupérer les publications', showConfirmButton:false, timer:3000 })
  }
}
fetchPublications()

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
const onSingleFileChange = (event, index) => {
  const file = event.target.files[0]
  if (!file) return

  // ✅ Vérification du type
  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp']
  if (!allowedTypes.includes(file.type)) {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title: "⚠️ Formats acceptés : JPG, PNG, WebP uniquement",
      showConfirmButton: false,
      timer: 3000
    })
    event.target.value = '' // reset input
    return
  }

  // ✅ Vérification de la taille (2 Mo max)
  const maxSize = 2 * 1024 * 1024
  if (file.size > maxSize) {
    Swal.fire({
      toast: true,
      position: 'top-end',
      icon: 'error',
      title: "⚠️ Chaque image doit être inférieure à 2 Mo",
      showConfirmButton: false,
      timer: 3000
    })
    event.target.value = ''
    return
  }

  // ✅ Si tout est bon → on garde le fichier et on affiche l’aperçu
  form.value.images[index] = file
  previewImages.value[index] = URL.createObjectURL(file)
}


// Submit
const submitPublication = async () => {
  if (isSubmitting.value) return;

  // Vérifications des champs obligatoires
  if (!selectedCountry.value) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez sélectionner un pays', showConfirmButton:false, timer:3000 })
  if (!selectedTown.value) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez sélectionner une ville', showConfirmButton:false, timer:3000 })
  if (!selectedDistrict.value) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez sélectionner un quartier', showConfirmButton:false, timer:3000 })
  if (!selectedCategory.value) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez sélectionner une catégorie', showConfirmButton:false, timer:3000 })
  if (!selectedPubType.value) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez sélectionner un type de publication', showConfirmButton:false, timer:3000 })
  if (selectedAttributes.value.length === 0 || selectedAttributes.value.includes(null)) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez sélectionner au moins un attribut', showConfirmButton:false, timer:3000 })
  if (form.value.images.length === 0 || form.value.images.includes(null)) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez ajouter au moins une photo', showConfirmButton:false, timer:3000 })
  if (!form.value.price) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez renseigner le prix', showConfirmButton:false, timer:3000 })
  if (!form.value.bathroom) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez renseigner le nombre de chambres', showConfirmButton:false, timer:3000 })
  // if (!form.value.surface) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez renseigner la surface', showConfirmButton:false, timer:3000 })
  if (!form.value.advance) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez renseigner l\'avance', showConfirmButton:false, timer:3000 })
  if (!form.value.deposit) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez renseigner la caution', showConfirmButton:false, timer:3000 })
  if (!form.value.sale_or_rent) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez sélectionner si c\'est à vendre ou à louer', showConfirmButton:false, timer:3000 })
  if (!form.value.status) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez sélectionner le statut', showConfirmButton:false, timer:3000 })
  if (!form.value.phone1) return Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Veuillez renseigner le numéro 1', showConfirmButton:false, timer:3000 })

  try {
    isSubmitting.value = true

    const payload = new FormData()
    payload.append('country_id', selectedCountry.value)
    payload.append('town_id', selectedTown.value)
    payload.append('district_id', selectedDistrict.value)
    payload.append('category_id', selectedCategory.value)
    payload.append('pub_type_id', selectedPubType.value)
    
    selectedAttributes.value.forEach(attr => payload.append('attributes[]', attr))
    
    for (const key in form.value) {
      if (key === 'images') continue
      if (key === 'sale_or_rent') payload.append('offer_type', form.value[key])
      else if (key === 'status') payload.append('is_active', form.value[key] === 'active' ? 1 : 0)
      else payload.append(key, form.value[key])
    }

    payload.append('phone1', form.value.phone1)
    if (form.value.phone2) payload.append('phone2', form.value.phone2)
    form.value.images.forEach(file => payload.append('images[]', file))

    const res = await axios.post('publication', payload)

    Swal.fire({ toast:true, position:'top-end', icon:'success', title: res.data.message || 'Publication créée ✅', showConfirmButton:false, timer:3000 })

    form.value = { price:'', bathroom:'', surface:'', advance:'', deposit:'', description:'', visit:'', sale_or_rent:'', status:'', images: [], phone1:'', phone2:'' }
    previewImages.value = []
    selectedCountry.value = null
    selectedTown.value = null
    selectedDistrict.value = null
    selectedCategory.value = null
    selectedPubType.value = null
    selectedAttributes.value = []
    fetchPublications()

  } catch (err) {
    console.error(err)
    Swal.fire({ toast:true, position:'top-end', icon:'error', title: err.response.data.message, showConfirmButton:false, timer:3000 })
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
/* Bord du select */
::v-deep(.vs__dropdown-toggle) {
  border: 2px solid #ffffff;
  border-radius: 8px;
}

/* Texte du placeholder */
::v-deep(.vs__placeholder) {
  color: #ffffff;
  font-style: italic;
}

/* Texte sélectionné */
::v-deep(.vs__selected) {
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
</style>
