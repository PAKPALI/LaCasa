<template>
    <!-- <nav class="navbar bg-dark text-white p-3">
    <h5 class="mb-0">Mon Application Vue</h5>
  </nav> -->
    <!-- <div class="site-mobile-menu">
        
        <div class="site-mobile-menu-header ">
            <TsParticle />
            <div class="site-mobile-menu-close mt-3 bg-dark">
                <span class="icon-close2 js-menu-toggle text-success"></span>
            </div>
        </div>
        <div class="site-mobile-menu-body"></div>
    </div> -->

    <!-- <div class="site-navbar fixed-top bg-dark mt-0"> -->
        <!-- Icônes flottantes -->
        <!-- <span class="navbar-icon icon-home w-25"></span>
        <span class="navbar-icon icon-star w-25"></span>
        <span class="navbar-icon icon-user w-25"></span>
        <span class="navbar-icon icon-map w-25"></span> -->
        <!-- <div class="container py-1"> -->
            <!-- <div class="row align-items-center"> -->
                <!-- <div class="col-8 col-md-8 col-lg-4"> -->
                    <!-- <h1 class="mb-0 text-light">LaCasa<a href="index.html" class="text-white h2 mb-0">
                        <strong><span class="text-danger">.</span></strong></a>
                    </h1> -->
                    <!-- <img :src="logo2" alt="logo2" class="img-fluid w-50 mb-4" /> -->
                <!-- </div> -->
                <!-- <div class="col-4 col-md-4 col-lg-8"> -->
                    <!-- <nav class="site-navigation text-right text-md-right" role="navigation">

                        <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3">
                            <a href="#" class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a>
                        </div> -->

                        <!-- <ul class="site-menu js-clone-nav d-none d-lg-block">
                            <li class="active"><router-link to='/home'> Acceuil </router-link></li> -->
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
                            <!-- <li v-if="isLocal" class="active">
                                <router-link to="/admin">Admin</router-link>
                            </li> -->
                            <!-- <li><a href="#">Publications</a></li>
                            <li><a href="#">A propos</a></li>
                            <li><a href="#">Contact</a></li> -->
                        <!-- </ul> -->
                    <!-- </nav> -->
                <!-- </div> -->
            <!-- </div> -->
        <!-- </div> -->
    <!-- </div> -->

    <!-- Navbar Start -->
        <div class="container-fluid nav-bar bg-transparent">
            <!-- Icônes flottantes -->
            <span class="navbar-icon icon-home w-25"></span>
            <span class="navbar-icon icon-star w-25"></span>
            <span class="navbar-icon icon-user w-25"></span>
            <span class="navbar-icon icon-map w-25"></span>
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
                <a href="index.html" class="navbar-brand d-flex align-items-center text-center">
                    <div class="icon p-2 me-2">
                        <img class="img-fluid" :src="logo2" alt="Icon" style="width: 30px; height: 30px;">
                    </div>
                    <h1 class="m-0 text-primary">La Casa</h1>
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <li class="nav-item nav-link"><router-link to='/home'> Acceuil </router-link></li>
                        <li class="nav-item nav-link"><router-link to='/home2'> Acceuil2 </router-link></li>
                        <li><a href="about.html" class="nav-item nav-link">About</a></li>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Property</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="property-list.html" class="dropdown-item">Property List</a>
                                <a href="property-type.html" class="dropdown-item">Property Type</a>
                                <a href="property-agent.html" class="dropdown-item">Property Agent</a>
                            </div>
                        </div>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="testimonial.html" class="dropdown-item">Testimonial</a>
                                <a href="404.html" class="dropdown-item">404 Error</a>
                            </div>
                        </div>
                        <a href="contact.html" class="nav-item nav-link">Contact</a>
                    </div>
                    <a href="" class="btn btn-primary px-3 d-none d-lg-flex">Add Property</a>
                </div>
            </nav>
        </div>
    <!-- Navbar End -->

</template>

<script setup>
    import logo from '@images/logo.jpeg'
    import logo2 from '@images/logo2.png'
    // import ParticleJs from '../partial/Particles.vue'
</script>

<style scoped>
    
</style>

