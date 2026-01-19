<template>
  <!-- Team Start -->
  <div class="container-xxl py-5 team-section mb-5 border border-1 border-primary">
    <div class="container">
      <!-- Titre -->
      <div class="text-center mx-auto mb-3" style="max-width: 600px;">
        <h1 class="mb-3 fw-bold text-gradient">L’Équipe LaCasa</h1>
        <h6 class="text-dark">
          <strong>
            Découvrez les talents et professionnels qui ont travaillé avec passion
            pour créer LaCasa et rendre la recherche de logements plus simple et sécurisée.
          </strong>
        </h6>
      </div>

      <!-- Membres -->
      <div class="row g-4">
        <div
          v-for="(m, i) in members"
          :key="i"
          class="col-lg-6 col-md-6"
        >
          <div class="team-item position-relative overflow-hidden rounded-4 shadow-lg">
            <!-- Image -->
            <div class="team-img position-relative overflow-hidden">
              <img class="img-fluid w-100" :src="m.img" :alt="m.name" />
              <div class="overlay d-flex align-items-center justify-content-center">
                <div class="social-links d-flex">
                  <!-- <a class="btn btn-square mx-2" href="#"><i class="fab fa-facebook-f"></i></a>
                  <a class="btn btn-square mx-2" href="#"><i class="fab fa-twitter"></i></a>
                  <a class="btn btn-square mx-2" href="#"><i class="fab fa-instagram"></i></a> -->
                  <a class="btn btn-square mx-2" href="#"><i class="fab fa-whatsapp"></i></a>
                  <a class="btn btn-square mx-2" href="#"><i class="fab fa-linkedin"></i></a>
                </div>
              </div>
            </div>

            <!-- Texte -->
            <div class="text-center p-4 bg-white member-info">
              <h5 class="fw-bold mb-1">{{ m.name }}</h5>
              <small class="text-muted">{{ m.role }}</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Team End -->
</template>

<script setup>
import icon1 from '@images/ana2.avif'
import icon2 from '@images/web.avif'
import icon3 from '@images/agent.avif'

const members = [
  { name: 'KONDI Arafat', role: 'Analyste technicien/QA/Devops', img: icon1 },
  { name: 'PAKPALI Essolissam Didier (CEO)', role: 'Développeur web', img: icon2 },
  // { name: 'ATTIGANE Samuel', role: 'Agent immobilier', img: icon3 },
]
</script>

<style scoped>
  /* SECTION STYLE */
  .team-section {
    background: rgba(255, 255, 255, 0.5);
    border-radius: 20px;
    backdrop-filter: blur(1px);
    transition: all 0.6s ease;
  }

  .text-gradient {
    background: linear-gradient(90deg, #00b98e, #007f6c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  /* TEAM CARD */
  .team-item {
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(0, 185, 142, 0.2);
    transition: all 0.5s ease;
    border-radius: 15px;
  }

  .team-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 12px 25px rgba(0, 185, 142, 0.25);
  }

  /* IMAGE + OVERLAY */
  .team-img {
    position: relative;
    border-bottom: 4px solid #00b98e;
  }

  .team-img img {
    transition: transform 0.5s ease;
  }

  .team-item:hover img {
    transform: scale(1.1);
  }

  /* OVERLAY SOCIALE */
  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 185, 142, 0.6);
    opacity: 0;
    transition: all 0.4s ease;
  }

  .team-item:hover .overlay {
    opacity: 1;
  }

  /* LIENS SOCIAUX */
  .social-links a {
    background: white;
    color: #00b98e;
    border-radius: 50%;
    transition: all 0.3s ease;
    animation: float 3s ease-in-out infinite;
  }

  .social-links a:hover {
    background: #00b98e;
    color: white;
    transform: scale(1.2);
  }

  /* FLOAT ICON ANIMATION */
  @keyframes float {
    0% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
    100% { transform: translateY(0); }
  }

  /* TEXTE */
  .member-info {
    transition: all 0.4s ease;
  }

  .member-info h5,
  .member-info small {
    color: #333;
    transition: color 0.4s ease;
  }

  /* ✅ Texte devient blanc quand on survole */
  .team-item:hover .member-info {
    background: linear-gradient(90deg, #00b98e, #007f6c);
  }

  .team-item:hover .member-info h5,
  .team-item:hover .member-info small {
    color: #fff !important;
  }

  /* RESPONSIVE */
  @media (max-width: 768px) {
    .team-item {
      margin-bottom: 20px;
    }
  }

</style>
