<template>
  <div
    class="register-page d-flex align-items-center justify-content-center min-vh-100 mt-5 text-light"
  >
    <!-- 🏠 Overlay -->
    <div class="overlay"></div>

    <!-- 🧾 FORMULAIRE D'INSCRIPTION -->
    <div class="register-card p-4 mt-5 mb-5 p-md-5 rounded shadow-lg position-relative bg-opacity-75">
      <h2 class="fw-bold text-center border-bottom mb-4  text-uppercase text-accent">Créer un compte</h2>

      <form @submit.prevent="registerUser">
        <div class="row">
          <!-- Nom -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Nom complet</label>
            <input type="text" v-model="form.name" class="form-control" placeholder="Votre nom" required />
          </div>

          <!-- Email -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Adresse email</label>
            <input type="email" v-model="form.email" class="form-control" placeholder="Ex: exemple@mail.com" required />
          </div>

          <!-- Mot de passe -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Mot de passe</label>
            <input type="password" v-model="form.password" class="form-control" placeholder="••••••••" required />
          </div>

          <!-- Confirmation -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Confirmer le mot de passe</label>
            <input type="password" v-model="form.password_confirmation" class="form-control" placeholder="••••••••" required />
          </div>

          <!-- Type d'utilisateur -->
          <div class="col-md-12 mb-3">
            <label class="form-label fw-semibold">Vous êtes :</label>
            <div class="d-flex gap-3">
              <div
                class="type-card p-3 flex-fill text-center rounded border"
                :class="{'active': form.user_type === 1}"
                @click="form.user_type = 1"
              >
                <i class="bi bi-person-circle fs-2 mb-2"></i>
                <div>Personne</div>
              </div>
              <div
                class="type-card p-3 flex-fill text-center rounded border"
                :class="{'active': form.user_type === 2}"
                @click="form.user_type = 2"
              >
                <i class="bi bi-building fs-2 mb-2"></i>
                <div>Agence</div>
              </div>
            </div>
          </div>

          <!-- Image de profil -->
            <div class="col-8 mb-3">
                <label class="form-label fw-semibold">Image de profil</label>
                <input type="file" @change="handleProfileImage" accept="image/*" class="form-control" />
                
            </div>
            <div v-if="previewImage" class=" col-4 mb-3 text-center">
                <img :src="previewImage" alt="Aperçu" class="img-thumbnail" style="max-width: 150px;" />
            </div>

          <!-- Localisation -->
          <div class="col-12 mt-5">
            <h5 class="border-bottom border-accent pb-2 fw-bold text-light">Localisation</h5>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Pays</label>
            <v-select
              v-model="selectedCountry"
              :options="countries"
              label="name"
              :reduce="c => c.id"
              placeholder="Choisissez un pays"
              :loading="loadingCountries"
              class="text-dark"
            />
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Ville</label>
            <v-select
              v-model="selectedTown"
              :options="towns"
              label="name"
              :reduce="t => t.id"
              placeholder="Choisissez une ville"
              :disabled="!selectedCountry"
              :loading="loadingTowns"
              class="text-dark"
            />
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold">Quartier</label>
            <v-select
              v-model="selectedDistrict"
              :options="districts"
              label="name"
              :reduce="d => d.id"
              placeholder="Choisissez un quartier"
              :disabled="!selectedTown"
              :loading="loadingDistricts"
              class="text-dark"
            />
          </div>

          <!-- Téléphone -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Numéro 1</label>
            <input type="number" v-model="form.phone1" class="form-control" placeholder="Ex: 90 00 00 00" />
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Numéro 2</label>
            <input type="number" v-model="form.phone2" class="form-control" placeholder="Ex: 90 00 00 00" />
          </div>

          <!-- Bouton -->
          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-accent btn-lg w-100" :disabled="isSubmitting">
              <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
              S'inscrire
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css'
import Swal from 'sweetalert2'

const profileImage = ref(null)
const previewImage = ref(null)

const handleProfileImage = (event) => {
  const file = event.target.files[0]
  if (!file) return

  profileImage.value = file

  // Aperçu
  const reader = new FileReader()
  reader.onload = (e) => {
    previewImage.value = e.target.result
  }
  reader.readAsDataURL(file)
}

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  phone: '',
  user_type: null,
})

const selectedCountry = ref(null)
const selectedTown = ref(null)
const selectedDistrict = ref(null)

const countries = ref([])
const towns = ref([])
const districts = ref([])

const loadingCountries = ref(false)
const loadingTowns = ref(false)
const loadingDistricts = ref(false)
const isSubmitting = ref(false)

