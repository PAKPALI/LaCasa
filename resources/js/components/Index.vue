<template>
  <!-- <div class="d-flex"> -->
    <!-- Sidebar à gauche -->
    <!-- <SideBar /> -->

    <!-- Contenu principal à droite -->
    <div class="site-loader"></div>
    <div class="site-wrap">
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
  // import SideBar from './partial/SideBar.vue'
  import NavBar from './partial/NavBar.vue'
  // import ParticleJs from './partial/Particles.vue'
  import { ref } from 'vue'

  const mode = ref('1')
  const bgColor = ref('bg-light')

  function setMode(newMode) {
    mode.value = newMode
    bgColor.value = newMode === '1' ? 'bg-light' : 'bg-dark'
  }
</script>

<style scoped>
  /* Tu peux ajouter du style local ici */
</style>
