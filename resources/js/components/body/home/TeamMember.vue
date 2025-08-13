<template>
  <div class="team-member border p-4">
    <img :src="image" alt="Image" class="img-fluid rounded mb-4" />
    <div class="text">
      <h2 class="mb-2 font-weight-light text-black h4">{{ name }}</h2>
      <span class="d-block mb-3 text-white-opacity-05">{{ position }}</span>
      <p>{{ description }}</p>
      <p>
        <a v-for="(icon, i) in social" :key="i" :href="icon.link" class="text-black p-2" target="_blank" rel="noopener noreferrer">
          <span :class="icon.class"></span>
        </a>
      </p>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  image: String,
  name: String,
  position: String,
  description: String,
  social: Array
})
</script>