// 🔹 Charger les pays
const fetchCountries = async () => {
  loadingCountries.value = true
  countries.value = (await axios.get('/api/country')).data
  loadingCountries.value = false
}
fetchCountries()

// 🔹 Dynamique : villes selon pays
watch(selectedCountry, async (newVal) => {
  towns.value = []
  districts.value = []
  selectedTown.value = null
  selectedDistrict.value = null
  if (!newVal) return
  loadingTowns.value = true
  towns.value = (await axios.get(`/api/town?country_id=${newVal}`)).data
  loadingTowns.value = false
})

// 🔹 Dynamique : quartiers selon ville
watch(selectedTown, async (newVal) => {
  districts.value = []
  selectedDistrict.value = null
  if (!newVal) return
  loadingDistricts.value = true
  districts.value = (await axios.get(`/api/district?town_id=${newVal}`)).data
  loadingDistricts.value = false
})

// 🔹 Soumission du formulaire
const registerUser = async () => {
  if (isSubmitting.value) return

  if (!form.value.user_type)
    return Swal.fire({ icon: 'error', title: 'Veuillez choisir votre type (personne ou agence)', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 })

  if (!selectedCountry.value || !selectedTown.value || !selectedDistrict.value)
    return Swal.fire({ icon: 'error', title: 'Veuillez sélectionner votre pays, ville et quartier', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 })

  try {
    isSubmitting.value = true

    const payload = new FormData()
    for (const key in form.value) {
      payload.append(key, form.value[key])
    }
    payload.append('country_id', selectedCountry.value)
    payload.append('town_id', selectedTown.value)
    payload.append('district_id', selectedDistrict.value)

    if (profileImage.value) {
      payload.append('profile_image', profileImage.value)
    }

    const res = await axios.post('/api/register', payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    Swal.fire({ icon: 'success', title: res.data.message || 'Inscription réussie ✅', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 })
  } catch (err) {
    Swal.fire({ icon: 'error', title: err.response?.data?.message || 'Erreur lors de l’inscription', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 })
  } finally {
    isSubmitting.value = false
  }
}

</script>

<style scoped>
/* 🌌 Arrière-plan avec image et dégradé */
.register-page {
  min-height: 100vh; /* prend toute la hauteur */
  background: linear-gradient(rgba(14,46,80,0.6), rgba(14,46,80,0.6)),
              url('https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=2000&q=80')center/cover no-repeat;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  color: #fff;
}

.register-card {
  background: rgba(5, 1, 27, 0.37); /* semi-transparent */
  backdrop-filter: blur(1px);           /* effet verre dépoli */
  -webkit-backdrop-filter: blur(10px);   /* safari */
  border-radius: 25px;
  padding: 2rem;
  box-shadow: 0 8px 20px rgba(27, 27, 27, 0.295);
  border: 3px solid rgba(0, 185, 142);
  width: 400px;
  color: #fff;
}

.register-card input,
.register-card select,
.register-card button {
  border-radius: 8px;
}

.register-card input,
.register-card select {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: #fff;
}

.register-card input::placeholder,
.register-card select::placeholder {
  color: rgba(255, 255, 255, 0.8);
}

.register-card button {
  background-color: #0e2e50;
  border: none;
}


.text-accent {
  color: #00b98e !important;
}
.border-accent {
  border-color: #00b98e !important;
}
.btn-accent {
  background-color: #00b98e; /* vert de base */
  border: none;
  color: #fff;
  transition: background 0.3s ease;
}

.btn-accent:hover {
  background-color: #00b98e;
}


/* Cartes de sélection (Personne / Agence) */
.type-card {
  cursor: pointer;
  border: 2px solid transparent;
  background: rgba(255,255,255,0.05);
  transition: all 0.3s ease;
}
.type-card:hover {
  background: rgba(0,185,142,0.15);
  transform: scale(1.05);
}
.type-card.active {
  border-color: #00b98e;
  background: rgba(0,185,142,0.25);
}

/* Inputs */
.form-control {
  background-color: rgba(255,255,255,0.9);
  border-radius: 8px;
}
.form-control:focus {
  box-shadow: 0 0 5px #00b98e;
  border-color: #00b98e;
}

/* Carte principale */
.register-card {
  width: 90%;
  max-width: 800px;
  z-index: 2;
}

/* Bord du select */
::v-deep(.vs__dropdown-toggle) {
  border: 2px solid #ffffff;
  border-radius: 8px;
}

/* Texte du placeholder */
::v-deep(.vs__placeholder) {
  color: #ffffff;
  background-color: #ffffff;
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
