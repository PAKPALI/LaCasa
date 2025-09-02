<template>
  <!-- Contenu principal -->
  <div class="container-xxl opacity bg-white p-0">

    <!-- Spinner Start -->
    <div
      id="spinner"
      :class="{ show: loading, hide: !loading }"
      class="bg-white position-fixed w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"
    >
      <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>
    <!-- Spinner End -->

    <NavBar />

    <router-view v-slot="{ Component }">
      <component :is="Component" :setMode="setMode" />
    </router-view>

    <Footer />
  </div>
</template>

<script setup>
import NavBar from './partial/NavBar2.vue'
import Footer from './partial/Footer.vue'
import { ref, onMounted } from 'vue'

const loading = ref(true)

// Simuler un chargement
onMounted(() => {
  setTimeout(() => {
    loading.value = false
  }, 1500) // ajuste la durée selon ton besoin
})
</script>

<style scoped>
#spinner {
  transform: translate(-50%, -50%);
  z-index: 2000;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}

#spinner.show {
  opacity: 1;
  visibility: visible;
}

#spinner.hide {
  opacity: 0;
  visibility: hidden;
}
</style>
