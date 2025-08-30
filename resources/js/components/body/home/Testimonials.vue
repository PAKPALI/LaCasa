<template>
  <div class="container-xxl py-5">
    <div class="container">
      <div class="text-center mx-auto mb-5" style="max-width: 600px;">
        <h1 class="mb-3">Témoignages</h1>
        <p>Découvrez ce que nos utilisateurs disent de LaCasa</p>
      </div>
      <div class="owl-carousel testimonial-carousel">
        <div class="testimonial-item bg-light rounded p-4" v-for="(t, i) in testimonials" :key="i">
          <p>{{ t.message }}</p>
          <h6 class="mb-1">{{ t.name }}</h6>
          <small>{{ t.role }}</small>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const testimonials = [
  { name: "Jean K.", role: "Client", message: "J’ai trouvé un appartement en 2 jours grâce à LaCasa !" },
  { name: "Amina D.", role: "Démarcheuse", message: "La plateforme est simple et m’aide à publier mes annonces facilement." },
]
</script>
