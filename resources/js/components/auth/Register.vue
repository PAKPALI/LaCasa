<template>
  <div class="register-container mt-5">
    <div class="register-card mt-3">
      <div class="text-center mb-4">
        <img src="https://img.icons8.com/3d-fluency/94/user-shield.png" alt="User" class="logo" />
        <h2 class="title">Créer un compte</h2>
        <p class="subtitle">Rejoignez LaCasa et bougez simplement🚀</p>
      </div>

      <form @submit.prevent="registerUser" class="fade-in">
        <div class="row">
          <!-- Nom complet -->
          <div class="col-md-6 mb-3">
            <label class="form-label">Nom complet</label>
            <input type="text" v-model="form.name" class="form-control" placeholder="Ex : Didier Pakpali" />
          </div>

          <!-- Email -->
          <div class="col-md-6 mb-3">
            <label class="form-label">Adresse email</label>
            <input type="email" v-model="form.email" class="form-control" placeholder="Ex : exemple@mail.com" />
          </div>

          <!-- Mot de passe -->
          <div class="col-md-6 mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" v-model="form.password" class="form-control" placeholder="••••••••" />
          </div>

          <!-- Confirmation -->
          <div class="col-md-6 mb-3">
            <label class="form-label">Confirmer le mot de passe</label>
            <input type="password" v-model="form.password_confirmation" class="form-control" placeholder="••••••••" />
          </div>

          <!-- Type d'utilisateur -->
          <div class="col-md-12 mb-3">
            <label class="form-label">Vous êtes :</label>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
              <div
                class="user-type-card"
                :class="{ active: form.user_type === 1 }"
                @click="form.user_type = 1"
              >
                <i class="bi bi-person-fill fs-2"></i>
                <span>Particulier</span>
              </div>
              <div
                class="user-type-card"
                :class="{ active: form.user_type === 2 }"
                @click="form.user_type = 2"
              >
                <i class="bi bi-building fs-2"></i>
                <span>Agence</span>
              </div>
            </div>
          </div>

          <!-- Profil -->
          <div class="col-md-8 mb-3">
            <label class="form-label">Image de profil</label>
            <input type="file" @change="handleProfileImage" accept="image/*" class="form-control" />
          </div>
          <div class="col-md-4 mb-3 text-center" v-if="previewImage">
            <img :src="previewImage" alt="Aperçu" class="preview-image" />
          </div>

          <!-- Localisation -->
          <div class="col-12 mt-3">
            <h5 class="section-title">Localisation</h5>
          </div>

          <div class="col-md-4 mb-3">
            <v-select v-model="selectedCountry" :options="countries" label="name" :reduce="c => c.id" placeholder="Pays" />
          </div>

          <div class="col-md-4 mb-3">
            <v-select v-model="selectedTown" :options="towns" label="name" :reduce="t => t.id" placeholder="Ville" :disabled="!selectedCountry" />
          </div>

          <div class="col-md-4 mb-3">
            <v-select v-model="selectedDistrict" :options="districts" label="name" :reduce="d => d.id" placeholder="Quartier" :disabled="!selectedTown" />
          </div>

          <!-- Téléphone -->
          <div class="col-md-6 mb-3">
            <label class="form-label">Téléphone principal</label>
            <input type="number" v-model="form.phone1" class="form-control" placeholder="Ex : 90 85 94 88" />
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Autre numéro</label>
            <input type="number" v-model="form.phone2" class="form-control" placeholder="Ex : 96 12 34 56" />
          </div>

          <!-- Bouton -->
          <div class="col-12 mt-4 text-center">
            <button type="submit" class="btn-register" :disabled="isSubmitting">
              <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
              S'inscrire
            </button>
          </div>

          <p class="text-center mt-3">
            Déjà un compte ?
            <router-link to="/login" class="login-link">Se connecter</router-link>
          </p>
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
.register-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  color: #fff;
  padding: 2rem;
  background: url('https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;
  background-size: cover;
}

.register-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  backdrop-filter: blur(1.5px); /* flou d'arrière-plan */
  background-color: rgba(14, 46, 80, 0.7);
  z-index: 0;
  border-radius: inherit;
}

.register-card {
  position: relative; /* pour être au-dessus du ::before */
  z-index: 1;
  background: rgba(5, 1, 27, 0.65);
  backdrop-filter: blur(1px);
  border: 1px solid rgba(0, 255, 170, 0.5);
  border-radius: 20px;
  padding: 3rem;
  width: 90%;
  max-width: 850px;
  box-shadow: 0 8px 25px rgba(0, 255, 170, 0.15);
  animation: fadeIn 0.8s ease;
}

.logo {
  width: 90px;
  border-radius: 50%;
  box-shadow: 0 0 15px rgba(0, 255, 170, 0.5);
}

.title {
  color: #00ffb2;
  font-weight: 700;
  margin-top: 0.5rem;
}

.subtitle {
  color: #9adbc9;
  font-size: 0.95rem;
}

.form-control {
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(0, 255, 170, 0.3);
  border-radius: 8px;
  color: #fff;
  padding: 0.6rem 0.8rem;
}
.form-control:focus {
  box-shadow: 0 0 10px rgba(0, 255, 170, 0.4);
  border-color: #00ffb2;
  outline: none;
}

.section-title {
  color: #00ffb2;
  border-bottom: 2px solid rgba(0, 255, 170, 0.2);
  padding-bottom: 4px;
  margin-bottom: 1rem;
}

.user-type-card {
  background: rgba(255, 255, 255, 0.08);
  border: 2px solid transparent;
  border-radius: 12px;
  padding: 1.2rem 2rem;
  text-align: center;
  cursor: pointer;
  transition: 0.3s;
  color: #bfeee0;
}
.user-type-card:hover {
  transform: scale(1.05);
  border-color: rgba(0, 255, 170, 0.3);
}
.user-type-card.active {
  border-color: #00ffb2;
  background: rgba(0, 255, 170, 0.15);
  color: #00ffb2;
}

.preview-image {
  width: 120px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 255, 170, 0.3);
}

.btn-register {
  background: linear-gradient(90deg, #00ffb2, #00a37a);
  border: none;
  border-radius: 10px;
  color: #04142c;
  font-weight: bold;
  font-size: 1rem;
  padding: 0.8rem 2rem;
  width: 100%;
  transition: 0.3s;
  box-shadow: 0 4px 20px rgba(0, 255, 170, 0.2);
}
.btn-register:hover {
  box-shadow: 0 0 25px rgba(0, 255, 170, 0.4);
  transform: translateY(-2px);
}

.login-link {
  color: #00ffb2;
  font-weight: 500;
  text-decoration: none;
}
.login-link:hover {
  text-decoration: underline;
}

/* Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
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