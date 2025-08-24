<template>
    <!-- <nav class="navbar bg-dark text-white p-3">
    <h5 class="mb-0">Mon Application Vue</h5>
  </nav> -->
    <div class="site-mobile-menu">
        
        <div class="site-mobile-menu-header ">
            <TsParticle />
            <div class="site-mobile-menu-close mt-3 bg-dark">
                <span class="icon-close2 js-menu-toggle text-success"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div> <!-- .site-mobile-menu -->

    <div class="site-navbar fixed-top bg-dark mt-0">
        <!-- Icônes flottantes -->
        <span class="navbar-icon icon-home w-25"></span>
        <span class="navbar-icon icon-star w-25"></span>
        <span class="navbar-icon icon-user w-25"></span>
        <span class="navbar-icon icon-map w-25"></span>
        <div class="container py-1">
            <div class="row align-items-center">
                <div class="col-8 col-md-8 col-lg-4">
                    <!-- <h1 class="mb-0 text-light">LaCasa<a href="index.html" class="text-white h2 mb-0">
                        <strong><span class="text-danger">.</span></strong></a>
                    </h1> -->
                    <img :src="logo2" alt="logo2" class="img-fluid w-50 mb-4" />
                </div>
                <div class="col-4 col-md-4 col-lg-8">
                    <nav class="site-navigation text-right text-md-right" role="navigation">

                        <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#"
                                class="site-menu-toggle js-menu-toggle text-white"><span
                                    class="icon-menu h3"></span></a></div>

                        <ul class="site-menu js-clone-nav d-none d-lg-block">
                            <li class="active"><router-link to='/home'> Acceuil </router-link></li>
                            <!-- <li><a href="buy.html">Buy</a></li> -->
                            <!-- <li><a href="rent.html">Rent</a></li> -->
                            <!-- <li class="has-children">
                                <a href="properties.html">Properties</a>
                                <ul class="dropdown arrow-top">
                                    <li><a href="#">Condo</a></li>
                                    <li><a href="#">Property Land</a></li>
                                    <li><a href="#">Commercial Building</a></li>
                                    <li class="has-children">
                                        <a href="#">Sub Menu</a>
                                        <ul class="dropdown">
                                            <li><a href="#">Menu One</a></li>
                                            <li><a href="#">Menu Two</a></li>
                                            <li><a href="#">Menu Three</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li> -->
                            <!-- <li><a href="blog.html">Blog</a></li> -->
                             <li><a href="#">Publications</a></li>
                            <li><a href="#">A propos</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
    import logo from '@images/logo.jpeg'
    import logo2 from '@images/logo2.png'
    // import ParticleJs from '../partial/Particles.vue'
    import Particles  from '../partial/TsParticle.vue'

</script>

<style scoped>
    .site-navbar {
        position: fixed;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.98), rgba(0, 0, 0, 0.95) 70%, rgba(53, 99, 26, 0.996));
        border-bottom: 2px solid rgba(89,165,44,0.4);
        overflow: hidden;
        z-index: 999;
        /* opacity: 0.9; */
    }

    .site-mobile-menu-body {
        position: relative;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.98), rgba(16, 39, 3, 0.996) 70%, rgba(53, 99, 26, 0.996));
        border-bottom: 2px solid rgba(89,165,44,0.4);
        overflow: hidden;
        z-index: 999;
        opacity: 0.9;
    }

    /* Effet de glow vert */
    .site-navbar::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(89,165,44,0.15) 0%, transparent 60%);
        animation: glowMove 12s linear infinite;
        z-index: 0;
    }

    @keyframes glowMove {
        0% { transform: translate(0, 0); }
        50% { transform: translate(30px, 20px); }
        100% { transform: translate(0, 0); }
    }

    /* Icônes flottantes */
    .navbar-icon {
        position: absolute;
        font-size: 18px;
        color: rgba(193, 213, 181, 0.8);
        animation: floatIcons 8s infinite ease-in-out;
        opacity: 0.5;
        z-index: 0;
    }

    .navbar-icon:nth-child(1) { top: 20%; left: 5%; animation-delay: 0s; }
    .navbar-icon:nth-child(2) { top: 100%; left: 90%; animation-delay: 2s; }
    .navbar-icon:nth-child(3) { top: 100%; left: 10%; animation-delay: 4s; }
    .navbar-icon:nth-child(4) { top: 10%; left: 80%; animation-delay: 6s; }

    @keyframes floatIcons {
        0% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(10deg); }
        100% { transform: translateY(0) rotate(0deg); }
    }

    /* Liens navbar */
    .site-menu a {
        color: #fff;
        transition: color 0.3s ease;
        position: relative;
        z-index: 1;
    }
    .site-menu a:hover {
        color: #35a516;
    }

    .site-mobile-menu-body a {
        color: #fff;
        transition: color 0.3s ease;
        position: relative;
        z-index: 1;
    }
    
    .site-mobile-menu-body a:hover {
        color: #35a516;
    }
</style>

