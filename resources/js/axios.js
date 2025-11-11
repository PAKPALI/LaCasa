import axios from 'axios'

// URL de ton backend Laravel
axios.defaults.baseURL = 'http://localhost:1111'

// Toujours envoyer les cookies (laravel_session, XSRF-TOKEN)
axios.defaults.withCredentials = true

// Intercepteur : ajoute le token CSRF automatiquement si présent
axios.interceptors.request.use(config => {
  const token = document.cookie
    .split('; ')
    .find(row => row.startsWith('XSRF-TOKEN='))
    ?.split('=')[1]

  if (token) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token)
  }

  return config
})

export default axios
