<template>
  <!-- <div class="d-flex"> -->
    <!-- Sidebar à gauche -->
    <!-- <SideBar /> -->

    <!-- Contenu principal -->
    <div class="container-xxl opacity bg-white p-0">
        <!-- Spinner Start -->
        <!-- <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> -->
        <!-- Spinner End -->
      <NavBar />
        <router-view v-slot="{ Component }">
          <component :is="Component" :setMode="setMode" />
        </router-view>
      <Footer />
    </div>
  <!-- </div> -->
</template>

<script setup>
  // import SideBar from './partial/SideBar.vue'
  import NavBar from './partial/NavBar2.vue'
  import Footer from './partial/Footer.vue'
  // import ParticleJs from './partial/Particles.vue'
  import { ref } from 'vue'
</script>

<style scoped>
  .opacity {
    /* background: #f9f9f9; */
    opacity: 0.9; /* 1 = opaque, 0 = transparent */
  }
</style>

