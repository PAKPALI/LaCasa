<!-- components/PropertyList.vue -->
<template>
  <div class="row mb-5">
    <div
      class="col-md-3 col-lg-4 mb-4"
      v-for="(property, idx) in properties"
      :key="idx"
    >
      <PropertyCard
        :image="property.image"
        :offerTypes="property.offerTypes"
        :title="property.title"
        :location="property.location"
        :price="property.price"
        :beds="property.beds"
        :baths="property.baths"
        :sqft="property.sqft"
        :favorite="property.favorite"
      />
    </div>
  </div>
</template>

<script setup>
import PropertyCard from './PropertyCard.vue'
import img1 from '@images/img_1.jpg'
import img2 from '@images/img_2.jpg'
import img3 from '@images/img_3.jpg'

const properties = [
  {
    image: img1,
    offerTypes: ['Sale', 'Rent'],
    title: '625 S. Berendo St',
    location: '625 S. Berendo St Unit 607 Los Angeles, CA 90005',
    price: '$2,265,500',
    beds: 2,
    baths: 2,
    sqft: 7000,
    favorite: false
  },
  {
    image: img2,
    offerTypes: ['Sale', 'Rent'],
    title: '871 Crenshaw Blvd',
    location: '1 New York Ave, Warners Bay, NSW 2282',
    price: '$2,265,500',
    beds: 2,
    baths: 2,
    sqft: 1620,
    favorite: true
  },
  {
    image: img3,
    offerTypes: ['Lease'],
    title: '853 S Lucerne Blvd',
    location: '853 S Lucerne Blvd Unit 101 Los Angeles, CA 90005',
    price: '$2,265,500',
    beds: 2,
    baths: 2,
    sqft: 5500,
    favorite: false
  }
]
</script>
