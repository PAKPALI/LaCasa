<template>
  <div class="register-page d-flex align-items-center justify-content-center min-vh-100 text-light">
    <div class="overlay"></div>

    <div class="register-card glass-card p-5 rounded shadow-lg position-relative">
      <!-- Logo utilisateur avec halo -->
      <div class="text-center mb-2 profile-logo">
        <img width="94" height="94" src="https://img.icons8.com/3d-fluency/94/user-shield.png" alt="user-shield"/>
        <div class="glow-ring"></div>
      </div>

      <h2 class="fw-bold text-center border-bottom mb-4 text-uppercase neon-text">Créer un compte</h2>

      <form @submit.prevent="registerUser">
        <div class="row">
          <!-- Nom -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold neon-text">Nom complet</label>
            <input type="text" v-model="form.name" class="form-control neon-input" placeholder="Votre nom" />
          </div>

          <!-- Email -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold neon-text">Adresse email</label>
            <input type="email" v-model="form.email" class="form-control neon-input" placeholder="Ex: exemple@mail.com" />
          </div>

          <!-- Mot de passe -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold neon-text">Mot de passe</label>
            <input type="password" v-model="form.password" class="form-control neon-input" placeholder="••••••••" />
          </div>

          <!-- Confirmation -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold neon-text">Confirmer le mot de passe</label>
            <input type="password" v-model="form.password_confirmation" class="form-control neon-input" placeholder="••••••••" />
          </div>

          <!-- Type d'utilisateur -->
          <div class="col-md-12 mb-3">
            <label class="form-label fw-semibold neon-text">Vous êtes :</label>
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
            <label class="form-label fw-semibold neon-text">Image de profil</label>
            <input type="file" @change="handleProfileImage" accept="image/*" class="form-control neon-input" />
          </div>
          <div v-if="previewImage" class="col-4 mb-3 text-center">
            <img :src="previewImage" alt="Aperçu" class="img-thumbnail" style="max-width: 150px;" />
          </div>

          <!-- Localisation -->
          <div class="col-12 mt-3">
            <h5 class="border-bottom border-accent pb-2 fw-bold neon-text">Localisation</h5>
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold neon-text">Pays</label>
            <v-select
              v-model="selectedCountry"
              :options="countries"
              label="name"
              :reduce="c => c.id"
              placeholder="Choisissez un pays"
              :loading="loadingCountries"
            />
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold neon-text">Ville</label>
            <v-select
              v-model="selectedTown"
              :options="towns"
              label="name"
              :reduce="t => t.id"
              placeholder="Choisissez une ville"
              :disabled="!selectedCountry"
              :loading="loadingTowns"
            />
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label fw-semibold neon-text">Quartier</label>
            <v-select
              v-model="selectedDistrict"
              :options="districts"
              label="name"
              :reduce="d => d.id"
              placeholder="Choisissez un quartier"
              :disabled="!selectedTown"
              :loading="loadingDistricts"
            />
          </div>

          <!-- Téléphone -->
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold neon-text">Numéro 1</label>
            <input type="number" v-model="form.phone1" class="form-control neon-input" placeholder="Ex: 90 00 00 00" />
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold neon-text">Numéro 2</label>
            <input type="number" v-model="form.phone2" class="form-control neon-input" placeholder="Ex: 90 00 00 00" />
          </div>

          <!-- Bouton -->
          <div class="col-12 text-center mt-4">
            <button type="submit" class="btn glow-btn-green btn-lg w-100" :disabled="isSubmitting">
              <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
              S'inscrire
            </button>
          </div>

          <div class="text-center mt-3">
            <router-link to='/login' class="nav-link neon-text">Se connecter?</router-link>
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
import { useRouter } from 'vue-router'

const profileImage = ref(null)
const previewImage = ref(null)

const handleProfileImage = (event) => {
  const file = event.target.files[0]
  if (!file) return
  profileImage.value = file
  const reader = new FileReader()
  reader.onload = (e) => previewImage.value = e.target.result
  reader.readAsDataURL(file)
}

const form = ref({ name:'', email:'', password:'', password_confirmation:'', phone1:'', phone2:'', user_type:null })
const selectedCountry = ref(null)
const selectedTown = ref(null)
const selectedDistrict = ref(null)
const countries = ref([]), towns = ref([]), districts = ref([])
const loadingCountries = ref(false), loadingTowns = ref(false), loadingDistricts = ref(false)
const isSubmitting = ref(false)
const router = useRouter()

const fetchCountries = async () => {
  loadingCountries.value = true
  countries.value = (await axios.get('/api/country')).data
  loadingCountries.value = false
}
fetchCountries()

watch(selectedCountry, async (newVal) => {
  towns.value = []; districts.value = []; selectedTown.value=null; selectedDistrict.value=null
  if(!newVal) return
  loadingTowns.value = true
  towns.value = (await axios.get(`/api/town?country_id=${newVal}`)).data
  loadingTowns.value = false
})

watch(selectedTown, async (newVal) => {
  districts.value = []; selectedDistrict.value=null
  if(!newVal) return
  loadingDistricts.value = true
  districts.value = (await axios.get(`/api/district?town_id=${newVal}`)).data
  loadingDistricts.value = false
})

