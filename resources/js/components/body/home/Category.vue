<template>
  <div class="container-xxl py-5">
    <div class="container">
      <div class="text-center mx-auto mb-5" style="max-width: 600px;">
        <h1 class="mb-3">Types de logements</h1>
        <p><h6>Découvrez les catégories de logements disponibles sur LaCasa.</h6></p>
      </div>
      <div class="row g-4">
        <div class="col-lg-4 col-md-4 col-sm-6" v-for="(item, index) in categories" :key="index">
          <a class="cat-item d-block bg-light text-center rounded p-3" href="#">
            <div class="rounded p-4">
              <div class="icon mb-3">
                <img class="img-fluid" :src="item.icon" :alt="item.name">
              </div>
              <h6>{{ item.name }}</h6>
              <span>{{ item.count }} annonces</span>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Import des icônes
import iconApartment from '@images2/icon-apartment.png'
import iconVilla from '@images2/icon-villa.png'
import iconHouse from '@images2/icon-house.png'
import iconOffice from '@images2/icon-housing.png'
import iconBuilding from '@images2/icon-building.png'
import iconShop from '@images2/icon-condominium.png'

const categories = [
  { name: "Appartement", count: 120, icon: iconApartment },
  { name: "Villa", count: 80, icon: iconVilla },
  { name: "Maison", count: 150, icon: iconHouse },
  { name: "Bureau", count: 60, icon: iconOffice },
  { name: "Immeuble", count: 45, icon: iconBuilding },
  { name: "Boutique", count: 70, icon: iconShop },
]
</script>
