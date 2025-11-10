<template>
  <!-- Navbar Start -->
  <div class="container-fluid nav-bar bg-transparent">
    <nav
      class="navbar fixed-top navbar-expand-lg navbar-dark py-2 px-4"
      style="background-color: rgba(14, 46, 80, 0.9);"
    >
      <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
        <img :src="logo2" alt="Logo" class="logo me-2" />
        <h1 class="m-0 text-primary"></h1>
      </a>

      <button
        type="button"
        class="navbar-toggler bg-dark"
        data-bs-toggle="collapse"
        data-bs-target="#navbarCollapse"
      >
        <span class="navbar-toggler-icon bg-dark"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse" ref="navbarCollapse" @click="handleMenuClick">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <router-link to="/home" class="nav-link" @mouseenter="hoverLink" @mouseleave="leaveLink">Accueil</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/publication" class="nav-link" @mouseenter="hoverLink" @mouseleave="leaveLink">Publications</router-link>
          </li>
          <li v-if="isAuthenticated && (user.role == 1 || user.role == 2)" class="nav-item">
            <router-link to="/admin" class="nav-link" @mouseenter="hoverLink" @mouseleave="leaveLink">Admin</router-link>
          </li>
          <li class="nav-item">
            <router-link to="/about" class="nav-link" @mouseenter="hoverLink" @mouseleave="leaveLink">À propos</router-link>
          </li>

          <!-- Si l'utilisateur n'est pas connecté -->
          <li v-if="!isAuthenticated" class="nav-item">
            <router-link to="/login" class="nav-link">Login</router-link>
          </li>

          <!-- Si l'utilisateur est connecté, afficher profil -->
          <li v-if="isAuthenticated" class="nav-item">
            <!-- <a
              class="nav-link dropdown-toggle d-flex align-items-center"
              href="#"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            > -->
            <router-link to="/profile" class="dropdown-item">
              <img :src="profileImage" alt="Photo profil" class="rounded-circle profile-nav-img me-2" />
              <span class="text-white">{{ user.name }}</span>
            </router-link>
            <!-- </a> -->
            <!-- <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <router-link to="/profile" class="dropdown-item">Mon profil</router-link>
              </li>
              <li>
                <a href="#" class="dropdown-item" @click.prevent="logout">Déconnexion</a>
              </li>
            </ul> -->
          </li>
        </ul>
      </div>
    </nav>
  </div>
  <!-- Navbar End -->
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from "vue"
import logo2 from "@images/logo2.png"
import { user, isAuthenticated, logout as authLogout } from "../auth/auth.js"

const navbarCollapse = ref(null)

// Hover animation
function hoverLink(e) {
  e.target.classList.add("text-primary")
}
function leaveLink(e) {
  e.target.classList.remove("text-primary")
}

// Fermeture automatique du menu mobile
function handleMenuClick(e) {
  const collapseEl = navbarCollapse.value
  if (!collapseEl) return
  if (e.target.tagName === "A" || e.target.tagName === "ROUTER-LINK") {
    if (collapseEl.classList.contains("show")) {
      const bsCollapse = bootstrap.Collapse.getInstance(collapseEl)
      if (bsCollapse) bsCollapse.hide()
      else new bootstrap.Collapse(collapseEl).hide()
    }
  }
}

// Computed pour image profil
const defaultImage = "https://cdn-icons-png.flaticon.com/512/847/847969.png"
const profileImage = computed(() => user.value?.profile_image ? `/${user.value.profile_image}` : defaultImage)

// Logout
const logout = () => {
  authLogout() // ta fonction logout dans auth.js
}
</script>

<style scoped>
.logo {
  width: 65px;
  height: 35px;
  object-fit: cover;
}

.profile-nav-img {
  width: 35px;
  height: 35px;
  object-fit: cover;
  border: 2px solid #fff;
}

.nav-link {
  transition: all 0.3s ease;
  color: #ffffff !important;
}
.nav-link:hover {
  color: #1fad6b !important;
}

.dropdown-menu {
  min-width: 150px;
}
</style>
