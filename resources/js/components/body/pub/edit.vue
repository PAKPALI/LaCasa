<template>
  <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" ref="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content p-4 bg-dark text-light rounded">

        <h4 class="mb-4">Modifier la publication</h4>

        <form @submit.prevent="submitPublication">

          <!-- Pays / Ville / Quartier -->
          <div class="row mb-3">
            <div class="col-md-4">
              <label>Pays</label>
              <v-select
                v-model="selectedCountry"
                :options="countries"
                label="name"
                :reduce="c => c.id"
                placeholder="Sélectionnez un pays"
                :loading="loadingCountries"
              />
            </div>
            <div class="col-md-4">
              <label>Ville</label>
              <v-select
                v-model="selectedTown"
                :options="towns"
                label="name"
                :reduce="t => t.id"
                placeholder="Sélectionnez une ville"
                :loading="loadingTowns"
                :disabled="!selectedCountry"
              />
            </div>
            <div class="col-md-4">
              <label>Quartier</label>
              <v-select
                v-model="selectedDistrict"
                :options="districts"
                label="name"
                :reduce="d => d.id"
                placeholder="Sélectionnez un quartier"
                :loading="loadingDistricts"
                :disabled="!selectedTown"
              />
            </div>
          </div>

          <!-- Catégorie / Type -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label>Catégorie</label>
              <v-select
                v-model="selectedCategory"
                :options="categories"
                label="name"
                :reduce="c => c.id"
                placeholder="Sélectionnez une catégorie"
                :loading="loadingCategories"
              />
            </div>
            <div class="col-md-6">
              <label>Type de publication</label>
              <v-select
                v-model="selectedPubType"
                :options="pubTypes"
                label="name"
                :reduce="p => p.id"
                placeholder="Sélectionnez un type"
                :loading="loadingPubTypes"
              />
            </div>
          </div>

          <!-- Attributs dynamiques -->
          <div class="mb-3">
            <label>Attributs</label>
            <div class="p-2 border rounded bg-dark">
              <div v-for="(attr, index) in selectedAttributes" :key="index" class="d-flex mb-2">
                <v-select
                  v-model="selectedAttributes[index]"
                  :options="attributes"
                  label="name"
                  :reduce="a => a.id"
                  class="flex-grow-1"
                />
                <button type="button" class="btn btn-outline-danger ms-2" @click="removeAttribute(index)">🗑</button>
              </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2" @click="addAttribute">+ Ajouter un attribut</button>
          </div>

          <!-- Images -->
          <div class="mb-3">
            <label>Photos</label>
            <div class="p-2 border rounded bg-light">
              <div v-for="(file, index) in form.images" :key="index" class="d-flex align-items-center mb-2">
                <input type="file" accept=".jpg,.jpeg,.png,.webp" class="form-control" @change="onSingleFileChange($event, index)" />
                <img v-if="previewImages[index]" :src="previewImages[index]" class="img-thumbnail ms-2" style="width: 80px; height: 60px;">
                <button type="button" class="btn btn-outline-danger ms-2" @click="removeImage(index)">🗑</button>
              </div>
              <button type="button" class="btn btn-outline-primary btn-sm" @click="addImage">+ Ajouter une image</button>
            </div>
          </div>

          <!-- Infos complémentaires -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label>Période de paiement</label>
              <v-select
                v-model="form.price_period"
                :options="pricePeriodOptions"
                label="label"
                :reduce="p => p.value"
                placeholder="Sélectionnez une période"
              />
            </div>

            <div class="col-md-6"><label>Prix (FCFA)</label><input type="number" v-model.number="form.price" class="form-control" /></div>
            <div class="col-md-6"><label>Chambres</label><input type="number" v-model.number="form.bathroom" class="form-control" /></div>
            <div class="col-md-6"><label>Ménage</label><input type="number" v-model.number="form.surface" class="form-control" /></div>
          </div>

          <div class="row mb-3">
            <div class="col-md-4"><label>Avance (Mois/F CFA)</label><input type="number" v-model.number="form.advance" class="form-control" /></div>
            <div class="col-md-4"><label>Caution (Mois/F CFA)</label><input type="number" v-model.number="form.deposit" class="form-control" /></div>
            <div class="col-md-4"><label>Prix de visite (FCFA)</label><input type="number" v-model.number="form.visit" class="form-control" /></div>
          </div>

          <div class="mb-3">
            <label>Description</label>
            <textarea class="form-control" v-model="form.description" rows="4"></textarea>
          </div>

          <!-- Statuts -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label>À vendre / à louer</label>
              <v-select v-model="form.sale_or_rent" :options="saleOrRentOptions" label="label" :reduce="o => o.value" />
            </div>
            <div class="col-md-6">
              <label>Statut</label>
              <v-select v-model="form.status" :options="statusOptions" label="label" :reduce="o => o.value" />
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6"><label>Numéro 1</label><input type="number" v-model="form.phone1" class="form-control" /></div>
            <div class="col-md-6"><label>Numéro 2</label><input type="number" v-model="form.phone2" class="form-control" /></div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <div v-if="formErrors.length" class="mt-2 text-warning small">
                <ul class="mb-0">
                  <li v-for="(err, i) in formErrors" :key="i">{{ err }}</li>
                </ul>
              </div>
            </div>
            <div class="col-md-6">
              <!-- Bouton submit et erreurs -->
              <div class="text-end mt-3">
                <button type="submit" class="btn btn-success me-2"
                  :disabled="isSubmitting || !isFormValid">
                  💾 {{ isSubmitting ? 'Mise à jour...' : 'Mettre à jour' }}
                </button>
                <button type="button" class="btn btn-secondary" @click="closeModal">❌ Fermer</button>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import { Modal } from 'bootstrap'
