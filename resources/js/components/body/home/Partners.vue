<template>
  <div class="container-xxl py-5 partenaires-section mt-5 mb-5">
    <div class="container text-center">
      <div class="mb-5">
        <h1 class="fw-bold mb-3 text-gradient">Nos Partenaires</h1>
        <h6 class="text-dark">
          <strong>
            Découvrez les agences immobilières et partenaires de confiance
            qui collaborent avec <span class="text-primary">LaCasa</span>.
          </strong>
        </h6>
      </div>

      <!-- Wrapper pour hover -->
      <div
        class="marquee-wrapper"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
      >
        <div class="marquee" :class="{ 'marquee-hover': isHovered }">
          <!-- contenu doublé pour continuité -->
          <div class="marquee-content" :style="{ animationPlayState: isHovered ? 'paused' : 'running' }">
            <div class="marquee-group" v-for="(block, bIndex) in 2" :key="bIndex">
              <div class="marquee-item" v-for="(p, i) in partenaires" :key="bIndex+'-'+i">
                <img :src="p.logo" :alt="p.nom" class="partner-logo" />
                <p class="partner-name">{{ p.nom }}</p>
                <div class="social-links">
                  <a v-if="p.facebook" :href="p.facebook" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                  <a v-if="p.instagram" :href="p.instagram" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                  <a v-if="p.tiktok" :href="p.tiktok" target="_blank" rel="noopener">
                    <!-- SVG TikTok -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="14" height="14" fill="currentColor">
                      <path d="M448,209.2c-16.3,7.3-33.9,12.1-52.4,14.2v91.8c0,62.7-50.8,113.5-113.5,113.5-36.2,0-68.5-16.5-90.5-42.5
                        c-8.1-9.6-14.8-20.7-19.9-32.9c-1.8-4.3-3.4-8.7-4.8-13.1c-0.1-0.3-0.2-0.5-0.2-0.8c-0.1-0.4-0.2-0.7-0.3-1c-0.1-0.4-0.1-0.7-0.2-1.1
                        c-0.1-0.4-0.1-0.8-0.2-1.2v-206.4h48.3v136.6c22.2,9.7,47.2,14.2,72.7,12.8v-69.4h-0.1c-0.2-0.1-0.4-0.1-0.5-0.2c-0.2,0-0.3-0.1-0.5-0.1
                        c-0.1,0-0.2,0-0.3-0.1c-0.1,0-0.2,0-0.3-0.1h-0.1c-1.2,0-2.5-0.1-3.7-0.1c-21.1-0.7-41.5-6.6-60.1-17.5c-23.7-13.6-44.4-33.6-60.3-58.1
                        c-8.4-12.8-15.1-26.7-19.9-41.4c-1.9-5.6-3.4-11.3-4.8-17c-0.1-0.3-0.2-0.7-0.2-1c-0.1-0.4-0.2-0.7-0.3-1.1c-0.1-0.4-0.1-0.8-0.2-1.2
                        V0h48.3v137.6c22.2,9.7,47.2,14.2,72.7,12.8v-69.4c-27.7,0-53.7-10.9-73.3-30.5C149.6,13.6,138.7-12.5,138.7-40.2h48.3
                        c22.2,9.7,47.2,14.2,72.7,12.8v-69.4h48.3v277.2h-0.1C362,243.1,405.7,219.3,448,209.2z"/>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- fin marquee-wrapper -->
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue"
import logo1 from "@images2/property-1.jpg"
import logo2 from "@images2/property-2.jpg"
import logo3 from "@images2/property-3.jpg"
import logo4 from "@images2/property-4.jpg"
import logo5 from "@images2/property-5.jpg"

const isHovered = ref(false)

const partenaires = [
  { nom: "ImmoTogo", logo: logo1, facebook: "#", instagram: "#", tiktok: "#" },
  { nom: "Kasa Lomé", logo: logo2, facebook: "#", instagram: "#", tiktok: "#" },
  { nom: "AfriHome", logo: logo3, facebook: "#", instagram: "#", tiktok: "#" },
  { nom: "CityHouse", logo: logo4, facebook: "#", instagram: "#", tiktok: "#" },
  { nom: "TogoRealty", logo: logo5, facebook: "#", instagram: "#", tiktok: "#" }
]
</script>

<style scoped>
.partenaires-section {
  background: rgba(255,255,255,0.7);
  border-radius: 20px;
  padding: 48px 0;
  transition: background 0.45s ease, box-shadow 0.45s ease;
}

.text-gradient {
  background: linear-gradient(90deg, #00b98e, #007f6c);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

/* wrapper hover */
.marquee-wrapper {
  margin-top: 10px;
  border-radius: 14px;
  padding: 18px;
  position: relative;
  overflow: hidden;
  z-index: 1;
  background: rgba(255,255,255,0.3); /* keep white look inside */
  transition: background 0.45s ease;
  box-shadow: 0 8px 20px rgba(0,0,0,0.6);
}

/* hover wrapper */
.marquee-wrapper:hover {
  background: linear-gradient(135deg, #00b98e, #007f6c);
}

/* marquee */
.marquee {
  overflow: hidden;
  white-space: nowrap;
}

.marquee-content {
  display: flex;
  gap: 20px;
  align-items: center;
  animation: marquee-scroll 15s linear infinite;
}

.marquee-group {
  display: flex;
  gap: 40px;
}

.marquee-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 180px;
  max-width: 220px;
  transition: transform 0.25s ease;
  box-shadow: 0 8px 20px rgba(0,0,0,0.5);
}

.marquee-item:hover {
  transform: scale(1.05);
}

.partner-logo {
  width: 110px;
  height: 110px;
  object-fit: cover;
  border-radius: 50%;
  border: 3px solid #e9f7f1;
  transition: transform 0.3s ease, border-color 0.3s ease;
}

.partner-name {
  margin-top: 10px;
  font-weight: 600;
  color: #007f6c;
  transition: color 0.35s ease;
}

.social-links {
  margin-top: 8px;
  display: flex;
  gap: 8px;
}

.social-links a {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: rgba(0,185,142,0.08);
  color: #007f6c;
  font-size: 14px;
  transition: background 0.25s ease, color 0.25s ease, transform 0.25s ease;
}

.social-links a:hover {
  transform: scale(1.08);
  background: rgba(255,255,255,0.18);
}

/* texte et logos blanc au hover global */
.marquee-wrapper:hover .partner-name,
.marquee-wrapper:hover .social-links a {
  color: #fff !important;
}

.marquee-wrapper:hover .partner-logo {
  border-color: rgba(255,255,255,0.35);
}

.marquee-wrapper:hover .social-links a {
  background: rgba(255,255,255,0.12);
}

/* pause au hover */
.marquee:hover .marquee-content,
.marquee-wrapper:hover .marquee-content {
  animation-play-state: paused;
}

/* keyframes défilement continu */
@keyframes marquee-scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); } /* contenu dupliqué */
}

/* responsive */
@media (max-width: 992px) {
  .partner-logo { width: 90px; height: 90px; }
  .marquee-item { min-width: 160px; max-width: 180px; }
  .marquee-content { gap: 20px; }
}

@media (max-width: 576px) {
  .partner-logo { width: 70px; height: 70px; }
  .partner-name { font-size: 0.9rem; }
  .marquee-content { gap: 15px; animation-duration: 5s; }
}
</style>
