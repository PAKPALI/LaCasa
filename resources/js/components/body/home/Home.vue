<template>
  <!-- <ParticleJs /> -->
  <Header />
  <!-- <SearchBar /> -->
  <About />
  <Agent/>
  <Category />
  <Partners />
  <Team />
  <Testimonials />
  <!-- <Footer /> -->
</template>

<script setup>
  // Lazy load des composants
  // const ParticleJs = defineAsyncComponent(() => import('../../partial/Particles.vue'))
  // import ParticleJs from '@/components/partial/Particles.vue'
  import Header from '@/components/body/home/Header.vue'
  import SearchBar from '@/components/body/home/SearchForm.vue'
  import Category from '@/components/body/home/Category.vue'
  import About from '@/components/body/home/About.vue'
  import Partners from '@/components/body/home/Partners.vue'
  import Agent from '@/components/body/home/WhyChooseUs.vue'
  import Team from '@/components/body/home/TeamMember.vue'
  import Testimonials from '@/components/body/home/Testimonials.vue'
  // import Footer from '@/components/body/home/Footer.vue'
</script>