import axios from 'axios'
import Swal from 'sweetalert2'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'

const props = defineProps({ publicationId: { type: Number, required: true } })
const emit = defineEmits(['close', 'updated'])

const modalInstance = ref(null)
const saleOrRentOptions = [{ value:'sale', label:'À vendre' }, { value:'rent', label:'À louer' }]
const statusOptions = [{ value:'active', label:'Actif' }, { value:'inactive', label:'Inactif' }]
const pricePeriodOptions = [
  { value: 'month', label: 'Mois' },
  { value: 'week', label: 'Semaine' },
  { value: 'day', label: 'Jour' }
]

// Selects & données
const countries = ref([]), towns = ref([]), districts = ref([]), categories = ref([]), pubTypes = ref([]), attributes = ref([])
const selectedCountry = ref(null), selectedTown = ref(null), selectedDistrict = ref(null), selectedCategory = ref(null), selectedPubType = ref(null)
const selectedAttributes = ref([])
const loadingCountries = ref(false), loadingTowns = ref(false), loadingDistricts = ref(false), loadingCategories = ref(false), loadingPubTypes = ref(false), loadingAttributes = ref(false)

const form = ref({ price:'', bathroom:'', surface:'', advance:'', deposit:'', description:'', visit:'', sale_or_rent:'', status:'', images:[], phone1:'', phone2:'' })
const previewImages = ref([]), existingImages = ref([])
const isSubmitting = ref(false)

// 🚨 Gestion des erreurs
const formErrors = ref([])
const validateForm = () => {
  const errors = []
  if(!selectedCountry.value) errors.push('Sélectionnez un pays')
  if(!selectedTown.value) errors.push('Sélectionnez une ville')
  if(!selectedDistrict.value) errors.push('Sélectionnez un quartier')
  if(!selectedCategory.value) errors.push('Sélectionnez une catégorie')
  if(!selectedPubType.value) errors.push('Sélectionnez un type de publication')
  if(!selectedAttributes.value.length || selectedAttributes.value.some(a=>!a)) errors.push('Ajoutez au moins un attribut')
  if(!form.value.images.some(f=>f) && existingImages.value.length===0) errors.push('Ajoutez au moins une image')
  if(!form.value.price_period) errors.push('Période de paiement obligatoire')
  if(!form.value.price) errors.push('Prix obligatoire')
  // if(!form.value.surface) errors.push('Ménage obligatoire')
  if(!form.value.phone1 && !form.value.phone2) errors.push('Au moins un numéro de téléphone requis')
  if(!form.value.sale_or_rent) errors.push('Sélectionnez à vendre / à louer')
  if(!form.value.status) errors.push('Sélectionnez un statut')
  formErrors.value = errors
  return errors.length === 0
}
const isFormValid = ref(false)

// Modal
const showModal = async () => {
  await nextTick()
  modalInstance.value = new Modal(document.getElementById('editModal'), { backdrop: 'static', keyboard: false })
  modalInstance.value.show()
}
const closeModal = () => { if(modalInstance.value) modalInstance.value.hide(); emit('close') }

// Fetch / Load
const fetchCountries = async () => { loadingCountries.value=true; countries.value=(await axios.get('/api/country')).data; loadingCountries.value=false }
const fetchTowns = async (countryId) => { loadingTowns.value=true; towns.value=(await axios.get(`/api/town?country_id=${countryId}`)).data; loadingTowns.value=false }
const fetchDistricts = async (townId) => { loadingDistricts.value=true; districts.value=(await axios.get(`/api/district?town_id=${townId}`)).data; loadingDistricts.value=false }
const fetchCategories = async (countryId) => { loadingCategories.value=true; categories.value=(await axios.get(`/api/category?country_id=${countryId}`)).data; loadingCategories.value=false }
const fetchPubTypes = async (categoryId) => { loadingPubTypes.value=true; pubTypes.value=(await axios.get(`/api/pub-type?category_id=${categoryId}`)).data; loadingPubTypes.value=false }
const fetchAttributes = async (pubTypeId) => { loadingAttributes.value=true; attributes.value=(await axios.get(`/api/attribute?pub_type_id=${pubTypeId}`)).data; loadingAttributes.value=false }

