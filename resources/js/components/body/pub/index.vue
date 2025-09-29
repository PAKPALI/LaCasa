<template>
  <!-- <ParticleJs /> -->
  <Header />
  <!-- <SearchBar /> -->
  <!-- <Category />
  <About /> -->
  <!-- <PropertyList /> -->
  <Publication :publications="publicationsList" />
  <!-- <Agent/>
  <Team />
  <Testimonials /> -->
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

import Header from '@/components/body/home/Header.vue'
import SearchBar from '@/components/body/home/SearchForm.vue'
import Publication from './generalPublication.vue'

const publicationsList = ref([])

// Charger les publications dès le montage
onMounted(async () => {
  try {
    const res = await axios.get('/api/publication')
    publicationsList.value = res.data
  } catch (err) {
    console.error('Erreur lors du chargement des publications', err)
  }
})
</script>
