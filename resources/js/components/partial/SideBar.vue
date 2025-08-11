<!-- npm install vue-router -->
<template>
  <div class="sidebar bg-light p-3" style="min-height: 100vh; width: 200px;">
    <p><strong>Menu</strong></p>
    <ul class="list-unstyled">
      <li><router-link to='/home'> Acceuil </router-link></li>
      <li><router-link to='/products'> Produit</router-link></li>
    </ul>
  </div>
</template>



<script setup>
</script>

<style scoped>
/* Style local pour la sidebar si nécessaire */
</style>
