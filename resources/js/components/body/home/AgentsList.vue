<template>
  <div class="site-section">
    <div class="container">
      <div class="row mb-5 justify-content-center">
        <div class="col-md-7">
          <div class="site-section-title text-center">
            <h2>L’Équipe LaCasa</h2>
            <p>
              <strong>
                Découvrez les talents et professionnels qui ont travaillé avec passion
                pour créer LaCasa et rendre la recherche de logements plus simple et sécurisée.
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
              <div class="social-links">
                <a v-for="(s, i) in agent.social" :key="i" :href="s.link" target="_blank">
                  <span :class="s.class"></span>
                </a>
              </div>
            </div>
            <div class="member-info">
              <h3>{{ agent.name }}</h3>
              <p class="position">{{ agent.position }}</p>
              <p class="description"><strong>{{ agent.description }}</strong></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import person1 from '@images/person_1.jpg'
import person2 from '@images/person_2.jpg'
import person3 from '@images/person_3.jpg'

const agents = [
  {
    image: person1,
    name: 'Megan Smith',
    position: 'Real Estate Agent',
    description: 'Professional agent with years of experience helping clients find perfect homes.',
    social: [
      { class: 'icon-facebook', link: '#' },
      { class: 'icon-twitter', link: '#' },
      { class: 'icon-linkedin', link: '#' }
    ]
  },
  {
    image: person2,
    name: 'Brooke Cagle',
    position: 'Real Estate Agent',
    description: 'Dedicated to providing top-notch real estate services with transparency.',
    social: [
      { class: 'icon-facebook', link: '#' },
      { class: 'icon-twitter', link: '#' },
      { class: 'icon-linkedin', link: '#' }
    ]
  },
  {
    image: person3,
    name: 'Philip Martin',
    position: 'Real Estate Agent',
    description: 'Expert in local markets, ensuring safe and fast property transactions.',
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
  font-size: 1.05rem;
  color: #fff;
  display: inline-block;
  padding: 20px 30px;
  border-radius: 15px;
  background: linear-gradient(135deg, rgba(47, 89, 23, 0.96), rgba(46, 112, 8, 0.652));
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
  background: #fff;
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
  background: linear-gradient(90deg, rgba(0,0,0,0.1), rgba(0,0,0,0.3), rgba(0,0,0,0.1));
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
  box-shadow: 0 0 20px rgba(89,165,44,0.6),
              0 0 40px rgba(89,165,44,0.4),
              0 0 60px rgba(89,165,44,0.2);
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
  background: linear-gradient(135deg, rgba(49, 89, 27, 0.975), rgba(55, 126, 14, 0.737));
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
