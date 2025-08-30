<template>
  <!-- Team Start -->
  <div class="container-xxl py-5">
    <div class="container">
      <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
        <h1 class="mb-3">L’Équipe LaCasa</h1>
        <p>
          Découvrez les talents et professionnels qui ont travaillé avec passion pour créer LaCasa et rendre la recherche de logements plus simple et sécurisée.
        </p>
      </div>

      <div class="row g-4">
        <div
          v-for="(m, i) in members"
          :key="i"
          class="col-lg-4 col-md-4 wow fadeInUp"
          :data-wow-delay="m.delay"
        >
          <div class="team-item rounded overflow-hidden">
            <div class="position-relative">
              <img class="img-fluid" :src="m.img" :alt="m.name">
              <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                <a class="btn btn-square mx-1" href="#"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-square mx-1" href="#"><i class="fab fa-twitter"></i></a>
                <a class="btn btn-square mx-1" href="#"><i class="fab fa-instagram"></i></a>
              </div>
            </div>
            <div class="text-center p-4 mt-3">
              <h5 class="fw-bold mb-0">{{ m.name }}</h5>
              <small>{{ m.role }}</small>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div><!-- Team End -->
</template>

<script setup>
// Import des images comme dans le premier template
import team1 from '@images2/team-1.jpg'
import team2 from '@images2/team-2.jpg'
import team3 from '@images2/team-3.jpg'
import icon1 from '@images/icon1.jpg'
// import team4 from '@images2/team-4.jpg'

const members = [
  { name: 'KONDJI Arafat', role: 'Analyste technicien', img: icon1, delay: '0.1s' },
  { name: 'PAKPALI Essolissam didier', role: 'Développeur web', img: icon1, delay: '0.3s' },
  { name: 'ATTIGANE samuel', role: 'Agent immobilier', img: icon1, delay: '0.5s' },
  // { name: 'Emily Davis', role: 'Agent immobilier', img: team4, delay: '0.7s' }
]
</script>
