<template>
  <div class="site-section bg-light">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-md-7 text-center">
          <div class="site-section-title">
            <h2>Recent Blog</h2>
          </div>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis maiores quisquam saepe architecto error corporis aliquam...</p>
        </div>
      </div>
      <div class="row">
        <div
          class="col-md-6 col-lg-4 mb-5"
          v-for="(post, idx) in posts"
          :key="idx"
          :data-aos="'fade-up'"
          :data-aos-delay="post.delay"
        >
          <a href="#"><img :src="post.img" alt="Image" class="img-fluid" /></a>
          <div class="p-4 bg-white">
            <span class="d-block text-secondary small text-uppercase">{{ post.date }}</span>
            <h2 class="h5 text-black mb-3"><a href="#">{{ post.title }}</a></h2>
            <p>{{ post.summary }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import img2 from '@images/img_2.jpg'
import img3 from '@images/img_3.jpg'
import img4 from '@images/img_4.jpg'

const posts = [
  {
    img: img4,
    date: 'Jan 20th, 2019',
    title: 'Art Gossip by Mike Charles',
    summary: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem veniam quae sunt.',
    delay: 100
  },
  {
    img: img2,
    date: 'Jan 20th, 2019',
    title: 'Art Gossip by Mike Charles',
    summary: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem veniam quae sunt.',
    delay: 200
  },
  {
    img: img3,
    date: 'Jan 20th, 2019',
    title: 'Art Gossip by Mike Charles',
    summary: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias enim, ipsa exercitationem veniam quae sunt.',
    delay: 300
  }
]
</script>
