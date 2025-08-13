<template>
  <div class="site-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-7 text-center">
          <div class="site-section-title">
            <h2>Why Choose Us?</h2>
          </div>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis maiores quisquam saepe architecto error corporis aliquam...</p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 col-lg-4" v-for="(service, i) in services" :key="i">
          <div class="service text-center">
            <span :class="service.icon"></span>
            <h2 class="service-heading">{{ service.title }}</h2>
            <p>{{ service.description }}</p>
            <a href="#" >
              <p><span class="read-more">Read More</span></p>
            </a>
            </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const services = [
  {
    icon: 'icon flaticon-house',
    title: 'Research Subburbs',
    description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis ex odio molestia.'
  },
  {
    icon: 'icon flaticon-sold',
    title: 'Sold Houses',
    description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis ex odio molestia.'
  },
  {
    icon: 'icon flaticon-camera',
    title: 'Security Priority',
    description: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt iure qui natus perspiciatis ex odio molestia.'
  }
]
</script>
