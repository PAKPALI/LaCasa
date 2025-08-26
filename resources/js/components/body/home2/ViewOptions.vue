<template>
  <div class="view-options bg-white py-3 px-3 d-md-flex align-items-center">
    <div class="mr-auto">
      <a href="#" class="icon-view view-module active"><span class="icon-view_module"></span></a>
      <a href="#" class="icon-view view-list"><span class="icon-view_list"></span></a>
    </div>
    <div class="ml-auto d-flex align-items-center">
      <div>
        <a href="#" class="view-list px-3 border-right active">All</a>
        <a href="#" class="view-list px-3 border-right">Rent</a>
        <a href="#" class="view-list px-3">Sale</a>
      </div>
      <div class="select-wrap">
        <select v-model="sortBy" class="form-control form-control-sm d-block rounded-0">
          <option value="">Sort by</option>
          <option value="price-asc">Price Ascending</option>
          <option value="price-desc">Price Descending</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const sortBy = ref('')
</script>
