<template>
  <div class="property-entry h-100 property-card">
    <a href="property-details.html" class="property-thumbnail">
      <div class="offer-type-wrap">
        <span
          v-for="(type, index) in offerTypes"
          :key="index"
          class="offer-type"
          :class="offerTypeClass(type)"
        >
          {{ type }}
        </span>
      </div>
      <img :src="image" alt="Image" class="img-fluid rounded-top" />
    </a>
    <div class="p-4 property-body">
      <a href="#" class="property-favorite" :class="{ active: favorite }">
        <span class="icon-heart-o"></span>
      </a>
      <h2 class="property-title">
        <a href="property-details.html">{{ title }}</a>
      </h2>
      <span class="property-location d-block mb-3">
        <span class="property-icon icon-room"></span> {{ location }}
      </span>
      <strong class="property-price mb-3 d-block text-success">
        {{ price }}
      </strong>

      <!-- SPECIFICATIONS -->
      <ul class="property-specs-wrap mb-3 mb-lg-0">
        <li>
          <span class="property-specs">Chambres</span>
          <span class="property-specs-number">{{ beds }}</span>
        </li>
        <li>
          <span class="property-specs">Salles d’eau</span>
          <span class="property-specs-number">{{ baths }}</span>
        </li>
        <li>
          <span class="property-specs">Surface</span>
          <span class="property-specs-number">{{ sqft }} m²</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  image: String,
  offerTypes: { type: Array, default: () => [] },
  title: String,
  location: String,
  price: String,
  beds: Number,
  baths: Number,
  sqft: Number,
  favorite: Boolean
})

function offerTypeClass(type) {
  switch (type.toLowerCase()) {
    case 'sale':
      return 'bg-gradient-sale'
    case 'rent':
      return 'bg-gradient-rent'
    case 'lease':
      return 'bg-gradient-lease'
    default:
      return 'bg-gradient-default'
  }
}
</script>

<style scoped>
.property-card {
  position: relative;
  border-radius: 1rem;
  overflow: hidden;
  background: #0a0a0a; /* intérieur sombre */
  transition: transform 0.2s ease;
  z-index: 0;

  /* Bordure animée style néon */
  border: 1px solid transparent;
  background-image: 
    conic-gradient(from 0deg, #024124, #024d1f, #065731, #074f14, #056c29);
  background-origin: border-box;
  background-clip: border-box;
  animation: spinBorder 10s linear infinite; /* ⚡ rapide */
  box-shadow: 0 0 20px #034008e7, 0 0 40px #012809; /* glow néon */
}

@keyframes spinBorder {
  0%   { background-image: linear-gradient(135deg, #122f02eb, rgb(0, 0, 0) 70%, #0e2501d9); }
  50%   { background-image: linear-gradient(135deg, #2d7308eb, rgb(7, 160, 43) 70%, #40a705d9); }
  100% { background-image: conic-gradient(from 360deg, #ccdace, #edf1f0, #e8f5ef, #ecf2f1, #f3f6f5); }
}

/* Intérieur */
.property-body {
  position: relative;
  background: linear-gradient(135deg, #122f02eb, rgb(0, 0, 0) 70%, #0e2501d9);
  border-radius: 0.9rem;
  padding: 1.5rem;
  z-index: 1;
}

/* === Texte en blanc néon === */
.property-title a {
  color: #fff;
  text-decoration: none;
  text-shadow: 0 0 5px #011004, 0 0 10px #033e1e;
}
.property-title a:hover {
  color: #00ffcc;
}

.property-location,
.property-specs,
.property-specs-number {
  color: #fff;
  text-shadow: 0 0 3px #00ff88;
}

.property-price {
  font-size: 1.3rem;
  font-weight: bold;
  color: #00ff88 !important;
  text-shadow: 0 0 10px #00ffcc, 0 0 20px #00ff88;
}
</style>


