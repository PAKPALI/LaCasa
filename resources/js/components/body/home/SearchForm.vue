<template>
  <form class="form-search col-md-12" style="margin-top: -100px;" @submit.prevent="onSubmit">
    <div class="row align-items-end">
      <div class="col-md-3">
        <label for="list-types">Listing Types</label>
        <select v-model="listingType" id="list-types" class="form-control d-block rounded-0">
          <option value="">Condo</option>
          <option value="">Commercial Building</option>
          <option value="">Land Property</option>
        </select>
      </div>
      <div class="col-md-3">
        <label for="offer-types">Offer Type</label>
        <select v-model="offerType" id="offer-types" class="form-control d-block rounded-0">
          <option value="">For Sale</option>
          <option value="">For Rent</option>
          <option value="">For Lease</option>
        </select>
      </div>
      <div class="col-md-3">
        <label for="select-city">Select City</label>
        <select v-model="city" id="select-city" class="form-control d-block rounded-0">
          <option value="">New York</option>
          <option value="">Brooklyn</option>
          <option value="">London</option>
          <option value="">Japan</option>
          <option value="">Philippines</option>
        </select>
      </div>
      <div class="col-md-3">
        <input type="submit" class="btn btn-success text-white btn-block rounded-0" value="Search" />
      </div>
    </div>
  </form>
</template>

<script setup>
import { ref } from 'vue'

const listingType = ref('')
const offerType = ref('')
const city = ref('')

function onSubmit() {
  alert(`Recherche: ${listingType.value}, ${offerType.value}, ${city.value}`)
}
</script>
