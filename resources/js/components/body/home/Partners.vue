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
        ref="marqueeWrapper"
        @mouseenter="isHovered = true"
        @mouseleave="isHovered = false"
      >
        <div
          class="marquee-content"
          :class="{ scrolling: canScroll }"
          :style="{ animationPlayState: isHovered ? 'paused' : 'running' }"
          ref="marqueeContent"
        >
          <div class="marquee-item" v-for="(p, i) in partnersToShow" :key="i">
            <img :src="p.logo" :alt="p.nom" class="partner-logo" />
            <p class="partner-name">{{ p.nom }}</p>
            <div class="social-links">
              <a v-if="p.tiktok" :href="p.tiktok" target="_blank" rel="noopener">
                <i class="fab fa-tiktok"></i>
              </a>
              <a v-if="p.facebook" :href="p.facebook" target="_blank" rel="noopener">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a v-if="p.whatsappLink" :href="p.whatsappLink" target="_blank" rel="noopener">
                <i class="fab fa-whatsapp"></i>
              </a>
              <a v-else @click.prevent="openWhatsappModal(p.whatsappNumber)" href="#">
                <i class="fab fa-whatsapp"></i>
              </a>

            </div>
          </div>
        </div>
      </div>
      <!-- fin marquee-wrapper -->
    </div>
  </div>

  <div class="modal fade" id="whatsappModal" tabindex="-1" ref="whatsappModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Numéro WhatsApp</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p class="mb-0">{{ currentWhatsappNumber }}</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

</template>

<script setup>
import { ref, computed, onMounted, nextTick } from "vue"
import axios from "axios"
import { Modal } from "bootstrap"  // ajouter l'import bootstrap Modal

const whatsappModal = ref(null)
const currentWhatsappNumber = ref("")
import logo1 from "@images2/property-1.jpg"
import logo2 from "@images2/property-2.jpg"
import logo3 from "@images2/property-3.jpg"
import logo4 from "@images2/property-4.jpg"
import logo5 from "@images2/property-5.jpg"

// Hover et scroll
const isHovered = ref(false)
const marqueeWrapper = ref(null)
const marqueeContent = ref(null)
const canScroll = ref(false)

// Liste par défaut si aucun partenaire certifié trouvé
const defaultPartners = [
  { nom: "ImmoTogo", logo: logo1, facebook: "#", whatsapp: "#", tiktok: "#" },
  { nom: "Kasa Lomé", logo: logo2, facebook: "#", whatsapp: "#", tiktok: "#" },
  { nom: "AfriHome", logo: logo3, facebook: "#", whatsapp: "#", tiktok: "#" },
  { nom: "CityHouse", logo: logo4, facebook: "#", whatsapp: "#", tiktok: "#" },
  { nom: "TogoRealty", logo: logo5, facebook: "#", whatsapp: "#", tiktok: "#" }
]

// Liste des utilisateurs certifiés récupérés depuis API
const certifiedUsers = ref([])

// Charger les partenaires certifiés
onMounted(async () => {
  try {
    const res = await axios.get("/api/users?is_verified=1&user_type=2")
    certifiedUsers.value = res.data.data || []
  } catch (e) {
    console.error("Erreur chargement partenaires certifiés :", e)
  }

  await nextTick()
  // Vérifier si le contenu dépasse la largeur du wrapper pour activer le scroll
  if (marqueeContent.value && marqueeWrapper.value) {
    canScroll.value = marqueeContent.value.scrollWidth > marqueeWrapper.value.clientWidth
  }
  whatsappModal.value = new Modal(document.getElementById("whatsappModal"))
})

// Choisir entre utilisateurs certifiés ou liste par défaut
const partnersToShow = computed(() => {
  return certifiedUsers.value.length > 0
    ? certifiedUsers.value.map(u => ({
        nom: u.name,
        logo: u.logo ?? logo1,
        facebook: u.facebook_link,
        whatsapp: u.whatsapp_link,
        tiktok: u.tiktok_link
      }))
    : defaultPartners
})

function openWhatsappModal(number) {
  currentWhatsappNumber.value = number
  whatsappModal.value.show()
}

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
  background: rgba(255,255,255,0.3);
  transition: background 0.45s ease;
  box-shadow: 0 8px 20px rgba(0,0,0,0.6);
}

.marquee-wrapper:hover {
  background: linear-gradient(135deg, #00b98e, #007f6c);
}

.marquee-content {
  display: inline-flex;
  gap: 20px;
  align-items: center;
}

/* Animation uniquement si canScroll = true */
.marquee-content.scrolling {
  animation: marquee-scroll 15s linear infinite;
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
.marquee-wrapper:hover .marquee-content {
  animation-play-state: paused;
}

@keyframes marquee-scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-100%); }
}

/* responsive */
@media (max-width: 992px) {
  .partner-logo { width: 90px; height: 90px; }
  .marquee-item { min-width: 160px; max-width: 180px; }
}

@media (max-width: 576px) {
  .partner-logo { width: 70px; height: 70px; }
  .partner-name { font-size: 0.9rem; }
  .marquee-content.scrolling { animation-duration: 10s; }
}
</style>
