<template>
  <div class="container-fluid header bg-white p-0">
    <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
      <div class="col-md-6 p-5 mt-lg-5">
        <h1 class="display-5 animated fadeIn mb-4">
          Trouvez <span class="text-primary">la maison idéale</span> pour vous et votre famille
        </h1>
        <p class="animated fadeIn mb-4 pb-2">
          LaCasa simplifie la recherche de logements au Togo. Consultez des annonces fiables, mises à jour et adaptées à vos besoins.
        </p>
        <a href="#" class="btn btn-primary py-3 px-5 me-3 animated fadeIn">Commencer</a>
      </div>
     <div class="col-md-6 animated fadeIn">
        <div class="owl-carousel header-carousel">
            <div class="owl-carousel-item">
              <img class="img-fluid" :src="carousel1" alt="">
            </div>
            <div class="owl-carousel-item">
              <img class="img-fluid" :src="carousel2" alt="">
            </div>
        </div>
    </div>
    </div>
  </div>
</template>


<script setup>
  import heroBg1 from '@images/hero_bg_1.jpg'
  import heroBg2 from '@images/hero_bg_2.jpg'
  import carousel1 from '@images2/carousel-1.jpg'
  import carousel2 from '@images2/carousel-2.jpg'
</script>
