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
          <div class="marquee-item btn-shimmer" v-for="(p, i) in partnersToShow" :key="i">
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

      <button class="btn btn-dark mt-4 fw-bold px-4 btn-shimmer" data-bs-toggle="modal" data-bs-target="#partnerInfoModal">
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
        <!-- Header -->
        <div class="modal-header bg-dark text-light">
          <h5 class="modal-title fw-bold text-light">Devenir partenaire LaCasa</h5>
          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
        </div>

        <!-- body -->
        <div class="modal-body">

          <!-- SECTION : Comment devenir partenaire -->
          <h5 class="fw-bold mb-0 text-success">Comment devenir partenaire ?</h5>
          <p class="text-dark">
            Pour devenir partenaire, vous devez obligatoirement être une 
            <strong>agence immobilière professionnelle</strong>.  
            Voici les étapes à suivre :
          </p>

          <ul class="text-dark">
            <li>Créer un compte sur la plateforme <strong>LaCasa</strong></li>
            <li>Vous connecter à votre espace personnel</li>
            <li>Accéder à votre profil et ouvrir la section 
              <strong>“Réseaux sociaux”</strong>
            </li>
            <li>Vous verrez un boutton <strong class="text-success">Payer Certification</strong>, cliquez simplement dessus et vous serez automatiquement redirigé vers la page sécurisée 
              <strong>KPrimePay</strong> afin d’effectuer le paiement de la certification
              <strong class="text-success">(3000 FCFA)</strong>
            </li>
          </ul>

          <p class="text-dark">
            La page de paiement vous permet de saisir votre numéro et de choisir votre mode de paiement 
            (<strong>Flooz, TMoney ou Mixx-by-Yas</strong>).  
            Une demande de confirmation sera envoyée sur votre téléphone : vous n’aurez qu’à valider avec votre mot de passe mobile.
          </p>

          <p class="text-dark">
            Une fois le paiement validé, vous serez automatiquement redirigé sur votre profil.  
            Vous verrez alors apparaître la possibilité de <strong>mettre à jour votre profil</strong> et de
            <strong>soumettre officiellement votre demande de certification</strong>.
          </p>

          <p class="text-dark">
            En revanche, si le paiement échoue ou est annulé, l’accès à la mise à jour de la section “Certification”
            ne sera pas disponible.
          </p>

          <hr />

          <!-- SECTION : La certification est-elle obligatoire ? -->
          <h5 class="fw-bold mb-0 text-success">La certification est-elle obligatoire ?</h5>
          <p class="text-dark">
            Non, ce n’est pas obligatoire. Vous pouvez créer votre compte gratuitement et publier des annonces même sans être certifié.
          </p>

          <hr />

          <!-- SECTION : Alors pourquoi se certifier ? -->
          <h5 class="fw-bold mb-0 text-success">Alors pourquoi se certifier ?</h5>
          <p class="text-dark">
            La certification établit un partenariat officiel avec <strong>LaCasa</strong>.  
            Elle permet :
          </p>

          <ul class="text-dark">
            <li>D’afficher une preuve de crédibilité et de sérieux auprès des clients</li>
            <li>De bénéficier d’une mise en avant dans la section <strong>“Partenaires”</strong></li>
            <li>D’obtenir une visibilité renforcée auprès des nombreux utilisateurs de la plateforme</li>
          </ul>

          <hr />

          <!-- SECTION : Y a-t-il un temps pour la certification ? -->
          <h5 class="fw-bold mb-0 text-success">Y a-t-il un temps pour la certification ?</h5>

          <p class="text-dark">
            Non, une fois la certification obtenue, elle est <strong>valable pour toujours</strong>.  
            Vous n’aurez plus jamais à repayer tant que vous respectez les règles de la plateforme.
          </p>

          <p class="text-danger fw-semibold">
            ⚠️ Cependant, si nous constatons que votre agence publie des contenus qui sortent du
            contexte immobilier ou enfreignent nos règles, votre certification pourra être
            <strong>retirée</strong> et votre compte potentiellement <strong>décativé temporairement</strong>.
          </p>

          <p class="text-dark">
            Une fois la situation régularisée, la certification peut être réactivée mais une 
            <strong class="text-danger">sanction financière</strong> s’appliquera :  
            vous devrez payer à nouveau la certification, cette fois-ci au 
            <strong>tarif doublé</strong> du prix en vigueur.
          </p>

          <!-- SECTION : Comment se certifier -->
          <h5 class="fw-bold mb-0 text-success">Comment se certifier ?</h5>
          <p class="text-dark">
            Après le paiement et l’accès à la section Certification, vous pourrez compléter la mise à jour de votre profil.  
            Notre équipe vérifiera ensuite :
          </p>

          <ul class="text-dark">
            <li>La vérification du <strong>paiement</strong></li>
            <li>La <strong>validité des liens</strong> fournis</li>
            <li>La présence d’une <strong>photo de profil claire et professionnelle</strong></li>
            <li>La cohérence des informations entre votre compte et vos activités réelles</li>
          </ul>

          <p class="text-dark">
            À l’issue de la vérification :
          </p>

          <ul class="text-dark">
            <li>Vous recevrez une <strong>notification Email + SMS</strong> indiquant le résultat</li>
            <li>Si votre certification est approuvée, votre agence apparaîtra automatiquement dans la 
              <strong>liste officielle des partenaires</strong>
            </li>
          </ul>

        </div>

        <!-- footer -->
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

  .btn-shimmer {
    position: relative;
    overflow: hidden;
    z-index: 0;
    /* pour lisibilité si le shimmer est fort */
    transition: box-shadow .15s ease;
  }

  /* Lueur "dorée/blanche" qui traverse : pseudo-élément avant */
  .btn-shimmer::before {
    content: "";
    position: absolute;
    top: -40%;
    left: -50%;
    width: 200%;
    height: 200%;
    /* gradient fin pour obtenir un trait lumineux doré/blanc */
    background: linear-gradient(
      120deg,
      rgba(255,255,255,0) 0%,
      rgba(255,255,255,0.95) 30%,
      rgba(199, 248, 211, 0.85) 45%,
      rgba(255,255,255,0.95) 60%,
      rgba(255,255,255,0) 100%
    );
    transform: translateX(-120%) rotate(15deg);
    z-index: 1;
    pointer-events: none;
    /* animation de traversée : courte et 'brusque' */
    animation: shimmer-swipe 3s cubic-bezier(.4,0,.6,1) infinite;
    mix-blend-mode: screen; /* rend la lueur plus naturelle selon le fond */
    opacity: 0; /* reste invisible hors du flash */
    filter: blur(6px);
  }

  /* petit flash / pulse de box-shadow pour renforcer l'effet "brusque" */
  @keyframes shimmer-swipe {
    0%   { transform: translateX(-120%) rotate(15deg); opacity: 0; }
    10%  { opacity: 0.0; }
    30%  { transform: translateX(0%) rotate(15deg); opacity: 1; }    /* apparition rapide */
    45%  { transform: translateX(30%) rotate(15deg); opacity: 0.9; } /* milieu lumineux */
    60%  { transform: translateX(70%) rotate(15deg); opacity: 0.0; } /* disparition rapide */
    100% { transform: translateX(120%) rotate(15deg); opacity: 0; }
  }

  /* flash brusque du bouton (ombre) synchronisée */
  .btn-shimmer {
    animation: shimmer-pulse-shadow 3s steps(1) infinite;
  }

  /* steps(1) crée un effet discontinu (brusque). Ajuste la durée si besoin. */
  @keyframes shimmer-pulse-shadow {
    0%   { box-shadow: none; }
    33%  { box-shadow: 0 6px 22px rgba(255,200,80,0); }
    34%  { box-shadow: 0 8px 28px rgba(255,220,110,0.26); } /* flash brusque */
    37%  { box-shadow: 0 4px 14px rgba(0,0,0,0.12); }
    100% { box-shadow: none; }
  }

  /* S'assurer que le texte reste au dessus du pseudo-élément */
  .btn-shimmer * {
    position: relative;
    z-index: 2;
  }

  /* Respecter la préférence de l'utilisateur pour réduire les animations */
  @media (prefers-reduced-motion: reduce) {
    .btn-shimmer::before,
    .btn-shimmer {
      animation: none;
      filter: none;
      opacity: 1;
    }
  }
</style>
