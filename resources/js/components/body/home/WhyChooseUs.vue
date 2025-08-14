<template>
  <div class="site-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-7 text-center">
          <div class="site-section-title">
            <h2>Pourquoi Choisir LaCasa ?</h2>
          </div>
          <p>
            <strong>
              LaCasa révolutionne la recherche de logements au Togo. 
              Plus besoin de courir derrière des tableaux physiques exposés en plein air : 
              trouvez votre maison idéale rapidement, en toute sécurité, depuis votre téléphone ou ordinateur.
            </strong>
          </p>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col-md-6 col-lg-4" v-for="(service, i) in services" :key="i">
          <div class="service text-center">
            <span :class="service.icon"></span>
            <h2 class="service-heading">{{ service.title }}</h2>
            <p><strong>{{ service.description }}</strong></p>
            <!-- <a href="#">
              <p><span class="read-more">En savoir plus</span></p>
            </a> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  const services = [
    {
      icon: 'icon flaticon-location',
      title: 'Recherche ciblée',
      description: 'Filtrez les annonces par ville, quartier, type de logement et budget pour trouver rapidement ce qui vous convient.'
    },
    {
      icon: 'icon flaticon-house',
      title: 'Annonces fiables',
      description: 'Les annonces sont actualisées régulièrement avec expiration automatique pour éviter les logements loués.'
    },
    {
      icon: 'icon flaticon-camera',
      title: 'Sécurité et confiance',
      description: 'Profitez d’une plateforme sécurisée, adaptée aux réalités locales, pour des informations transparentes et sans stress.'
    }
  ]
</script>

<style scoped>
  .site-section {
    padding: 60px 0;
    background: #f9f9f9;
  }

  .site-section-title h2 {
    font-size: 2.2rem;
    font-weight: bold;
    color: #fff;
    padding: 15px 30px;
    border-radius: 12px;
    text-align: center;
    background: linear-gradient(135deg, #163603d2, rgba(0,0,0,0.9) 70%, #163603b5);
    box-shadow: 0 6px 20px rgba(0,0,0,0.4), 0 0 15px rgba(89,165,44,0.3);
    display: inline-block;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  /* Effet au survol */
  .site-section-title h2:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 10px 25px rgba(0,0,0,0.5), 0 0 20px rgba(89,165,44,0.4);
  }

  .site-section-title p {
    color: #555;
    font-size: 1.05rem;
  }

  .service {
    background: #fff;
    border-radius: 15px;
    padding: 35px 25px;
    margin-bottom: 30px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
  }

  .service span.icon {
    font-size: 45px;
    display: inline-block;
    margin-bottom: 20px;
    color: #050303;
    transition: transform 0.4s ease, color 0.4s ease;
  }

  .service:hover span.icon {
    transform: rotate(10deg) scale(1.2);
    color: #173f0130;
  }

  .service-heading {
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 15px;
  }

  /* Effet au survol */
  .site-section-title h2:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 10px 25px rgba(0,0,0,0.5), 0 0 20px rgba(89,165,44,0.4);
  }

  .site-section p {
    color: #555;
    font-size: 1.05rem;
  }

  .service {
    background: #fff;
    border-radius: 15px;
    padding: 35px 25px;
    margin-bottom: 30px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.4s ease;
    position: relative;
    overflow: hidden;
  }

  .service::after {
    content: "";
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    border-radius: 15px;
    background: linear-gradient(90deg, rgba(0,0,0,0.1), rgba(0,0,0,0.3), rgba(0,0,0,0.1));
    animation: rotateBorder 4s linear infinite;
    z-index: 0;
  }

  /* Animation rotation */
  @keyframes rotateBorder {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  /* Faire passer le contenu au-dessus */
  .service > * {
    position: relative;
    z-index: 1;
  }

  .service span.icon {
    font-size: 45px;
    display: inline-block;
    margin-bottom: 20px;
    color: #050303;
    transition: transform 0.4s ease, color 0.4s ease;
  }

  .service:hover span.icon {
    transform: rotate(10deg) scale(1.2);
    color: #173f0130;
  }

  .service-heading {
    font-size: 1.4rem;
    font-weight: 600;
    margin-bottom: 15px;
  }

  .read-more {
    font-weight: 600;
    color: #59A52C;
    transition: color 0.3s ease;
  }

  /* Effet hover futuriste */
  .service::before {
    content: "";
    position: absolute;
    top: -100%;
    left: -100%;
    width: 200%;
    height: 200%;
    background: linear-gradient(135deg, rgba(89, 165, 44, 0.1), rgba(89, 165, 44, 0.3));
    transform: rotate(45deg);
    transition: all 0.6s ease;
  }

  .service:hover::before {
    top: 0;
    left: 0;
  }

  .service:hover {
    transform: translateY(-10px) scale(1.03);
    box-shadow: 0 0 20px rgba(89, 165, 44, 0.6),
                0 0 40px rgba(89, 165, 44, 0.4),
                0 0 60px rgba(89, 165, 44, 0.2); /* Glow néon */
  }

  .service:hover span.icon {
    transform: rotate(10deg) scale(1.2);
    color: #173f0130;
  }

  .service:hover .read-more {
    color: #4b8d27;
  }

  .site-section p {
    position: relative;
    display: inline-block;
    padding: 25px 30px;
    font-size: 1.05rem;
    color: #fff;
    background: linear-gradient(135deg, rgb(51, 96, 25), rgba(56, 131, 12, 0.793));
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    backdrop-filter: blur(5px);
  }

/* effet au survol */
.site-section p:hover {
  transform: translateY(-5px) scale(1.02);
  box-shadow: 0 12px 35px rgba(246, 248, 244, 0.917);
}

</style>
