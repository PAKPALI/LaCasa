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
      <div class="marquee-wrapper" ref="marqueeWrapper" @mouseenter="isHovered = true" @mouseleave="isHovered = false">
        <div class="marquee-content" :class="{ scrolling: canScroll }" :style="{ animationPlayState: isHovered ? 'paused' : 'running' }" ref="marqueeContent">
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

      <button class="btn btn-dark mt-4 fw-bold px-4" data-bs-toggle="modal" data-bs-target="#partnerInfoModal">
        Comment devenir partenaire ?
      </button>
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

  <div class="modal fade mt-5" id="partnerInfoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        
        <div class="modal-header bg-dark text-light">
          <h5 class="modal-title fw-bold text-light">Devenir partenaire LaCasa</h5>
          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <!-- SECTION : Comment devenir partenaire -->
          <h5 class="fw-bold mb-0 text-success">Comment devenir partenaire ?</h5>
          <p class="text-dark">
            Pour devenir partenaire, vous devez obligatoirement être une 
            <strong>agence immobilière professionnelle</strong>.  
            Les étapes à suivre :
          </p>
          <ul class="text-dark">
            <li>Créer un compte sur la plateforme <strong>LaCasa</strong></li>
            <li>Vous connecter à votre espace personnel</li>
            <li>Accéder à votre profil et <strong>mettre à jour la section “Réseaux sociaux”</strong></li>
          </ul>

          <hr />

          <!-- SECTION : La certification est-elle obligatoire ? -->
          <h5 class="fw-bold mb-0 text-success">La certification est-elle obligatoire ?</h5>
          <p class="text-dark">
            Non, ce n’est pas obligatoire. L’agence peut créer son compte gratuitement 
            et publier des annonces sans certification pour le moment.
          </p>

          <hr />

          <!-- SECTION : Alors pourquoi se certifier ? -->
          <h5 class="fw-bold mb-0 text-success">Alors pourquoi se certifier ?</h5>
          <p class="text-dark">
            La certification établit un lien officiel de partenariat avec <strong>LaCasa</strong>.  
            Elle permet :
          </p>
          <ul class="text-dark">
            <li>De montrer aux clients potentiels que vos publications sont fiables et que votre agence est crédible</li>
            <li>À LaCasa de promouvoir votre agence en mettant en avant les liens mis à jour lors de la certification dans la section “Partenaires”</li>
            <li>De bénéficier d’une visibilité auprès de nombreux utilisateurs qui consultent régulièrement la plateforme pour en savoir plus sur les agences</li>
          </ul>

          <hr />

          <!-- SECTION : Comment se certifier -->
          <h5 class="fw-bold mb-0 text-success">Comment se certifier ?</h5>
          <p class="text-dark">
            Après la mise à jour de vos réseaux sociaux, notre équipe procède à la vérification 
            des informations fournies et s'assure du payement des frais  <strong class ="text-success">(3000 FCFA)</strong> de certification.
          </p>
          <ul class="text-dark">
            <li>Vérification de la <strong>validité des liens</strong> que vous avez insérés</li>
            <li>Contrôle que vous possédez une <strong>photo de profil claire et professionnelle</strong></li>
            <li>Analyse de la cohérence entre les informations de votre compte et votre activité réelle</li>
          </ul>

          <p class="text-dark">
            Une fois la vérification terminée :
          </p>
          <ul class="text-dark">
            <li>Vous recevez une <strong>notification Email + SMS</strong> indiquant le statut</li>
            <li>Si votre certification est approuvée, votre agence apparaît automatiquement dans la 
              <strong>liste officielle des partenaires</strong>
            </li>
          </ul>

        </div>

        <div class="modal-footer bg-dark">
          <!-- <button class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button> -->
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

function getPartnerLogo(user) {
  // Si le partenaire a une image de profil, on l'utilise
  if (user.profile_image && user.profile_image.length > 0) {
    return `/${user.profile_image}`
  }
  // Sinon, on prend le logo par défaut (ici logo1)
  return logo1
}


// Choisir entre utilisateurs certifiés ou liste par défaut
  const partnersToShow = computed(() => {
    return certifiedUsers.value.length > 0
      ? certifiedUsers.value.map(u => ({
          nom: u.name,
          logo: getPartnerLogo(u),  // <-- ici le fallback appliqué
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