const registerUser = async () => {
  if(isSubmitting.value) return
 if (!form.value.name.trim()) 
    return Swal.fire({ icon:'error', title:'Veuillez entrer votre nom complet', toast:true, position:'top-end', showConfirmButton:false, timer:3000 })

  if (!form.value.email.trim()) 
    return Swal.fire({ icon:'error', title:'Veuillez entrer une adresse email valide', toast:true, position:'top-end', showConfirmButton:false, timer:3000 })

  if (form.value.password.length < 6) 
    return Swal.fire({ icon:'error', title:'Le mot de passe doit contenir au moins 6 caractères', toast:true, position:'top-end', showConfirmButton:false, timer:3000 })

  if (form.value.password !== form.value.password_confirmation) 
    return Swal.fire({ icon:'error', title:'Les mots de passe ne correspondent pas', toast:true, position:'top-end', showConfirmButton:false, timer:3000 })

  if (!form.value.user_type) 
    return Swal.fire({ icon:'error', title:'Veuillez choisir votre type (Personne ou Agence)', toast:true, position:'top-end', showConfirmButton:false, timer:3000 })

  if (!selectedCountry.value || !selectedTown.value || !selectedDistrict.value)
    return Swal.fire({ icon:'error', title:'Veuillez sélectionner votre pays, ville et quartier', toast:true, position:'top-end', showConfirmButton:false, timer:3000 })

  if (!form.value.phone1 && !form.value.phone2)
    return Swal.fire({ icon:'error', title:'Veuillez entrer au moins un numéro de téléphone', toast:true, position:'top-end', showConfirmButton:false, timer:3000 })
  try {
    isSubmitting.value = true
    const payload = new FormData()
    for(const key in form.value) payload.append(key, form.value[key])
    payload.append('country_id', selectedCountry.value)
    payload.append('town_id', selectedTown.value)
    payload.append('district_id', selectedDistrict.value)
    if(profileImage.value) payload.append('profile_image', profileImage.value)
    const res = await axios.post('/api/register', payload, { headers:{ 'Content-Type':'multipart/form-data' } })
    if(res.data.status){
      Swal.fire({ icon:'success', title: res.data.message||'Inscription réussie ✅', toast:true, position:'top-end', showConfirmButton:false, timer:3000 })
      router.push('/login')
    }else {
      Swal.fire({ icon:'error', title: res.data.message||'Erreur lors de l’inscription', toast:true, position:'top-end', showConfirmButton:false, timer:3000 })
    }
    
  } catch(err) {
    Swal.fire({ icon:'error', title: err.response?.data?.message||'Erreur lors de l’inscription', toast:true, position:'top-end', showConfirmButton:false, timer:3000 })
  } finally { isSubmitting.value=false }
}
</script>

<style scoped>
.register-page {
  min-height: 100vh;
  background: 
              url('https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;
  display:flex; align-items:center; justify-content:center; position:relative; color:#fff;
}

.glass-card {
  background: rgba(5,1,27,0.7);
  backdrop-filter: blur(0px);
  border-radius: 25px;
  padding: 2rem;
  box-shadow: 0 8px 30px rgba(0,185,142,0.3);
  border: 3px solid rgba(0,185,142,0.4);
  width:90%; max-width:800px;
  position:relative; z-index:2;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.glass-card:hover {
  transform: scale(1.03);
  box-shadow: 0 0 30px rgba(0,185,142,0.4),0 0 50px rgba(0,185,142,0.3);
}

.neon-input {
  background: rgba(0,0,0,0.3);
  border:1px solid rgba(0,185,142,0.3);
  color:#00b98e;
  border-radius:8px;
  box-shadow: inset 0 0 3px rgba(0,185,142,0.2);
}
.neon-input::placeholder { color: rgba(0,185,142,0.5); }
.neon-input:focus { border-color:#00b98e; box-shadow:0 0 5px rgba(0,185,142,0.4), inset 0 0 2px rgba(0,185,142,0.2); outline:none; }

.glow-btn-green {
  background: linear-gradient(90deg,#00b98e80,#00b98e60);
  border:none; color:#fff; transition:0.3s;
  box-shadow:0 0 5px rgba(0,185,142,0.3),0 0 10px rgba(0,185,142,0.2);
}
.glow-btn-green:hover {
  background: linear-gradient(90deg,#014b3a80,#00b98e60);
  box-shadow:0 0 10px rgba(0,185,142,0.4),0 0 20px rgba(0,185,142,0.3);
}

.neon-text { color:#00b98e; text-shadow:0 0 3px rgba(0,185,142,0.4),0 0 6px rgba(0,185,142,0.3); }

.overlay { position:absolute; top:0; left:0; width:100%; height:100%; background-color: rgba(14,46,80,0.6); z-index:1; }

.profile-logo { display:flex;justify-content:center;align-items:center;position:relative;margin-bottom:1rem }
.glow-ring { position:absolute; top:-10px; left:-10px; right:-10px; bottom:-10px; border:2px solid rgba(0,185,142,0.3); border-radius:50%; box-shadow:0 0 10px rgba(0,185,142,0.3); animation:pulse 2s infinite ease-in-out; }
@keyframes pulse { 0%,100%{transform:scale(1);opacity:0.5}50%{transform:scale(1.05);opacity:0.7} }

.type-card {
  cursor: pointer;
  border: 2px solid transparent;
  background: rgba(0,0,0,0.2);
  transition: all 0.3s ease;
  z-index:3;
}
.type-card:hover { background: rgba(0,185,142,0.25); transform: scale(1.05); }
.type-card.active { border-color: #00b98e; background: rgba(0,185,142,0.3); }

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