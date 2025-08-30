<template>
  <ParticleJs />
  <Header />
  <SearchBar />
  <Category />
  <About />
  <PropertyList />
  <Agent/>
  <Team />
  <Testimonials />
  <!-- <Footer /> -->
</template>

<script setup>
  // Lazy load des composants
  const ParticleJs = defineAsyncComponent(() => import('@/components/partial/Particles.vue'))
  // import ParticleJs from '@/components/partial/Particles.vue'
  import Header from '@/components/body/home/Header.vue'
  import SearchBar from '@/components/body/home/SearchForm.vue'
  import Category from '@/components/body/home/Category.vue'
  import About from '@/components/body/home/About.vue'
  import PropertyList from '@/components/body/home/PropertyList.vue'
  import Agent from '@/components/body/home/Agent.vue'
  import Team from '@/components/body/home/TeamMember.vue'
  import Testimonials from '@/components/body/home/Testimonials.vue'
  // import Footer from '@/components/body/home/Footer.vue'
</script>
