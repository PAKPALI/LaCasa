<!-- components/PropertyCard.vue -->
<template>
  <div class="property-entry h-100">
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
      <img :src="image" alt="Image" class="img-fluid" />
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
      <strong class="property-price text-primary mb-3 d-block text-success">
        {{ price }}
      </strong>
      <ul class="property-specs-wrap mb-3 mb-lg-0">
        <li>
          <span class="property-specs">Beds</span>
          <span class="property-specs-number">{{ beds }} <sup>+</sup></span>
        </li>
        <li>
          <span class="property-specs">Baths</span>
          <span class="property-specs-number">{{ baths }}</span>
        </li>
        <li>
          <span class="property-specs">SQ FT</span>
          <span class="property-specs-number">{{ sqft }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  image: String,
  offerTypes: { type: Array,default: () => []},
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
      return 'bg-danger'
    case 'rent':
      return 'bg-success'
    case 'lease':
      return 'bg-info'
    default:
      return ''
  }
}
</script>
