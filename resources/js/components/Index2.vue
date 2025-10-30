<template>
  <div class="container-xxl p-0" style="background-color: rgba(255, 255, 255, 0.8);">
    <!-- Background Starfall -->
     <Starfall />
    
    <!-- Spinner Start -->
    <div
      id="spinner" :class="{ show: loading, hide: !loading }"
      class="bg-white position-fixed w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center"
    >
      <!-- ======== NOUVEAU LOADER ======== -->
      <div class="out_container">
        <div class="ring"></div>
        <div class="ring"></div>
      </div>

      
      <!-- ======== FIN NOUVEAU LOADER ======== -->

      <!-- ======== ANCIEN SPINNER (COMMENTÉ) ========
      <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      =========================================== -->
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
import Starfall from './partial/Starfall.vue'
import Particles from './partial/Particles.vue'
import { ref, onMounted } from 'vue'

const loading = ref(true)

onMounted(() => {
  setTimeout(() => {
    loading.value = false
  }, 1500)
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

/* ======= NOUVEAU LOADER ORIGINAL ======= */
.out_container {
  position: fixed;
  font-size: 1.5vw;
  perspective: 10em;
  transform-style: preserve-3d;
  width: 10em;
  height: 10em;
}
.ring {
  position: fixed;
  transform-style: preserve-3d;
  animation: ringmove 1s infinite linear;
  width: 100%;
  height: 100%;
  border-radius: 50%;
}
.ring:nth-child(1) {
  /* border: solid 1em hsl(240deg, 100%, 50%); */
  border: solid 1em hsl(153, 72%, 39%);
  margin-top: -3em;
}
.ring:nth-child(2) {
  /* border: solid 1em hsl(220deg, 100%, 70%); */
   border: solid 1em hsl(153, 60%, 55%);
  margin-top: 3em;
  animation-delay: -0.5s;
}
@keyframes ringmove {
  0% {
    transform: rotateX(90deg) rotateZ(0deg) rotateX(30deg);
  }
  100% {
    transform: rotateX(90deg) rotateZ(360deg) rotateX(30deg);
  }
}
/* ====================================== */
</style>
