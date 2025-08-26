<template>
  <div class="site-section">
    
    <div class="container">
      <div class="row mb-5 justify-content-center">
        <div class="col-md-7">
          <div class="site-section-title text-center">
            <h2>Réinventer la recherche de logement avec LaCasa</h2>
            <p>
              <strong><h5>
                Solution digitale pour un marché immobilier efficace, Cependant avant de saisir pleinement la valeur ajoutée de LaCasa, 
                il est essentiel de comprendre les réalités actuelles du marché immobilier au Togo et les limites des méthodes
                traditionnelles de recherche de logement.
                </h5>
              </strong>
            </p>
          </div>
        </div>
      </div>

      <div class="row">
        <div
          class="col-md-6 col-lg-4 mb-5 mb-lg-5"
          v-for="(agent, idx) in agents"
          :key="idx"
        >
          <div class="team-member">
            <div class="image-wrapper">
              <img :src="agent.image" :alt="agent.name" />
              <!-- <div class="social-links">
                <a v-for="(s, i) in agent.social" :key="i" :href="s.link" target="_blank">
                  <span :class="s.class"></span>
                </a>
              </div> -->
            </div>
            <div class="member-info">
              <h3 class="text-light"><strong>{{ agent.name }}</strong></h3>
              <p class="position text-light">{{ agent.position }}</p>
              <p class="description"><strong><h6>{{ agent.description }}</h6></strong></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
    import question from '@images/question.jpeg'
    import solution from '@images/solution.jpeg'
    import goal from '@images/goal.jpeg'
    import ParticleJs from '../../partial/Particles.vue'
    import person2 from '@images/person_2.jpg'
    import dixon from '@images/dixon.jpeg'
    import person3 from '@images/person_3.jpg'

    const agents = [
    {
        image: question,
        name: 'Problématique',
        // position: 'Real Estate Agent',
        description: 
        'Aujourd’hui, la recherche de logement au Togo reste manuelle : tableaux exposés en rue, informations vulnérables aux intempéries, nécessité de se déplacer physiquement et annonces souvent obsolètes. La situation limite la visibilité, ralentit le marché et complique la vie des clients et démarcheurs, rendant l’expérience frustrante et inefficace. LaCasa répond à ce besoin de modernisation et de fiabilité.',
        social: [
        { class: 'icon-facebook', link: '#' },
        { class: 'icon-twitter', link: '#' },
        { class: 'icon-linkedin', link: '#' }
        ]
    },
    {
        image: solution,
        name: 'Solution proposée',
        // position: 'Développeur web',
        description: "LaCasa propose la publication en ligne des annonces avec photos, localisation, type de logement, prix et disponibilité. Les clients peuvent filtrer rapidement selon leurs critères, liker et commenter les annonces. Les démarcheurs gèrent facilement leurs annonces, qui expirent automatiquement après un mois avec rappel par email. Cette solution garantit fiabilité, pertinence et une expérience numérique fluide.",
        social: [
        { class: 'icon-facebook', link: '#' },
        { class: 'icon-twitter', link: '#' },
        { class: 'icon-linkedin', link: '#' }
        ]
    },
    {
        image: goal,
        name: 'Objectifs',
        // position: 'Real Estate Agent',
        description: 'À court terme, LaCasa met en ligne sa version web avec un design intuitif et les fonctionnalités de base. À moyen terme, une application mobile sera disponible pour toucher plus d’utilisateurs. À long terme, le service s’étendra à d’autres villes et pays voisins, intégrera un module de paiement en ligne et un système de vérification des logements, assurant une solution complète et fiable pour tous.',
        social: [
        { class: 'icon-facebook', link: '#' },
        { class: 'icon-twitter', link: '#' },
        { class: 'icon-linkedin', link: '#' }
        ]
    }
    ]
</script>

<style scoped>
    .site-section {
        padding: 60px 0;
        background: #f9f9f9;
    }

    /* Title & paragraph */
    .site-section-title h2 {
        font-size: 2.2rem;
        font-weight: bold;
        color: #fff;
        padding: 15px 30px;
        border-radius: 12px;
        text-align: center;
        /* background: linear-gradient(135deg, #163603d2, rgba(0,0,0,0.9) 70%, #163603b5); */
        background: linear-gradient(135deg, #122f02, rgb(0, 0, 0) 70%, #0e2501d9);
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
        font-size: 1.05rem;
        color: #fff;
        display: inline-block;
        padding: 20px 30px;
        border-radius: 15px;
        background: linear-gradient(135deg, rgb(22, 52, 3), rgba(46, 111, 9, 0.793));
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(5px);
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        
    }

    .site-section-title p:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 12px 35px rgba(89,165,44,0.6);
    }

    /* Team member card */
    .team-member {
        position: relative;
        background: #001f04;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }

    .team-member::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        border-radius: 15px;
        background: linear-gradient(90deg, rgba(0,0,0,0.1), rgba(5, 115, 38, 0.973), rgba(0,0,0,0.1));
        animation: rotateBorder 4s linear infinite;
        z-index: 0;
    }

    @keyframes rotateBorder {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Pour que le contenu reste au-dessus */
    .team-member > * {
        position: relative;
        z-index: 1;
    }

    .team-member:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 0 20px rgba(89,165,44,0.6), 0 0 40px rgba(89,165,44,0.4),0 0 60px rgba(89,165,44,0.2);
    }

    /* Image wrapper */
    .image-wrapper {
        position: relative;
        overflow: hidden;
    }

    .image-wrapper img {
        width: 100%;
        border-bottom: 1px solid #eee;
        transition: transform 0.4s ease;
    }

    .team-member:hover img {
        transform: scale(1.05);
    }

    /* Social icons */
    .social-links {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 15px;
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .team-member:hover .social-links {
        opacity: 1;
    }

    .social-links a span {
        font-size: 18px;
        color: #59A52C;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    .social-links a span:hover {
        transform: scale(1.3);
        color: #4b8d27;
        text-shadow: 0 0 8px #59A52C, 0 0 15px #59A52C;
    }

    /* Member info */
    .member-info {
        padding: 20px;
        text-align: center;
    }

    .member-info h3 {
        font-size: 1.4rem;
        margin-bottom: 5px;
        color: #333;
    }

    .member-info .position {
        color: #59A52C;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .member-info .description {
        font-size: 0.95rem;
        color: #555;
    }

    .member-info .description {
        font-size: 0.95rem;
        color: #fff;
        background: linear-gradient(135deg, rgb(22, 52, 3), rgba(46, 111, 9, 0.793));
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        text-align: center;
        position: relative;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* effet hover sur la description */
    .team-member:hover .description {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 30px rgba(89,165,44,0.6);
    }
</style>
