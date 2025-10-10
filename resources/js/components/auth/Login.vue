<template>
  <div class="login-page d-flex align-items-center justify-content-center min-vh-100 text-light">
    <div class="overlay"></div>

    <div class="login-card p-4 rounded shadow-lg position-relative bg-opacity-75">
      <h2 class="fw-bold text-center border-bottom mb-4 text-uppercase text-light">Connexion</h2>

      <form @submit.prevent="loginUser">
        <div class="mb-3">
          <label class="form-label fw-semibold">Adresse email</label>
          <input
            type="email"
            v-model="form.email"
            class="form-control"
            placeholder="Ex: exemple@mail.com"
            
          />
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Mot de passe</label>
          <input
            type="password"
            v-model="form.password"
            class="form-control"
            placeholder="••••••••"
          />
        </div>

        <div class="mb-3 form-check">
          <input
            type="checkbox"
            class="form-check-input"
            v-model="form.remember"
            id="rememberCheck"
          />
          <label class="form-check-label" for="rememberCheck">Se souvenir de moi</label>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-accent btn-lg w-100" :disabled="isSubmitting">
            <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
            Se connecter
          </button>
        </div>

        <div class="text-center mt-3">
          <a href="/forgot-password" class="text-accent">Mot de passe oublié ?</a>
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


// 🔧 Important : pour que Laravel puisse stocker la session (cookie)
axios.defaults.withCredentials = true

const router = useRouter()

const form = ref({
  email: '',
  password: '',
  remember: false
})

const isSubmitting = ref(false)

const loginUser = async () => {
  if (isSubmitting.value) return

  try {
    isSubmitting.value = true

    await axios.get('/sanctum/csrf-cookie');
    const res = await axios.post('/myLogin', form.value, { withCredentials: true })

    // On stocke l'utilisateur globalement
    setUser(res.data.user)

    if (res.data.status) {
      Swal.fire({
        icon: 'success',
        title: res.data.message || 'Connexion réussie ✅',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500
      })

      // 🎯 Redirection après connexion
      setTimeout(() => {
        if (res.data.user.role === 1) router.push('/admin')
        else router.push('/home')
      }, 800)
    } else {
      Swal.fire({
        icon: 'error',
        title: res.data.message || 'Email ou mot de passe incorrect',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      })
    }
  } catch (err) {
    Swal.fire({
      icon: 'error',
      title: err.response?.data?.message || 'Erreur lors de la connexion',
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    })
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)),
              url('https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=2000&q=80') center/cover no-repeat;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  color: #fff;
}

.login-card {
  background: rgba(5, 1, 27, 0.37); /* semi-transparent */
  backdrop-filter: blur(1px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 25px;
  padding: 2rem;
  box-shadow: 0 8px 20px rgba(27, 27, 27, 0.295);
  border: 3px solid rgba(0, 185, 142, 0.7);
  width: 90%;
  max-width: 400px;
  z-index: 2;
}

.login-card input {
  background-color: rgba(255,255,255,0.2);
  border: none;
  border-radius: 8px;
  color: #fff;
}

.login-card input::placeholder {
  color: rgba(255,255,255,0.8);
}

.login-card input:focus {
  box-shadow: 0 0 5px #00b98e;
  border-color: #00b98e;
}

.btn-accent {
  background-color: #0e2e50;
  border: none;
  color: #fff;
  transition: background 0.9s ease;
}
.btn-accent:hover {
  background-color: #00b98e;
}

.text-accent {
  color: #00b98e !important;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(14,46,80,0.6);
  z-index: 1;
}
</style>
