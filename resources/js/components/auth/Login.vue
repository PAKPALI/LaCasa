<template>
  <div class="login-page d-flex align-items-center justify-content-center min-vh-100 text-light">
    <div class="overlay"></div>

    <div class="login-card glass-card p-4 rounded shadow-lg position-relative">
      <!-- Logo utilisateur avec halo -->
      <div class="text-center mb-2 profile-logo">
        <img width="94" height="94" src="https://img.icons8.com/3d-fluency/94/user-male-circle.png" alt="user-male-circle"/>
        <div class="glow-ring"></div>
      </div>

      <h2 class="fw-bold text-center border-bottom mb-4 text-uppercase neon-text">Connexion</h2>

      <form @submit.prevent="loginUser">
        <div class="mb-3">
          <label class="form-label fw-semibold neon-text">Adresse email</label>
          <input
            type="email"
            v-model="form.email"
            class="form-control neon-input"
            placeholder="Ex: exemple@mail.com"
          />
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold neon-text">Mot de passe</label>
          <input
            type="password"
            v-model="form.password"
            class="form-control neon-input"
            placeholder="••••••••"
          />
        </div>

        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" v-model="form.remember" id="rememberCheck" />
          <label class="form-check-label neon-text" for="rememberCheck">Se souvenir de moi</label>
        </div>

        <div class="text-center">
          <button type="submit" class="btn glow-btn-green btn-lg text-light w-100" :disabled="isSubmitting">
            <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
            Se connecter
          </button>
        </div>


        <div class="text-center mt-3">
          <router-link to='/register' class="nav-link neon-text">Créer un compte?</router-link>
          <a href="#" class="neon-text">Mot de passe oublié ?</a>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import { useRouter } from 'vue-router'
import { setUser } from './auth.js'

axios.defaults.withCredentials = true
const router = useRouter()

const form = ref({ email:'', password:'', remember:false })
const isSubmitting = ref(false)

const loginUser = async () => {
  if (isSubmitting.value) return
  try {
    isSubmitting.value = true
    await axios.get('/sanctum/csrf-cookie')
    const res = await axios.post('/myLogin', form.value, { withCredentials:true })
    setUser(res.data.user)
    if(res.data.status){
      Swal.fire({icon:'success', title:res.data.message||'Connexion réussie ✅', toast:true, position:'top', showConfirmButton:false, timer:2500})
      setTimeout(()=>{res.data.user.role===1?router.push('/admin'):router.push('/home')},800)
    } else {
      Swal.fire({icon:'error', title:res.data.message||'Email ou mot de passe incorrect', toast:true, position:'top', showConfirmButton:false, timer:3000})
    }
  } catch(err) {
    Swal.fire({icon:'error', title:err.response?.data?.message||'Erreur lors de la connexion', toast:true, position:'top', showConfirmButton:false, timer:3000})
  } finally { isSubmitting.value=false }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  background: 
              url('https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;
  display: flex; align-items: center; justify-content: center; position: relative; color:#fff;
}

/* ======= Glass Card ======= */
.glass-card {
  background: rgba(5,1,27,0.7);
  backdrop-filter: blur(0px);
  border-radius: 25px;
  padding: 2rem;
  box-shadow: 0 8px 30px rgba(0,185,142,0.3);
  border: 3px solid rgba(0,185,142,0.4);
  width: 90%;
  max-width: 400px;
  position: relative;
  z-index:2;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.glass-card:hover {
  transform: scale(1.03);
  box-shadow: 0 0 30px rgba(0,185,142,0.4),0 0 50px rgba(0,185,142,0.3);
}

/* ======= Inputs doux ======= */
.neon-input {
  background: rgba(0,0,0,0.3);
  border:1px solid rgba(0,185,142,0.3);
  color:#00b98e;
  border-radius:8px;
  box-shadow: inset 0 0 3px rgba(0,185,142,0.2);
}
.neon-input::placeholder { color: rgba(0,185,142,0.5); }
.neon-input:focus { border-color:#00b98e; box-shadow:0 0 5px rgba(0,185,142,0.4), inset 0 0 2px rgba(0,185,142,0.2); outline:none; }

/* ======= Boutons verts doux ======= */
.glow-btn-green {
  background: linear-gradient(90deg,#00b98e80,#00b98e60);
  border:none; color:#000; transition:0.3s;
  box-shadow:0 0 5px rgba(0,185,142,0.3),0 0 10px rgba(0,185,142,0.2);

}
.glow-btn-green:hover {
  background: linear-gradient(90deg,#014b3a80,#00b98e60);
  box-shadow:0 0 10px rgba(0,185,142,0.4),0 0 20px rgba(0,185,142,0.3);
}

/* ======= Textes doux ======= */
.neon-text { color:#00b98e; text-shadow:0 0 3px rgba(0,185,142,0.4),0 0 6px rgba(0,185,142,0.3); }

/* ======= Overlay ======= */
.overlay { position:absolute; top:0; left:0; width:100%; height:100%; background-color: rgba(14,46,80,0.6); z-index:1; }

/* ======= Logo utilisateur halo doux ======= */
.profile-logo { position: relative; display:flex; justify-content: center; align-items: center; margin-bottom: 1rem;}


.glow-ring {
  position:absolute; top:-10px; left:-10px; right:-10px; bottom:-10px;
  border:2px solid rgba(0,185,142,0.3);
  border-radius:50%;
  box-shadow:0 0 10px rgba(0,185,142,0.3);
  animation: pulse 2s infinite ease-in-out;
}
@keyframes pulse {
  0%,100%{ transform: scale(1); opacity:0.5; }
  50%{ transform: scale(1.05); opacity:0.7; }
}
</style>
