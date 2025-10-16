<template>
    <!-- Navbar Start -->
    <div class="container-fluid nav-bar bg-transparent">
        <!-- Icônes flottantes -->
        <span class="navbar-icon icon-home w-25"></span>
        <span class="navbar-icon icon-star w-25"></span>
        <span class="navbar-icon icon-user w-25"></span>
        <span class="navbar-icon icon-map w-25"></span>

        <nav class="navbar fixed-top navbar-expand-lg bg-dark navbar-light py-0 px-4">
            <a href="index.html" class="navbar-brand bg-light d-flex align-items-center text-center">
                <div class="icon p-3 me-2">
                    <img class="img-fluid" :src="logo2" alt="Icon" style="width: 30px; height: 30px;">
                </div>
                <h1 class="m-0 text-primary">La Casa</h1>
            </a>
            <span class="text-white text-center fs-5 ms-1">{{ currentTime }}</span>

            <button type="button" class="navbar-toggler bg-light " data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Conteneur du menu avec ref pour Bootstrap Collapse -->
            <div class="collapse navbar-collapse" id="navbarCollapse" ref="navbarCollapse" @click="handleMenuClick">
                <div class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <router-link 
                            to='/home' 
                            class="nav-link text-white"
                            @mouseenter="hoverLink($event)" 
                            @mouseleave="leaveLink($event)">
                            Accueil
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link 
                            to='/publication' 
                            class="nav-link text-white"
                            @mouseenter="hoverLink($event)" 
                            @mouseleave="leaveLink($event)">
                            Publications
                        </router-link>
                    </li>
                    <!-- admin -->
                    <li v-if="isAuthenticated && (user.role == 1 || user.role == 2)" class="nav-item">
                        <router-link 
                            to='/admin' 
                            class="nav-link text-white"
                            @mouseenter="hoverLink($event)" 
                            @mouseleave="leaveLink($event)">
                            Admin
                        </router-link>
                    </li>

                    <!-- <li class="nav-item">
                        <router-link 
                            to='/register' 
                            class="nav-link text-white"
                            @mouseenter="hoverLink($event)" 
                            @mouseleave="leaveLink($event)">
                            Register
                        </router-link>
                    </li> -->

                    <!-- login -->
                    <li v-if="!isAuthenticated"  class="nav-item">
                        <router-link 
                            to='/login' 
                            class="nav-link text-white"
                            @mouseenter="hoverLink($event)" 
                            @mouseleave="leaveLink($event)">
                            Login
                        </router-link>
                    </li>

                    <!-- profil -->
                    <li v-if="isAuthenticated" class="nav-item">
                        <router-link 
                            to='/Profile'
                            class="nav-link text-white"
                            @mouseenter="hoverLink($event)" 
                            @mouseleave="leaveLink($event)">
                            Profil
                        </router-link>
                    </li>
                    <li><a href="" class="nav-item nav-link"></a></li>

                    <!-- Dropdown Pages -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <!-- <a href="testimonial.html" class="dropdown-item">Testimonial</a> -->
                            <a href="" class="dropdown-item">404 Error</a>
                        </div>
                    </div>

                    <!-- Autres liens/commentaires conservés -->
                    <!-- <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Property</a>
                        <div class="dropdown-menu rounded-0 m-0">
                            <a href="property-list.html" class="dropdown-item">Property List</a>
                            <a href="property-type.html" class="dropdown-item">Property Type</a>
                            <a href="property-agent.html" class="dropdown-item">Property Agent</a>
                        </div>
                    </div> -->
                    <!-- <a href="" class="nav-item nav-link">Contact</a> -->
                    <!-- <a href="" class="btn btn-primary px-3 d-none d-lg-flex">Add Property</a> -->
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import logo from '@images/logo.jpeg'
import logo2 from '@images/logo2.png'
import { user, isAuthenticated } from '../auth/auth.js'

// import ParticleJs from '../partial/Particles.vue'

const navbarCollapse = ref(null)

function hoverLink(e) {
    e.target.classList.remove('text-white')
    e.target.classList.add('text-primary')
}

function leaveLink(e) {
    e.target.classList.remove('text-primary')
    e.target.classList.add('text-white')
}

function click(e) {
    e.target.classList.remove('text-white')
    e.target.classList.add('text-primary')
}

// Fermeture automatique du menu sur mobile
function handleMenuClick(e) {
    const collapseEl = navbarCollapse.value
    if (!collapseEl) return

    // Vérifie si l'élément cliqué est un lien du menu
    if (e.target.tagName === 'A' || e.target.tagName === 'ROUTER-LINK') {
        if (collapseEl.classList.contains('show')) {
            const bsCollapse = bootstrap.Collapse.getInstance(collapseEl)
            if (bsCollapse) {
                bsCollapse.hide()
            } else {
                new bootstrap.Collapse(collapseEl).hide()
            }
        }
    }
}

const currentTime = ref('')

// Fonction pour formater et mettre à jour l’heure
function updateTime() {
  const now = new Date()
  const hours = String(now.getHours()).padStart(2, '0')
  const minutes = String(now.getMinutes()).padStart(2, '0')
  const seconds = String(now.getSeconds()).padStart(2, '0')
  currentTime.value = `${hours}:${minutes}:${seconds}`
}

// Met à jour chaque seconde
let intervalId = null
onMounted(() => {
  updateTime()
  intervalId = setInterval(updateTime, 1000)
})

// Nettoyage à la destruction du composant
onBeforeUnmount(() => {
  clearInterval(intervalId)
})
</script>

<style scoped>
@media (max-width: 991px) {
    .navbar-nav {
        text-align: center;
        width: 100%;
    }

    .nav-link {
        font-size: 1.5rem;
        margin: 1rem 0;
        transition: all 0.3s ease;
        cursor: pointer;
        text-align: center;
    }

    .nav-link:hover,
    .nav-link:active {
        transform: scale(1.05);
        color: #1fad6b !important;
    }

    .dropdown-menu {
        text-align: center;
    }

    .dropdown-item {
        font-size: 1.3rem;
        transition: all 0.3s ease;
        text-align: center;
    }

    .dropdown-item:hover {
        color: #1fad6b !important;
        transform: scale(1.05);
    }
}

</style>
