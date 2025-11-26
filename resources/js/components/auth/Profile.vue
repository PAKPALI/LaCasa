<template>
  <div class="container py-5 mt-5">
    <h2 class="text-center mb-4">Mon profil</h2>

    <div v-if="isAuthenticated" class="card shadow-lg bg-dark p-4 rounded">

      <!-- PHOTO DE PROFIL -->
      <div class="d-flex flex-column align-items-center mb-4">
        <img
          :src="previewImage || imageUrl(user?.profile_image)"
          class="rounded-circle border shadow-sm"
          width="120"
          height="120"
        />
        <button v-if="user?.profile_image" class="btn btn-danger btn-sm mt-2" @click="removeImage" :disabled="loading.image">
          <span v-if="loading.image" class="spinner-border spinner-border-sm"></span>
          <span v-else>Supprimer la photo</span>
        </button>

        <input type="file" class="form-control mt-3 w-100" @change="onImageChange" />
        <marquee class="border text-light mt-1" behavior="scroll" direction="left" scrollamount="6">
          <strong>Veuillez appuyer sur "Enregistrer" pour mettre à jour la photo</strong>
        </marquee>
      </div>

      <!-- ONGLETS -->
      <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
          <button class="nav-link" :class="{ active: activeTab === 'info' }" @click="activeTab='info'">Infos</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" :class="{ active: activeTab === 'email' }" @click="activeTab='email'">Email</button>
        </li>
        <li class="nav-item">
          <button class="nav-link" :class="{ active: activeTab === 'password' }" @click="activeTab='password'">Mot de passe</button>
        </li>
        <li class="nav-item" v-if="user?.user_type === 2">
          <button class="nav-link" :class="{ active: activeTab === 'social' }" @click="activeTab='social'">
            Réseaux sociaux
          </button>
        </li>
      </ul>

      <!-- ▶ INFOS -->
      <div v-if="activeTab==='info'">
        <form @submit.prevent="updateProfile">
          <div class="row">
            <div class="col-md-12 mb-3">
              <label>Nom complet</label>
              <input v-model="form.name" type="text" class="form-control" />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label>Téléphone principal</label>
              <input v-model="form.phone1" type="text" class="form-control" />
            </div>
            <div class="col-md-6 mb-3">
              <label>Téléphone secondaire</label>
              <input v-model="form.phone2" type="text" class="form-control" />
            </div>
          </div>
          <button class="btn btn-success" :disabled="loading.info">
            <span v-if="loading.info" class="spinner-border spinner-border-sm"></span>
            <span v-else>Enregistrer</span>
          </button>
        </form>
      </div>

      <!-- ▶ EMAIL -->
      <div v-if="activeTab==='email'">
        <form @submit.prevent="updateEmail">
          <div class="mb-3">
            <label>Nouvel email</label>
            <input v-model="emailForm.email" type="email" class="form-control" />
          </div>
          <div class="mb-3">
            <label>Mot de passe actuel</label>
            <input v-model="emailForm.password" type="password" class="form-control" />
          </div>
          <button class="btn btn-success" :disabled="loading.email">
            <span v-if="loading.email" class="spinner-border spinner-border-sm"></span>
            <span v-else>Mettre à jour l’email</span>
          </button>
        </form>
      </div>

      <!-- ▶ PASSWORD -->
      <div v-if="activeTab==='password'">
        <form @submit.prevent="updatePassword">
          <div class="mb-3">
            <label>Mot de passe actuel</label>
            <input v-model="passwordForm.current_password" type="password" class="form-control" />
          </div>
          <div class="mb-3">
            <label>Nouveau mot de passe</label>
            <input v-model="passwordForm.new_password" type="password" class="form-control" />
          </div>
          <div class="mb-3">
            <label>Confirmer le mot de passe</label>
            <input v-model="passwordForm.new_password_confirmation" type="password" class="form-control" />
          </div>
          <button class="btn btn-success" :disabled="loading.password">
            <span v-if="loading.password" class="spinner-border spinner-border-sm"></span>
            <span v-else>Modifier le mot de passe</span>
          </button>
        </form>
      </div>

      <!-- ▶ RÉSEAUX SOCIAUX -->
      <div v-if="activeTab==='social'">

        <!-- 1️⃣ L’utilisateur n’a pas encore payé la certification -->
        <div v-if="user && !user.certify_payment_status" class="alert alert-info text-dark mb-3">
          <strong>Certification requise :</strong><br />
          Pour déposer une demande de certification, vous devez d’abord effectuer
          le paiement des frais de certification.<br /><br />
          
          Une fois le paiement effectué, vous pourrez compléter votre profil social
          et soumettre votre demande de certification.<br /><br />

          <button class="btn btn-warning btn-shimmer" @click="goToPayment">
            Payer pour la certification
          </button>
        </div>

        <!-- Si pas encore payé → on bloque tout le reste -->
        <div v-if="user && !user.certify_payment_status">
          <!-- ON NE MONTRE PAS LE FORMULAIRE NI LES TEMPLATES -->
          <br />
          <p class="text-warning">Effectuez le paiement pour continuer.</p>
        </div>

        <!-- 2️⃣ L’utilisateur a payé mais n’est pas encore certifié -->
        <div class="alert alert-warning text-dark mb-3" v-if="user && user.certify_payment_status && !user.is_verified">
          <strong>Information importante :</strong><br />
          Après la mise à jour de vos réseaux sociaux, un délai de
          <strong>48h minimum</strong> est requis pour le traitement de votre demande de certification.<br />
          Vous recevrez un <strong>email</strong> et un <strong>SMS</strong> concernant l’évolution.<br /><br />

          Veuillez vérifier que les liens fournis sont corrects.
        </div>

        <!-- 3️⃣ L’utilisateur est déjà certifié -->
        <div class="alert alert-success text-dark mb-3" v-if="user && user.is_verified">
          <strong>Note :</strong><br />
          Votre agence est déjà certifiée. Vous pouvez continuer à mettre à jour vos réseaux sociaux.<br /><br />

          Ces informations renforcent votre image professionnelle.
        </div>

        <!--  Formulaire réseaux sociaux (visible pour état 2 et 3) -->
        <form @submit.prevent="updateSocial" v-if="user && (user.certify_payment_status)">
          <div class="mb-3" v-for="social in socials" :key="social.key">
            <label>{{ social.label }}</label>
            <div class="input-group">
              <span class="input-group-text bg-dark text-light">
                <i :class="social.icon"></i>
              </span>
              <input
                v-model="form[social.key]"
                :type="social.type"
                class="form-control"
                :placeholder="social.placeholder"
              />
            </div>
          </div>

          <button class="btn btn-success" :disabled="!hasSocialInput || loading.social">
            <span v-if="loading.social" class="spinner-border spinner-border-sm"></span>
            <span v-else>Enregistrer</span>
          </button>
        </form>
      </div>

    </div>

    <!-- NON AUTH -->
    <div v-if="!isAuthenticated" class="card shadow-lg p-4 rounded text-light text-center">
      <h4>Veuillez vous connecter pour accéder à votre profil.</h4>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted, computed } from "vue"
  import axios from "axios"
  import Swal from "sweetalert2"
  import { user, fetchUser, isAuthenticated } from "../auth/auth.js"

  const activeTab = ref("info")
  const defaultImage = "https://cdn-icons-png.flaticon.com/512/847/847969.png"
  const previewImage = ref(null)
  const uploadedFile = ref(null)

  // LOADING
  const loading = ref({
    info: false,
    email: false,
    password: false,
    social: false,
    image: false
  })

  // FORM PRINCIPAL
  const form = ref({
    name: "",
    phone1: "",
    phone2: "",
    facebook_link: "",
    tiktok_link: "",
    whatsapp_link: ""
  })

  const emailForm = ref({ email: "", password: "" })
  const passwordForm = ref({ current_password: "", new_password: "", new_password_confirmation: "" })

  // Réseaux sociaux
  const socials = [
    { key: 'facebook_link', label: 'Facebook', icon: 'bi bi-facebook', type: 'url', placeholder: 'https://facebook.com/...' },
    { key: 'tiktok_link', label: 'TikTok', icon: 'fab fa-tiktok', type: 'url', placeholder: 'https://tiktok.com/@...' },
    { key: 'whatsapp_link', label: 'WhatsApp', icon: 'bi bi-whatsapp', type: 'text', placeholder: 'Lien ou numéro WhatsApp' },
  ]

  const hasSocialInput = computed(() =>
    (form.value.facebook_link || '').trim() !== '' ||
    (form.value.tiktok_link || '').trim() !== '' ||
    (form.value.whatsapp_link || '').trim() !== ''
  )

  onMounted(async () => {
    await fetchUser()
    if (user.value) {
      form.value = {
        name: user.value.name || "",
        phone1: user.value.phone1 || "",
        phone2: user.value.phone2 || "",
        facebook_link: user.value.facebook_link || "",
        tiktok_link: user.value.tiktok_link || "",
        whatsapp_link: user.value.whatsapp_link || "",
      }
      emailForm.value.email = user.value.email || ""
    }
  })

  function imageUrl(path) { return path ? `/${path}` : defaultImage }
  function onImageChange(e) {
    const file = e.target.files[0]
    if (file) { previewImage.value = URL.createObjectURL(file); uploadedFile.value = file }
  }

  // UPDATE INFOS
  async function updateProfile() {
    try {
      loading.value.info = true
      const data = new FormData()
      Object.entries(form.value).forEach(([k,v])=>{
        if(['facebook_link','tiktok_link','whatsapp_link'].includes(k)) return
        if(v) data.append(k,v)
      })
      if(uploadedFile.value) data.append('profile_image', uploadedFile.value)
      const res = await axios.post("/me/update", data, { withCredentials:true })
      if(res.data.status) { Swal.fire("Succès", res.data.message,"success"); fetchUser() }
      else Swal.fire("Erreur", res.data.message,"error")
    } catch(err) { Swal.fire("Erreur", err.response?.data?.message||"Erreur inconnue","error") }
    finally { loading.value.info = false }
  }

  // UPDATE EMAIL
  async function updateEmail() {
    try {
      loading.value.email = true
      const res = await axios.post("/me/updateEmail", {
        email: emailForm.value.email,
        password_confirmation: emailForm.value.password
      })
      Swal.fire("Succès", res.data.message,"success")
      fetchUser()
    } catch(err) { Swal.fire("Erreur", err.response?.data?.message||"Erreur inconnue","error") }
    finally { loading.value.email = false }
  }

  // UPDATE PASSWORD
  async function updatePassword() {
    try {
      loading.value.password = true
      const res = await axios.post("/me/update-password", passwordForm.value)
      Swal.fire("Succès", res.data.message,"success")
      passwordForm.value = { current_password:"", new_password:"", new_password_confirmation:"" }
    } catch(err) { Swal.fire("Erreur", err.response?.data?.message||"Erreur inconnue","error") }
    finally { loading.value.password = false }
  }

  // UPDATE SOCIAL
  async function updateSocial() {
    try {
      loading.value.social = true
      const res = await axios.post("/me/update-social", {
        facebook_link: form.value.facebook_link,
        tiktok_link: form.value.tiktok_link,
        whatsapp_link: form.value.whatsapp_link
      })
      if(res.data.status) { Swal.fire("Succès", res.data.message,"success"); fetchUser() }
      else Swal.fire("Erreur", res.data.message,"error")
    } catch(err){ Swal.fire("Erreur", err.response?.data?.message||"Erreur inconnue","error") }
    finally { loading.value.social = false }
  }

  // REMOVE PHOTO
  async function removeImage() {
    try {
      loading.value.image = true
      const res = await axios.delete("/me/remove-image")
      Swal.fire("Supprimée", res.data.message,"success")
      previewImage.value=null; fetchUser()
    } catch(err){ Swal.fire("Erreur","Impossible de supprimer","error") }
    finally{ loading.value.image=false }
  }

  async function goToPayment() {
    try {
      Swal.fire({
        title: "Paiement en cours...",
        text: "Veuillez patienter",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });

      const response = await axios.post("/me/certify_payment");

      if (!response.data || !response.data.payment_url) {
        Swal.fire("Erreur", "Lien de paiement introuvable.", "error");
        return;
      }

      const paymentUrl = response.data.payment_url;

      // 🔥 REDIRECTION DIRECTE VERS KPRIMEPAY
      window.location.href = paymentUrl;

    } catch (error) {
      Swal.fire(
        "Erreur",
        error.response?.data?.message || "Impossible de démarrer le paiement.",
        "error"
      );
    }
  }

</script>

<style scoped>
  .nav-link {
    background-color: #00b98e38 !important;
    color: #fff !important;
  }
  .nav-link.active {
    background-color: #00b98e !important;
    color: #fff !important;
  }
  label { font-weight: 600; color: #fff }
  img.rounded-circle { object-fit: cover }

  /* CSS - coller dans ton fichier global (ex : app.css) */

  /* wrapper : relative pour le pseudo-élément de lueur */
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
      rgba(255,230,150,0.85) 45%,
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
