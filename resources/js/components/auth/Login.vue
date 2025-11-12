<template>
  <div class="login-page d-flex align-items-center justify-content-center min-vh-100 text-light">
    <div class="overlay"></div>

    <div class="login-card glass-card p-4 rounded-4 shadow-lg position-relative">
      <!-- Logo utilisateur avec halo -->
      <div class="text-center mb-3 profile-logo position-relative">
        <img
          width="90"
          height="90"
          src="https://img.icons8.com/3d-fluency/94/user-male-circle.png"
          alt="user-male-circle"
          class="user-icon"
        />
        <div class="glow-ring"></div>
      </div>

      <h2 class="fw-bold text-center border-bottom mb-4 text-uppercase neon-text">Connexion</h2>

      <form @submit.prevent="loginUser">
        <div class="mb-3">
          <label class="form-label fw-semibold neon-text">Adresse email</label>
          <div class="input-group input-group-lg">
            <span class="input-group-text neon-input-prepend">
              <i class="bi bi-envelope-fill text-light"></i>
            </span>
            <input
              type="email"
              v-model="form.email"
              class="form-control neon-input"
              placeholder="exemple@mail.com"
            />
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold neon-text">Mot de passe</label>
          <div class="input-group input-group-lg">
            <span class="input-group-text neon-input-prepend">
              <i class="bi bi-lock-fill text-light"></i>
            </span>
            <input
              type="password"
              v-model="form.password"
              class="form-control neon-input"
              placeholder="••••••••"
            />
          </div>
        </div>

        <div class="mb-3 form-check text-start">
          <input type="checkbox" class="form-check-input" v-model="form.remember" id="rememberCheck" />
          <label class="form-check-label neon-text small" for="rememberCheck">
            Se souvenir de moi
          </label>
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn glow-btn-green btn-lg text-light w-100 fw-semibold" :disabled="isSubmitting">
            <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
            Se connecter
          </button>
        </div>

        <div class="text-center mt-4">
          <router-link to="/register" class="nav-link neon-text small">Créer un compte ?</router-link>
          <a href="#" class="neon-text small">Mot de passe oublié ?</a>
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

const form = ref({ email: '', password: '', remember: false })
const isSubmitting = ref(false)

const loginUser = async () => {
  if (isSubmitting.value) return
  try {
    isSubmitting.value = true
    await axios.get('/sanctum/csrf-cookie')
    const res = await axios.post('/myLogin', form.value, { withCredentials: true })
    setUser(res.data.user)
    if (res.data.status) {
      Swal.fire({
        icon: 'success',
        title: res.data.message || 'Connexion réussie ✅',
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 2500,
      })
      setTimeout(() => {
        res.data.user.role === 1 ? router.push('/admin') : router.push('/home')
      }, 800)
    } else {
      Swal.fire({
        icon: 'error',
        title: res.data.message || 'Email ou mot de passe incorrect',
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
      })
    }
  } catch (err) {
    Swal.fire({
      icon: 'error',
      title: err.response?.data?.message || 'Erreur lors de la connexion',
      toast: true,
      position: 'top',
      showConfirmButton: false,
      timer: 3000,
    })
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
/* @import "bootstrap-icons/font/bootstrap-icons.css"; */

.login-page {
  background: url('https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  color: #fff;
}

/* ======= Overlay ======= */
.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(14, 46, 80, 0.7);
  z-index: 1;
  backdrop-filter: blur(1.5px);
}

/* ======= Glass Card ======= */
.glass-card {
  background: rgba(5, 1, 27, 0.65);
  border: 2px solid rgba(0, 185, 142, 0.5);
  border-radius: 25px;
  padding: 2rem;
  max-width: 400px;
  width: 90%;
  box-shadow: 0 10px 40px rgba(0, 185, 142, 0.5);
  z-index: 2;
  transition: all 0.35s ease;
}
.glass-card:hover {
  transform: scale(1.02);
  box-shadow: 0 0 30px rgba(0, 185, 142, 0.4), 0 0 60px rgba(0, 185, 142, 0.25);
}

/* ======= Inputs ======= */
.neon-input {
  background: rgba(255, 255, 255, 0.05);
  border: 1px solid rgba(0, 185, 142, 0.4);
  color: #fdfdfd;
  border-radius: 10px;
  transition: 0.3s ease;
}
.neon-input::placeholder {
  color: rgba(169, 228, 214, 0.4);
}
.neon-input:focus {
  border-color: #00b98e;
  box-shadow: 0 0 8px rgba(0, 185, 142, 0.5);
  outline: none;
}

.neon-input-prepend {
  background: rgba(240, 245, 244, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-right: none;
  color: #ffffff;
}

/* ======= Bouton ======= */
.glow-btn-green {
  background: linear-gradient(90deg, #00b98e, #009b79);
  border: none;
  color: #fff;
  transition: 0.3s ease;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 185, 142, 0.5);
}
.glow-btn-green:hover {
  background: linear-gradient(90deg, #009b79, #00b98e);
  transform: scale(1.02);
  box-shadow: 0 0 20px rgba(0, 185, 142, 0.6);
}

/* ======= Textes ======= */
.neon-text {
  color: #00b98e;
  text-shadow: 0 0 5px rgba(0, 185, 142, 0.5);
}

/* ======= Logo avec halo ======= */
.profile-logo {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}

.user-icon {
  border-radius: 50%;
  z-index: 2;
}

.glow-ring {
  position: absolute;
  top: -8px;
  left: -8px;
  right: -8px;
  bottom: -8px;
  border: 2px solid rgba(0, 185, 142, 0.4);
  border-radius: 50%;
  box-shadow: 0 0 15px rgba(0, 185, 142, 0.4);
  animation: pulse 2s infinite ease-in-out;
}

@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 0.5; }
  50% { transform: scale(1.05); opacity: 0.8; }
}
</style>
