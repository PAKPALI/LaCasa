<template>
  <!-- <div class="d-flex"> -->
    <!-- Sidebar à gauche -->
    <!-- <SideBar /> -->

      <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
          <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
          </div>
        </div>
        <!-- Spinner End -->
        <NavBar />
        <!-- <div class="container border bg-dark p-3 mt-5" :class="bgColor"> -->
          <router-view v-slot="{ Component }">
            <component :is="Component" :mode="mode" :setMode="setMode" :bgColorChange="bgColorChange" />
          </router-view>
        <!-- </div> -->
     </div>
  <!-- </div> -->
</template>

<script setup>
  import NavBar from './partial/NavBar2.vue'
  // import ParticleJs from './partial/Particles.vue'
  import { ref } from 'vue'
</script>

<style scoped>
  .container-xxl.py-5 {
  position: relative;
  z-index: 1;
  opacity: 1;
}
</style>
