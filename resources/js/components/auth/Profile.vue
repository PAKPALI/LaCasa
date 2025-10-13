<template>
  <div class="container py-5">
    <h2 class="text-center mb-4">Mon profil</h2>

    <div class="card shadow-lg bg-dark p-4 rounded">
      <!-- Image de profil -->
      <div class="d-flex flex-column align-items-center mb-4">
        <img
          :src="previewImage || imageUrl(user?.profile_image)"
          alt="Photo de profil"
          class="rounded-circle border shadow-sm"
          width="120"
          height="120"
        />
        <input type="file" class="form-control mt-3 w-100" @change="onImageChange" />
        <marquee class="border text-light mt-1" behavior="scroll" direction="left" scrollamount="6">
            <strong>Veuillez appuyez sur "enregistrer" pour mettre a jour la photo</strong>
        </marquee>
      </div>

      <!-- Onglets -->
      <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
          <button
            class="nav-link"
            :class="{ active: activeTab === 'info' }"
            @click="activeTab = 'info'"
          >
            Infos 
          </button>
        </li>
        <li class="nav-item">
          <button
            class="nav-link"
            :class="{ active: activeTab === 'email' }"
            @click="activeTab = 'email'"
          >
            Email
          </button>
        </li>
        <li class="nav-item">
          <button
            class="nav-link"
            :class="{ active: activeTab === 'password' }"
            @click="activeTab = 'password'"
          >
            Mot de passe
          </button>
        </li>
      </ul>

      <!-- Onglet : Infos générales -->
      <div v-if="activeTab === 'info'">
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
            <!-- <div class="col-md-6 mb-3">
              <label>Rôle</label>
              <input :value="roleLabel(user?.role)" class="form-control" disabled />
            </div> -->
          </div>

          <button class="btn btn-success">Enregistrer</button>
        </form>
      </div>

      <!-- Onglet : Email -->
      <div v-if="activeTab === 'email'">
        <form @submit.prevent="updateEmail">
          <div class="mb-3">
            <label>Nouvel email</label>
            <input v-model="emailForm.email" type="email" class="form-control" />
          </div>
          <div class="mb-3">
            <label>Mot de passe actuel (pour confirmer)</label>
            <input
              v-model="emailForm.password"
              type="password"
              class="form-control"
            />
          </div>
          <button class="btn btn-success">Mettre à jour l’email</button>
        </form>
      </div>

      <!-- Onglet : Mot de passe -->
      <div v-if="activeTab === 'password'">
        <form @submit.prevent="updatePassword">
          <div class="mb-3">
            <label>Mot de passe actuel</label>
            <input
              v-model="passwordForm.current_password"
              type="password"
              class="form-control"
            />
          </div>

          <div class="mb-3">
            <label>Nouveau mot de passe</label>
            <input
              v-model="passwordForm.new_password"
              type="password"
              class="form-control"
            />
          </div>

          <div class="mb-3">
            <label>Confirmer le mot de passe</label>
            <input
              v-model="passwordForm.new_password_confirmation"
              type="password"
              class="form-control"
            />
          </div>

          <button class="btn btn-success">Modifier le mot de passe</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from "axios"
import Swal from "sweetalert2"
import { user, fetchUser } from "../auth/auth.js"

const activeTab = ref("info")
const defaultImage = "https://cdn-icons-png.flaticon.com/512/847/847969.png"
const previewImage = ref(null)
const uploadedFile = ref(null)

const form = ref({
  name: "",
  phone1: "",
  phone2: "",
})
const emailForm = ref({
  email: "",
  password: "",
})
const passwordForm = ref({
  current_password: "",
  new_password: "",
  new_password_confirmation: "",
})

// Charger les données utilisateur à l’ouverture
onMounted(async () => {
  await fetchUser()
  if (user.value) {
    form.value = {
      name: user.value.name || "",
      phone1: user.value.phone1 || "",
      phone2: user.value.phone2 || "",
    }
    emailForm.value.email = user.value.email || ""
  }
})

// 🧠 Helpers
function imageUrl(path) {
  return path ? `/${path}` : defaultImage;
}

function onImageChange(e) {
  const file = e.target.files[0]
  if (file) {
    previewImage.value = URL.createObjectURL(file)
    uploadedFile.value = file
  }
}

// 🧾 Mettre à jour les infos générales
async function updateProfile() {
  try {
    const data = new FormData()

    // 🔸 On n’envoie que les champs remplis
    Object.entries(form.value).forEach(([k, v]) => {
      if (v !== null && v !== undefined && v !== "") {
        data.append(k, v)
      }
    })

    if (uploadedFile.value) data.append("profile_image", uploadedFile.value)

    const res = await axios.post("/me/update", data, { withCredentials: true })
    Swal.fire("Succès", res.data.message, "success")
    await fetchUser()
  } catch (err) {
    Swal.fire(
      "Erreur",
      err.response?.data?.message || "Une erreur est survenue",
      "error"
    )
  }
}

// 📧 Mettre à jour l’email
async function updateEmail() {
  try {
    const res = await axios.post(
      "/me/updateEmail",
      {
        email: emailForm.value.email,
        password_confirmation: emailForm.value.password,
      },
      { withCredentials: true }
    )
    Swal.fire("Email mis à jour", res.data.message, "success")
    await fetchUser()
  } catch (err) {
    Swal.fire(
      "Erreur",
      err.response?.data?.message || "Impossible de changer l'email",
      "error"
    )
  }
}

// 🔑 Mettre à jour le mot de passe
async function updatePassword() {
  try {
    const res = await axios.post(
      "/me/update-password",
      passwordForm.value,
      { withCredentials: true }
    )
    Swal.fire("Succès", res.data.message, "success")
    passwordForm.value = {
      current_password: "",
      new_password: "",
      new_password_confirmation: "",
    }
  } catch (err) {
    Swal.fire(
      "Erreur",
      err.response?.data?.message || "Impossible de changer le mot de passe",
      "error"
    )
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
label {
  font-weight: 600;
  color: #fff;
}
img.rounded-circle {
  object-fit: cover;
}
</style>
