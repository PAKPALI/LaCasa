<template>
  <!-- Particles : démarre animation seulement si visible -->
  <!-- <div ref="particleSection">
    <ParticleJs v-if="showParticle" />
  </div> -->

  <!-- <div ref="homeSliderSection"> -->
    <HomeSlider />
  <!-- </div> -->

  <!-- <div ref="introSection">
    <Intro v-if="showIntro" />
  </div>

  <div ref="infoSection">
    <Info v-if="showInfo" />
  </div>

  <div ref="propertyListSection">
    <PropertyList v-if="showPropertyList" />
  </div>

  <div ref="whyChooseSection">
    <WhyChooseUs v-if="showWhyChoose" />
  </div>

  <div ref="agentsListSection">
    <AgentsList v-if="showAgentsList" />
  </div>

  <div ref="testimonialsSection">
    <Testimonials v-if="showTestimonials" />
  </div>

  <div ref="footerSection">
    <Footer v-if="showFooter" />
  </div> -->
</template>

<script setup>
import { ref, onMounted, defineAsyncComponent } from 'vue'

import HomeSlider from './HomeSlider.vue'

// Lazy load des composants
const ParticleJs = defineAsyncComponent(() => import('../../partial/Particles.vue'))
// const HomeSlider = defineAsyncComponent(() => import('./HomeSlider.vue'))
const Intro = defineAsyncComponent(() => import('./Introduction.vue'))
const Info = defineAsyncComponent(() => import('./Information.vue'))
const PropertyList = defineAsyncComponent(() => import('./PropertyList.vue'))
const WhyChooseUs = defineAsyncComponent(() => import('./WhyChooseUs.vue'))
const AgentsList = defineAsyncComponent(() => import('./AgentsList.vue'))
const Testimonials = defineAsyncComponent(() => import('./Testimonials.vue'))
const Footer = defineAsyncComponent(() => import('./Footer.vue'))

// Flags pour affichage différé
const showParticle = ref(false)
// const showHomeSlider = ref(false)
const showIntro = ref(false)
const showInfo = ref(false)
const showPropertyList = ref(false)
const showWhyChoose = ref(false)
const showAgentsList = ref(false)
const showTestimonials = ref(false)
const showFooter = ref(false)

// Refs pour sections
const particleSection = ref(null)
// const homeSliderSection = ref(null)
const introSection = ref(null)
const infoSection = ref(null)
const propertyListSection = ref(null)
const whyChooseSection = ref(null)
const agentsListSection = ref(null)
const testimonialsSection = ref(null)
const footerSection = ref(null)

onMounted(() => {
  const sections = [
    { ref: particleSection, flag: showParticle },
    // { ref: homeSliderSection, flag: showHomeSlider },
    { ref: introSection, flag: showIntro },
    { ref: infoSection, flag: showInfo },
    { ref: propertyListSection, flag: showPropertyList },
    { ref: whyChooseSection, flag: showWhyChoose },
    { ref: agentsListSection, flag: showAgentsList },
    { ref: testimonialsSection, flag: showTestimonials },
    { ref: footerSection, flag: showFooter },
  ]

  // Intersection Observer pour afficher chaque section uniquement quand visible
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        const section = sections.find(s => s.ref.value === entry.target)
        if(section && !section.flag.value){
          section.flag.value = true
          observer.unobserve(entry.target) // plus besoin d'observer
        }
      }
    })
  }, { threshold: 0.1 })

  sections.forEach(s => {
    if(s.ref.value) observer.observe(s.ref.value)
  })
})
</script>
