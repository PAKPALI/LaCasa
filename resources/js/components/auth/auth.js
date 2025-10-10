// src/stores/auth.js
import { ref } from 'vue'
import axios from 'axios'

export const user = ref(null)   // ref globale pour stocker l'utilisateur
export const isAuthenticated = ref(false)

// fonction pour récupérer les infos utilisateur depuis le backend
export async function fetchUser() {
  try {
    const res = await axios.get('/api/me', { withCredentials: true })
    user.value = res.data
    isAuthenticated.value = true
  } catch (err) {
    user.value = null
    isAuthenticated.value = false
  }
}

// fonction pour mettre à jour localement l'utilisateur après login
export function setUser(u) {
  user.value = u
  isAuthenticated.value = !!u
}

// fonction pour logout
export function logout() {
  user.value = null
  isAuthenticated.value = false
  axios.post('/api/logout', {}, { withCredentials: true })
}
