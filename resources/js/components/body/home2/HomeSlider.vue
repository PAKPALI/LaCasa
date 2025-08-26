<!-- components/HomeSlider.vue -->
<template>
  <!-- <div class="slide-one-item home-slider owl-carousel">

    <div
      class="site-blocks-cover overlay"
      :style="{ backgroundImage: `url(${heroBg1})` }"
      data-aos="fade"
      data-stellar-background-ratio="0.5"
    >
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
            <span class="d-inline-block bg-success text-white px-3 mb-3 property-offer-type rounded">A LOUER</span>
            <h1 class="mb-2">Villa de lux</h1>
            <p class="mb-5"><strong class="h2 text-success font-weight-bold">50.000.000 FCFA</strong></p>
            <p><a href="#" class="btn btn-white btn-outline-white py-3 px-5 rounded-0 btn-2">Detail</a></p>
          </div>
        </div>
      </div>
    </div>

    <div
      class="site-blocks-cover overlay"
      :style="{ backgroundImage: `url(${heroBg2})` }"
      data-aos="fade"
      data-stellar-background-ratio="0.5"
    >
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
            <span class="d-inline-block bg-danger text-white px-3 mb-3 property-offer-type rounded">A vendre</span>
            <h1 class="mb-2">Maison</h1>
            <p class="mb-5"><strong class="h2 text-success font-weight-bold">10.000.000 FCFA</strong></p>
            <p><a href="#" class="btn btn-white btn-outline-white py-3 px-5 rounded-0 btn-2">détail</a></p>
          </div>
        </div>
      </div>
    </div>

  </div> -->

  <!-- Header Start -->
        <div class="container-fluid header bg-white p-0">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-5 mt-lg-5">
                    <h1 class="display-5 animated fadeIn mb-4">Find A <span class="text-primary">Perfect Home</span> To Live With Your Family</h1>
                    <p class="animated fadeIn mb-4 pb-2">Vero elitr justo clita lorem. Ipsum dolor at sed stet
                        sit diam no. Kasd rebum ipsum et diam justo clita et kasd rebum sea elitr.</p>
                    <a href="" class="btn btn-primary py-3 px-5 me-3 animated fadeIn">Get Started</a>
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
        <!-- Header End -->
</template>

<script setup>
  import heroBg1 from '@images/hero_bg_1.jpg'
  import heroBg2 from '@images/hero_bg_2.jpg'
  import carousel1 from '@images2/carousel-1.jpg'
  import carousel2 from '@images2/carousel-2.jpg'
</script>
