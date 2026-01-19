<template>
  <div class="container-xxl py-2">
    <div class="container">
      <div class="text-center mx-auto mb-3" style="max-width: 600px;">
        <h1 class="mb-3 fw-bold text-dark">Statistiques utilisateurs</h1>
        <h5 class="text-muted">
            Aperçu global des utilisateurs et agences présentes sur <strong class="text-success">LaCasa
          </strong>
        </h5>
      </div>

      <!-- 🔄 Loader -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-3 text-muted">Chargement des statistiques...</p>
      </div>

      <!-- 📊 Cards -->
      <div v-else class="row g-4">
        <div
          v-for="(item, index) in statsCards"
          :key="index"
          class="col-lg-3 col-md-6 col-sm-6"
        >
          <div class="cat-card text-center rounded-4 shadow-sm p-4 position-relative">
            <div class="floating-icon">
              <i :class="item.iconClass" style="font-size: 2rem;"></i>
            </div>

            <div class="mt-5 pt-3 card-content">
              <!-- Compteur animé -->
              <h3 class="fw-bold mb-1">{{ item.displayValue }}</h3>
              <p class="small mb-0"><strong>{{ item.label }}</strong></p>
            </div>

            <div class="hover-bar"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const loading = ref(true)
const statsCards = ref([])

/**
 * Fonction compteur animé
 */
const animateCounter = (item, duration = 5000) => {
  const start = 0
  const end = item.value
  const startTime = performance.now()

  const update = (currentTime) => {
    const progress = Math.min((currentTime - startTime) / duration, 1)
    item.displayValue = Math.floor(progress * end)

    if (progress < 1) {
      requestAnimationFrame(update)
    } else {
      item.displayValue = end
    }
  }

  requestAnimationFrame(update)
}

onMounted(async () => {
  try {
    const { data } = await axios.get('/users/stats')

    statsCards.value = [
      {
        label: 'Utilisateurs',
        value: data.total_users,
        displayValue: 0,
        iconClass: 'bi bi-people-fill text-primary'
      },
      {
        label: 'Personnes',
        value: data.persons,
        displayValue: 0,
        iconClass: 'bi bi-person-fill text-primary'
      },
      {
        label: 'Agences',
        value: data.agencies,
        displayValue: 0,
        iconClass: 'bi bi-building text-primary'
      },
      {
        label: 'Agences certifiées',
        value: data.verified_agencies,
        displayValue: 0,
        iconClass: 'bi bi-patch-check-fill text-primary'
      }
    ]

    // Lancer l’animation compteur
    statsCards.value.forEach(item => animateCounter(item))

  } catch (error) {
    console.error('Erreur chargement stats utilisateurs', error)
  } finally {
    loading.value = false
  }
})
</script>


<style scoped>
.cat-card {
  background-color: rgba(255, 255, 255, 0.7);
  transition: all 0.35s ease;
}

.cat-card:hover {
  transform: translateY(-8px);
  background: linear-gradient(90deg, #0e2e50, #00b894);
}

.floating-icon {
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  background: #fff;
  border-radius: 50%;
  padding: 14px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
}

.floating-icon i {
  animation: float 3s ease-in-out infinite;
}

@keyframes float {
  0% { transform: translateY(0); }
  50% { transform: translateY(-6px); }
  100% { transform: translateY(0); }
}

.card-content h3,
.cat-card:hover .card-content p {
  background: linear-gradient(90deg, #0e2e50, #00b894);
  color: #fff;
}

.hover-bar {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  width: 0;
  background: linear-gradient(90deg, #0e2e50, #00b894);
  transition: width 0.4s ease;
}

.cat-card:hover .hover-bar {
  width: 100%;
}
</style>