const loadPublication = async () => {
  try {
    const res = await axios.get(`/api/publication/${props.publicationId}`)
    const pub = res.data
    selectedCountry.value = pub.country_id
    await fetchTowns(pub.country_id)
    selectedTown.value = pub.town_id
    await fetchDistricts(pub.town_id)
    selectedDistrict.value = pub.district_id
    await fetchCategories(pub.country_id)
    selectedCategory.value = pub.category_id
    await fetchPubTypes(pub.category_id)
    selectedPubType.value = pub.pub_type_id
    await fetchAttributes(pub.pub_type_id)
    selectedAttributes.value = pub.attributes.map(a=>a.id)

    form.value = {
      price_period: pub.price_period,price: pub.price, bathroom: pub.bathroom, surface: pub.surface,
      advance: pub.advance, deposit: pub.deposit, description: pub.description,
      visit: pub.visit, sale_or_rent: pub.offer_type, status: pub.is_active?'active':'inactive',
      images: pub.images.map(()=>null), phone1: pub.phone1, phone2: pub.phone2
    }
    previewImages.value = pub.images.map(img => '/' + img.path)
    existingImages.value = pub.images.map(img=>img.id)
    await showModal()
    isFormValid.value = validateForm() // 🔥 validation immédiate
  } catch(err){
    console.error(err)
    Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Impossible de charger la publication', showConfirmButton:false, timer:3000 })
  }
}

onMounted(async () => { await fetchCountries(); loadPublication() })

// Watchers pour re-validation
watch([selectedCountry, selectedTown, selectedDistrict, selectedCategory, selectedPubType, selectedAttributes, form], () => {
  isFormValid.value = validateForm()
}, { deep:true })

// Attributs / Images
const addAttribute = () => selectedAttributes.value.push(null)
const removeAttribute = i => selectedAttributes.value.splice(i,1)
const addImage = () => { form.value.images.push(null); previewImages.value.push(null) }
const removeImage = i => { form.value.images.splice(i,1); previewImages.value.splice(i,1); existingImages.value.splice(i,1) }
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
  if(!validateForm() || isSubmitting.value) return
  isSubmitting.value = true
  try {

    const payload = new FormData()
    payload.append('_method','PUT')
    payload.append('country_id',selectedCountry.value)
    payload.append('town_id',selectedTown.value)
    payload.append('district_id',selectedDistrict.value)
    payload.append('category_id',selectedCategory.value)
    payload.append('pub_type_id',selectedPubType.value)
    selectedAttributes.value.forEach(a=>payload.append('attributes[]',a))

    for(const key in form.value){
      if(key==='images') continue
      if(key==='sale_or_rent') payload.append('offer_type', form.value[key])
      else if(key==='status') payload.append('is_active', form.value[key]==='active'?1:0)
      else payload.append(key, form.value[key])
    }
    form.value.images.forEach(f=>{if(f) payload.append('images[]',f)})
    existingImages.value.forEach(id=>payload.append('existing_images[]',id))
    const res = await axios.post(`/api/publication/${props.publicationId}`, payload, { headers:{'Content-Type':'multipart/form-data'} })
    if(res.data.status){
      Swal.fire({ toast:true, position:'top-end', icon:'success', title:res.data.message||'Publication mise à jour ✅', showConfirmButton:false, timer:3000 })
      emit('updated', res.data)
      closeModal()
    }else{
      Swal.fire({ toast:true, position:'top-end', icon:'error', title:res.data.message||'Erreur lors de la mise à jour', showConfirmButton:false, timer:3000 })
    }
  } catch(err){
    console.error('❌ Erreur lors de la mise à jour', err.response?.data || err)
    Swal.fire({ toast:true, position:'top-end', icon:'error', title:'Erreur lors de la mise à jour(Renseignez Ménage, avance, caution et prix de visite)', showConfirmButton:false, timer:5000 })
  } finally { isSubmitting.value=false }
}



</script>

<style scoped>
.vs__dropdown-toggle { border-radius:6px; border:2px solid #fff; }
.vs__selected, .vs__placeholder { color:#fff; font-weight:bold }
.vs__dropdown-option--highlight { background:#1fad6b !important; color:#fff !important }
</style>
